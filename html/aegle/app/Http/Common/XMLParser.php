<?php

namespace App\Http\Common;

use Exception;
use ErrorException;

class XMLParser
{
	public static function getInputs($response)
	{
    $attr="name";
    $xml = simplexml_load_string($response);
    $input_list="";
    $parser = new XMLParser();

    foreach ($xml->inputs->param as $param) {
      //if($param['name'] != "input")
      //{
        $input_list .= $parser->getInputTag($param,'no');
      //}
    }

    foreach ($xml->inputs->conditional as $conditions) {
      $conName = $conditions['name'];

      foreach ($conditions->param as $param) {
        $input_list .= $parser->getInputTag($param, 'yes');    
      }

      foreach ($conditions->when as $when) {
        if($when['value']=='y') {
          foreach ($when->param as $whenParam) {
            $name = $whenParam['name'];
            $label = $whenParam['label'];  

            if($whenParam['type']=='text'){
              $input_list .= $parser->setTextInputForCondition($name, $conName, $label);
            }
            elseif($whenParam['type'] == "float" || $whenParam['type'] == "integer") 
            {
              $size=$whenParam['size'];
              $min=$whenParam['min'];
              $max=$whenParam['max'];
              $value=$whenParam['value'];

              $input_list .= $parser->setNumberInputForCondition($name, $conName, $label, $size, $min, $max, $value); 
              
            }
          }
        }
      }      
    }
    return $input_list;
	}

  private function getInputTag($inputParam, $onchange)
  {
    $parser = new XMLParser();

    $name = $inputParam['name'];
    $label = $inputParam['label'];   

    if($inputParam['type'] == "text")
    {         
      return $parser->setTextInput($name, $label);  
    }
    elseif($inputParam['type'] == "select")
    {
      return $parser->setSelectInput($name, $label, $inputParam, $onchange); 
    }
    elseif($inputParam['type'] == "float" || $inputParam['type'] == "integer")
    {
      $size=$inputParam['size'];
      $min=$inputParam['min'];
      $max=$inputParam['max'];
      $value=$inputParam['value'];

      return $parser->setNumberInput($name, $label, $size, $min, $max, $value); 
    }
    elseif ($inputParam['type'] == "file") 
    {
      return $parser->setFileTextInput($name, $label, $inputParam['tag'], $inputParam['format']);
    }
    
  }

  private function setTextInput($name, $label)
  {    
    $strInput = '<div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <input type="text"  class="form-control"  name="'.$name.'" id="'.$name.'"  required>
    </div>';

    return $strInput;
  }

  private function setFileTextInput($name, $label, $tag, $format)
  {    
    $strInput = '<div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <input type="text"  class="form-control"  name="'.$name.'" value="'.$tag.'.'.$format.'" id="'.$name.'"  required>
    </div>';

    return $strInput;
  }

  private function setSelectInput($name, $label, $options, $onchange)
  {
    $strInput = '<div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <select class="form-control"  name="'.$name.'" id="'.$name.'"';
      if($onchange=='yes')
        $strInput .= 'onchange="ctrlVisbile(this)"';
      $strInput .= '>';     
    
    foreach ($options->option as $option) {
        $strInput .= '<option value="'.$option['value'].'"';
        if($option['selected']!=null && $option['selected'] == true){ $strInput .= ' selected';}        
        $strInput .= '>'.$option.'</option>';
      }

    $strInput .= '</select></div>';
    return $strInput;
  }

  private function setNumberInput($name, $label, $size, $min, $max, $value)
  {
    $strInput = '<div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <input type="number"  class="form-control"  name="'.$name.'" id="'.$name.'" size="'.$size.'" min="'.$min.'" max="'.$max.'" value="'.$value.'">
    </div>';

    return $strInput;
  }

  private function setNumberInputForCondition($name, $classname, $label, $size, $min, $max, $value)
  {
    $strInput = '<span class="'.$classname.'" style="display:none;"><div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <input type="number"  class="form-control"  name="'.$name.'" id="'.$name.'" size="'.$size.'" min="'.$min.'" max="'.$max.'" value="'.$value.'">
    </div></span>';

    return $strInput;
  }

  private function setTextInputForCondition($name, $classname, $label)
  {    
    $strInput = '<span id="'.$classname.'" class="'.$classname.'" style="display:none;"><div class="form-group">
      <label for="'.$name.'">'.$label.'</label>
      <input type="text" class="form-control"  name="'.$name.'" id="'.$name.'">
    </div></span>';

    return $strInput;
  }

