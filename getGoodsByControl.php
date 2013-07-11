<?php
/**
 *  组产品数据文件
 * ============================================================================
 * * 版权所有 2013 shoufen
 * $Author: 小龙 $
 * yipoo@126.com
 * $Id: getGoodsByControl.php  2013-07-01 02:29:08  xiaolong $
*/
//引入ecshop系统文件
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
$cid = $_GET['act'];
//cid in(1,134)

//getGoodsParameter();
if(getGoodsParameter()){
	
	echo "3、产品参数生成上传成功！"."<br>";
}else{
	echo "产品参数生成失败，请核查错误！"."<br>";
	exit;
}

exit;
//插入产品基本数据

if(GetGoodsInfoFromControl($cid)){
	
	echo "1、产品基本数据生成成功！"."<br>";
}else{
	echo "产品基本数据生成失败，请核查错误！"."<br>";
}



//插入产品属性
//getGoodsProperty();
if(getGoodsProperty()){
	
	echo "2、产品属性生成上传成功！"."<br>";
}else{
	echo "产品属性生成失败，请核查错误！"."<br>";
	exit;
}

//插入产品参数
//getGoodsParameter();
if(getGoodsParameter()){
	
	echo "3、产品参数生成上传成功！"."<br>";
}else{
	echo "产品参数生成失败，请核查错误！"."<br>";
	exit;
}

//插入产品评论 先导完产品数据在执行这个函数
//getGoodsComment();
if(getGoodsComment()){
	
	echo "4、产品评论生成上传成功！"."<br>";
}else{
	echo "产品评论生成失败，请核查错误！"."<br>";
	exit;
}
// 取产品的分类
//getGoodsCategory();
if(getGoodsCategory()){
	
	echo "5、产品分类生成上传成功！"."<br>";
}else{
	echo "产品分类生成失败，请核查错误！"."<br>";
	exit;
}

//第二次更新数据,排除之前已到入
//GetGoodsInfoTwo($cid);

/*---------------------------------------------------------------------------------------*/
// 函数
/*---------------------------------------------------------------------------------------*/

function GetGoodsInfoTwo(){
	$coreidarr= $dou = "";
	$types=$goods= array();
	$sql  = "SELECT  `core_id` FROM " .  $GLOBALS['ecs']->table('goods') ;
	$goods2 = $GLOBALS['db']->getAll($sql);
	$goods2 = array_unique($goods2);
	foreach($goods2 as $v){
		$coreidarr  .= $dou.$v['core_id'];
		$dou = ',';
	}
	$sql = "select `pId`, `cId`, `price` from " .  $GLOBALS['ecs2']->table('products_name') . "  where  pId not in(".$coreidarr.")";
	$goods = $GLOBALS['db2']->getAll($sql);
	foreach($goods as $k => $v){
		$pId = $v['pId'];
		$type = $GLOBALS['db2']->getAll("SELECT `pContent`,pId FROM " .  $GLOBALS['ecs2']->table('products_property') . " WHERE pId = '$pId' and  pPNum in(8,10) ");	
		foreach($type as $v2){
			if($v2['pContent']){
				
				if(trim($v2['pContent']) == "人気商品"){$types[$pId]['is_hot'] = '1';}
				if(trim($v2['pContent']) == "新品") {$types[$pId]['is_news'] = '1';}
			}
		}
				
		$proTit =  get_goods_title($pId);
		 
		if($pId){
		 $sql = "INSERT INTO " .  $GLOBALS['ecs']->table('goods') . " (goods_name,  goods_sn, cat_id,core_id," .
				" brand_id, shop_price,keywords, goods_brief,is_best, is_new, is_hot, is_real, " .
				" goods_number, warn_number,is_on_sale, is_alone_sale,  " .
				" is_shipping, goods_desc, add_time, last_update, goods_type)" .
			"VALUES ('".$proTit['title']."', '".$type[0]['pContent']."', '".$v['cId']."','$pId', " .
				" '".$v['cId']."', '".$v['price']."', '".$proTit['SeoKeywords']."' ,'".$proTit['SeoDescription']."','0','".$types[$v['pId']]['is_news']."','".$types[$v['pId']]['is_hot']."','1', ".
				" '99999','10','1','1', ".
				" '1','".$proTit['description']."', '" . gmtime() . "', '". gmtime() ."', '1')";
				
			$GLOBALS['db']->query($sql);			
		}
		
	}
	
	return true;
	 
}

