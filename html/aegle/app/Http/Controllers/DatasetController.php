<?php

namespace App\Http\Controllers;

use org\apache\hadoop\WebHDFS;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracurl;
use Session;


class DatasetController extends Controller
{
    public function index()
    {      
        session_start();
        $case_type=$_SESSION["case_select"];

        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/?user.name=root&op=LISTSTATUS';
        $path_raw = Laracurl::get($server_response);
        $path_list = json_decode($path_raw, true);
               
        return View('datasets.dataset', ['path_list' => $path_list])->with('case_type',$case_type);
    }

    public function getDatasets(Request $request)
    {
        session_start();
        $case_type=$_SESSION["case_select"];

        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/?user.name=root&op=LISTSTATUS';
        $path_raw = Laracurl::get($server_response);
        $path_list = json_decode($path_raw, true);

        if ($request->isMethod('post')){    
            return response()->json(['response' => 'This is post method']); 
        }

        return response()->json(['response' => 'This is get method']);
    }

    public function getDetail()
    {
        $filename = $_GET['filename'];
        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/CLL/'.$filename.'?user.name=root&op=GETFILESTATUS';
        $path_raw = Laracurl::get($server_response);
        $desc = json_decode($path_raw, true);
        
       //echo var_dump($desc);
        return $desc;
    }

    public function getFileDetail()
    {        
          session_start();

          $filename = $_GET['filename'];

          $case_type = $_SESSION["case_select"];

          $sPath = $case_type . '/' . substr($_GET['filename'], 0, -4) . '.xml' ;

          #$sPath = 'yarn-site.xml' ;

          $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
  
          $status = $hdfs->GETFILESTATUS($sPath);
          
          #$json = json_encode($status);
          #$json_output = json_decode($status);
          #$length = $json_output->FileStatus->length;

          if (strpos($status, 'FileNotFoundException') == false) {
            $response='';
            $response .=  $hdfs->open($sPath);
            strlen($response);
            $xml = simplexml_load_string($response);
            $json_output = json_decode($response);
          
            $columnNames='';

            foreach($xml as $key => $value)
            {
                $device[(string)$key] = (string)$value;
                
                if($key=='columns'){
                  # Get all the colums 
                  $columns = $this->get_string_between($response, '</sharing>', '</file>');
                  $xmlcolumns = simplexml_load_string($columns);

                  foreach ($xmlcolumns as $keycolumns => $valuecolumn) {

                    foreach($valuecolumn->attributes() as $a => $b) {
                      if($a=='name'){
                        if((string) $b != ''){
                           $columnNames .= (string) $b . ' , ';
                        }
                      }
                    }
                  }
                  
                  $columnNames = trim($columnNames);
                  $device[(string)$key] = rtrim($columnNames,",");
                }
            }

          }
          else{
            return null;
          }          
          
          return $device;
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
