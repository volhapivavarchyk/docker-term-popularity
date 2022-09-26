<?php

declare(strict_types=1);

namespace App\Tests\Provider;

use App\Provider\GitHubProvider;
use PHPUnit\Framework\TestCase;

class GitHubProviderTest extends TestCase
{
    public function testGetList(): void
    {
        $gitHubProvider = new GitHubProvider();
        $result = $gitHubProvider->getList('test+rocks');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('total_count', $result);

    }
}