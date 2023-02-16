<?php

namespace App\Http\Controllers\News;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Repositories\NewsCategoryRepositoryInterface;
use App\Services\INewsCategoryService;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    private $newsCategoryService;
    public function __construct(
        INewsCategoryService $newsCategoryService
    ) {

        $this->newsCategoryService = $newsCategoryService;
    }
    public function index()
    {
        return MyHelper::customResponse($this->newsCategoryService->all());
    }
}
