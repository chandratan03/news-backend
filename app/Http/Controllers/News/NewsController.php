<?php

namespace App\Http\Controllers\News;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Services\INewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(INewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $pageSize = $request["pageSize"] ?? 20;
        return MyHelper::customResponse($this->newsService->paginate($pageSize, $request));
    }

    public function personalize(Request $request)
    {
        $pageSize = $request["pageSize"] ?? 20;
        return MyHelper::customResponse($this->newsService->paginateByPersonalize($pageSize, $request));
    }
}
