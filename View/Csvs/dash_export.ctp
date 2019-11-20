<?php
$line= $result[0]['k'];
echo $this->CSV->addRow(array_keys($line));
for($i=0; $i<count($result);$i++)
{
	$line= $result[$i]['k'];
    echo $this->CSV->addRow(array_values($line));
}
$file=$filename;
echo $this->CSV->render($file);


?>
