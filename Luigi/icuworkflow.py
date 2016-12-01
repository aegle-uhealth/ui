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
strConcatenateFiles=""

class PVIpre(luigi.Task):
    ran = False
    def requires(self):
        return None
    def complete(self):
        return self.ran
    def run(self):
	for num in range(1,2):
		try:
			stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster  hdfs:///prototype/tools/icu/PVIpre.R hdfs:///ICU/Data/PVI/PVI/' + str(num) + '.csv 30 100 "Thu Jan 21 16:43:47 2010" preprocessedPVI' + str(num) +'.csv')
			stderr.readlines()
			stdout.readlines()
		except Exception:
			continue
	self.ran = True

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
			stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/PVI_indeces.R hdfs:///ICU/preprocessedPVI' + str(num) +'.csv 5 1 4 0.6 0.2 IEProcessedPVI' + str(num) +'.csv')		
			stderr.readlines()
			stdout.readlines()
			strConcatenateFiles = strConcatenateFiles + '\'hdfs:///ICU/IEProcessedPVI' + str(num) +'.csv\' '
		except Exception:
			continue
	self.ran = True

class concatenateFiles(luigi.Task):
    ran = False
    def requires(self):
        return [PVI_indeces()]
    def complete(self):
        return self.ran
    def run(self):
	global strConcatenateFiles
	strConcatenateFiles = strConcatenateFiles.replace(" ", ",")
	print "Data " + strConcatenateFiles.rstrip(',')
	print "Command " + 'spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/concatenateFiles.R '+ strConcatenateFiles.rstrip(',') +' \'row\' ConcatenatedFile.csv'
	stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master=yarn-cluster hdfs:///prototype/tools/icu/concatenateFiles.R '+ strConcatenateFiles.rstrip(',') +' \'row\' ConcatenatedFile.csv')
	stderr.readlines()
	stdout.readlines()
	self.ran = True
	
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

class executeworkflow(luigi.Task):
    ran = False
    def requires(self):
        return [combineClinicalData()]
    def complete(self):
        return self.ran
    def run(self):
        #print("{task} says: Work flow executed successfully!".format(task=self.__class__.__name__))
        self.ran = True
	
if __name__ == '__main__':
    luigi.run()