  public static function generateAnalyticCommand($xml,$commandData,$case_type,$script_path)
  {
    $sXml = simplexml_load_string($xml);
    $arguments = (string) NULL;
    $script = (string) NULL;
    $process_id = (string) NULL;
    $input = (string) NULL;
    $parser = new XMLParser();
    $script_path = "hdfs:///" . $script_path;
    $file_path = "hdfs:///". strtoupper($case_type) ."/";


    $arguments = (string) $sXml->command->arguments;
    $script = (string) $sXml->command->script;
    
    foreach ($commandData as $data) {
      if($data[0] == "process_id")
      {
        $process_id = $data[1];
      }elseif ($data[0] == "input") {
        $input = $data[1];
      }
    }

    foreach ($sXml->inputs->param as $param) {
      if($param['type'] == "file")
      {
        $arguments = str_replace("$" . $param['name'] , $file_path . $input , $arguments);
      }
    }

    foreach ($sXml->outputs->data as $data) {
      if($data['type'] == "file")
      {
        $arguments = str_replace("$" . $data['name'] , $file_path . $data['tag'] . "." . $data['format'] , $arguments);
        //$arguments = str_replace("$" . $data['name'] , $file_path . $process_id . "_" . $data['tag'] . "." . $data['format'] , $arguments);
      }
    }

    foreach ($commandData as $data) {
      $arguments = str_replace("$" . $data[0] , $data[1] , $arguments);
    }

    $arguments = $script_path . $script . " " . $arguments;
    
    return $arguments;
  }

