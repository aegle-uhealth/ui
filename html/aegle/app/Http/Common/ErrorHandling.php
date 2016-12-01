<?php

namespace App\Http\Common;

use Exception;
use ErrorException;

class ErrorHandling 
{

 private static $FileNotFoundException = 2 ;
 private static $FileAlreadyExistsException = 1 ;

 //Form Add New Workflow
 public static $SpaceInWorkflowNameException = 175138 ;

  public static function GetErrorMessage(Exception $e){
  	$strMessage='';
  	
  	if(intval($e->getCode())==self::$FileNotFoundException){
  		$strMessage = 'Analytics cannot be added required files not available';
  	}elseif(intval($e->getCode())==self::$FileAlreadyExistsException){
  		$strMessage = 'Analytics already exists';
    }elseif(intval($e->getCode())==self::$SpaceInWorkflowNameException){
      $strMessage = 'A workflow name cannot contain spaces';
  	}else{
      $strMessage = $e->getMessage();
    }

    return response()->json([
                'success' => 'false',
                'errors'  => $strMessage,
            ], 400);

  }

}


