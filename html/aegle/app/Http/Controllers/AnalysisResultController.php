<?php

namespace App\Http\Controllers;

use org\apache\hadoop\WebHDFS;
use Illuminate\Http\Request;

use App\Http\Requests;
use Laracurl;
use Session;


class AnalysisResultController extends Controller
{
    public function index()
    {
        return View('analysisresult.index');
    }

    public function buildTree()
    {
        
            session_start();
            $case_type=$_SESSION["case_select"];

            $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/?user.name=root&op=LISTSTATUS';
            $path_raw = Laracurl::get($server_response);
            $json = json_decode($path_raw,true);

            $treelist = '[{
                           "text": "Analysis Results",';

            if(count($json) > 0)
            {
                 $treelist .= '"nodes": [';
                           
                foreach($json['FileStatuses']['FileStatus'] as $item)
                {                
                    $treelist .= '{"text": "'. $item['pathSuffix'].'"';
                    
                    $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/'.$item['pathSuffix'].'?user.name=root&op=LISTSTATUS';

                    $path_raw = Laracurl::get($server_response);
                    $json2 = json_decode($path_raw,true);

                     if(count($json2) > 0)
                    {
                        $treelist .= ',"nodes": [';
                           
                        foreach($json2['FileStatuses']['FileStatus'] as $item_2)
                        {                            
                            if($item_2['type'] == 'DIRECTORY')              
                            {
                                $treelist .= '{"text": "'. $item_2['pathSuffix'].'"';

                                $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/'.$item['pathSuffix'].'/'.$item_2['pathSuffix'].'?user.name=root&op=LISTSTATUS';

                                $path_raw = Laracurl::get($server_response);
                                $json3 = json_decode($path_raw,true);

                                if(count($json3) > 0)
                                {
                                    $path = $item['pathSuffix'].'/'.$item_2['pathSuffix'];
                                    $treelist .= ',"nodes": [';

                                    foreach($json3['FileStatuses']['FileStatus'] as $item_3)
                                    {
                                        $treelist .= '{"text": "'. substr($item_3['pathSuffix'], 0, -4).'", "href":"'.$case_type .'/Workflows/'. $item['pathSuffix'] . '/' .$item_2['pathSuffix'] .'/'  . $item_3['pathSuffix'].'"}';

                                        $treelist .= ',';
                                    }

                                    $treelist =rtrim($treelist, ",");
                                    $treelist .= ']';
                                }

                                $treelist .= '},';
                            }
                        }

                        $treelist =rtrim($treelist, ",");
                        $treelist .= ']';
                    }

                    $treelist .= '},';
                }

                $treelist =rtrim($treelist, ",");
                $treelist .= ']';
            }

            $treelist .= '}]';
            return $treelist;
    }

    public function getFileDetail()
    {                  
        $path = $_GET['path'];        

          //echo $path;

          $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');  
          $status = $hdfs->GETFILESTATUS($path);
          
          #$json = json_encode($status);
          #$json_output = json_decode($status);
          #$length = $json_output->FileStatus->length;

          //echo $status;

          if (strpos($status, 'FileNotFoundException') == false) 
          {
            $response='';
            $response .=  $hdfs->open($path);
            strlen($response);
            $xml = simplexml_load_string($response);
            //$json_output = json_decode($response);
            
            //echo "JSon Output <br>";
            //echo $xml->description;
            $columnNames='';

            foreach($xml as $key => $value)
            {

                $device[(string)$key] = (string)$value;                

                if($key=='columns'){
                    $columns = $this->get_string_between($response, '</description>', '</file>');
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

        //echo $device->description;
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