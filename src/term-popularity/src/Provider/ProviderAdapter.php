<?php

declare(strict_types=1);

namespace App\Provider;

class ProviderAdapter
{
    private ProviderInterface $provider;

    public function __construct(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getList(string $term): array
    {
        return $this->provider->getList($term);
    }

    public function getProviderName(): string
    {
        return $this->provider->getName();
    }
}