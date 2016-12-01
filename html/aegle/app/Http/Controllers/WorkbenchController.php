<?php

namespace App\Http\Controllers;

use org\apache\hadoop\WebHDFS;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracurl;
use Session;


class WorkbenchController extends Controller
{
    public function index()
    {
    	try {
          
          session_start();
          //$case_type=strtolower($_SESSION["case_select"]);

          $server_response = 'http://83.212.112.144:8088/ws/v1/cluster/apps/';
          $path_raw = Laracurl::get($server_response);
          $workflows_list = json_decode($path_raw, true);

          return View('workbench.index', ['workflows_list' => $workflows_list]);

         }catch(\Exception $e){
            return ErrorHandling::GetErrorMessage($e);
         }

        //return View('workbench.index');
    }
}