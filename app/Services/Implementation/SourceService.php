<?php

namespace App\Services\Implementation;

use App\Repositories\SourceRepositoryInterface;
use App\Services\ISourceService;

class SourceService implements ISourceService
{
    private $sourceRepository;

    public function __construct(SourceRepositoryInterface $sourceRepository)
    {
        $this->sourceRepository = $sourceRepository;
    }

    public function all()
    {
        return $this->sourceRepository->all();
    }
}
