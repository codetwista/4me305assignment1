<?php

namespace App\Controllers;


class HomeController extends BaseController
{
    /**
     * Default page
     * @return string
     */
    public function index()
    {
        return view('home', [
            'title' => 'Web 2.0 Development - Welcome',
        ]);
    }
}
