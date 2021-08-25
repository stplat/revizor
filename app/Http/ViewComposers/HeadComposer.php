<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class HeadComposer
{

    public function __construct()
    {
    }

    public function compose(View $view)
    {
        $routeName = request()->route() ? request()->route()->getName() : '';
        $routeName = str_replace('.index', '', $routeName);
        $routeName = str_replace('s.show', '', $routeName);
        $cssFilePath = public_path('css/view-' . $routeName . '.css');

        if (request()->route() && file_exists($cssFilePath)) {
            $cssFilePath = asset('css/view-' . $routeName . '.css');
        } else {
            $cssFilePath = false;
        }

        $view->with([
            'cssFilePath' => $cssFilePath,
            'routeName' => $routeName
        ]);
    }
}
