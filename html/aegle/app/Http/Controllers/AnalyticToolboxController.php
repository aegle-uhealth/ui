<?php

namespace App\Http\Controllers;

use App\Http\Common\ErrorHandling;
use org\apache\hadoop\WebHDFS;
use Illuminate\Http\Request;
use App\Http\Requests;
use Laracurl;


class AnalyticToolboxController extends Controller
{
    public function index()
    {
        session_start();
        $case_type=strtolower($_SESSION["case_select"]);

        //http://83.212.112.144:50070/explorer.html#/prototype/tools/icu
        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/prototype/tools/'.$case_type.'/?user.name=root&op=LISTSTATUS';
        $path_raw = Laracurl::get($server_response);
        $path_list = json_decode($path_raw, true);

        return View('analyticstoolbox.analyticstoolbox' , ['path_list' => $path_list]);
    }

    public function getFileDetail()
    {        
       
       try {       
                session_start();
                $case_type=strtolower($_SESSION["case_select"]);

                $filename = $_GET['filename']; 
                $sPath = 'prototype/tools/'.$case_type.'/'.$filename ;

                $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
                $status = $hdfs->GETFILESTATUS($sPath);
                
                if (strpos($status, 'FileNotFoundException') == false) {
                    $response='';
                    $response .= $hdfs->open($sPath);
                    strlen($response);
                    $xml = simplexml_load_string($response);
                    $json_output = json_decode($response);
                  
                    $columnNames='';

                    foreach($xml as $key => $value)
                    {
                        $detail[(string)$key] = (string)$value;
                        
                        if($key=='inputs'){
                            # Get all the colums 
                            $columns = $this->get_string_between($response, '</command>', '<outputs>');
                            $xmlcolumns = simplexml_load_string($columns);

                            foreach ($xmlcolumns as $keycolumns => $valuecolumn) {

                            foreach($valuecolumn->attributes() as $a => $b) {
                                if($a=='name'){
                                if((string) $b != ''){
                                    $columnNames .= (string) $b . ' , ';
                                }
                                }elseif ($a=='label') {
                                if((string) $b != ''){
                                    $columnNames .= (string) $b . ' | ';
                                }
                                }
                            }
                            }
                          
                            $columnNames = trim($columnNames);
                            $detail[(string)$key] = rtrim($columnNames,"|");
                        }

                        $columnNames='';
                        if($key=='outputs'){
                            # Get all the colums 
                            $columns = $this->get_string_between($response, '</inputs>', '<help>');
                            $xmlcolumns = simplexml_load_string($columns);

                            foreach ($xmlcolumns as $keycolumns => $valuecolumn) {

                            foreach($valuecolumn->attributes() as $a => $b) {
                                if($a=='name'){
                                if((string) $b != ''){
                                    $columnNames .= (string) $b . ' , ';
                                }
                                }elseif ($a=='tag') {
                                if((string) $b != ''){
                                    $columnNames .= (string) $b . ' | ';
                                }
                                }
                            }
                            }
                          
                            $columnNames = trim($columnNames);
                            $detail[(string)$key] = rtrim($columnNames,"|");
                        }
                    }
                }
                else{
                    return null;
                }   
                return $detail;

            }catch(\Exception $e){
              return ErrorHandling::GetErrorMessage($e);
            }

     }

  function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
      }
}

?>
