<?php

namespace App\Services;

use App\Repositories\ViolationRepository;

class ViolationService
{
    /**
     * @var ViolationRepository
     */
    protected $violationRepository;

    /**
     * ViolationService constructor.
     */
    public function __construct()
    {
        $this->violationRepository = app(ViolationRepository::class);
    }
}
