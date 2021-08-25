<?php

namespace App\Services;

use App\Models\Server;
use App\Models\ServerType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ServerService
{
  protected $tableService;

  public function __construct(TableService $tableService)
  {
    $this->tableService = $tableService;
  }

  /**
   * Получение данных о всех серверов
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getAll()
  {
    return Server::with('serverType')->get();
  }

  /**
   * Получение данных о конкретном сервере
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getOne($id)
  {
    return Server::find($id);
  }

  /**
   * Получение данных о серверах на основе массива параметров
   *
   * @param array $parameters
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getByParameters(array $parameters)
  {
    //
  }

  /**
   * Добавление одного или более серверов с возможностью формирования файла
   * экспорта
   *
   * @param TableService $tableService
   * @param integer $qty
   * @param array $parameters
   * @param boolean $export
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function store(array $parameters, int $qty = 1, bool $export = false)
  {
    $serverType = $this->getServerType($parameters['server_type_id']);

    $newServers = [];
    $passwords = [];

    for ($i = 0; $i < $qty; $i ++) {
      $password = Str::random(10);
      $passwords[] = $password;

      $newServers[] = [
        'online' => false,
        'password' => Hash::make($password),
        'server_ip' => 'none'
      ];
    }

    $createdServers = $serverType->servers()->createMany($newServers);

    $serversToExport = [];
    for ($i = 0; $i < $qty; $i ++) {
      $serversToExport[] = [
        'id' => $createdServers[$i]->server_id,
        'password' => $passwords[$i]
      ];
    }

    $result = collect(['servers' => $this->getAll()]);

    if ($export) {
      $result->put('exportUrl', $this->tableService->export($serversToExport, ['Server ID' => 'ID сервера', 'Password' => 'Пароль']));
    }

    return $result;
  }

  /**
   * Получение данных о всех типах серверов
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getServerTypes()
  {
    return ServerType::get();
  }

  /**
   * Получение данных о конкретном типе серверов
   *
   * @param integer $id
   *
   * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object
   * @return \Illuminate\Support\Collection
   */
  public function getServerType(int $id)
  {
    return ServerType::find($id);
  }
}
