<?php

namespace App\Services;

use App\Repositories\UikRepository;

class UikService
{
    /**
     * @var UikRepository
     */
    protected $uikRepository;

    /**
     * UikService constructor.
     */
    public function __construct()
    {
        $this->uikRepository = app(UikRepository::class);
    }
}
