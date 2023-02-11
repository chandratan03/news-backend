<?php

namespace App\Http\Controllers\News;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Services\NewsService;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $pageSize = $request["pageSize"] ?? 20;
        return MyHelper::customResponse($this->newsService->paginate($pageSize));
    }

    public function search(Request $request)
    {
        return MyHelper::customResponse($this->newsService->search($request));
    }
}
