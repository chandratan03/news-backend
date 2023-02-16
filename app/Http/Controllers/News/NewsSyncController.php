<?php

namespace App\Http\Controllers\News;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Services\INewsService;

class NewsSyncController extends Controller
{

    private $newsService;

    public function __construct(INewsService $newsService)
    {
        $this->newsService = $newsService;
    }


    public function sync()
    {
        $res = $this->newsService->sync();
        return MyHelper::customResponse($res["data"], $res["message"]);
    }
}
