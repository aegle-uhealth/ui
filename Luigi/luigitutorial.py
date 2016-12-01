import luigi
import paramiko
import StringIO

host="83.212.112.144"
user="root"
keypath="/home/aegle_welcome/aeglekeys"

paramiko.util.log_to_file('ssh.log') # sets up logging

mykey = paramiko.RSAKey.from_private_key_file(keypath, password="fpDHnB")

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(host,2122, username=user,pkey=mykey)

class ngsFilteringsemisparked(luigi.Task):
    def requires(self):
        return None
    def output(self):
        return None
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ngsFiltering-semisparked.py hdfs:///CLL/input.dat TCR y y y y 0.0 null null null hdfs:///CLL/output1.dat hdfs:///CLL/output2.dat hdfs:///CLL/output3.dat null null null sampleDataset')
	print "stderr: ", stderr.readlines()
	print "pwd: ", stdout.readlines()
	print "stdin: ", stdin.readlines()

class compclonoJCDR3(luigi.Task):
    def requires(self):
        return [ngsFilteringsemisparked()]
    def output(self):
        return None
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/comp_clono_JCDR3-semisparked.py hdfs:///CLL/output1.dat hdfs:///CLL/comp_clono_JCDR3-semisparked_clonos.dat hdfs:///CLL/comp_clono_JCDR3-semisparked_topcl.dat hdfs:///CLL/comp_clono_JCDR3-semisparked_summ2.dat sampleDataset')
		
		
		<arguments>$input $clonos $topcl $summ2 ${input.name}</arguments>
		
	print "stderr: ", stderr.readlines()
	print "pwd: ", stdout.readlines()
	print "stdin: ", stdin.readlines()

class compclonoVCDR3(luigi.Task):
    def requires(self):
        return [ngsFilteringsemisparked()]
    def output(self):
        return None
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/comp_clono_VCDR3-semisparked.py hdfs:///CLL/output1.dat hdfs:///CLL/comp_clono_VCDR3-semisparked_clonos.dat hdfs:///CLL/comp_clono_VCDR3-semisparked_topcl.dat hdfs:///CLL/comp_clono_VCDR3-semisparked_summ2.dat sampleDataset')
	print "stderr: ", stderr.readlines()
	print "pwd: ", stdout.readlines()
	print "stdin: ", stdin.readlines()

class extrepertoirej(luigi.Task):
    def requires(self):
        return [compclonoJCDR3()]
    def output(self):
        return None
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ext_repertoire_J-semisparked.py hdfs:///CLL/comp_clono_JCDR3-semisparked_clonos.dat hdfs:///CLL/ext_repertoire_J-semisparked_rep.dat hdfs:///CLL/ext_repertoire_J-semisparked_summ.dat sampleDataset')
	print "stderr: ", stderr.readlines()
	print "pwd: ", stdout.readlines()
	print "stdin: ", stdin.readlines()

class extrepertoirev(luigi.Task):
    def requires(self):
        return [compclonoVCDR3()]
    def output(self):
        return None
    def run(self):
        stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ext_repertoire_V-semisparked.py hdfs:///CLL/comp_clono_VCDR3-semisparked_clonos.dat hdfs:///CLL/ext_repertoire_V-semisparked_rep.dat hdfs:///CLL/ext_repertoire_V-semisparked_toprep.dat hdfs:///CLL/ext_repertoire_V-semisparked_summ.dat sampleDataset')
	print "stderr: ", stderr.readlines()
	print "pwd: ", stdout.readlines()
	print "stdin: ", stdin.readlines()

class executeworkflow(luigi.Task):
    def requires(self):
        return [extrepertoirev(),extrepertoirej()]
    def output(self):
        return None
    def run(self):
        print("{task} says: Work flow executed successfully!".format(task=self.__class__.__name__))
	
if __name__ == '__main__':
    luigi.run()

