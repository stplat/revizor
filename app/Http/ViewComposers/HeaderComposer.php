<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class HeaderComposer
{
  protected $cartService;

  public function __construct()
  {
  }

  public function compose(View $view)
  {

    $view->with([
      'cartCount' => session()->get('cart') ? session()->get('cart')['count'] : 0,
      'callback' => session()->get('callback') ?? 0,
      'menu' => ''
    ]);
  }
}
