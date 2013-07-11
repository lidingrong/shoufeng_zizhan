<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

//good表需要的字段
// core_id     goods_thumb   	goods_img   	original_img 
//gallery表需要的字段
//goods_id 	   img_url 	    	thumb_url 	    img_original 


$current_date=date("Ym",time());
$u0='images/'.$current_date;
$u1='images/'.$current_date.'/source_img/';
$u2='images/'.$current_date.'/thumb_img/';
$u3='images/'.$current_date.'/goods_img/';

if(!is_dir($u0))
{
	mkdir($u0);
	if($u1){
		mkdir($u1);
	}
	if($u2){
		mkdir($u2);
	}
	if($u3){
		mkdir($u3);
	}
}




$url_array=array($u1,$u2,$u3);
$goods_data_url=array('original_img','goods_thumb','goods_img');
$galler_data_url=array('img_original','thumb_url','img_url');

$core_url="http://192.168.1.25:81/admin/";
echo "<h2 style='width:1100px; margin:50px auto 10px; color:#FFF;font-size:25px;background:#0099FF;padding:30px;'>图片批量入库程式</h2>";

if($_GET['act']=='ok' && isset($_GET['act'])){
	
	//全部入库
	if($_POST['all_or_which']=='all')
	{
		//生成图片url
		$all_core_pid="select distinct pid from ".$GLOBALS['ecs2']->table('products_image')." where pid>3000";
		$all_pid=$GLOBALS['db2']->getCol($all_core_pid);
		//print_r($all_pid);
		//exit();
		//循环每个产品pid
		 foreach($all_pid as $k=>$v){
			$k1=$k+1;
			echo "<span style='color:#000;font-weight:bold;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>产品标号：".$v."</span>";
			//处理每一个商品的图片函数
			due_all_pid($v);
		 }
		 //exit();
	
	
		//显示查询状态
		$statu=insert_query_info();
		echo "<h2 style='width:1100px; margin:50px auto 10px; color:#FFF;font-size:25px;background:#0099FF;padding:30px; text-align:center;'>$statu</h2>";
		
		
			
	//单个更新功能——暂时不用	
	}elseif($_POST['all_or_which']=='which'){
		if(!empty($_POST['pid']) && isset($_POST['pid']) ){
				echo "<div style='width:980px;margin:20px auto; color:red; font-size:35px;'>功能开发中！</div>";	
			}
	}
	
	
}





//处理函数
//所有图片处理函数
function due_all_pid($pid){
	global $url_array;
	global $goods_data_url;
	global $galler_data_url;
	global $core_url;
	
	$core_sql="select imgAddress from ".$GLOBALS['ecs2']->table(products_image)." where  Pid= ".$pid;
	//核心指定产品id所有图片数组
	$row=$GLOBALS['db2']->getCol($core_sql);
	//循环每个pid的所有图片
	foreach($row as $v){
		echo "<span style='color:#999;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>".$core_url.$v."&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;转化为</span>";
	
		
		
	
		//生成随机图片名，格式为：产品pid_时间戳microtime(true) * 1000_mt_rand(1111, 9999)
		$temp_name=$core_url.$v;
		$houzhui=get_extension($temp_name);
		$m_time=md5(uniqid()); 
		$t_name0=$pid."_".$m_time."_".mt_rand(1111, 9999).".".$houzhui;
		$t_name=$url_array[0].$t_name0;
		$all_url[]=$t_name;
		
		echo "<span style='color:green;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>".$t_name."<span style='color:green; font-weight:bold;'>&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;OK</span></span>";
		//图片复制到空间
		
		//url空格转化
		$temp_name_zhuan=str_replace(" ","%20",$temp_name);
		if(!copy($temp_name_zhuan,$t_name)){
			
			echo "<span style='color:#000;font-weight:bold;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>产品标号：".$pid."</span>";
			echo "<span style='color:#999;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>".$temp_name."&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;&gt;转化为</span>";
			echo "<span style='color:#999;display:block;width:1160px; margin:0px auto;height:22px; text-align:left;'>".$temp_name_zhuan."</span>";
			echo "<br/><span style='color:red;'>copy failed!</span><br/>";
		}
		
		
	     
		
		//两步入库操作——主要插入一张主图地址到ecs_goods表的original_img字段，以及插入所有原图地址到ecs_goods_gallery表的img_original字段
		 $sql001="select goods_id from  ".$GLOBALS['ecs']->table('goods')." where core_id=".$pid;
		 $goods_id=$GLOBALS['db']->getOne($sql001);
		 
		 
		 
		  
		  //在goods表中找到pid所对应的goods_id，并插入相册字段
		  $gallery_sql="INSERT INTO ".$GLOBALS['ecs']->table('goods_gallery')." (goods_id,img_original)  VALUES (".$goods_id.",'".$t_name."')";
		  //echo "<br/>".$gallery_sql;
		  $GLOBALS['db']->query($gallery_sql);
		  
		  
		  //生成两张缩略图,保存的路径在images/时间/source_img/XXXXXX.jpg_300X300.jpg与images/时间/source_img/XXXXXX.jpg_自定义宽X自定义高.jpg
		  //默认规格300X300
		  
		   $new_name=$t_name."_300X300.".$houzhui;
		   ImageResize($temp_name,300,300,$new_name);
		  //自定义规格
		  if(($_POST['thumb_width']!='') && $_POST['thumb_height'])
		  {
		  	$zzw_w=$_POST['thumb_width'];
			$zzw_h=$_POST['thumb_height'];
			$new_name2=$t_name."_".$zzw_w."X".$zzw_h.".".$houzhui;
		  	ImageResize($temp_name,$zzw_w,$zzw_h,$new_name2);
		  }
		 /*  */
		  
		  
	}
	
	
	//在goods表，并插入图片字段
	 $last_url=end($all_url);
	 $sql002="select goods_id from  ".$GLOBALS['ecs']->table('goods')." where core_id=".$pid;
	 $goods_id2=$GLOBALS['db']->getOne($sql002);
	 $goods_sql="UPDATE  ".$GLOBALS['ecs']->table('goods')." SET  original_img = '".$last_url."' "." where goods_id=".$goods_id2;
	 //echo $goods_sql;
	 $GLOBALS['db']->query($goods_sql);
	 
	 /**/
}

