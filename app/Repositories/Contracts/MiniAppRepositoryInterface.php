<?php

namespace App\Repositories\Contracts;

interface MiniAppRepositoryInterface extends BaseRepositoryInterface
{
    public function findBySlug(string $slug);

    public function getActiveApps(int $perPage = 15);

    public function getByCreator(int $creatorId, int $perPage = 15);
}
