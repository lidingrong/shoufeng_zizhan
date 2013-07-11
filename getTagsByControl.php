<?php
/**
 *  组文章数据文件
 * ============================================================================
 * * 版权所有 2013 shoufen
 * $Author: 小龙 $
 * yipoo@126.com
 * $Id: getTagsByControl.php  2013-07-01 02:29:08  xiaolong $
*/
//引入ecshop系统文件
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
$cid = $_GET['act'];

// 分类id：12,16,17,18,19,20,21,22,23,24,25,26,27,28,29,,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44 
//一次性 每个分类取1000条
//getTagsInfoAll(1000);
if(getTagsInfoAll(1000)){
	echo "1、tags生成上传成功！"."<br>";
}else{
	echo "tags生成失败，请核查错误！"."<br>";
	exit;
}

//按分类取tags，数量默认为每个分类随机100
//getTagsInfo($cid,100);

//获取tags的分类
//getTagsCategory();
if(getTagsCategory()){
	echo "2、tags分类生成上传成功！"."<br>";
}else{
	echo "tags分类生成失败，请核查错误！"."<br>";
	exit;
}
//按分类增加tags，排除已经存在的，数量默认为每个分类随机100
//getTagsInfoTwo($cid,50);


 /* ============================================================================*/
 //随机从每个分类去取，一次性取完
 function getTagsInfoAll($sum){
	 $category = array();
	$tags_sql = "SELECT `CateId` FROM " .  $GLOBALS['ecs2']->table('tags_category') . " WHERE 1 ";
	$category = $GLOBALS['db2']->getAll($tags_sql);
	foreach($category as $k => $v){
		getTagsInfo($v['CateId'],$sum);
	}	
	return true;	 
 }

/**
 * 获取tags内容
 * @access  private
 * @access  cid 分类id
 * @return  true		
**/
function getTagsInfo($CateId,$sum=100){
	$tags = array();
	$Keyword = $aContent = $description  = $content =  "";
	//$tags_sql = "SELECT `CateId`, `Keyword` FROM ecshop3.`ecs_tags_all` WHERE CateId = '$CateId' ";
	$tags_sql = "SELECT  `TId` ,`CateId`, `Keyword`  FROM " .  $GLOBALS['ecs2']->table('tags_all') . " where `CateId` = '$CateId'  ORDER BY rand() limit  $sum "; //随机分类下$sum个tags
	$tags = $GLOBALS['db2']->getAll($tags_sql);
	
	foreach($tags as $k => $v){
		$Keyword = $aContent = $description  = $content =  "";
		$j = rand(5,10);		
		$result_sql = "SELECT  `tagsDescription` FROM " .  $GLOBALS['ecs2']->table('tags_description') . " ORDER BY rand() limit  $j";
		$result =$GLOBALS['db2']->getAll($result_sql);
		
		foreach($result as $va){			
			$content .= str_replace('*',$v['Keyword'],$va['tagsDescription']);	//替换*
		}
		if (!get_magic_quotes_gpc()){
			$Keyword = addslashes(trim($v['Keyword']));
			$aContent = addslashes($content);
			$description = addslashes(cut_str($content, 72)."……");
		}else{
			echo "没有转义";
		}	
		
		$CateId	 = $v['CateId']+ 200;//为与文章文类区别开，每个分类id都加200
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('article') . "(`core_id`,`cat_id`, `title`, `content`, `article_type`, `is_open`, `add_time`, `description`) VALUES ('".$v['TId']."','".$CateId."','".$Keyword."','".$aContent."','0','1','".gmtime()."','".$description."')";
		$GLOBALS['db']->query($sql);
	}
	
	return true;
}


/**
 * 获取tags分类
 * @access  private
 * @return  true		
**/
function getTagsCategory(){
	
	$category = array();
	$tags_sql = "SELECT `CateId`, `Category` FROM " .  $GLOBALS['ecs2']->table('tags_category') . " WHERE 1 ";
	$category = $GLOBALS['db2']->getAll($tags_sql);
	foreach($category as $k => $v){
		$CateId = $v['CateId']+200; //为与文章文类区别开，每个分类id都加200
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('article_cat') . "(`cat_id`, `cat_name`, `cat_type`,`parent_id` ) VALUES ('".$CateId."','".$v['Category']."','6','10')";
		$GLOBALS['db']->query($sql);
	}	
	
	return true;
}

/**
 * 更新tags内容，也就是增加tags
 * @access  private
 * @access  cid 分类id
 * @return  true		
**/

function getTagsInfoTwo($CateId,$sum=100){
	$tagsarr= $coreidarr = array();
	$dou = "";
	$core_sql = "SELECT  `core_id`  FROM " .  $GLOBALS['ecs']->table('article') . " WHERE 1";
	$coreid = $GLOBALS['db']->getAll($core_sql);
	foreach($coreid as $v){
		$coreidarr  .= $dou.$v['core_id'];
		$dou = ',';
	}
	
	//随机分类下$sum个tags,排除已存在的子站的tags
	$tags_sql = "SELECT  `TId` ,`CateId`, `Keyword`  FROM " .  $GLOBALS['ecs2']->table('tags_all') . "  where `CateId` = '$CateId' and `TId` not in('".$coreidarr ."')  ORDER BY rand() limit  $sum "; 
	$tagsarr = $GLOBALS['db2']->getAll($tags_sql);
	foreach($tagsarr as $k => $v){
		$j = rand(5,10);		
		$result_sql = "SELECT  `tagsDescription` FROM " .  $GLOBALS['ecs2']->table('tags_description') . "  ORDER BY rand() limit  $j";
		$result =$GLOBALS['db2']->getAll($result_sql);
		foreach($result as $va){			
			$content .= str_replace('*',$v['Keyword'],$va['tagsDescription']);	//替换*
		}
		if (!get_magic_quotes_gpc()){
			$Keyword = addslashes($v['Keyword']);
			$aContent = addslashes($content);
			$description = addslashes(cut_str($content, 72)."……");
		}else{
			echo "没有转义";
		}	
		
		$CateId	 = $v['CateId']+ 200;//为与文章文类区别开，每个分类id都加200
		
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('article') . "(`core_id`,`cat_id`, `title`, `content`, `article_type`, `is_open`, `add_time`, `description`) VALUES ('".$v['TId']."','".$CateId."','".$Keyword."','".$aContent."','0','1','".gmtime()."','".$description."')";
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