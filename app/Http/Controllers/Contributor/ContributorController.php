<?php

namespace App\Http\Controllers\Contributor;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Services\IContributorService;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    private $contributorService;
    public function __construct(IContributorService $contributorService)
    {
        $this->contributorService = $contributorService;
    }

    public function findById($id)
    {
        return MyHelper::customResponse($this->contributorService->findById($id));
    }

    public function getRandomAuthor()
    {
        return MyHelper::customResponse($this->contributorService->getRandomAuthor());
    }
}
