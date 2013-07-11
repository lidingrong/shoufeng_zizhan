<?php
/**
 *  组文章数据文件
 * ============================================================================
 * * 版权所有 2013 shoufen
 * $Author: 小龙 $
 * yipoo@126.com
 * $Id: getArticleByControl.php  2013-07-01 02:29:08  xiaolong $
*/
//引入ecshop系统文件
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');


//获取文章内容
//getArticleInfo(); //单品站：参数 $cid = 文章分类

if(getArticleInfo()){	
	echo "1、文章生成上传成功！"."<br>";
}else{
	echo "文章生成失败，请核查错误！"."<br>";
	exit;
}
//获取文章的分类
//getArticleCategory();
if(getArticleCategory()){
	echo "2、文章分类生成上传成功！"."<br>";
}else{
	echo "文章分类生成失败，请核查错误！"."<br>";
	exit;
}
 /* ============================================================================*/
/**
 * 获取文章内容
 * @access  private
 * @return  true		
**/
function getArticleInfo($cid=0){
	$article = array();
	if($cid){
		$where = " WHERE aCId = '".$cid."' "; 
	}else{
		$where = " WHERE 1 "; 
	}
	//$result_sql = "SELECT  *  FROM ecshop3.`ecs_article_all` where `aCId` = '$CateId'  ORDER BY rand() limit  $sum "; //按分类随机取100
	$sql = "SELECT `aId`, `aCId`, `aTitle`, `aContent` FROM  " .  $GLOBALS['ecs2']->table('article_all') .$where;
	$article = $GLOBALS['db2']->getAll($sql);
	foreach($article as $k => $v){
		//$description = cut_str($v['aContent'], 72)."……";	//生成SeoDescription,最多72字符
		if (!get_magic_quotes_gpc()){
			$aTitle = addslashes($v['aTitle']);
			$aContent = addslashes($v['aContent']);
			$description = addslashes(cut_str($v['aContent'], 72)."……");
		}else{
			echo "没有转义";
		}	
		$cat_id	 = $v['aCId']+ 100;//为与系统文章文类区别开，每个分类id都加00
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('article') . "(`cat_id`, `title`, `content`, `article_type`, `is_open`, `add_time`, `description`) VALUES ('".$cat_id."','".$aTitle."','".$aContent."','0','1','".gmtime()."','".$description."')";
		
		$GLOBALS['db']->query($sql);
	}
	
	return true;
}

/**
 * 获取文章的分类
 * @access  private
 * @return  true		
**/
function getArticleCategory(){
	$category = array();
	$sql = "SELECT `aCId`, `aCJName` FROM  " .  $GLOBALS['ecs2']->table('article_category') . "  WHERE 1 ";
	$category = $GLOBALS['db2']->getAll($sql);
	foreach($category as $k => $v){
		$aCId = $v['aCId'] + 100;//为与系统文章文类区别开，每个分类id都加00
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('article_cat') . "(`cat_id`, `cat_name`, `cat_type`, `parent_id` ) VALUES ('".$aCId."','".$v['aCJName']."','1','9')";
		$GLOBALS['db']->query($sql);
	}
	
	return true;
}








/*-------------------------------------------------------------------------------------------------------*/
//公用函数
/*-------------------------------------------------------------------------------------------------------*/


//剪切字符串
function cut_str($str, $lenth, $start=0){	//剪切字符串
	$str=str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $str);
	$len=strlen($str);
	if($len<=$length){
		return str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $str);
	}
	$substr='';
	$n=$m=0;
	for($i=0; $i<$len; $i++){
		$x=substr($str, $i, 1);
		$a=base_convert(ord($x), 10, 2);
		$a=substr('00000000'.$a, -8);
		if($n<$start){
			if(substr($a, 0, 3)==110){
				$i+=1;
			}elseif(substr($a, 0, 4)==1110){
				$i+=2;
			}
			$n++;
		}else{
			if(substr($a, 0, 1)==0){
				$substr.=substr($str, $i, 1);
			}elseif(substr($a, 0, 3)==110){
				$substr.=substr($str, $i, 2);
				$i+=1;
			}elseif(substr($a, 0, 4)==1110){
				$substr.=substr($str, $i, 3);
				$i+=2;
			}else{
				$substr.='';
			}
			
			if(++$m>=$lenth){
				break;
			}
		}
	}
	return str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $substr);
}




?>