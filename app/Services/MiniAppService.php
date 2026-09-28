<?php

namespace App\Services;

use App\Models\MiniApp;
use App\Repositories\Contracts\MiniAppRepositoryInterface;
use Illuminate\Support\Str;

class MiniAppService
{
    private MiniAppRepositoryInterface $miniAppRepository;

    public function __construct(MiniAppRepositoryInterface $miniAppRepository)
    {
        $this->miniAppRepository = $miniAppRepository;
    }

    public function list(int $perPage = 15)
    {
        return $this->miniAppRepository->paginate($perPage);
    }

    public function find(int $id): MiniApp
    {
        return $this->miniAppRepository->findOrFail($id);
    }

    public function findBySlug(string $slug)
    {
        return $this->miniAppRepository->findBySlug($slug);
    }

    public function create(array $data): MiniApp
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $this->miniAppRepository->create($data);
    }

    public function update(int $id, array $data): MiniApp
    {
        return $this->miniAppRepository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->miniAppRepository->delete($id);
    }

    public function createToken(MiniApp $miniApp, string $name = 'api-token'): string
    {
        $user = $miniApp->creator;

        return $user->createToken(
            "{$miniApp->slug}-{$name}",
            ["mini-app:{$miniApp->id}"]
        )->plainTextToken;
    }

    public function getByCreator(int $creatorId, int $perPage = 15)
    {
        return $this->miniAppRepository->getByCreator($creatorId, $perPage);
    }
}
