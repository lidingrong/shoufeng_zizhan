<?php

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

$cj_sql="CREATE TABLE IF NOT EXISTS  " . $ecs->table('url') ." (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `type` smallint(1) DEFAULT NULL COMMENT '1:商品分类2:商品前缀3:文章总分名4:文章列表5:文章前缀6:tags列名7:tags 8:help列名 9:help列表 10:help前缀',
  `re_id` int(10) DEFAULT NULL,
  `url` varchar(80) DEFAULT NULL,
  `rank` smallint(1) DEFAULT NULL COMMENT '0:总列名1:一级2:二级3:前缀',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=177 ;";
$db->query($cj_sql);


set_time_limit(20000);
ini_set('memory_limit','-1');
require_once './Classes/PHPExcel.php';
require_once './Classes/PHPExcel/IOFactory.php';
require_once './Classes/PHPExcel/Reader/Excel5.php';




//商品品牌
shengcheng_url('./excel/category.xlsx','1','1');
//商品系列
shengcheng_url('./excel/erji/Louis Vuitton.xlsx','1','2', '', 'ルイ・ヴィトン');
shengcheng_url('./excel/erji/Hermes.xlsx','1','2', '', 'エルメス');
shengcheng_url('./excel/erji/Gucci.xlsx','1','2', '', 'グッチ');
shengcheng_url('./excel/erji/Chanel.xlsx','1','2', '', 'シャネル');
shengcheng_url('./excel/erji/Celine.xlsx','1','2', '', 'セリーヌ');
//商品前缀
shengcheng_url('./excel/pro_qz.xlsx', '2', '3', '0');

//文章总分
shengcheng_url('./excel/arti_lie.xlsx', '3', '0', '9'); // 最后一个参数需要设置 （**** 必须）： 文章总分类ID
//文章品牌
shengcheng_url('./excel/article_cat.xlsx','4','1');
//文章前缀
shengcheng_url('./excel/arti_qz.xlsx','5', '3', '0');

//tags列
shengcheng_url('./excel/tags_lie.xlsx', '6', '0', '10'); // 最后一个参数需要设置 （**** 必须）： Tags总分类ID
//tags前缀
shengcheng_url('./excel/tags_qz.xlsx', '7', '3', '0');

//help列
shengcheng_url('./excel/help_lie.xlsx', '8', '0', '1'); // 最后一个参数需要设置 （**** 必须）： Help总分类ID
//help列表
shengcheng_url('./excel/help_cat.xlsx','9','1');
//help前缀
shengcheng_url('./excel/help_qz.xlsx', '10', '3', '0');




function shengcheng_url($filepath,$type,$rank,$re_id='',$parent_name=''){
	
	if($type==1){
		$typename=($rank==1)?'商品品牌分类':'商品系列分类';	
	}elseif($type==2){
		$typename='商品前缀';
	}elseif($type==3){
		$typename='文章总分名';
	}elseif($type==4){
		$typename='文章列表';
	}elseif($type==5){
		$typename='文章前缀';
	}elseif($type==6){
		$typename='tags列名';
	}elseif($type==7){
		$typename='tags';
	}elseif($type==8){
		$typename='help列名';
	}elseif($type==9){
		$typename='help列表';
	}elseif($type==10){
		$typename='help前缀';
	}
	
	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcel = $objReader->load($filepath); //指定的文件
	$sheet = $objPHPExcel->getSheet(0);
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumn = $sheet->getHighestColumn(); // 取得总列数
	
	for($j=1;$j<=$highestRow;$j++)
	{
		$lie="";
		for($c="B"; $c<=$highestColumn; $c++){
			$lie.=$c;
		}//从B开始循环看有多少列
	
		$rand_url=  rand_url($lie); //随机一个列
		while(($url=$objPHPExcel->getActiveSheet()->getCell($rand_url.$j)->getValue())==false){
			$rand_url=  rand_url($lie);
		}
	
		$cat_name=$objPHPExcel->getActiveSheet()->getCell("A".$j)->getValue(); //获取随机列的值
		
		if($type==1){
			if($rank==1){
				$cat_id=$GLOBALS['db']->getOne("SELECT cat_id FROM  " . $GLOBALS['ecs']->table('category') ."  where  cat_name='".$cat_name."'");
			}else{
				$parent_id=$GLOBALS['db']->getOne("SELECT cat_id FROM  " . $GLOBALS['ecs']->table('category') ."  where  cat_name='".$parent_name."'");
				$cat_id=$GLOBALS['db']->getOne("SELECT cat_id FROM  " . $GLOBALS['ecs']->table('category') ."  where  cat_name='".$cat_name."' and  parent_id='".$parent_id."' ");
			}
		}elseif(($type==4)){
			$cat_id=$GLOBALS['db']->getOne("SELECT cat_id FROM  " . $GLOBALS['ecs']->table('article_cat') ."  where  cat_name='".$cat_name."'");
		}elseif(($type==9)){
			$cat_id=$GLOBALS['db']->getOne("SELECT cat_id FROM  " . $GLOBALS['ecs']->table('article_cat') ."  where  cat_name='".$cat_name."'");
		}else{
			$cat_id=$re_id;
		}
		
		$fs_url=$GLOBALS['db']->getOne("SELECT url FROM  " . $GLOBALS['ecs']->table('url') ."  where  type='".$type."'   and   re_id= '".$cat_id."'   and   rank='".$rank."' ");
		if($fs_url==true){
			echo $typename.'---> '.$cat_name.'------<font color="#FF0000">已经存在数据，不需要生成</font><br/>';
		}else{
		
			$url=$objPHPExcel->getActiveSheet()->getCell($rand_url.$j)->getValue();
			$sql = "INSERT INTO " . $GLOBALS['ecs']->table('url') . " (type, re_id, url, rank) VALUES ('".$type."', '".$cat_id."', '".$url."', '".$rank."')";
			$GLOBALS['db']->query($sql);
			echo $cat_name.':----'.$url.'----'.$cat_id.'-----'.$rank.'------------------<font color="#00CC33">完成</font><br/>';
		}
		

	}
	
	
	
	echo '<font color="#FF0000">【'.$typename.'】--excel查询总共：'.$highestRow.'条数据</font><br/><br/>';
	
}


function rand_url($lie){
	$chars = substr($lie,(mt_rand()%strlen($lie)),1);
	if(empty($chars)){
		rand_url($lie);
	}
	return $chars;
}

?>