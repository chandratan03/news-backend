<?php

namespace App\Http\Controllers\News;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Repositories\NewsCategoryRepositoryInterface;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    private $newsCategoryRepository;
    public function __construct(
        NewsCategoryRepositoryInterface $newsCategoryRepository
    ) {
        $this->newsCategoryRepository = $newsCategoryRepository;
    }
    public function index()
    {
        return MyHelper::customResponse($this->newsCategoryRepository->all());
    }
}
