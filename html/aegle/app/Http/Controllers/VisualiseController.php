<?php

namespace App\Http\Controllers;

use org\apache\hadoop\WebHDFS;
use Illuminate\Http\Request;
use App\Http\Common\XMLParser;
use App\Http\Requests;
use Laracurl;
use Session;


class VisualiseController extends Controller
{
    public function index()
    {
        //Index page
        return View('visualise.index');
    }

    public function buildAddVisualTree()
    {
    	session_start();
        $case_type=$_SESSION["case_select"];

        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/?user.name=root&op=LISTSTATUS';
        $path_raw = Laracurl::get($server_response);
        $json = json_decode($path_raw,true);

         $treelist = '[{
                       "text": "Datasets",';

        if(count($json) > 0)
        {
             $treelist .= '"nodes": [';
                       
            foreach($json['FileStatuses']['FileStatus'] as $item)
            {
            	if (strpos($item['pathSuffix'],'csv') != false)
            	{            		
            		$treelist .= '{"text": "'. substr($item['pathSuffix'], 0, -4).'"
  								   ,"selectable": "true"
                                   ,"href":"'.$case_type.'/'.$item['pathSuffix'].'"},';
            	}                
            }

            $treelist =rtrim($treelist, ",");            
            $treelist .= ']},';
        }


        ///////////// Analysis Result /////////////

        $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/?user.name=root&op=LISTSTATUS';
        $path_raw = Laracurl::get($server_response);
        $json = json_decode($path_raw,true);        

        $treelist .= '{"text": "Analysis Results",';

        if(count($json) > 0 && $path_raw->statusCode != 404)
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



        //echo $treelist;
        return $treelist;

    }

    public function getPath()
    {
        session_start();
        $case_type=$_SESSION["case_select"];
        $file_name = $_GET['filename'];
        $file_path = $_GET['filepath'];
        $file_type = substr($file_path, -3);

        if ($case_type=="visualization"){
            $url='http://83.212.98.38:3838/plotly_aegle_v2_serv/?test=';
        }elseif($case_type=="ICU"){
            $url='http://83.212.98.38:3838/plotly_aegle_ICU1/?test=';
        }elseif($case_type=="CLL"){
            $url='http://83.212.98.38:3838/plotly_aegle_CLL/?test=';
        }

        if($file_type=="xml")
        {
            $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
            $response = $hdfs->open($file_path);          
            $input_list = XMLParser::getFileName($response);

            $url = $url . "/" . $case_type . "/" . $input_list . ".csv";

        }elseif ($file_type=="csv") {

             $url = $url . "/" . $case_type . "/" . $file_name . ".csv";

        }

        return $url;
    }
}

