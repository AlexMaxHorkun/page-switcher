<?php
include 'src/AMH/Model/Model.php';
include 'src/AMH/Mapper/Mapper.php';
echo "HELLO<br>";
$str='yoba';
if(($str instanceof string)){
	echo 'YES';
}
else
	echo 'NO';
?>
