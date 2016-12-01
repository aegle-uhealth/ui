import paramiko
import StringIO

host="83.212.112.144"
user="root"
keypath="/home/aegle_welcome/aeglekeys"


paramiko.util.log_to_file('ssh.log') # sets up logging

mykey = paramiko.RSAKey.from_private_key_file(keypath, password="CANTWalk221")

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(host,2122, username=user,pkey=mykey)
stdin, stdout, stderr = client.exec_command('spark-submit --driver-memory 1024m --executor-memory 2560m --master yarn-cluster hdfs:///prototype/cllWorkflowExample/ngsFiltering-semisparked.py hdfs:///CLL/input.dat TCR y y y y 0.0 null null null hdfs:///CLL/output1.dat hdfs:///CLL/output2.dat hdfs:///CLL/output3.dat null null null sampleDataset')
print "stderr: ", stderr.readlines()
print "pwd: ", stdout.readlines()
