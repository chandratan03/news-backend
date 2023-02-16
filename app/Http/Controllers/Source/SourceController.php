<?php

namespace App\Http\Controllers\Source;

use App\Helper\MyHelper;
use App\Http\Controllers\Controller;
use App\Services\ISourceService;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    private $sourceService;
    public function __construct(ISourceService $sourceService)
    {
        $this->sourceService = $sourceService;
    }

    public function index()
    {
        return MyHelper::customResponse($this->sourceService->all());
    }
}
