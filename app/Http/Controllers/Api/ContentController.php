<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContentService;
use App\Http\Requests\StoreContentRequest;
use App\Traits\ApiResponse;

class ContentController extends Controller
{
    use ApiResponse;
    protected $contentService;

    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    public function index()
    {
        $contents = auth()->user()->currentGroup->contents()
            ->with(['media', 'tourLeader'])
            ->latest()
            ->paginate(20);

        return $this->successResponse($contents);
    }

    public function store(StoreContentRequest $request)
    {
        $content = $this->contentService->storeContent(
            auth()->user(),
            $request->validated()
        );

        return $this->successResponse($content, 'Content uploaded successfully');
    }
}
