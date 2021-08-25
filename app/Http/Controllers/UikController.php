<?php

namespace App\Http\Controllers;

use App\Http\Requests\Uik\UikShow;

use App\Services\UikService;

class UikController extends Controller
{
    /**
     * @var UikService
     */
    protected $uikService;

    public function __construct(UikService $uikService)
    {
        $this->uikService = $uikService;
    }
}
