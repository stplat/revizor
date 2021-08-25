<?php

namespace App\Http\Controllers;

use App\Services\ServerService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\SettingsService;
use Illuminate\View\View;

class SettingsController extends Controller
{
  protected $settingsService;

  public function __construct(SettingsService $settingsService)
  {
    $this->settingsService = $settingsService;
  }

  /**
   * @param ServerService $serverService
   *
   * @return View
   */
  public function index(ServerService $serverService)
  {
    $data = collect([
      'servers' => $serverService->getAll()
    ]);

    return view('settings')->with('data', $data);
  }
}
