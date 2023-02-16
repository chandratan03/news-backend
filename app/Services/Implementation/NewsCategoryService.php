<?php

namespace App\Services\Implementation;

use App\Repositories\NewsCategoryRepositoryInterface;
use App\Services\INewsCategoryService;

class NewsCategoryService implements INewsCategoryService
{
    private $newsCategoryRepository;

    public function __construct(NewsCategoryRepositoryInterface $newsCategoryRepository)
    {
        $this->newsCategoryRepository = $newsCategoryRepository;
    }

    public function all()
    {
        return $this->newsCategoryRepository->all();
    }
}