  public static function generateScript($commands,$script_location)
  {
    
    $parser = new XMLParser();
    $luigi_script = (string) NULL;
    $luigi_class_name = (string) NULL;
    $script_location = "hdfs:///" . $script_location;

    $header =
    "
import luigi
import paramiko
import StringIO

host=\"83.212.112.144\"
user=\"root\"
keypath=\"/home/aegle_welcome/aeglekeys\"

paramiko.util.log_to_file('ssh.log') # sets up logging

mykey = paramiko.RSAKey.from_private_key_file(keypath, password=\"fpDHnB\")

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(host,2122, username=user,pkey=mykey)

    ";

    $luigi_script = $header;

    foreach ($commands as $key=>$command) {
      
      $command_array = explode(" ",$command);
      $script_path = $command_array[0];
      $luigi_class_name = str_replace($script_location,"",$script_path);
      $luigi_class_name = substr ($luigi_class_name,0, strlen($luigi_class_name) - 3);
      $luigi_class_name = str_replace("-","",$luigi_class_name);

      $return=(string) NULL;

      if($key==0){
        $return="None";
      }

      $luigi_class = 
      "
class ". $luigi_class_name ."(luigi.Task):
    ran = False
    def requires(self):
        return " . $return ."
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster " . $command . "')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

      ";

      $luigi_script .= $luigi_class;
    }

    $footer =
    "

class executeworkflow(luigi.Task):
    ran = False
    def requires(self):
        return [". $luigi_class_name ."()]
    def complete(self):
        return self.ran
    def run(self):
        #print(\"{task} says: Work flow executed successfully!\".format(task=self.__class__.__name__))
        self.ran = True

if __name__ == '__main__':
    luigi.run()

    ";
    
    $luigi_script .= $footer;

    return $luigi_script;
  }


  public static function generateICUAnalyticCommand($xml,$commandData,$case_type,$script_path)
  {
    $sXml = simplexml_load_string($xml);
    $arguments = (string) NULL;
    $script = (string) NULL;
    $process_id = (string) NULL;
    $input = (string) NULL;
    $parser = new XMLParser();
    $script_path = "hdfs:///" . $script_path;
    $file_path = "hdfs:///". strtoupper($case_type) ."/";
    
    $arguments = (string) $sXml->command->arguments;
    $script = (string) $sXml->command->script;
    
    //session_start();

    foreach ($commandData as $data) {
      if($data[0] == "process_id")
      {
        $process_id = $data[1];
      }elseif ($data[0] == "input") {
        $input = $data[1];
      }
    }

    if ((string) $sXml->command->script=="PVIpre.R"){

      foreach ($sXml->inputs->param as $param) {
        if($param['type'] == "file")
        {
          if ($param['tag']=="rawPVI"){
            $arguments = str_replace("$" . $param['name'] , "hdfs:///ICU/Data/PVI/PVI/' + str(num) + '.csv", $arguments);
          }
        }
      }

      foreach ($sXml->outputs->data as $data) {
        if($data['type'] == "file")
        {
          if ($data['tag']=="preprocessedPVI"){
            $arguments = str_replace("$" . $data['name'] , "preprocessedPVI' + str(num) +'.csv" , $arguments);
          }
        }
      }

      foreach ($commandData as $data) {
        $arguments = str_replace("$" . $data[0] , $data[1] , $arguments);
      }

      $arguments = str_replace( '$date'  ,  "\"" . date("D M j G:i:s Y") . "\"", $arguments);
      $PVIpre_arguments = $script_path . $script . " " . $arguments;
      $_SESSION["PVIpre_arguments"] = $PVIpre_arguments;
    
    }else if ((string) $sXml->command->script=="PVI_Event_Analysis.R"){

      foreach ($sXml->inputs->param as $param) {
        if($param['type'] == "file")
        {
          if ($param['tag']=="preprocessedPVI"){
            $arguments = str_replace("$" . $param['name'] , "hdfs:///ICU/preprocessedPVI' + str(num) +'.csv", $arguments);
          }
        }
      }

      foreach ($sXml->outputs->data as $data) {
        if($data['type'] == "file")
        {
          if ($data['tag']=="IEProcessedPVI"){
            $arguments = str_replace("$" . $data['name'] , "IEProcessedPVI' + str(num) +'.csv" , $arguments);
          }
        }
      }

      foreach ($commandData as $data) {
          $arguments = str_replace("$" . $data[0] , $data[1] , $arguments);
      }

      $PVI_indeces_arguments = "hdfs:///prototype/tools/icu/PVI_indeces.R" . " " . $arguments;
      $_SESSION["PVI_indeces_arguments"] = $PVI_indeces_arguments;
    }

    $header =
    "

import luigi
import paramiko
import StringIO

host=\"83.212.112.144\"
user=\"root\"
keypath=\"/home/aegle_welcome/aeglekeys\"

paramiko.util.log_to_file('ssh.log') # sets up logging

mykey = paramiko.RSAKey.from_private_key_file(keypath, password=\"fpDHnB\")

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(host,2122, username=user,pkey=mykey)
strConcatenateFiles=\"\"

";


$luigi_script = $header;

$PVIpre =
"

class PVIpre(luigi.Task):
    ran = False
    def requires(self):
        return None
    def complete(self):
        return self.ran
    def run(self):
        for num in range(1,2):
          try:
            stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster  " . (string) $_SESSION["PVIpre_arguments"] . "')
            stderr.readlines()
            stdout.readlines()
          except Exception:
            continue
        self.ran = True

";

$luigi_script .= $PVIpre;

$PVI_indeces = 
"

class PVI_indeces(luigi.Task):
    ran = False
    def requires(self):
        return [PVIpre()]
    def complete(self):
        return self.ran
    def run(self):
        global strConcatenateFiles
        for num in range(1,2):
          try:
            stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster " . (string) $_SESSION["PVI_indeces_arguments"] . "')    
            stderr.readlines()
            stdout.readlines()
            strConcatenateFiles = strConcatenateFiles + '\'hdfs:///ICU/IEProcessedPVI' + str(num) +'.csv\' '
          except Exception:
            continue
        self.ran = True

";

$luigi_script .= $PVI_indeces;

$concatenateFiles =
"

class concatenateFiles(luigi.Task):
    ran = False
    def requires(self):
        return [PVI_indeces()]
    def complete(self):
        return self.ran
    def run(self):
        global strConcatenateFiles
        strConcatenateFiles = strConcatenateFiles.replace(\" \", \",\")
        print \"Data \" + strConcatenateFiles.rstrip(',')
        print \"Command \" + 'spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/concatenateFiles.R '+ strConcatenateFiles.rstrip(',') +' \'row\' ConcatenatedFile.csv'
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/concatenateFiles.R '+ strConcatenateFiles.rstrip(',') +' \'row\' ConcatenatedFile.csv')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

 ";

$luigi_script .= $concatenateFiles;


$combineClinicalData = 
"  

class combineClinicalData(luigi.Task):
    ran = False
    def requires(self):
        return [concatenateFiles()]
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/combineClinical.R hdfs:///ICU/ConcatenatedFile.csv hdfs:///ICU/Data/clinical.csv combineClinical.csv')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

";

$luigi_script .= $combineClinicalData;

$executeworkflow = 
"

class executeworkflow(luigi.Task):
    ran = False
    def requires(self):
        return [combineClinicalData()]
    def complete(self):
        return self.ran
    def run(self):
        #print(\"{task} says: Work flow executed successfully!\".format(task=self.__class__.__name__))
        self.ran = True
  
if __name__ == '__main__':
    luigi.run()

";

  $luigi_script .= $executeworkflow;

  return $luigi_script;

  }

  public static function generateCLLAnalyticCommand($xml,$commandData,$case_type,$script_path)
  {
    
    $sXml = simplexml_load_string($xml);
    $arguments = (string) NULL;
    $script = (string) NULL;
    $process_id = (string) NULL;
    $input = (string) NULL;
    $parser = new XMLParser();
    $script_path = "hdfs:///" . $script_path;
    $file_path = "hdfs:///". strtoupper($case_type) ."/";
    $luigi_script = (string) NULL;

    $arguments = (string) $sXml->command->arguments;
    $script = (string) $sXml->command->script;
    
    foreach ($commandData as $data) {
      if($data[0] == "process_id")
      {
        $process_id = $data[1];
      }elseif ($data[0] == "input") {
        $input = $data[1];
      }
    }

    foreach ($sXml->inputs->param as $param) {
      if($param['type'] == "file")
      {
        $arguments = str_replace("$" . $param['name'] , $file_path . $input , $arguments);
      }
    }

    foreach ($sXml->outputs->data as $data) {
      if($data['type'] == "file")
      {

          $arguments = str_replace("$" . $data['name'] , $file_path . $data['tag'] . "." . $data['format'] , $arguments);

          //$arguments = str_replace("$" . $data['name'] , $file_path . $process_id . "_" . $data['tag'] . "." . $data['format'] , $arguments);
        if($data['tag']=="filterin"){

            $filterin = $file_path . $data['tag'] . "." . $data['format'];

            //$filterin = $file_path . $process_id . "_" . $data['tag'] . "." . $data['format'];

        }
      }
    }

    foreach ($commandData as $data) {
      $arguments = str_replace("$" . $data[0] , $data[1] , $arguments);
    }

    $command = $script_path . $script . " " . $arguments;
    
    //session_start();

    $header =
    "

import luigi
import paramiko
import StringIO

host=\"83.212.112.144\"
user=\"root\"
keypath=\"/home/aegle_welcome/aeglekeys\"

paramiko.util.log_to_file('ssh.log') # sets up logging

mykey = paramiko.RSAKey.from_private_key_file(keypath, password=\"fpDHnB\")

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(host,2122, username=user,pkey=mykey)

";

$luigi_script = $header;

$ngsFilteringsemisparked =

"

class ngsFilteringsemisparked(luigi.Task):
    ran = False
    def requires(self):
        return None
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster " . $command . "')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

";

$luigi_script .= $ngsFilteringsemisparked ;

$compclonoJCDR3 =

"

class compclonoJCDR3(luigi.Task):
    ran = False
    def requires(self):
        return [ngsFilteringsemisparked()]
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/comp_clono_JCDR3-semisparked.py  " .  $filterin . " hdfs:///CLL/clonosJCDR3_full.csv hdfs:///CLL/clonosJCDR3_top10.csv hdfs:///CLL/clonosJCDR3_summary.csv " .  $process_id . "')    
        stderr.readlines()
        stdout.readlines()
        self.ran = True
  
  ";


  $luigi_script .= $compclonoJCDR3 ;

  $compclonoVCDR3 = 

  "

class compclonoVCDR3(luigi.Task):
    ran = False
    def requires(self):
        return [ngsFilteringsemisparked()]
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/comp_clono_VCDR3-semisparked.py " .  $filterin . " hdfs:///CLL/clonosVCDR3_full.csv hdfs:///CLL/clonosVCDR3_top10.csv hdfs:///CLL/clonosVCDR3_summary.csv " .  $process_id . "')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

  ";
  
  $luigi_script .= $compclonoVCDR3 ;

  $extrepertoirej =

  "

class extrepertoirej(luigi.Task):
    ran = False
    def requires(self):
        return [compclonoJCDR3()]
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ext_repertoire_J-semisparked.py hdfs:///CLL/clonosJCDR3_full.csv hdfs:///CLL/repertoireJ_full.csv hdfs:///CLL/repertoireJ_summary.csv " .  $process_id . "')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

";

$luigi_script .= $extrepertoirej ;

$extrepertoirev = 

"

class extrepertoirev(luigi.Task):
    ran = False
    def requires(self):
        return [compclonoVCDR3()]
    def complete(self):
        return self.ran
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ext_repertoire_V-semisparked.py hdfs:///CLL/clonosVCDR3_full.csv hdfs:///CLL/repertoireV_full.csv hdfs:///CLL/repertoireV_top10.csv hdfs:///CLL/repertoireV_summary.csv " .  $process_id . "')
        stderr.readlines()
        stdout.readlines()
        self.ran = True

";

$luigi_script .= $extrepertoirev ;

$executeworkflow = 

"

class executeworkflow(luigi.Task):
    ran = False
    def requires(self):
        return [extrepertoirev(),extrepertoirej()]
    def complete(self):
        return self.ran
    def run(self):
        #print(\"{task} says: Work flow executed successfully!\".format(task=self.__class__.__name__))
        self.ran = True
  
if __name__ == '__main__':
    luigi.run()

";

$luigi_script .= $executeworkflow ;

return $luigi_script;

  }

public static function getFileName($response)
{

    $xml = simplexml_load_string($response);
    $parser = new XMLParser();

    $Text = $xml['tag']->__toString();

    return $Text ;
    
}

}