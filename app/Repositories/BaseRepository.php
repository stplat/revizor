<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Application;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository
{
  /**
   * @var Model
   */
  protected $model;

  /**
   * BaseRepositories constructor.
   */
  public function __construct()
  {
    $this->model = app($this->getModelClass());
  }

  /**
   * @return mixed
   */
  abstract protected function getModelClass();

  /**
   * @return Model|Application|mixed
   */
  protected function startCondition()
  {
    return clone $this->model;
  }
}
