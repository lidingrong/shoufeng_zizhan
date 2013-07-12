<?php
//开发文件适用场景：
//产品顶级分类名（日文），文章分类名（日文），tags分类名（英文）必须对应起来，即各种分类的顶级分类必须保持一致性

/*使用方法实例：
1.请在init.php中包含这个文件
2.一共四个函数，分别在产品分类页，文章分类页，产品详细页，文章详细页调用
3.特别注意：产品详情页与文章详情页的关联数量最多可以调用30个



实例1：在产品分类页 
$a=zzw_goods_cat_link($调用类型,$分类id,$调用数量);
调用类型为 'gcat_to_goods' 为相关产品
          'gcat_to_article' 为相关文章
          'gcat_to_tags' 为相关tags
如：$a=zzw_goods_cat_link('gcat_to_goods',387,8);
然后将$a结果assign给模板变量即可！




实例2：在文章分类页
$b=zzw_article_cat_link($调用类型,$分类id,$调用数量);
调用类型为 'acat_to_goods' 为相关产品
          'acat_to_article' 为相关文章
          'acat_to_tags' 为相关tags
如：$b=zzw_article_cat_link('acat_to_goods',387,8);
然后将$b结果assign进模板变量即可！




实例3：在产品详情页
$c=zzw_goods_link($调用类型,$产品id,$数量,$匹配或者随机)
调用类型为  'goods_to_goods' 相关产品
		  'goods_to_article' 相关文章
		  'goods_to_tags'  相关tags
第四个参数 false为随机，true为关联，关联第一次调用会比较慢
如：$c=zzw_goods_link('goods_to_goods',12344,10,false);
然后将$c结果assign进模板变量即可!

实例4：在文章详情页
$d=zzw_article_link($调用类型,$产品id,$数量,$匹配或者随机)
调用类型为  'article_to_goods' 相关产品
		  'article_to_article' 相关文章
		  'article_to_tags'  相关tags
第四个参数 false为随机，true为关联，关联第一次调用会比较慢
如：$d=zzw_article_link('article_to_goods',12344,10,false);
然后将$d结果assign进模板变量即可!


*/
//不能随意修改！
//定义全局数组，用于匹配英文与日文的对应，目前有33个分类,多出来的分类会自动与热门的分类做匹配
$japan_and_english=array(
									'ミュウミュウ'=>'miu miu',
								   	'ボッテガ・ヴェネタ'=>'bottega veneta',
									'バーバリー'=>'burberry',
									'ブルガリ'=>'Bvlgari',
									'カルティエ'=>'Cartier',
									'セリーヌ'=>'Celine',
									'シャネル'=>'chanel',
									'クロエ'=>'chloe',
									'コーチ'=>'coach',
									'ディオール'=>'Dior',
									'ドルチェ&ガッバーナ'=>'Dolce & Gabbana',
									'フェンディ'=>'Fendi',
									'ジバンシー'=>'Givenchy',
									'グッチ'=>'gucci',
									'エルメス'=>'Hermes',
									'ジミー・チュウ'=>'Jimmy choo',
									'ランセル'=>'Lancel',
									'ロエベ'=>'loewe',
									'ルイ・ヴィトン'=>'louis vuitton',
									'マーク・ジェイコブス'=>'marc jacobs',
									'マルベリー'=>'mulberry',
									'プラダ'=>'prada',
									'プロエンザスクーラー'=>'Proenza Schoule',
									'サルヴァトーレ・フェラガモ'=>'Salvatore Ferragamo',
									'サマンサタバサ'=>'Samantha Thavasa',
									'ステラ・マッカートニー'=>'Stella McCartney',
									'トーマス・ワイルド'=>'Thomas Wylde',
									'トッズ'=>'Tods',
									'バレンチノ'=>'Valentino',
									'イヴ・サンローラン'=>'ysl',
									'アレキサンダー・ワン'=>'Alexander Wang',
									'バレンシアガ'=>'balenciaga',
									'3.1フィリップリム'=>'3.1 Phillip Lim',
									
									
									
									'その他'=>'LV2'
									
									);
$english_and_japan=array(
									'miu miu'=>  'ミュウミュウ',
								   	'bottega veneta'=>'ボッテガ・ヴェネタ',
									'burberry'=>'バーバリー',
									'Bvlgari'=>'ブルガリ',
									'Cartier'=>'カルティエ',
									'Celine'=>'セリーヌ',
									'chanel'=>'シャネル',
									'chloe'=>'クロエ',
									'coach'=>'コーチ',
									'Dior'=>'ディオール',
									'Dolce & Gabbana'=>'ドルチェ&ガッバーナ',
									'Fendi'=>'フェンディ',
									'Givenchy'=>'ジバンシー',
									'gucci'=>'グッチ',
									'Hermes'=>'エルメス',
									'Jimmy choo'=>'ジミー・チュウ',
									'Lancel'=>'ランセル',
									'loewe'=>'ロエベ',
									'louis vuitton'=>'ルイ・ヴィトン',
									'marc jacobs'=>'マーク・ジェイコブス',
									'mulberry'=>'マルベリー',
									'prada'=>'プラダ',
									'Proenza Schoule'=>'プロエンザスクーラー',
									'Salvatore Ferragamo'=>'サルヴァトーレ・フェラガモ',
									'Samantha Thavasa'=>'サマンサタバサ',
									'Stella McCartney'=>'ステラ・マッカートニー',
									'Thomas Wylde'=>'トーマス・ワイルド',
									'Tods'=>'トッズ',
									'Valentino'=>'バレンチノ',
									'ysl'=>'イヴ・サンローラン',
									'Alexander Wang'=>'アレキサンダー・ワン',
									'balenciaga'=>'バレンシアガ',
									'3.1 Phillip Lim'=>'3.1フィリップリム',
									
									
									'Juicy Couture'=>'LV2',
									'Armani'=>'CHANEL2',
									'others'=>'GUCCI2'
									);