/**
 * 组合产品数据 插入数据库
 * @param   $act  	分类ID
 * @access  private
 * @return  array		
 */
function GetGoodsInfoFromControl($cid="")
{
/*	$sql_con1 = "ALTER TABLE " .  $GLOBALS['ecs']->table('goods') . "  ADD `core_id` INT( 8 ) NOT NULL AFTER `goods_id` ";
	$sql_con2 = "ALTER TABLE " .  $GLOBALS['ecs']->table('goods') . "  ADD INDEX ( `core_id` )  ";
	$GLOBALS['db']->query($sql_con1);
	$GLOBALS['db']->query($sql_con2);
*/	
	$goods = array();
	$types = array();
	$where = "";
	if($cid){
		$where = " where cId = '$cid' ";
	}
	$sql = "select `pId`, `cId`, `price` from " .  $GLOBALS['ecs2']->table('products_name') .$where;
	$goods = $GLOBALS['db2']->getAll($sql);
	foreach($goods as $k => $v){
		$pId = $v['pId'];
		$type = $GLOBALS['db2']->getAll("SELECT `pContent`,pId FROM  " .  $GLOBALS['ecs2']->table('products_property') . " WHERE pId = '$pId' and  pPNum in(8,10) ");	
		foreach($type as $v2){
			if($v2['pContent']){
				
				if(trim($v2['pContent']) == "人気商品"){$types[$pId]['is_hot'] = '1';}
				if(trim($v2['pContent']) == "新品") {$types[$pId]['is_news'] = '1';}
			}
		}
				
		$proTit =  get_goods_title($pId);
		 
		if($pId){
		 $sql = "INSERT INTO " .  $GLOBALS['ecs']->table('goods') . " (goods_name,  goods_sn, cat_id,core_id," .
				" brand_id, shop_price,keywords, goods_brief,is_best, is_new, is_hot, is_real, " .
				" goods_number, warn_number,is_on_sale, is_alone_sale,  " .
				" is_shipping, goods_desc, add_time, last_update, goods_type)" .
			"VALUES ('".$proTit['title']."', '".$type[0]['pContent']."', '".$v['cId']."','$pId', " .
				" '".$v['cId']."', '".$v['price']."', '".$proTit['SeoKeywords']."' ,'".$proTit['SeoDescription']."','0','".$types[$v['pId']]['is_news']."','".$types[$v['pId']]['is_hot']."','1', ".
				" '99999','10','1','1', ".
				" '1','".$proTit['description']."', '" . gmtime() . "', '". gmtime() ."', '1')";
				
			$GLOBALS['db']->query($sql);			
		}
		
	}
	return true;
	// echo date('Y-m-d H:i:s',time());
}


/**
 * 组 商品 标题
 * @param   $pId  	商品ID
 * @param   $group  分组的数量 一般为4
 * @param   $count  某一个分组下面词的总数
 * @access  private
 * @return  array		随机产品 标题 关键字 seo标题
 */
function get_goods_title($pId){	
	$separator_0=$separator_1='';
	$proTit = array();
	
	$count_tNum_sql = "SELECT  count(DISTINCT `tNum`) FROM  " .  $GLOBALS['ecs2']->table('products_title') . "   WHERE `pId` = '$pId'";
	$group = $GLOBALS['db2']->getOne($count_tNum_sql);
	for($i=1; $i<=$group; $i++){
		$count_tId_sql = "select count(*) from " .  $GLOBALS['ecs2']->table('products_title') . "  where pId = '$pId'  and tNum = '$i'";
		$count = $GLOBALS['db2']->getOne($count_tId_sql);
		$j=rand(0, $count-1);
		$result_sql = "SELECT * FROM " .  $GLOBALS['ecs2']->table('products_title') . " WHERE `pId`= '$pId' and  `tNum` = '$i' limit $j , 1";
		$result =$GLOBALS['db2']->getRow($result_sql);
		$proTit['title']  .= $result['title'];
		//$proTit['SeoTitle'] .= $separator_0.$result['title'];
		$proTit['SeoKeywords'] .= $separator_1.$result['title'];
		$des = get_goods_description($pId,$result['tId']);
		$proTit['description'] .= $des['description'];
		$proTit['SeoDescription'] .= $des['SeoDescription'];
		$separator_0=' ';
		$separator_1=',';
	}
	
	$j = rand(2,5);	//产品描述后面的中性句随机数量范围	
	$result_sql = "SELECT  `zContent` FROM " .  $GLOBALS['ecs2']->table('products_zhongxingju') . "  ORDER BY rand() limit  $j";
	$result =$GLOBALS['db2']->getAll($result_sql);
	foreach($result as $va){			
		$zhongxing .= $va['zContent'];	//替换*
	}
	if (!get_magic_quotes_gpc()){
		$zhongxing = addslashes($zhongxing);
		$proTit['description'] = addslashes($proTit['description']);
		$proTit['SeoDescription'] = addslashes($proTit['SeoDescription']);
		$proTit['title']  = addslashes($proTit['title']) ;
	}else{
		echo "没有转义";
	}	
	$proTit['description'] = $proTit['description'].$zhongxing;
	return $proTit;
}

