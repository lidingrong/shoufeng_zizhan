<?php
$zipcode=$_GET['zipcode'];


!substr_count($zipcode, '-') && $zipcode=substr($zipcode, 0, 3).'-'.substr($zipcode, 3, 7);
$file='zipcode/'.substr($zipcode, 0, 2).'.txt';


if(is_file($file)){
	$str=@file_get_contents($file);
	$str_ary=@explode("\n", $str);
	for($i=0; $i<count($str_ary); $i++){		
		if(substr($str_ary[$i], 0, 8)==$zipcode){			
			echo trim(str_replace($zipcode, '', $str_ary[$i])).'   ';
			exit;
		}
	}
}

echo 'error';
?>