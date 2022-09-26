<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ProviderTerm;
use App\Entity\Term;
use App\Provider\ProviderAdapter;
use App\Entity\Provider;
use App\Repository\ProviderRepository;
use App\Repository\ProviderTermRepository;
use App\Repository\TermRepository;

class ScoreService
{
    private ProviderTermRepository $providerTermRepository;
    private ProviderRepository $providerRepository;
    private TermRepository $termRepository;

    public function __construct(
        ProviderTermRepository $providerTermRepository,
        ProviderRepository $providerRepository,
        TermRepository $termRepository
    ) {
        $this->providerTermRepository = $providerTermRepository;
        $this->providerRepository     = $providerRepository;
        $this->termRepository         = $termRepository;
    }

    public function getScore(string $term, ProviderAdapter $provider): ?array
    {
        $providerName = $provider->getProviderName();
        $score        = $this->providerTermRepository->findTermScoreByProvider(['term' => $term, 'provider' => $providerName]);

        if (false === empty($score)) {
            return $score;
        }

        $listRocks  = $provider->getList($term.'+rocks');
        $countRocks = !empty($listRocks) ? $listRocks['total_count'] : 0;

        $listSucks  = $provider->getList($term.'+sucks');
        $countSucks = !empty($listSucks) ? $listSucks['total_count'] : 0;

        $score      = $this->calculateScore((int)$countRocks, (int)$countSucks);

        $this->saveScore($providerName, $term, $score);

        return [
            'score'    => $score,
            'provider' => $providerName,
            'term'     => $term,
        ];
    }

    public function calculateScore(int $countRocks, int $countSucks): float
    {
        if ($countRocks === 0) {
            return 0;
        }

        $score = ($countRocks + $countSucks) / $countRocks;
        return round($score, 2);
    }

    private function saveScore(string $providerName, string $term, float $score): void
    {
        $entityTerm = $this->termRepository->findOneBy(['text' => $term]);
        if ($entityTerm === null) {
            $entityTerm = new Term();
            $entityTerm->setText($term);
            $this->termRepository->add($entityTerm, true);
        }

        $entityProvider = $this->providerRepository->findOneBy(['name' => $providerName]);
        if ($entityProvider === null) {
            $entityProvider = new Provider();
            $entityProvider->setName($providerName);
            $this->providerRepository->add($entityProvider, true);
        }

        $entityProviderTerm = new ProviderTerm();
        $entityProviderTerm->setTerm($entityTerm);
        $entityProviderTerm->setProvider($entityProvider);
        $entityProviderTerm->setScore($score);
        $this->providerTermRepository->add($entityProviderTerm, true);
    }
}