/**
 * 组 商品 描述
 * @param   $pId  	商品ID
 * @param   $group  分组的数量 一般为4
 * @param   $count  某一个分组下面词的总数
 * @access  private
 * @return  array		随机产品 描述 产品简介
 */
function get_goods_description($pId,$tId)
{
	$des= array();
	$count_pDId_sql = "SELECT  count(DISTINCT `pDId`) FROM " .  $GLOBALS['ecs2']->table('products_description') . " WHERE `tId` = '$tId'";
	$group = $GLOBALS['db2']->getOne($count_pDId_sql);
	$j=rand(0, $group-1);
	$result_sql = "SELECT description FROM " .  $GLOBALS['ecs2']->table('products_description') . " WHERE `tId`= '$tId'  limit $j , 1";
	$result = $GLOBALS['db2']->getOne($result_sql);
	$des['description'] =  $result;
	$des['SeoDescription'] = cut_str($result, 72);	//生成SeoDescription,最多72字符
	return $des;
	
}


/**
 * 获取产品属性值插入数据库
 * @access  private
 * @return  true		
 */
function getGoodsProperty(){
	$goods2= array();
	$sql  = "SELECT  `goods_id`,`core_id` FROM " .  $GLOBALS['ecs']->table('goods') ;//goods表里面的所有产品id
	$goods2 = $GLOBALS['db']->getAll($sql);
	
	foreach($goods2  as  $v2){
		
		$property = array();
		$property_sql = "SELECT `pId`, `pPNum`, `pContent` FROM " .  $GLOBALS['ecs2']->table('products_property') . "  WHERE  pId = ".$v2['core_id'];
		$property = $GLOBALS['db2']->getAll($property_sql);
		foreach($property as $v){
			$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('goods_attr') . "(`goods_id`, `attr_id`, `attr_value`) VALUES ('".$v2['goods_id']."','".$v['pPNum']."','".$v['pContent']."')";
			$GLOBALS['db']->query($sql);
		}
	}
	
	


	return true;

}


/**
 * 获取产参数值插入数据库
 * @access  private
 * @return  true		
 */
function getGoodsParameter(){
	$goods2= array();
	$sql  = "SELECT  `goods_id`,`core_id` FROM " .  $GLOBALS['ecs']->table('goods') ;//goods表里面的所有产品id
	$goods2 = $GLOBALS['db']->getAll($sql);
	foreach($goods2  as  $v2){
		$parameter = array();
		$parameter_sql = "SELECT `pId`, `pSName`, `pSDescription` FROM  " .  $GLOBALS['ecs2']->table('products_specification') . "  WHERE  pId=".$v2['core_id'];
		$parameter = $GLOBALS['db2']->getAll($parameter_sql);
		foreach($parameter as $v){
			$pSDescription = addslashes($v['pSDescription']);
			$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('goods_parameter') . " (`ProId`, `ParName`, `ParValue`) VALUES ('".$v2['goods_id']."','".$v['pSName']."','".$pSDescription."')";
			$GLOBALS['db']->query($sql);
		}
	}
	return true;

}


/**
 * 获取产品的评论并插入数据库
 * @access  private
 * @return  true		
 */
