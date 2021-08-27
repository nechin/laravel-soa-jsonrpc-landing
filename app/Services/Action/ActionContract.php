<?php

namespace App\Services\Action;

/**
 * Interface ActionContract
 */
interface ActionContract
{
    public function store(string $url): void;
    public function show(int $page): array;
}
