<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\MiniApp\StoreMiniAppRequest;
use App\Http\Requests\MiniApp\UpdateMiniAppRequest;
use App\Http\Resources\MiniAppCollection;
use App\Http\Resources\MiniAppResource;
use App\Models\MiniApp;
use App\Services\MiniAppService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MiniAppController extends Controller
{
    use ApiResponse;

    private MiniAppService $miniAppService;

    public function __construct(MiniAppService $miniAppService)
    {
        $this->miniAppService = $miniAppService;
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $miniApps = $this->miniAppService->list($perPage);

        return $this->success(
            new MiniAppCollection($miniApps),
            'Mini apps retrieved successfully'
        );
    }

    public function store(StoreMiniAppRequest $request)
    {
        $data = $request->validated();
        $data['creator_id'] = $request->user()->id;

        $miniApp = $this->miniAppService->create($data);

        return $this->created(
            new MiniAppResource($miniApp->load('creator')),
            'Mini app created successfully'
        );
    }

    public function show(int $id)
    {
        $miniApp = $this->miniAppService->find($id);

        return $this->success(
            new MiniAppResource($miniApp->load(['creator', 'settings'])),
            'Mini app retrieved successfully'
        );
    }

    public function update(UpdateMiniAppRequest $request, int $id)
    {
        $miniApp = $this->miniAppService->update($id, $request->validated());

        return $this->success(
            new MiniAppResource($miniApp->load(['creator', 'settings'])),
            'Mini app updated successfully'
        );
    }

    public function destroy(int $id)
    {
        $this->miniAppService->delete($id);

        return $this->success(null, 'Mini app deleted successfully');
    }

    public function createToken(MiniApp $miniApp)
    {
        $token = $miniApp->creator->createToken(
            "mini-app-{$miniApp->slug}",
            ["app:{$miniApp->slug}"]
        );

        return $this->created([
            'token' => $token->plainTextToken,
            'mini_app' => new MiniAppResource($miniApp),
        ], 'Token created successfully');
    }

    public function revokeToken(MiniApp $miniApp, int $tokenId)
    {
        $token = $miniApp->creator->tokens()->where('id', $tokenId)->first();

        if (!$token) {
            return $this->error('Token not found', 404);
        }

        $token->delete();

        return $this->success(null, 'Token revoked successfully');
    }
}
