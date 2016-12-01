<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Laracurl;


class CaseController extends Controller
{
    public function index($user_role)
    {

        return View('case.index', ['user_role' => $user_role]);
    }
}
