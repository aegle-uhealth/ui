
from pywebhdfs.webhdfs import PyWebHdfsClient
import logging

logging.basicConfig(level=logging.DEBUG)
_LOG = logging.getLogger(__name__)


example_dir = '/CLL/Workflows/MainClonoGeneRepExt/'
example_file = '{dir}ngs_filtering.xml'.format(dir=example_dir)
example_data ="""<?xml version="1.0"?>
<tool version="0.9" name="NGS Filtering" id="ngsFilt">
<description>Filter IMGT Summary Data</description>
<command>
<script>ngsFiltering-semisparked.py</script>
<arguments>$input $TCR_or_BCR $Vfun $spChar $prod $delCF $threshold $Vg.Vgid $clen.cdr3len1 $cdp.cdr3part $filtin $filtout $summ $Jg.Jgid $Dg.Dgid $clen.cdr3len2 $process_id</arguments>
</command>
<inputs>
<param name="input" label="IMGT Summary Output" tag="imgtReport" format="csv" type="file"/>
<param name="process_id" label="Process ID" type="text"/>
<param name="TCR_or_BCR" label="T-cells or B-cell option" type="select">
<option value="TCR">T-cells</option>
<option value="BCR">B-cells</option>
</param>
<param name="Vfun" label="Only Take Into Account Fuctional V-GENE? " type="select">
<option value="y">yes</option>
<option value="n">no</option>
</param>
<param name="spChar" label="Only Take Into Account CDR3 with no Special Characters (X,*,#)? " type="select">
<option value="y">yes</option>
<option value="n">no</option>
</param>
<param name="prod" label="Only Take Into Account Productive Sequences? " type="select">
<option value="y">yes</option>
<option value="n">no</option>
</param>
<param name="delCF" label="Only Take Into Account CDR3 with valid start/end landmarks? " type="select">
<option value="y">yes</option>
<option value="n">no</option>
</param>
<param name="threshold" label="V-REGION identity %" type="float" value="0" max="100" min="0" size="3"/>
<conditional name="Vg">
<param name="Vg_select" label="Select Specific V gene?" type="select">
<option value="y">Yes</option>
<option value="n" selected="true">No</option>
</param>
<when value="y">
<param name="Vgid" label="Type V gene" format="txt" type="text"/>
</when>
<when value="n">
<param name="Vgid" type="hidden" value="null"/>
</when>
</conditional>
<conditional name="Jg">
<param name="Jg_select" label="Select Specific J gene?" type="select">
<option value="y">Yes</option>
<option value="n" selected="true">No</option>
</param>
<when value="y">
<param name="Jgid" label="Type J gene" format="txt" type="text"/>
</when>
<when value="n">
<param name="Jgid" type="hidden" value="null"/>
</when>
</conditional>
<conditional name="Dg">
<param name="Dg_select" label="Select Specific D gene?" type="select">
<option value="y">Yes</option>
<option value="n" selected="true">No</option>
</param>
<when value="y">
<param name="Dgid" label="Type D gene" format="txt" type="text"/>
</when>
<when value="n">
<param name="Dgid" type="hidden" value="null"/>
</when>
</conditional>
<conditional name="clen">
<param name="clen_select" label="Select CDR3 length range?" type="select">
<option value="y">Yes</option>
<option value="n" selected="true">No</option>
</param>
<when value="y">
<param name="cdr3len1" label="CDR3 Length Lower Threshold" type="integer" value="0" max="100" min="0" size="3"/>
<param name="cdr3len2" label="CDR3 Length Upper Threshold" type="integer" value="0" max="100" min="0" size="3"/>
</when>
<when value="n">
<param name="cdr3len1" type="hidden" value="null"/>
<param name="cdr3len2" type="hidden" value="null"/>
</when>
</conditional>
<conditional name="cdp">
<param name="cdp_select" label="Only select CDR3 containing specific amino-acid sequence?" type="select">
<option value="y">Yes</option>
<option value="n" selected="true">No</option>
</param>
<when value="y">
<param name="cdr3part" label="Type specific amino-acid sequence" format="txt" type="text"/>
</when>
<when value="n">
<param name="cdr3part" type="hidden" value="null"/>
</when>
</conditional>
</inputs>
<outputs>
<data name="filtin" label="${process_id}_filterin" tag="filterin" format="csv" type="file"/>
<data name="filtout" label="${process_id}_filterout" tag="filterout" format="csv" type="file"/>
<data name="summ" label="${process_id}_summary" tag="filtering_summary" format="csv" type="file"/>
</outputs>
<help> This tool filters IMGT Summary Data based on a combination of criteria. </help>
</tool>""" 

# create a new client instance
hdfs = PyWebHdfsClient(host='83.212.112.144', port='50070',
                       user_name='root')

# delete existing file
print('delete current file\n'.format(example_file))
hdfs.delete_file_dir(example_file,recursive=False)

# create a new directory for the example
#print('making new HDFS directory at: {0}\n'.format(example_dir))
#hdfs.make_dir(example_dir)

# get a dictionary of the directory's status
#dir_status = hdfs.get_file_dir_status(example_dir)
#print(dir_status)

# create a new file on hdfs
print('making new file at: {0}\n'.format(example_file))
hdfs.create_file(example_file, example_data)

#file_status = hdfs.get_file_dir_status(example_file)
#print(file_status)

# get the checksum for the file
#file_checksum = hdfs.get_file_checksum(example_file)
#print(file_checksum)

# append to the file created in previous step
#print('appending to file at: {0}\n'.format(example_file))
#hdfs.append_file(example_file, example_data)

#file_status = hdfs.get_file_dir_status(example_file)
#print(file_status)

# checksum reflects file changes
#file_checksum = hdfs.get_file_checksum(example_file)
#print(file_checksum)

