<?php

namespace App\Services;

use App\Repositories\ContributorRepositoryInterface;
use App\Services\Interfaces\IContributorService;
use Illuminate\Http\Request;

class ContributorService implements IContributorService
{
    private $contributorRepository;

    public function __construct(ContributorRepositoryInterface $contributorRepository)
    {
        $this->contributorRepository = $contributorRepository;
    }

    public function findById($id){
        return $this->contributorRepository->findById($id);
    }

    public function getRandomAuthor()
    {
        return $this->contributorRepository->all()->random(6);
    }
}