function get_extension($file)
{
	return pathinfo($file, PATHINFO_EXTENSION);
}









/**
 * 生成缩略图
*/
function ImageResize($srcFile,$toW,$toH,$toFile="")
{
	if($toFile==""){ $toFile = $srcFile; }
	$info = "";
	$data = GetImageSize($srcFile,$info);
	switch ($data[2])
	{
	case 1:
	if(!function_exists("imagecreatefromgif")){
	echo "你的GD库不能使用GIF格式的图片，请使用Jpeg或PNG格式！<a href='javascript:go(-1);'>返回</a>";
	exit();
	}
	$im = ImageCreateFromGIF($srcFile);
	break;
	case 2:
	if(!function_exists("imagecreatefromjpeg")){
	echo "你的GD库不能使用jpeg格式的图片，请使用其它格式的图片！<a href='javascript:go(-1);'>返回</a>";
	exit();
	}
	$im = ImageCreateFromJpeg($srcFile);
	break;
	case 3:
	$im = ImageCreateFromPNG($srcFile);
	break;
	}
	$srcW=ImageSX($im);
	$srcH=ImageSY($im);
	$toWH=$toW/$toH;
	$srcWH=$srcW/$srcH;
	if($toWH<=$srcWH){
		$ftoW=$toW;
		$ftoH=$ftoW*($srcH/$srcW);
	}
	else{
		$ftoH=$toH;
		$ftoW=$ftoH*($srcW/$srcH);
	}
	if($srcW>$toW||$srcH>$toH)
	{
	if(function_exists("imagecreatetruecolor")){
	@$ni = ImageCreateTrueColor($ftoW,$ftoH);
	if($ni) ImageCopyResampled($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
	else{
	$ni=ImageCreate($ftoW,$ftoH);
	ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
	}
	}else{
	$ni=ImageCreate($ftoW,$ftoH);
	ImageCopyResized($ni,$im,0,0,0,0,$ftoW,$ftoH,$srcW,$srcH);
	}
	if(function_exists('imagejpeg')) ImageJpeg($ni,$toFile);
	else ImagePNG($ni,$toFile);
	ImageDestroy($ni);
	}
	ImageDestroy($im);
} 

?>
<div style="width:980px; margin:30px auto;">
	<form action="zzw_upload.php?act=ok" method="post">
		<label><input type="radio" name="all_or_which" value="all"  checked="checked"/>第一次操作！将所有产品图片入库</label><br />
		<label>第一次导入图片时，会自动生成所有图片的300X300缩略图规格，你可以再自定义多一张：<br />宽度（像素）<input  type="text" name="thumb_width" value=""/><br />高度（像素）<input  type="text" name="thumb_height" value=""/></label><br/><br/><br/>
		<br/><br/><br/>
		<label><input type="radio" name="all_or_which" value="which" />图库已经存在，但是我需要更新某个产品pid的图片</label><br />
		<label>输入需要更新图片的产品pid（如果选择了第一次操作，所有产品图入库，此字段无效！）：<input  type="text" name="pid" value=""/></label><br/><br/><br/>
		<input type="submit" name="submit" value="开始操作"  />
	</form>
</div>

		
		






