<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use org\apache\hadoop\WebHDFS;
use App\Http\Requests;

use App\Http\Common\ErrorHandling;
use App\Http\Common\XMLParser;

use Laracurl;
use SSH;
use Session;

class WorkflowController extends Controller
{
    public function index()
    {
        try {
          
          session_start();
          $case_type=strtolower($_SESSION["case_select"]);

          if($case_type != "visualization"){
            $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.strtoupper($case_type).'/Workflows/?user.name=root&op=LISTSTATUS';
          }else{
            $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'. $case_type .'/Workflows/?user.name=root&op=LISTSTATUS';
          }
          $path_raw = Laracurl::get($server_response);
          $path_list = json_decode($path_raw, true);

          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/prototype/tools/'.$case_type.'/?user.name=root&op=LISTSTATUS';
          $path_raw = Laracurl::get($server_response);
          $tool_list = json_decode($path_raw, true);

          return View('workflows.workflows', ['path_list' => $path_list], ['tool_list' => $tool_list])->with('case_type',$case_type);

         }catch(\Exception $e){
            return ErrorHandling::GetErrorMessage($e);
         }
        
    }

    public function addWorkflow(Request $request)
    {
        
       try {
          session_start();
          $case_type=$_SESSION["case_select"];

          $name = $_POST['name'];
          $desc = $_POST['desc'];
          $lic = $_POST['lics'];

          // $name = $request->name;
          // $desc = $request->desc;
          // $lic = $request->lics;

          //$hdfs = new WebHDFS('mynamenode.hadoop.com', '50070', 'hadoop-username');
          //$hdfs->mkdirs('user/hadoop-username/new/directory/structure');

          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/'.$name.'?user.name=root&op=MKDIRS';
          $path_raw = Laracurl::put($server_response);

          if ($path_raw->statusCode ==='400'){
              throw new \Exception('',ErrorHandling::$SpaceInWorkflowNameException);
          }

          $this->CrearteWorkflowXML($name, $desc, $lic);

          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/?user.name=root&op=LISTSTATUS';
          $path_raw = Laracurl::get($server_response);
          $path_list = json_decode($path_raw, true);

          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/prototype/tools/'.strtolower($case_type).'/?user.name=root&op=LISTSTATUS';
          $path_raw = Laracurl::get($server_response);
          $tool_list = json_decode($path_raw, true);

          return View('workflows.workflows', ['path_list' => $path_list], ['tool_list' => $tool_list])->with('case_type',$case_type);

        }catch(\Exception $e){
          return ErrorHandling::GetErrorMessage($e);
        }

    }

    private function CrearteWorkflowXML($name, $desc, $lic)
    {
        
      try {
        //session_start();
        $case_type=$_SESSION["case_select"];

        $workflowXML = new \SimpleXMLElement("<workflow></workflow>");
        $workflowName = $workflowXML->addChild('name',$name);
        $workflowName = $workflowXML->addChild('desc', $desc);
        $workflowName = $workflowXML->addChild('lic', $lic);
        Header('Content-type: text/xml');

         $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
         $response = $hdfs->createWithData($case_type.'/Workflows/'.$name.'/'.$name.'.xml',  $workflowXML->asXML());

      }catch(\Exception $e){
        return ErrorHandling::GetErrorMessage($e);
      }

    }

    public function getWorkflowDetail()
    {        
        
        try {
          session_start();
          $case_type=$_SESSION["case_select"];

          $filename = $_GET['filename'];
            
          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.$case_type.'/Workflows/'. $filename.'/?user.name=root&op=LISTSTATUS';
          $path_raw = Laracurl::get($server_response);
          $path_list = json_decode($path_raw, true);

          foreach ($path_list as $pathkey => $FileStatuses) {
            foreach ($FileStatuses as $FileStatuseskey => $FileStatus) {
              foreach ($FileStatus as $key => $value) {
                if (substr($value['pathSuffix'], 0, -4) == $filename){
                  unset($path_list[$pathkey][$FileStatuseskey][$key]);
                }
                if (substr($value['pathSuffix'],-4) != '.xml'){
                  unset($path_list[$pathkey][$FileStatuseskey][$key]);
                }
              } 
            }
          }

          return $path_list;  

        }catch(\Exception $e){
          return ErrorHandling::GetErrorMessage($e);
        }
    }

