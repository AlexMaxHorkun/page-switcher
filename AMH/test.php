<?php
include 'src/AMH/Model/Model.php';
include 'src/AMH/Mapper/Mapper.php';
echo "HELLO<br>";
/*preg_match_all('"\{:([A-z_][A-z0-9_-]*)\}"i','fjkdsf {:yoba} jkjsd86hd_{:_0z}kdsa fjksd kldsj{:0NULL} {:-kdlas},',$res,PREG_SET_ORDER);
print_r($res);
echo '<br><br>';
echo str_replace('{:id}',2,'kldas{:id}mkdmsa {:id} ');*/

//echo preg_replace('"[A-z0-9_-]+\\\"i','','YOBA\YOBA1\YOBA2');
if(preg_match('"^block_[0-9]+$"i','block_10')){
	echo 'YES';
}
?>