/*四个基本调用函数*/
function  zzw_goods_cat_link($link_type,$cat_id,$num=8)
{
	//参数过滤
	$zzw_link_type=$link_type;
	$zzw_cat_id   =$cat_id;
	$zzw_num      =$num;
	
	//最后生成的数据存放的数组
	$zzw_related_all=array();

	switch ($zzw_link_type){
		case 'gcat_to_goods':
				//获取分类id下的子级分类id的序列
				$temp_1= zzw_get_children($zzw_cat_id);
				$sql_1 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;
				$row_1 = $GLOBALS['db']->getAll($sql_1);
				if($row_1)
				{
					//随机
					shuffle($row_1);
					$zzw_related_all=array_slice($row_1,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
						$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					//print_r($zzw_related_all);
				}else{
					return $zzw_related_all;
				}
		break;
		
		case 'gcat_to_article':
			//获取当前分类顶级分类的名字
			$parent=zzw_get_parent_cats($zzw_cat_id);
			if($parent){
				$top_cat=end($parent);
			}else{
				return $zzw_related_all;
			}
			//分类名
			$cat_name=$top_cat['cat_name'];
			global $japan_and_english;
			
			if(array_key_exists($cat_name,$japan_and_english)){
				//获取与商品分类对应的文章分类相关文章
				$article_cat_name=$cat_name;
				$sql_3="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." as ac WHERE cat_name= '".$article_cat_name."'";
				$article_cat_id=$GLOBALS['db']->getOne($sql_3);
				//echo $article_cat_id;
				//exit();
				$temp_3= zzw_get_article_children($article_cat_id);
				$sql_4="SELECT article_id,cat_id,title,description FROM ".$GLOBALS['ecs']->table('article')." as a WHERE  ".$temp_3;
				$row_2=$GLOBALS['db']->getAll($sql_4);
				if($row_2)
				{
					//随机
					shuffle($row_2);
					$zzw_related_all=array_slice($row_2,0,$zzw_num);
					
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					
					
				}else{
					return $zzw_related_all;
				}
				
			}else{
				return $zzw_related_all;
			}
		break;
		
		case 'gcat_to_tags':
			//获取当前分类顶级分类的名字
			$parent=zzw_get_parent_cats($zzw_cat_id);
			if($parent){
				$top_cat=end($parent);
			}else{
				return $zzw_related_all;
			}
			//print_r($top_cat_id);
			//exit;
			$cat_name=$top_cat['cat_name'];
			global $japan_and_english;
			//print_r($goods_link_article);
			
			if(array_key_exists($cat_name,$japan_and_english)){
				//获取与商品分类对应的文章分类相关文章
				$cat_name_en=$japan_and_english[$cat_name];
				//echo $cat_name_en;
				//exit();
				$article_cat_name=$cat_name_en;
				$sql_3="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." as ac WHERE cat_name= '".$article_cat_name."'";
				$article_cat_id=$GLOBALS['db']->getOne($sql_3);
				//echo $article_cat_id;
				//exit();
				$temp_3= zzw_get_article_children($article_cat_id);
				$sql_4="SELECT article_id,cat_id,title,description FROM ".$GLOBALS['ecs']->table('article')." as a WHERE  ".$temp_3;
				$row_2=$GLOBALS['db']->getAll($sql_4);
				if($row_2)
				{
					//随机
					shuffle($row_2);
					$zzw_related_all=array_slice($row_2,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
				}else{
					return $zzw_related_all;
				}
				
			}else{
				return $zzw_related_all;
			}
		break;
		default:
		exit();
	}
	//增加url
	
	return $zzw_related_all;
}


function  zzw_article_cat_link($link_type,$article_cat_id,$num=8)
{
	
	//参数过滤
	$zzw_link_type=$link_type;
	$zzw_article_cat_id   =$article_cat_id;
	$zzw_num      =$num;
	//最后生成的数据存放的数组
	$zzw_related_all=array();
	
	switch ($zzw_link_type){
		case 'acat_to_goods':
				//获取当前文章分类的名字
				$sql001="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$zzw_article_cat_id;
				$cat_name=$GLOBALS['db']->getOne($sql001);
				global $japan_and_english;	
				global $english_and_japan;
				
				
				//如果名字为日文，说明是普通文章分类
				if(array_key_exists($cat_name,$japan_and_english)){
					//判断一些扩展不对应的分类，对应到热门分类下
					if($cat_name=='その他'){
						$cat_name='ルイ・ヴィトン';
					}
					
					$sql_1="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('category')." as ac WHERE parent_id=0 and  cat_name= '".$cat_name."'";
					$my_cat_id=$GLOBALS['db']->getOne($sql_1);
				}
				
				if(in_array($cat_name,$japan_and_english)){
					//判断一些扩展不对应的分类，对应到热门分类下
					if($cat_name=='Juicy Couture'){
						$cat_name='louis vuitton';
					}elseif($cat_name=='Armani'){
						$cat_name='chanel';
					}elseif($cat_name=='others'){
						$cat_name='gucci';
					}
				
					$temp_j_name=$english_and_japan[$cat_name];
					$sql_1="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('category')." as ac WHERE parent_id=0 and  cat_name= '".$temp_j_name."'";
					$my_cat_id=$GLOBALS['db']->getOne($sql_1);
				}
				//获取分类id下的子级分类id的序列
				$temp_1= zzw_get_children($my_cat_id);
				$sql_1 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;
				$row_1 = $GLOBALS['db']->getAll($sql_1);
				if($row_1)
				{
					//随机
					shuffle($row_1);
					$zzw_related_all=array_slice($row_1,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
						$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
				    }
				}else{
					return $zzw_related_all;
				}
		break;
		
		case 'acat_to_article':
			    $sql001="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$zzw_article_cat_id;
				$cat_name=$GLOBALS['db']->getOne($sql001);
				global $japan_and_english;	
				global $english_and_japan;
				
				//如果名字为日文，说明是普通文章分类
				if(array_key_exists($cat_name,$japan_and_english)){
						$sql001="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$zzw_article_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql001);
				}
				
				if(in_array($cat_name,$japan_and_english)){//如果是英文，说明是tags
						//判断一些扩展不对应的分类，对应到热门分类下
						if($cat_name=='Juicy Couture'){
							$cat_name='louis vuitton';
						}elseif($cat_name=='Armani'){
							$cat_name='chanel';
						}elseif($cat_name=='others'){
							$cat_name='gucci';
						}
					    $temp_j_name=$english_and_japan[$cat_name];
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('article_cat')." where cat_name='".$temp_j_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						
						$sql002="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$a_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql002);
				}
				
			if($row_2)
			{
				//随机
				shuffle($row_2);
				$zzw_related_all=array_slice($row_2,0,$zzw_num);
				for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
			}else{
				return $zzw_related_all;
			}
				
			
		break;
		
		case 'acat_to_tags':
				 $sql001="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$zzw_article_cat_id;
				$cat_name=$GLOBALS['db']->getOne($sql001);
				global $japan_and_english;	
				global $english_and_japan;
				
				//如果名字为日文，说明是普通文章分类
				if(array_key_exists($cat_name,$japan_and_english)){
						//判断一些扩展不对应的分类，对应到热门分类下
						if($cat_name=='その他'){
							$cat_name='ルイ・ヴィトン';
						}
						$temp_j_name=$japan_and_english[$cat_name];
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('article_cat')." where cat_name= '".$temp_j_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						
						$sql002="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$a_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql002);
						
				}
				
				if(in_array($cat_name,$japan_and_english)){//如果是英文，说明是tags
					  $sql001="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$zzw_article_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql001);
				}
				
			if($row_2)
			{
				//随机
				shuffle($row_2);
				$zzw_related_all=array_slice($row_2,0,$zzw_num);
				for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
				//print_r($zzw_related_all);
				//exit();
			}else{
					return $zzw_related_all;
			}
				
		break;
		default:
		exit();
	}
	return $zzw_related_all;
}

function  zzw_goods_link($link_type,$goods_id,$num=8,$rand_or_match=false)
{
	//参数过滤
	$zzw_link_type     =$link_type;
	$zzw_goods_id      =$goods_id;
	$zzw_num           =$num;
	$zzw_rand_or_match = $rand_or_match;
	
	//最后生成的数据存放的数组
	$zzw_related_all=array();
	if($zzw_num>40){
		return $zzw_related_all;
	}
	switch ($zzw_link_type){
		case 'goods_to_goods':
				$sql001="select  goods_name,cat_id  from ".$GLOBALS['ecs']->table('goods')." where goods_id=".$zzw_goods_id;
				$row001=$GLOBALS['db']->getRow($sql001);
				$goods_name=$row001['goods_name'];
				$temp_1= zzw_get_children($row001['cat_id']);
				
				$sql002 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;
				$row002= $GLOBALS['db']->getAll($sql002);
				if($row002)
				{
					//产品均在同个分类下，匹配性完全可以忽略为随机性
					if(!$zzw_rand_or_match){
							//随机
							shuffle($row002);
							$zzw_related_all=array_slice($row002,0,$zzw_num);
							for($i=0;$i<count($zzw_related_all);$i++){
								//echo $zzw_related_all[$i]['article_id'];
								//echo $zzw_related_all[$i]['title'];
								//exit();
								$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
								$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
								array_merge($zzw_related_all[$i],array('url'=>$url));
								//echo $url;
								//exit();
							}
							
						}else{
							//$sql003="select link_goods_id  from ".$GLOBALS['ecs']->table('link_goods') ."  where goods_id=".$zzw_goods_id;
							//echo $sql003;
							$sql004="select count(*)  from ".$GLOBALS['ecs']->table('link_goods')."  where goods_id=".$zzw_goods_id;
							$temp_num=$GLOBALS['db']->getOne($sql004);
							if(!$temp_num){
										//关联的文章数为空的话，直接进行匹配入库
										 foreach($row002 as $k2=>$v2){
											//匹配标题
											 $compare_article_name=$v2['goods_name']; 
											 $compare_article_id[]=$v2['goods_id'];    
											 similar_text($goods_name,$compare_article_name,$p);  
											 $per[]=$p; }
									 
									  arsort($per,SORT_NUMERIC);//逆序排列相似百分比
									
									  $zzw_max_n=count($per);
									 // echo $zzw_max_n;
									 if($zzw_max_n){
									 	 $zzw_max_n=($zzw_max_n>30)?30:$zzw_max_n;
									  }else{
									  	return $zzw_related_all;
									  }
									   for($i=0;$i<$zzw_max_n;$i++){
												$temp_id=key($per);
												next($per);
												$temp_id_real[]=$compare_article_id[$temp_id];
										 }
										//入库
										foreach($temp_id_real  as $kk=>$vv){
													if($zzw_goods_id != $vv){
													$insert_sql="INSERT INTO ".$GLOBALS['ecs']->table('link_goods')." (goods_id, link_goods_id) VALUES (".$zzw_goods_id.", ".$vv.")";
													//echo $insert_sql."<br/>";
													$GLOBALS['db']->query($insert_sql);}
										}
														
										$sql3="select  link_goods_id  from  ".$GLOBALS['ecs']->table('link_goods')."  where goods_id =".$zzw_goods_id;
										$all_id=$GLOBALS['db']->getCol($sql3); 
										$temp_id_str=implode(',',$all_id);
										
										$sql4="select  goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id  from  ".$GLOBALS['ecs']->table('goods')."  where goods_id in (".$temp_id_str.")  limit 0,".$zzw_num;
										$zzw_related_all=$GLOBALS['db']->getAll($sql4);  
										for($i=0;$i<count($zzw_related_all);$i++){
											//echo $zzw_related_all[$i]['article_id'];
											//echo $zzw_related_all[$i]['title'];
											//exit();
											$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
											$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
											array_merge($zzw_related_all[$i],array('url'=>$url));
											//echo $url;
											//exit();
										}
										
							}else{
									//直接读取数据就可以了
									$sql3="select  link_goods_id  from  ".$GLOBALS['ecs']->table('link_goods')."  where goods_id =".$zzw_goods_id;
									$all_id=$GLOBALS['db']->getCol($sql3); 
									$temp_id_str=implode(',',$all_id);
									 $sql4="select  goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id  from  ".$GLOBALS['ecs']->table('goods')."  where goods_id in (".$temp_id_str.")  limit 0,".$zzw_num;
									 $zzw_related_all=$GLOBALS['db']->getAll($sql4); 
									 for($i=0;$i<count($zzw_related_all);$i++){
											//echo $zzw_related_all[$i]['article_id'];
											//echo $zzw_related_all[$i]['title'];
											//exit();
											$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
											$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
											array_merge($zzw_related_all[$i],array('url'=>$url));
											//echo $url;
											//exit();
										} 
									 }
								
							}
					}
				
		break;
		
		
		case 'goods_to_article':
				$sql="select  goods_name  from ".$GLOBALS['ecs']->table('goods')." where goods_id=".$zzw_goods_id;
				$row=$GLOBALS['db']->getOne($sql);
				$goods_name=$row;
				
				
				$sql001="select cat_id from ".$GLOBALS['ecs']->table('goods')." where goods_id=".$zzw_goods_id;
				$cat_id=$GLOBALS['db']->getOne($sql001);
				
				$parent=zzw_get_parent_cats($cat_id);
				if($parent){
					$top_cat=end($parent);
				}else{
					return $zzw_related_all;
				}
				$cat_name=$top_cat['cat_name'];
				/**/
				//直接用顶级分类名对应文章的分类
				$article_cat_name=$cat_name;
				$sql002="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." as ac WHERE cat_name= '".$article_cat_name."'";
				$article_cat_id=$GLOBALS['db']->getOne($sql002);
				
				$sql_4="SELECT article_id,cat_id,title,description FROM ".$GLOBALS['ecs']->table('article')." as a WHERE  cat_id=".$article_cat_id;
				//$sql_4="SELECT * FROM ".$GLOBALS['ecs']->table('article');
				$row002=$GLOBALS['db']->getAll($sql_4);
				
				
				if(!$zzw_rand_or_match)
				{
					//$sql_5="SELECT * FROM ".$GLOBALS['ecs']->table('article')." as a WHERE  cat_id=".$article_cat_id;
					//$row003=$GLOBALS['db']->getAll($sql_5);
					//随机
					shuffle($row002);
					$zzw_related_all=array_slice($row002,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					
				}else{
						//精确匹配入库
						$sql004="select count(*)  from ".$GLOBALS['ecs']->table('goods_article')."  where goods_id=".$zzw_goods_id;
						$temp_num=$GLOBALS['db']->getOne($sql004);
						
						if($temp_num > $zzw_num){
								//直接读取数据就可以了
								$sql3="select  article_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where goods_id =".$zzw_goods_id;
								$all_id=$GLOBALS['db']->getCol($sql3); 
								$temp_id_str=implode(',',$all_id);
								$sql4="select  article_id,cat_id,title,description  from  ".$GLOBALS['ecs']->table('article')."  where article_id in (".$temp_id_str.")  limit 0,".$zzw_num;
								$zzw_related_all=$GLOBALS['db']->getAll($sql4);  
								for($i=0;$i<count($zzw_related_all);$i++){
									//echo $zzw_related_all[$i]['article_id'];
									//echo $zzw_related_all[$i]['title'];
									//exit();
									$a=array('aid'=>$zzw_related_all[$i]['article_id']);
									$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
									array_merge($zzw_related_all[$i],array('url'=>$url));
									//echo $url;
									//exit();
								}
						}else{
								if(!$temp_num || $temp_num < $zzw_num){
									//关联的文章数为空的话，直接进行匹配入库
										foreach($row002 as $k2=>$v2){
									 	//匹配标题
										 $compare_article_name=$v2['title']; 
										 $compare_article_id[]=$v2['article_id'];    
										 similar_text($goods_name,$compare_article_name,$p);  
										 $per[]=$p;
									 }
								 	arsort($per,SORT_NUMERIC);//逆序排列相似百分比
										  $zzw_max_n=count($per);
										   if($zzw_max_n){
												 $zzw_max_n=($zzw_max_n>30)?30:$zzw_max_n;
											  }else{
												return $zzw_related_all;
											  }
										   for($i=0;$i<$zzw_max_n;$i++){
													$temp_id=key($per);
													next($per);
													$temp_id_real[]=$compare_article_id[$temp_id];
											 }
											//入库
											foreach($temp_id_real  as $kk=>$vv){
														$insert_sql="INSERT INTO ".$GLOBALS['ecs']->table('goods_article')." (goods_id, article_id) VALUES (".$zzw_goods_id.", ".$vv.")";
														//echo $insert_sql."<br/>";
														$GLOBALS['db']->query($insert_sql);
											}
											
											$sql3="select  article_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where goods_id =".$zzw_goods_id;
											
											$all_id=$GLOBALS['db']->getCol($sql3); 
											$temp_id_str=implode(',',$all_id);
											$sql4="select  article_id,cat_id,title,description  from  ".$GLOBALS['ecs']->table('article')."  where article_id in (".$temp_id_str.")   limit 0,".$zzw_num;
											 $zzw_related_all=$GLOBALS['db']->getAll($sql4);  
											 for($i=0;$i<count($zzw_related_all);$i++){
												//echo $zzw_related_all[$i]['article_id'];
												//echo $zzw_related_all[$i]['title'];
												//exit();
												$a=array('aid'=>$zzw_related_all[$i]['article_id']);
												$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
												array_merge($zzw_related_all[$i],array('url'=>$url));
												//echo $url;
												//exit();
											}
									}else{
													//直接读取数据就可以了
													$sql3="select  article_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where goods_id =".$zzw_goods_id;
													$all_id=$GLOBALS['db']->getCol($sql3); 
													$temp_id_str=implode(',',$all_id);
													$sql4="select  article_id,cat_id,title,description  from  ".$GLOBALS['ecs']->table('article')."  where article_id in (".$temp_id_str.")  limit 0,".$zzw_num;
													$zzw_related_all=$GLOBALS['db']->getAll($sql4);  
													for($i=0;$i<count($zzw_related_all);$i++){
													//echo $zzw_related_all[$i]['article_id'];
													//echo $zzw_related_all[$i]['title'];
													//exit();
													$a=array('aid'=>$zzw_related_all[$i]['article_id']);
													$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
													array_merge($zzw_related_all[$i],array('url'=>$url));
													//echo $url;
													//exit();
												}
									}
						
								}
									
							}
			
		break;
		
		case 'goods_to_tags':
				$sql="select  goods_name  from ".$GLOBALS['ecs']->table('goods')." where goods_id=".$zzw_goods_id;
				$row=$GLOBALS['db']->getOne($sql);
				$goods_name=$row;
				//echo $goods_name;
				$sql001="select cat_id from ".$GLOBALS['ecs']->table('goods')." where goods_id=".$zzw_goods_id;
				$cat_id=$GLOBALS['db']->getOne($sql001);
				//获取顶级产品分类id
				$parent=zzw_get_parent_cats($cat_id);
				if($parent){
					$top_cat=end($parent);
				}else{
					return $zzw_related_all;
				}
				$cat_name=$top_cat['cat_name'];
				//echo $cat_name;
				//exit();
				//直接用顶级分类名对应文章的分类
				global $japan_and_english;
				$article_cat_name=$japan_and_english[$cat_name];
				//echo $article_cat_name;
				//exit();
				
				$sql002="SELECT ac.cat_id FROM ".$GLOBALS['ecs']->table('article_cat')." as ac WHERE cat_name= '".$article_cat_name."'";
				$article_cat_id=$GLOBALS['db']->getOne($sql002);
				//echo $article_cat_id;
				//exit();
				//$temp_3= zzw_get_article_children($article_cat_id);
				$sql_4="SELECT article_id,cat_id,title,description FROM ".$GLOBALS['ecs']->table('article')." as a WHERE  cat_id=".$article_cat_id;
				$row002=$GLOBALS['db']->getAll($sql_4);
				if(!$zzw_rand_or_match)
				{
					//随机
					shuffle($row002);
					$zzw_related_all=array_slice($row002,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					//print_r($zzw_related_all);
				}else{
					//暂时随机
					shuffle($row002);
					$zzw_related_all=array_slice($row002,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					//print_r($zzw_related_all);
					//exit();
				}		
		break;
		default:
		exit();
	}
	return $zzw_related_all;
}

function  zzw_article_link($link_type,$article_id,$num=8,$rand_or_match=false)
{
	
	//参数过滤
	$zzw_link_type     =$link_type;
	$zzw_article_id     =$article_id;
	$zzw_num           =$num;
	$zzw_rand_or_match = $rand_or_match;
	
	//最后生成的数据存放的数组
	$zzw_related_all=array();
	
	if($zzw_num>30){
		return $zzw_related_all;
	}
	switch ($zzw_link_type){
		case 'article_to_article':
				$sql="select cat_id from ".$GLOBALS['ecs']->table('article')." where article_id=".$zzw_article_id;
				$article_cat_id=$GLOBALS['db']->getOne($sql);
				
				
				$sql1="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$article_cat_id;
				$article_cat_name=$GLOBALS['db']->getOne($sql1);
				
				//echo $article_cat_name;
				//exit();
				global $japan_and_english;	
				global $english_and_japan;
				
				//如果名字为日文，说明是普通文章分类
				if(array_key_exists($article_cat_name,$japan_and_english)){
						 $sql001="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$article_cat_id;
						 //echo $sql001;
						 //exit();
						 $row_2=$GLOBALS['db']->getAll($sql001);
							 
				}
				
				if(in_array($article_cat_name,$japan_and_english)){//如果是英文，说明是tags详情页
						//判断一些扩展不对应的分类，对应到热门分类下
						if($cat_name=='Juicy Couture'){
							$cat_name='louis vuitton';
						}elseif($cat_name=='Armani'){
							$cat_name='chanel';
						}elseif($cat_name=='others'){
							$cat_name='gucci';
						}
				
					 	 $temp_j_name=$english_and_japan[$article_cat_name];
						 //echo $temp_j_namel;
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('article_cat')." where cat_name='".$temp_j_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						
						$sql002="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$a_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql002);
				}
				
				 if($row_2)
				 {
					//如果选择随即关联的话
					if(!$zzw_rand_or_match){
							//随机
							shuffle($row_2);
							$zzw_related_all=array_slice($row_2,0,$zzw_num);
							for($i=0;$i<count($zzw_related_all);$i++){
								//echo $zzw_related_all[$i]['article_id'];
								//echo $zzw_related_all[$i]['title'];
								//exit();
								$a=array('aid'=>$zzw_related_all[$i]['article_id']);
								$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
								array_merge($zzw_related_all[$i],array('url'=>$url));
								//echo $url;
								//exit();
							}
						}else{
							//随机
							shuffle($row_2);
								$zzw_related_all=array_slice($row_2,0,$zzw_num);
								for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('aid'=>$zzw_related_all[$i]['article_id']);
						$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
							}
				 }else{
					return $zzw_related_all;
				}
				
				
		break;
		
		
		case 'article_to_goods':
				
				$sql="select cat_id from ".$GLOBALS['ecs']->table('article')." where article_id=".$zzw_article_id;
				$article_cat_id=$GLOBALS['db']->getOne($sql);
				$sql="select title from ".$GLOBALS['ecs']->table('article')." where article_id=".$zzw_article_id;
				$article_name=$GLOBALS['db']->getOne($sql);
				
				$sql1="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$article_cat_id;
				$article_cat_name=$GLOBALS['db']->getOne($sql1);
				
				global $japan_and_english;	
				global $english_and_japan;
				if(array_key_exists($article_cat_name,$japan_and_english)){//如果名字为日文，说明是普通文章分类
						/*
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('category')." where cat_name='".$article_cat_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						$temp_1= zzw_get_children($a_cat_id);
						$sql002 = "SELECT * FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;*/
						//判断一些扩展不对应的分类，对应到热门分类下
						if($article_cat_name=='その他'){
							$article_cat_name='ルイ・ヴィトン';
						}
						$sql002 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods');
						$row002_article= $GLOBALS['db']->getAll($sql002); //所有产品信息
				}
				if(in_array($article_cat_name,$japan_and_english)){//如果是英文，说明是tags详情页
						//判断一些扩展不对应的分类，对应到热门分类下
						if($article_cat_name=='Juicy Couture'){
							$article_cat_name='louis vuitton';
						}elseif($article_cat_name=='Armani'){
							$article_cat_name='chanel';
						}elseif($article_cat_name=='others'){
							$article_cat_name='gucci';
						}
						/**/$temp_j_name=$english_and_japan[$article_cat_name];
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('category')." where cat_name='".$temp_j_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						$temp_1= zzw_get_children($a_cat_id);
						$sql002 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;
						$row002_tags= $GLOBALS['db']->getAll($sql002); //所有产品信息
				}
				if($row002_tags){
					//随机
					shuffle($row002_tags);
					$zzw_related_all=array_slice($row002_tags,0,$zzw_num);
					for($i=0;$i<count($zzw_related_all);$i++){
						//echo $zzw_related_all[$i]['article_id'];
						//echo $zzw_related_all[$i]['title'];
						//exit();
						$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
						$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
						array_merge($zzw_related_all[$i],array('url'=>$url));
						//echo $url;
						//exit();
					}
					//print_r($zzw_related_all);
				}elseif($row002_article){
								if(!$zzw_rand_or_match)
								{
									$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('category')." where cat_name='".$article_cat_name."'";
									$a_cat_id=$GLOBALS['db']->getOne($sql001);
									$temp_1= zzw_get_children($a_cat_id);
									$sql002 = "SELECT goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id FROM ".$GLOBALS['ecs']->table('goods')."as g WHERE ".$temp_1;
									//随机
									shuffle($row002_article);
									$zzw_related_all=array_slice($row002_article,0,$zzw_num);
									for($i=0;$i<count($zzw_related_all);$i++){
										//echo $zzw_related_all[$i]['article_id'];
										//echo $zzw_related_all[$i]['title'];
										//exit();
										$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
										$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
										array_merge($zzw_related_all[$i],array('url'=>$url));
										//echo $url;
										//exit();
									}
									//print_r($zzw_related_all);
								}else{
										//精确匹配入库
										$sql004="select count(*)  from ".$GLOBALS['ecs']->table('goods_article')."  where article_id=".$zzw_article_id;
										$temp_num=$GLOBALS['db']->getOne($sql004);
										
										if($temp_num > $zzw_num){
												//直接读取数据就可以了
												$sql3="select  goods_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where  article_id =".$zzw_article_id;
												$all_id=$GLOBALS['db']->getCol($sql3); 
												$temp_id_str=implode(',',$all_id);
												$sql4="select  goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id  from  ".$GLOBALS['ecs']->table('goods')."  where goods_id in (".$temp_id_str.")  limit 0,".$zzw_num;
												$zzw_related_all=$GLOBALS['db']->getAll($sql4);  
										}else{
												if(!$temp_num || $temp_num < $zzw_num){
													//关联的文章数为空的话，直接进行匹配入库
														foreach($row002_article as $k2=>$v2){
														//匹配标题
														 $compare_article_name=$v2['goods_name']; 
														 $compare_article_id[]=$v2['goods_id'];    
														 similar_text($article_name,$compare_article_name,$p);  
														 $per[]=$p;
													 }
													arsort($per,SORT_NUMERIC);//逆序排列相似百分比
														  $zzw_max_n=count($per);
														   if($zzw_max_n){
																 $zzw_max_n=($zzw_max_n>30)?30:$zzw_max_n;
															  }else{
																return $zzw_related_all;
															  }
														   for($i=0;$i<$zzw_max_n;$i++){
																	$temp_id=key($per);
																	next($per);
																	$temp_id_real[]=$compare_article_id[$temp_id];
															 }
															//入库
															foreach($temp_id_real  as $kk=>$vv){
																		$insert_sql="INSERT INTO ".$GLOBALS['ecs']->table('goods_article')." (goods_id, article_id) VALUES (".$vv.", ".$zzw_article_id.")";
																		$GLOBALS['db']->query($insert_sql);

															}
															
															$sql3="select  goods_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where article_id =".$zzw_article_id;
															
															$all_id=$GLOBALS['db']->getCol($sql3); 
															$temp_id_str=implode(',',$all_id);
															$sql4="select  goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id  from  ".$GLOBALS['ecs']->table('goods')."  where goods_id in (".$temp_id_str.")   limit 0,".$zzw_num;
															 $zzw_related_all=$GLOBALS['db']->getAll($sql4);  
															 for($i=0;$i<count($zzw_related_all);$i++){
																//echo $zzw_related_all[$i]['article_id'];
																//echo $zzw_related_all[$i]['title'];
																//exit();
																$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
																$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
																array_merge($zzw_related_all[$i],array('url'=>$url));
																//echo $url;
																//exit();
															}
														}else{
																	//直接读取数据就可以了
																	$sql3="select  goods_id  from  ".$GLOBALS['ecs']->table('goods_article')."  where article_id =".$zzw_article_id;
																	$all_id=$GLOBALS['db']->getCol($sql3); 
																	$temp_id_str=implode(',',$all_id);
																	$sql4="select  goods_id,cat_id,goods_name,shop_price,goods_desc,original_img,core_id  from  ".$GLOBALS['ecs']->table('goods')."  where goods_id in (".$temp_id_str.")  limit 0,".$zzw_num;
																	$zzw_related_all=$GLOBALS['db']->getAll($sql4); 
																	for($i=0;$i<count($zzw_related_all);$i++){
																		//echo $zzw_related_all[$i]['article_id'];
																		//echo $zzw_related_all[$i]['title'];
																		//exit();
																		$a=array('gid'=>$zzw_related_all[$i]['goods_id']);
																		$url=build_uri('goods', $a, $zzw_related_all[$i]['goods_name']);
																		array_merge($zzw_related_all[$i],array('url'=>$url));
																		//echo $url;
																		//exit();
																	} 
															}
										
												}
												
											}
						}else{
							return $zzw_related_all;
						}
				
				
				
		break;
		
		case 'article_to_tags':
				$sql="select cat_id from ".$GLOBALS['ecs']->table('article')." where article_id=".$zzw_article_id;
				$article_cat_id=$GLOBALS['db']->getOne($sql);
				
				$sql1="select cat_name from ".$GLOBALS['ecs']->table('article_cat')." where cat_id=".$article_cat_id;
				$article_cat_name=$GLOBALS['db']->getOne($sql1);
				
				//echo $article_cat_name;
				
				global $japan_and_english;	
				global $english_and_japan;
				
				//如果名字为日文，说明是普通文章分类
				if(array_key_exists($article_cat_name,$japan_and_english)){
						 $temp_j_name=$japan_and_english[$article_cat_name];
						$sql001="select  cat_id  from ".$GLOBALS['ecs']->table('article_cat')." where cat_name='".$temp_j_name."'";
						$a_cat_id=$GLOBALS['db']->getOne($sql001);
						
						$sql002="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$a_cat_id;
						$row_2=$GLOBALS['db']->getAll($sql002);
				
						
				}
				
				if(in_array($article_cat_name,$japan_and_english)){//如果是英文，说明是tags详情页
					 	 $sql001="select article_id,cat_id,title,description from ".$GLOBALS['ecs']->table('article')." where cat_id=".$article_cat_id;
						 $row_2=$GLOBALS['db']->getAll($sql001);
				}
				
				 if($row_2)
				 {
					//如果选择随即关联的话
					if(!$zzw_rand_or_match){
							//随机
							shuffle($row_2);
							$zzw_related_all=array_slice($row_2,0,$zzw_num);
							for($i=0;$i<count($zzw_related_all);$i++){
								//echo $zzw_related_all[$i]['article_id'];
								//echo $zzw_related_all[$i]['title'];
								//exit();
								$a=array('aid'=>$zzw_related_all[$i]['article_id']);
								$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
								array_merge($zzw_related_all[$i],array('url'=>$url));
								//echo $url;
								//exit();
							}
						}else{
							//随机
							shuffle($row_2);
							$zzw_related_all=array_slice($row_2,0,$zzw_num);
							for($i=0;$i<count($zzw_related_all);$i++){
								//echo $zzw_related_all[$i]['article_id'];
								//echo $zzw_related_all[$i]['title'];
								//exit();
								$a=array('aid'=>$zzw_related_all[$i]['article_id']);
								$url=build_uri('article', $a, $zzw_related_all[$i]['title']);
								array_merge($zzw_related_all[$i],array('url'=>$url));
								//echo $url;
								//exit();
							}
							}
				 }else{
					return $zzw_related_all;
				}
			
		break;
		default:
		exit();
	}
	return $zzw_related_all;
}


/*公用函数*/
/**
 * 创建像这样的查询: "IN('a','b')";
 *
 * @access   public
 * @param    mix      $item_list      列表数组或字符串
 * @param    string   $field_name     字段名称
 *
 * @return   void
 */
function zzw_db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}
/**
 * 获得指定分类下所有底层分类的ID
 *
 * @access  public
 * @param   integer     $cat        指定的分类ID
 * @return  string
 */
function zzw_get_children($cat = 0)
{
    return 'g.cat_id ' . zzw_db_create_in(array_unique(array_merge(array($cat), array_keys(zzw_cat_list($cat, 0, false)))));
}
/**
 * 获得指定分类下的子分类的数组
 *
 * @access  public
 * @param   int     $cat_id     分类的ID
 * @param   int     $selected   当前选中分类的ID
 * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
 * @param   int     $level      限定返回的级数。为0时返回所有级数
 * @param   int     $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
 * @return  mix
 */
function zzw_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true)
{
    static $res = NULL;

    if ($res === NULL)
    {
        $data = read_static_cache('cat_pid_releate');
        if ($data === false)
        {
            $sql = "SELECT c.cat_id, c.cat_name, c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(s.cat_id) AS has_children ".
                'FROM ' . $GLOBALS['ecs']->table('category') . " AS c ".
                "LEFT JOIN " . $GLOBALS['ecs']->table('category') . " AS s ON s.parent_id=c.cat_id ".
                "GROUP BY c.cat_id ".
                'ORDER BY c.parent_id, c.sort_order ASC';
            $res = $GLOBALS['db']->getAll($sql);

            $sql = "SELECT cat_id, COUNT(*) AS goods_num " .
                    " FROM " . $GLOBALS['ecs']->table('goods') .
                    " WHERE is_delete = 0 AND is_on_sale = 1 " .
                    " GROUP BY cat_id";
            $res2 = $GLOBALS['db']->getAll($sql);

            $sql = "SELECT gc.cat_id, COUNT(*) AS goods_num " .
                    " FROM " . $GLOBALS['ecs']->table('goods_cat') . " AS gc , " . $GLOBALS['ecs']->table('goods') . " AS g " .
                    " WHERE g.goods_id = gc.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 " .
                    " GROUP BY gc.cat_id";
            $res3 = $GLOBALS['db']->getAll($sql);

            $newres = array();
            foreach($res2 as $k=>$v)
            {
                $newres[$v['cat_id']] = $v['goods_num'];
                foreach($res3 as $ks=>$vs)
                {
                    if($v['cat_id'] == $vs['cat_id'])
                    {
                    $newres[$v['cat_id']] = $v['goods_num'] + $vs['goods_num'];
                    }
                }
            }

            foreach($res as $k=>$v)
            {
                $res[$k]['goods_num'] = !empty($newres[$v['cat_id']]) ? $newres[$v['cat_id']] : 0;
            }
            //如果数组过大，不采用静态缓存方式
            if (count($res) <= 1000)
            {
                write_static_cache('cat_pid_releate', $res);
            }
        }
        else
        {
            $res = $data;
        }
    }

    if (empty($res) == true)
    {
        return $re_type ? '' : array();
    }

    $options = zzw_cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

    $children_level = 99999; //大于这个分类的将被删除
    if ($is_show_all == false)
    {
        foreach ($options as $key => $val)
        {
            if ($val['level'] > $children_level)
            {
                unset($options[$key]);
            }
            else
            {
                if ($val['is_show'] == 0)
                {
                    unset($options[$key]);
                    if ($children_level > $val['level'])
                    {
                        $children_level = $val['level']; //标记一下，这样子分类也能删除
                    }
                }
                else
                {
                    $children_level = 99999; //恢复初始值
                }
            }
        }
    }

    /* 截取到指定的缩减级别 */
    if ($level > 0)
    {
        if ($cat_id == 0)
        {
            $end_level = $level;
        }
        else
        {
            $first_item = reset($options); // 获取第一个元素
            $end_level  = $first_item['level'] + $level;
        }

        /* 保留level小于end_level的部分 */
        foreach ($options AS $key => $val)
        {
            if ($val['level'] >= $end_level)
            {
                unset($options[$key]);
            }
        }
    }

    if ($re_type == true)
    {
        $select = '';
        foreach ($options AS $var)
        {
            $select .= '<option value="' . $var['cat_id'] . '" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0)
            {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['cat_name']), ENT_QUOTES) . '</option>';
        }

        return $select;
    }
    else
    {
        foreach ($options AS $key => $value)
        {
            $options[$key]['url'] = build_uri('category', array('cid' => $value['cat_id']), $value['cat_name']);
        }

        return $options;
    }
}
/**
 * 过滤和排序所有分类，返回一个带有缩进级别的数组
 *
 * @access  private
 * @param   int     $cat_id     上级分类ID
 * @param   array   $arr        含有所有分类的数组
 * @param   int     $level      级别
 * @return  void
 */
function zzw_cat_options($spec_cat_id, $arr)
{
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id]))
    {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0]))
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        $data = read_static_cache('cat_option_static');
        if ($data === false)
        {
            while (!empty($arr))
            {
                foreach ($arr AS $key => $value)
                {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0)
                    {
                        if ($value['parent_id'] > 0)
                        {
                            break;
                        }

                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0)
                        {
                            continue;
                        }
                        $last_cat_id  = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id)
                    {
                        $options[$cat_id]          = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id']    = $cat_id;
                        $options[$cat_id]['name']  = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0)
                        {
                            if (end($cat_id_array) != $last_cat_id)
                            {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id    = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    }
                    elseif ($value['parent_id'] > $last_cat_id)
                    {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1)
                {
                    $last_cat_id = array_pop($cat_id_array);
                }
                elseif ($count == 1)
                {
                    if ($last_cat_id != end($cat_id_array))
                    {
                        $last_cat_id = end($cat_id_array);
                    }
                    else
                    {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id]))
                {
                    $level = $level_array[$last_cat_id];
                }
                else
                {
                    $level = 0;
                }
            }
            //如果数组过大，不采用静态缓存方式
            if (count($options) <= 2000)
            {
                write_static_cache('cat_option_static', $options);
            }
        }
        else
        {
            $options = $data;
        }
        $cat_options[0] = $options;
    }
    else
    {

        $options = $cat_options[0];
    }

    if (!$spec_cat_id)
    {
        return $options;
    }
    else
    {
        if (empty($options[$spec_cat_id]))
        {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value)
        {
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}
/**
 * 获得指定文章分类下所有底层分类的ID
 *
 * @access  public
 * @param   integer     $cat        指定的分类ID
 *
 * @return void
 */
function zzw_get_article_children ($cat = 0)
{
    return zzw_db_create_in(array_unique(array_merge(array($cat), array_keys(zzw_article_cat_list($cat, 0, false)))), 'cat_id');
}
/**
 * 获得指定分类下的子分类的数组
 *
 * @access  public
 * @param   int     $cat_id     分类的ID
 * @param   int     $selected   当前选中分类的ID=0
 * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组 默认=false
 * @param   int     $level      限定返回的级数。为0时返回所有级数
 * @return  mix
 */
function zzw_article_cat_list($cat_id = 0, $selected = 0, $re_type = false, $level = 0)
{
    static $res = NULL;

    if ($res === NULL)
    {
        $data = read_static_cache('art_cat_pid_releate');
        if ($data === false)
        {
            $sql = "SELECT c.*, COUNT(s.cat_id) AS has_children, COUNT(a.article_id) AS aricle_num ".
               ' FROM ' . $GLOBALS['ecs']->table('article_cat') . " AS c".
               " LEFT JOIN " . $GLOBALS['ecs']->table('article_cat') . " AS s ON s.parent_id=c.cat_id".
               " LEFT JOIN " . $GLOBALS['ecs']->table('article') . " AS a ON a.cat_id=c.cat_id".
               " GROUP BY c.cat_id ".
               " ORDER BY parent_id, sort_order ASC";
            $res = $GLOBALS['db']->getAll($sql);
            write_static_cache('art_cat_pid_releate', $res);
        }
        else
        {
            $res = $data;
        }
    }

    if (empty($res) == true)
    {
        return $re_type ? '' : array();
    }

    $options = zzw_article_cat_options($cat_id, $res); // 获得指定分类下的子分类的数组

    /* 截取到指定的缩减级别 */
    if ($level > 0)
    {
        if ($cat_id == 0)
        {
            $end_level = $level;
        }
        else
        {
            $first_item = reset($options); // 获取第一个元素
            $end_level  = $first_item['level'] + $level;
        }

        /* 保留level小于end_level的部分 */
        foreach ($options AS $key => $val)
        {
            if ($val['level'] >= $end_level)
            {
                unset($options[$key]);
            }
        }
    }

    $pre_key = 0;
    foreach ($options AS $key => $value)
    {
        $options[$key]['has_children'] = 1;
        if ($pre_key > 0)
        {
            if ($options[$pre_key]['cat_id'] == $options[$key]['parent_id'])
            {
                $options[$pre_key]['has_children'] = 1;
            }
        }
        $pre_key = $key;
    }

    if ($re_type == true)
    {
        $select = '';
        foreach ($options AS $var)
        {
            $select .= '<option value="' . $var['cat_id'] . '" ';
            $select .= ' cat_type="' . $var['cat_type'] . '" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0)
            {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['cat_name'])) . '</option>';
        }

        return $select;
    }
    else
    {
        foreach ($options AS $key => $value)
        {
            $options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
        }
        return $options;
    }
}
/**
 * 过滤和排序所有文章分类，返回一个带有缩进级别的数组
 *
 * @access  private
 * @param   int     $cat_id     上级分类ID
 * @param   array   $arr        含有所有分类的数组
 * @param   int     $level      级别
 * @return  void
 */