function getGoodsComment(){
	
	$goods_idarr= "";
	$goods2= array();
	$sql  = "SELECT  `goods_id` FROM " .  $GLOBALS['ecs']->table('goods') ;//goods表里面的所有产品id
	$goods2 = $GLOBALS['db']->getAll($sql);
	//$goods2 = array_unique($goods2);
	
	//$count_sql = "SELECT  count(*) FROM  ".$GLOBALS['ecs']->table('goods'); //查询产品数量
	//$count = $GLOBALS['db']->getOne($count_sql);
	//for($i=1; $i<=$count; $i++){
	foreach($goods2  as  $v2){
		$comment = array();
		$sum = rand(0,20); //随机评论条数
		//$sql = "SELECT * FROM `ecs_comment_all` WHERE `comId` >= (SELECT  floor(RAND() * (SELECT MAX(`comId`) FROM `ecs_comment_all`))) ORDER BY `comId` LIMIT $sum ";
		$sql = "SELECT * FROM " .  $GLOBALS['ecs2']->table('comment_all') . " ORDER BY rand() LIMIT $sum";
		$comment = $GLOBALS['db2']->getAll($sql);	
		foreach($comment as $v){
			$comment_rank = rand(3,5); //随机评论级别
			if (!get_magic_quotes_gpc()){
				//转义评论内容
				$content = addslashes(htmlspecialchars(urldecode(trim($v['content']))));
			}else{
				echo "没有转义";
			}
			$sql = "INSERT INTO  " .  $GLOBALS['ecs']->table('comment') . "(`comment_type`, `id_value`,  `user_name`, `content`, `comment_rank`, `add_time`, `ip_address`, `status`) VALUES ('0','".$v2['goods_id']."','".$v['name']."','".$content."','".$comment_rank."','". gmtime()."','".$v['area']."','1')";
			
			$GLOBALS['db']->query($sql);
		}
		
	}

	return true;
}

/**
 * 获取产品的分类
 * @access  private
 * @return  true		
**/

function getGoodsCategory(){
	$category = array();
	$separator_0=$separator_1='';
	$name_sql = "SELECT `cId`, `parentId`, `name`, `eName` FROM " .  $GLOBALS['ecs2']->table('category_name') . " WHERE 1 ";
	$category = $GLOBALS['db2']->getAll($name_sql);
	foreach($category as $v){
		$count_tNum_sql = "SELECT  count(DISTINCT `kNum`) FROM " .  $GLOBALS['ecs2']->table('category_keywords') . " WHERE `cId` = '".$v['cId']."' ";
		$group = $GLOBALS['db2']->getOne($count_tNum_sql); //算出多少组词
		$keywords = $description = "";
		for($i=1; $i<=$group; $i++){
			$count_tId_sql = "select count(*) from " .  $GLOBALS['ecs2']->table('category_keywords') . "  where cId = '".$v['cId']."'  and kNum = '$i'";
			$count = $GLOBALS['db2']->getOne($count_tId_sql);
			$j=rand(0, $count-1);
			$result_sql = "SELECT kId,keywords FROM " .  $GLOBALS['ecs2']->table('category_keywords') . " WHERE `cId`= '".$v['cId']."' and  `kNum` = '$i' limit $j , 1";
			$result =$GLOBALS['db2']->getRow($result_sql);
			$keywords .= $separator_1.$result['keywords'];
			//$SeoTitle .= $separator_0.$result['title'];
			$description .= getGoodsCategoryDescription($result['kId']);
			$separator_0=' ';
			$separator_1=',';
		}
		if (!get_magic_quotes_gpc()){
			$keywords = addslashes($keywords);
			$description = addslashes($description);
		}else{
			echo "没有转义";
		}	
		$sql = "INSERT INTO " .  $GLOBALS['ecs']->table('category') . "(`cat_id`, `cat_name`, `keywords`, `cat_desc`, `parent_id`, `is_show`, `grade`, `filter_attr`) VALUES ('".$v['cId']."','".$v['name']."','".$keywords."','".$description."','".$v['parentId']."','1','4','3,4,5,6,7,9,10')";
		$GLOBALS['db']->query($sql);
			
	}

	return true;
}

function getGoodsCategoryDescription($kId){	
	$des= array();
	$count_dId_sql = "SELECT  count(DISTINCT `dId`) FROM " .  $GLOBALS['ecs2']->table('category_description') . " WHERE `kId` = '". $kId."' ";
	$group = $GLOBALS['db2']->getOne($count_dId_sql);
	$f=rand(0, $group-1);
	$result_sql = "SELECT description FROM " .  $GLOBALS['ecs2']->table('category_description') . " WHERE `kId`= '".$kId."'  limit $f , 1";
	$description = $GLOBALS['db2']->getOne($result_sql);
	//$SeoDescription = cut_str($result, 72);	//生成SeoDescription,最多72字符
	return $description;
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