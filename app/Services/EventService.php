<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventStatus;
use App\Models\Uik;
use App\Models\User;

class EventService
{

  /**
   * Получаем события
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getEvents($check_id)
  {
    return Event::with(['type', 'uik'])->where('check_id', $check_id)->orderBy('event_id')->get();
  }

  /**
   * Получаем событие
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getEvent($id)
  {
    return Event::with(['type', 'uik'])->where('event_id', $id)->first();
  }

  /**
   * Получаем события по типам
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getEventsByType($check_id)
  {
    return Event::with(['type', 'uik'])->where('check_id', $check_id)->get()->groupBy('event_type_id')->map(function ($item) {
      return collect([
        'type' => $item[0]->type->type_name ?? '',
        'count' => count($item)
      ]);
    })->values();
  }

  /**
   * Получаем события проверено/не проверено
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getEventsByCheck($check_id)
  {
    return collect([
      'checked' => Event::with(['type', 'uik'])->where('check_id', $check_id)->where('checked', 'false')->get()->count(),
      'unchecked' => Event::with(['type', 'uik'])->where('check_id', $check_id)->where('checked', 'true')->get()->count(),
    ]);
  }

  /**
   * Получаем проверенные проверки по Ролям
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getCheckedEventsByRole($check_id)
  {
    $events = Event::where('check_id', $check_id)->get()->pluck('event_id');

    return User::with(['check_results' => function ($query) use ($events) {
      return $query->whereIn('event_id', $events);
    }, 'role'])->get()->filter(function ($item) {
      return $item->check_results->count();
    })->groupBy('role_id')->map(function ($item) {
      return collect([
        'role' => $item[0]->role->role_name,
        'count' => $item[0]->check_results->count(),
      ]);
    })->values();
  }

  /**
   * Получаем нарушения
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getViolations($check_id)
  {
    return Event::with(['type', 'uik', 'status'])->whereHas('result', function ($query) {
      return $query->where('violation', true);
    })->where('check_id', $check_id)->orderBy('event_id')->get();
  }

  /**
   * Получаем нарушения по статусам
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getViolationsByStatus($check_id)
  {
    return Event::with(['type', 'uik'])->whereHas('result', function ($query) {
      return $query->where('violation', true);
    })->where('check_id', $check_id)->get()->groupBy('status_id')->map(function ($item) {
      return collect([
        'status' => $item[0]->status->status_name ?? '',
        'count' => count($item)
      ]);
    })->values();
  }

  /**
   * Получаем нарушения по статусам
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getViolationsByType($check_id)
  {
    return Event::with(['type', 'uik'])->whereHas('result', function ($query) {
      return $query->where('violation', true);
    })->where('check_id', $check_id)->get()->groupBy('event_type_id')->map(function ($item) {
      return collect([
        'type' => $item[0]->type->type_name ?? '',
        'count' => count($item)
      ]);
    })->values();
  }

  /**
   * Получаем нарушения по регионам
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getViolationsByRegion($check_id)
  {
    return Uik::with(['events' => function ($query) use ($check_id) {
      return $query->where('check_id', $check_id)->whereHas('result', function ($query) {
        return $query->where('violation', true);
      });
    }])->get()->map(function ($item) {
      $item->events = count($item->events);
      return $item;
    })->groupBy('region_number')->map(function ($item) {
      return collect([
        'region' => $item[0]->region,
        'count' => $item->sum('events'),
      ]);
    })->filter(function ($item) {
      return $item['count'];
    })->values();
  }

  /**
   * Получаем нарушение
   *
   * @param $check_id
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   */
  public function getViolation($id)
  {
    return Event::with(['type', 'uik'])->where('event_id', $id)->whereHas('result', function ($query) {
      return $query->where('violation', true);
    })->first();
  }

}