function zzw_article_cat_options($spec_cat_id, $arr)
{
    static $cat_options = array();

    if (isset($cat_options[$spec_cat_id]))
    {
        return $cat_options[$spec_cat_id];
    }

    if (!isset($cat_options[0]))
    {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        while (!empty($arr))
        {
            foreach ($arr AS $key => $value)
            {
                $cat_id = $value['cat_id'];
                if ($level == 0 && $last_cat_id == 0)
                {
                    if ($value['parent_id'] > 0)
                    {
                        break;
                    }

                    $options[$cat_id]          = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id']    = $cat_id;
                    $options[$cat_id]['name']  = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] == 0)
                    {
                        continue;
                    }
                    $last_cat_id  = $cat_id;
                    $cat_id_array = array($cat_id);
                    $level_array[$last_cat_id] = ++$level;
                    continue;
                }

                if ($value['parent_id'] == $last_cat_id)
                {
                    $options[$cat_id]          = $value;
                    $options[$cat_id]['level'] = $level;
                    $options[$cat_id]['id']    = $cat_id;
                    $options[$cat_id]['name']  = $value['cat_name'];
                    unset($arr[$key]);

                    if ($value['has_children'] > 0)
                    {
                        if (end($cat_id_array) != $last_cat_id)
                        {
                            $cat_id_array[] = $last_cat_id;
                        }
                        $last_cat_id    = $cat_id;
                        $cat_id_array[] = $cat_id;
                        $level_array[$last_cat_id] = ++$level;
                    }
                }
                elseif ($value['parent_id'] > $last_cat_id)
                {
                    break;
                }
            }

            $count = count($cat_id_array);
            if ($count > 1)
            {
                $last_cat_id = array_pop($cat_id_array);
            }
            elseif ($count == 1)
            {
                if ($last_cat_id != end($cat_id_array))
                {
                    $last_cat_id = end($cat_id_array);
                }
                else
                {
                    $level = 0;
                    $last_cat_id = 0;
                    $cat_id_array = array();
                    continue;
                }
            }

            if ($last_cat_id && isset($level_array[$last_cat_id]))
            {
                $level = $level_array[$last_cat_id];
            }
            else
            {
                $level = 0;
            }
        }
        $cat_options[0] = $options;
    }
    else
    {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id)
    {
        return $options;
    }
    else
    {
        if (empty($options[$spec_cat_id]))
        {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value)
        {
            if ($key != $spec_cat_id)
            {
                unset($options[$key]);
            }
            else
            {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value)
        {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                ($spec_cat_id_level > $value['level']))
            {
                break;
            }
            else
            {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}
/**
 * 获得指定商品分类的所有上级分类id与名字,当然包括本身
 *
 * @access  public
 * @param   integer $cat    分类编号
 * @return  array
 */
function zzw_get_parent_cats($cat)
{
    if ($cat == 0)
    {
        return array();
    }

    $arr = $GLOBALS['db']->GetAll('SELECT cat_id, cat_name, parent_id FROM ' . $GLOBALS['ecs']->table('category'));

    if (empty($arr))
    {
        return array();
    }

    $index = 0;
    $cats  = array();

    while (1)
    {
        foreach ($arr AS $row)
        {
            if ($cat == $row['cat_id'])
            {
                $cat = $row['parent_id'];

                $cats[$index]['cat_id']   = $row['cat_id'];
                $cats[$index]['cat_name'] = $row['cat_name'];

                $index++;
                break;
            }
        }

        if ($index == 0 || $cat == 0)
        {
            break;
        }
    }

    return $cats;
}
/**
 * 获得指定文章分类的所有上级分类id与名字,当然包括本身
 *
 * @access  public
 * @param   integer $cat    分类编号
 * @return  array
 返回一个包含cat_id和cat_name的二维数组
 */
function zzw_get_article_parent_cats($cat)
{
    if ($cat == 0)
    {
        return array();
    }

    $arr = $GLOBALS['db']->GetAll('SELECT cat_id, cat_name, parent_id FROM ' . $GLOBALS['ecs']->table('article_cat'));

    if (empty($arr))
    {
        return array();
    }

    $index = 0;
    $cats  = array();

    while (1)
    {
        foreach ($arr AS $row)
        {
            if ($cat == $row['cat_id'])
            {
                $cat = $row['parent_id'];

                $cats[$index]['cat_id']   = $row['cat_id'];
                $cats[$index]['cat_name'] = $row['cat_name'];

                $index++;
                break;
            }
        }

        if ($index == 0 || $cat == 0)
        {
            break;
        }
    }

    return $cats;
}
?>