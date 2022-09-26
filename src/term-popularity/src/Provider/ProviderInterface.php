<?php

namespace App\Provider;

interface ProviderInterface
{
    public function getName(): string;

    public function getList(string $query): array;
}