      public function addTool(Request $request)
      {
          
          try{
          	
            session_start();
	          $case_type=strtolower($_SESSION["case_select"]);

	          $workflowName = $_POST['workflow_name'];
	          $filename = $_POST['analytics_name'];
	          $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename);

	          $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
	          $response = $hdfs->open('prototype/tools/'.$case_type.'/'.$filename);

	          $xml = simplexml_load_string($response);

	          $scriptfile = $xml->command->script;
	          $scripfileresp = $hdfs->open('prototype/tools/'.$case_type.'/'.$scriptfile);

	          $hdfs->createWithData(strtoupper($case_type).'/Workflows/'.$workflowName.'/'.$filename,  $response);
	          $hdfs->createWithData(strtoupper($case_type).'/Workflows/'.$workflowName.'/'.$scriptfile,  $scripfileresp);

	          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/'.strtoupper($case_type).'/Workflows/?user.name=root&op=LISTSTATUS'; 
	          $path_raw = Laracurl::get($server_response); 
	          $path_list = json_decode($path_raw, true); 

	          $server_response = 'http://83.212.112.144:50070/webhdfs/v1/prototype/tools/'.$case_type.'/?user.name=root&op=LISTSTATUS';
	          $path_raw = Laracurl::get($server_response); 
	          $tool_list = json_decode($path_raw, true); 

	          return View('workflows.workflows', ['path_list' => $path_list], ['tool_list' => $tool_list])->with('case_type',$case_type);

           }catch(\Exception $e){
           		return ErrorHandling::GetErrorMessage($e);
            }   
      }


      public function runScript(Request $request)
      {
          try{
              
              session_start();
              $case_type=$_SESSION["case_select"];
              $luigi_script = $_SESSION["luigi_script"];
              $workflow_name = $_POST['workflow'];
              $file_name=$case_type . date("YmdHis");
              $folder_name = date('d_M_y_Hi');
            
              $newFileName = '/home/aegle_welcome/testdir/'.$file_name.".py";
              
              if (file_put_contents($newFileName, $luigi_script) !== false) {
                 
                echo "File created (" . basename($newFileName) . ")";
                 
                $output = shell_exec('cd /home/aegle_welcome/testdir ; /usr/bin/nohup  python '. $file_name .'.py executeworkflow --local-scheduler >/dev/null 2>&1 &');

                $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
                $hdfs->mkdirs($case_type.'/Workflows/'.$workflow_name.'/'.$folder_name);
              
                if($workflow_name=="MainClonoGeneRepExt" && $case_type=="CLL"){
   
                  // Copy three output files to new folders
                  $fromFilePath=$case_type.'/Results/clonosVCDR3_full_schema_v01.xml';
                  $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/clonosVCDR3_full_schema_v01.xml';
                  $response = $hdfs->open($fromFilePath);
                  $hdfs->createWithData($newFilePath,$response);

                  $fromFilePath=$case_type.'/Results/filterin_schema_v01.xml';
                  $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/filterin_schema_v01.xml';
                  $response = $hdfs->open($fromFilePath);
                  $hdfs->createWithData($newFilePath,$response);

                  $fromFilePath=$case_type.'/Results/imgtReport_schema_v01.xml';
                  $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/imgtReport_schema_v01.xml';
                  $response = $hdfs->open($fromFilePath);
                  $hdfs->createWithData($newFilePath,$response);
                
                  return "Workflow started successfully";

                }elseif ($workflow_name=="IEsFeatExtFrmPVIData" && $case_type=="ICU") {
                 
                  // Copy both output files to new folders
                  $fromFilePath=$case_type.'/Results/IEProcessedPVI_schema_v01.xml';
                  $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/IEProcessedPVI_schema_v01.xml';
                  $response = $hdfs->open($fromFilePath);
                  $hdfs->createWithData($newFilePath,$response);

                  $fromFilePath=$case_type.'/Results/preprocessedPVI_schema_v01.xml';
                  $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/preprocessedPVI_schema_v01.xml';
                  $response = $hdfs->open($fromFilePath);
                  $hdfs->createWithData($newFilePath,$response);

                  return "Workflow started successfully";
              
                }

              } else {
                  echo "Cannot create file (" . basename($newFileName) . ")";
              }

          }catch(\Exception $e){
            return ErrorHandling::GetErrorMessage($e);
          }
       
      }

      // public function runScript(Request $request)
      // {
      //     try{
              
      //         session_start();
      //         $case_type=$_SESSION["case_select"];

      //         $workflow_name = $_POST['workflow'];
      //         $folder_name = date('d_M_y_Hi');

      //         $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
      //         $hdfs->mkdirs($case_type.'/Workflows/'.$workflow_name.'/'.$folder_name);
              
      //         if($workflow_name=="MainClonoGeneRepExt" && $case_type=="CLL"){
 
      //           // Copy three output files to new folders
      //           $fromFilePath=$case_type.'/Results/clonosVCDR3_full_schema_v01.xml';
      //           $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/clonosVCDR3_full_schema_v01.xml';
      //           $response = $hdfs->open($fromFilePath);
      //           $hdfs->createWithData($newFilePath,$response);

      //           $fromFilePath=$case_type.'/Results/filterin_schema_v01.xml';
      //           $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/filterin_schema_v01.xml';
      //           $response = $hdfs->open($fromFilePath);
      //           $hdfs->createWithData($newFilePath,$response);

      //           $fromFilePath=$case_type.'/Results/imgtReport_schema_v01.xml';
      //           $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/imgtReport_schema_v01.xml';
      //           $response = $hdfs->open($fromFilePath);
      //           $hdfs->createWithData($newFilePath,$response);

      //           #SSH::run([
      //            #   'cd aegle',
      //             #  'python icuworkflow.py executeworkflow --local-scheduler'
      //           #]);


    	 //        $output = shell_exec('cd /home/aegle_welcome/testdir ; /usr/bin/nohup  python cllworkflow.py executeworkflow --local-scheduler >/dev/null 2>&1 &');
	            
      //         return "Workflow started successfully";

      //         }elseif ($workflow_name=="IEsFeatExtFrmPVIData" && $case_type=="ICU") {
               
      //           // Copy both output files to new folders
      //           $fromFilePath=$case_type.'/Results/IEProcessedPVI_schema_v01.xml';
      //           $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/IEProcessedPVI_schema_v01.xml';
      //           $response = $hdfs->open($fromFilePath);
      //           $hdfs->createWithData($newFilePath,$response);

      //           $fromFilePath=$case_type.'/Results/preprocessedPVI_schema_v01.xml';
      //           $newFilePath=$case_type.'/Workflows/'.$workflow_name.'/'.$folder_name.'/preprocessedPVI_schema_v01.xml';
      //           $response = $hdfs->open($fromFilePath);
      //           $hdfs->createWithData($newFilePath,$response);

      //           #SSH::run([
      //               #'cd aegle',
      //               #'python icuworkflow.py executeworkflow --local-scheduler'
      //           #]);
                
    	 //          $output = shell_exec('cd /home/aegle_welcome/testdir ;/usr/bin/nohup  python icuworkflow.py executeworkflow --local-scheduler >/dev/null 2>&1 &');

	     //          return "Workflow started successfully";
            
      //         }
              
      //         return 'Cant Run This Workflow';
      //     }catch(\Exception $e){
      //       return ErrorHandling::GetErrorMessage($e);

      //     }
      // }


      public function buildModelForm(Request $request)
      {
        try {
          session_start();
          $case_type=$_SESSION["case_select"];
          $workflowname = $_POST['workflow'];
          $filename = $_POST['filename'].'.xml';
          
          $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
          $response = $hdfs->open(strtoupper($case_type).'/Workflows/'.$workflowname.'/'.$filename);          

          $input_list = XMLParser::getInputs($response);
          
          return $input_list;

        }catch(\Exception $e){
          return ErrorHandling::GetErrorMessage($e);
        }
      }

      public function processAnalytic(Request $request)
      {
        try {

          session_start();
          $command_stack = array();
          $case_type=$_SESSION["case_select"];
          $array= json_decode($_POST['analytic']);
          $workflowname = $_POST['workflow'];
          $filename = $_POST['filename'].'.xml';
          $hdfs = new WebHDFS('83.212.112.144', '50070', 'root','false','8022','false');
          $response = $hdfs->open(strtoupper($case_type).'/Workflows/'.$workflowname.'/'.$filename);
          $script_path=strtoupper($case_type).'/Workflows/'.$workflowname.'/';


          //ICU case
          if ($workflowname=="IEsFeatExtFrmPVIData"){
            $luigi_script = XMLParser::generateICUAnalyticCommand($response,$array,$case_type,$script_path);
          //CLL case
          }else if ($workflowname=="MainClonoGeneRepExt"){
            $luigi_script = XMLParser::generateCLLAnalyticCommand($response,$array,$case_type,$script_path);
          }else{
            $analytic_command = XMLParser::generateAnalyticCommand($response,$array,$case_type,$script_path);
            array_push($command_stack, $analytic_command);
            $luigi_script = XMLParser::generateScript($command_stack,$script_path);
          }

          $_SESSION["luigi_script"] = $luigi_script;

          return $luigi_script;

        }catch(\Exception $e){
          return "Before execting the script make sure that all required analytics have data";
        }
      }
}
