<?php

namespace App\Http\Controllers;

use App\Http\Requests\Violation\ViolationShow;

use App\Services\ViolationService;

class ViolationController extends Controller
{
    /**
     * @var ViolationService
     */
    protected $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
    }
}
