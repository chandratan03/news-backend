<?php

namespace App\Http\Controllers\News;

use App\Constants\HttpResponse;
use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\NewsSyncDate;
use App\Services\NewsService;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsSyncController extends Controller
{

    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }


    public function sync()
    {
        $res = $this->newsService->sync();
        return MyHelper::customResponse($res["data"], $res["message"]);
    }
}
