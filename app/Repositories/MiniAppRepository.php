<?php

namespace App\Repositories;

use App\Models\MiniApp;
use App\Repositories\Contracts\MiniAppRepositoryInterface;

class MiniAppRepository extends BaseRepository implements MiniAppRepositoryInterface
{
    public function __construct(MiniApp $model)
    {
        parent::__construct($model);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    public function getActiveApps(int $perPage = 15)
    {
        return $this->model->active()->paginate($perPage);
    }

    public function getByCreator(int $creatorId, int $perPage = 15)
    {
        return $this->model->where('creator_id', $creatorId)->paginate($perPage);
    }
}
