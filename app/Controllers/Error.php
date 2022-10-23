<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Error extends BaseController
{

    protected $helpers = ['html'];

    public function forbidden()
    {
        return view('App\Views\errors\html\error_403');
    }
}
