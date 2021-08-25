<?php

namespace App\Services;

use App\Models\Check;
use App\Models\Event;
use App\Models\Server;

class IndexService
{
  /**
   * Количество проверок
   *
   * @return Number
   */
  public function checksCount()
  {
    return Check::all()->count();
  }

  /**
   * Количество непроверенных событий
   *
   * @return Number
   */
  public function unEventsCount()
  {
    return Event::where('status_id', 1)->get()->count();
  }

  /**
   * Количество нарушений
   *
   * @return Number
   */
  public function violationCount()
  {
    return Event::whereHas('result', function ($query) {
      $query->where('violation', true);
    })->get()->count();
  }

  /**
   * Количество серверов онлайн
   *
   * @return Number
   */
  public function serversOnlineCount()
  {
    return Server::where('online', true)->get()->count();
  }

  /**
   * Виды выявленных событий
   *
   * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
   */
  public function violationsByTypes()
  {
    return Event::whereHas('result', function ($query) {
      $query->where('violation', true);
    })->with('type')->get()->groupBy('event_type_id')->map(function ($item) {
      return collect([
        'type' => $item && count($item) && $item[0]->type ? $item[0]->type['type_name'] : 'н/д',
        'count' => count($item),
      ]);
    })->values();
  }

  /**
   * Нарушения по регионам
   *
   * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
   */
  public function violationsByRegions()
  {
    return Event::whereHas('result', function ($query) {
      $query->where('violation', true);
    })->with('uik')->get()->groupBy('uik_id')->map(function ($item) {
      return collect([
        'region' => count($item) ? $item[0]->uik ? $item[0]->uik['region_number'] : '' : '',
        'count' => count($item),
      ]);
    })->groupBy('region')->map(function ($item, $key) {
      return collect([
        'region' => $key ?: 'НЕИЗВЕСТНЫЙ РЕГИОН',
        'count' => count($item),
      ]);
    })->values();
  }

  /**
   * Нарушения по статусам
   *
   * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
   */
  public function violationsByStatuses()
  {
    return Event::whereHas('result', function ($query) {
      $query->where('violation', true);
    })->with('type')->get()->groupBy('status_id')->map(function ($item) {
      return collect([
        'status' => $item && count($item) ? $item[0]->status['status_name'] : '',
        'count' => count($item),
      ]);
    })->values();
  }
}
