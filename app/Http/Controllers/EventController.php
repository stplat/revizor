<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\EventIndex;
use App\Http\Requests\Event\EventViolations;
use App\Services\EventService;

class EventController extends Controller
{
  protected $eventService;

  public function __construct(EventService $eventService)
  {
    $this->eventService = $eventService;
  }

  /**
   * Display a listing of the resource.
   *
   * @param EventIndex $request
   * @return array
   */
  public function index(EventIndex $request)
  {
    $check_id = $request->input('check_id');

    return collect([
      'events' => $this->eventService->getViolations($check_id),
      'eventsByType' => $this->eventService->getEventsByType($check_id),
      'eventsByCheck' => $this->eventService->getEventsByCheck($check_id),
      'checkedEventsByRole' => $this->eventService->getCheckedEventsByRole($check_id),
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
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function show($id)
  {
    return $this->eventService->getEvent($id);
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

  /**
   * Нарушения
   *
   * @param EventIndex $request
   * @return array
   */
  public function violations(EventViolations $request)
  {
    $check_id = $request->input('check_id');

    return collect([
      'violations' => $this->eventService->getViolations($check_id),
      'violationsByStatus' => $this->eventService->getViolationsByStatus($check_id),
      'violationsByType' => $this->eventService->getViolationsByType($check_id),
      'violationsByRegion' => $this->eventService->getViolationsByRegion($check_id),
    ]);
  }

  /**
   * Нарушение
   *
   * @param EventIndex $request
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function violation($id)
  {
    return $this->eventService->getViolation($id);
  }
}
