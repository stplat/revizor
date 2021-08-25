<?php

namespace App\Http\Controllers;

use App\Models\Check;
use App\Services\IndexService;
use Illuminate\Database\Eloquent\Collection;

class IndexController extends Controller
{
  protected $indexService;

  public function __construct(IndexService $indexService)
  {
    $this->indexService = $indexService;
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('index');
  }

  /**
   * Получаем данные для графиков
   *
   * @return \Illuminate\Database\Eloquent\Collection
   */
  public function charts()
  {
    return collect([
      'checksCount' => $this->indexService->checksCount(),
      'UnEventCount' => $this->indexService->unEventsCount(),
      'violationCount' => $this->indexService->violationCount(),
      'serversOnlineCount' => $this->indexService->serversOnlineCount(),
      'violationsByTypes' => $this->indexService->violationsByTypes(),
      'violationsByRegions' => $this->indexService->violationsByRegions(),
      'violationsByStatuses' => $this->indexService->violationsByStatuses()
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param \Illuminate\Http\Request $request
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param int $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    //
  }
}
