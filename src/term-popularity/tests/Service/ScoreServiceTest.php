<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Provider\ProviderAdapter;
use App\Repository\ProviderRepository;
use App\Repository\ProviderTermRepository;
use App\Repository\TermRepository;
use App\Service\ScoreService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ScoreServiceTest extends TestCase
{
    public ProviderAdapter|MockObject $provider;
    public ProviderTermRepository|MockObject $providerTermRepository;
    public ProviderRepository|MockObject $providerRepository;
    public TermRepository|MockObject $termRepository;
    public ScoreService|MockObject $scoreService;

    public function setUp(): void
    {
        $this->provider               = $this->createMock(ProviderAdapter::class);
        $this->providerTermRepository = $this->createMock(ProviderTermRepository::class);
        $this->providerRepository     = $this->createMock(ProviderRepository::class);
        $this->termRepository         = $this->createMock(TermRepository::class);

        $this->scoreService           = new ScoreService(
            $this->providerTermRepository,
            $this->providerRepository,
            $this->termRepository
        );
    }

    /**
     * @dataProvider dataDbResultProvider
     */
    public function testGetScoreFromDb(array $data, mixed $expected): void
    {
        $this->provider->expects($this->once())
            ->method('getProviderName')
            ->willReturn($data['input']['provider']);

        $this->providerTermRepository->expects($this->once())
            ->method('findTermScoreByProvider')
            ->with($data['input'])
            ->willReturn([
                'score'    => $data['score'],
                'provider' => $data['input']['provider'],
                'term'     => $data['input']['term'],
            ]);
        
        $data = $this->scoreService->getScore($data['input']['term'], $this->provider);
        $this->assertEquals($data, $expected);
    }

    /**
     * @return iterable<array[]>
     */
    public function dataDbResultProvider(): iterable
    {
        yield [
            [
                'input' => [
                    'term'     => 'test-term',
                    'provider' => 'test-provider',
                ],
                'score'        => 2.0,
            ],
            [
                'score'    => 2.0,
                'provider' => 'test-provider',
                'term'     => 'test-term',
            ]
        ];
    }

    /**
     * @dataProvider dataProviderResultProvider
     */
    public function testGetScoreFromProvider(array $data, mixed $expected): void
    {
        $this->provider->expects($this->once())
            ->method('getProviderName')
            ->willReturn($data['input']['provider']);

        $this->providerTermRepository->expects($this->once())
            ->method('findTermScoreByProvider')
            ->with($data['input'])
            ->willReturn([]);

        $this->provider->expects($this->exactly(2))
            ->method('getList')
            ->withConsecutive(
                [ $data['input']['term'].'+rocks'],
                [ $data['input']['term'].'+sucks'],
            )
            ->willReturnOnConsecutiveCalls(
                $data['resultRocks'], $data['resultSucks'],
               );

        $data = $this->scoreService->getScore($data['input']['term'], $this->provider);
        $this->assertEquals($data, $expected);
    }

    /**
     * @return iterable<array[]>
     */
    public function dataProviderResultProvider(): iterable
    {
        yield [
            [
                'input' => [
                    'term'     => 'test-term',
                    'provider' => 'test-provider',
                ],
                'resultRocks'  => ['total_count' => 10000],
                'resultSucks'  => ['total_count' => 2000],
            ],
            [
                'score'    => 1.2,
                'provider' => 'test-provider',
                'term'     => 'test-term',
            ]
        ];

        yield [
            [
                'input' => [
                    'term'     => 'test-term',
                    'provider' => 'test-provider',
                ],
                'resultRocks'  => ['total_count' => 0],
                'resultSucks'  => ['total_count' => 2000],
            ],
            [
                'score'    => 0,
                'provider' => 'test-provider',
                'term'     => 'test-term',
            ]
        ];
    }

    /**
     * @dataProvider dataScoreProvider
     */
    public function testCalculateScore(array $data, mixed $expected): void
    {
        $score = $this->scoreService->calculateScore($data['rocks'], $data['sucks']);
        $this->assertEquals($score, $expected);
    }

    /**
     * @return iterable<array[]>
     */
    public function dataScoreProvider(): iterable
    {
        yield [
            [
                'rocks' => 10000,
                'sucks' => 2000,
            ],
            1.2
        ];
        yield [
            [
                'rocks' => 0,
                'sucks' => 2000,
            ],
            0
        ];
        yield [
            [
                'rocks' => 0,
                'sucks' => 0,
            ],
            0
        ];
    }
}
