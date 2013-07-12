<?php
header("Content-type: text/html; charset=utf-8"); 
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');

//核心站数据表名配置
$core_table_goods="products_image";

//子站数据表名配置
$local_table_goods="goods2";
$local_table_gallery="goods_gallery2";




/*各种恶心的表单参数
radio: radio_1 =choose_1
text:  is_thumb_1_w   is_thumb_1_h  is_thumb_2_w   is_thumb_2_h  is_water1

radio:radio_1 =choose_2
checkbox: checkbox_1=action_1    checkbox_1=action_2
text:  is_thumb_3_w      is_thumb_3_h     is_water2

/*全局变量设置*/
$current_date=date("Ym",time());
$img_dir='images/'.$current_date;
if(!is_dir($img_dir))
{
	mkdir($img_dir);
}
$core_url="http://192.168.1.25:81/admin/";





/*获取表单的所有数据*/
$zzw_choose=$_POST['radio_1'];
$zzw_is_thumb_1_w=$_POST['is_thumb_1_w'];
$zzw_is_thumb_1_h=$_POST['is_thumb_1_h'];
$zzw_is_thumb_2_w=$_POST['is_thumb_2_w'];
$zzw_is_thumb_2_h=$_POST['is_thumb_2_h'];
$zzw_is_water1=$_POST['is_water1'];


$zzw_checkbox_1=$_POST['thumb_or_water'];  //功能二的操作
$zzw_is_thumb_3_w=$_POST['is_thumb_3_w'];
$zzw_is_thumb_3_h=$_POST['is_thumb_3_h'];
$zzw_is_water2=$_POST['is_water2'];
if(isset($_POST['submit']) && $_POST['submit']!='' && $_GET['do']=='ok'){
	if(isset($zzw_choose) &&  $zzw_choose!=''){
		//功能1
		if($zzw_choose=='choose_1'){
			//根据goods_id与code_id生成每个产品图片的存放目录,这里用where控制取出多少个产品的pid
			$cole_sql1="select distinct pid from ".$GLOBALS['ecs2']->table($core_table_goods)." where pid<25 order by pid";
			$all_core_pid=$GLOBALS['db2']->getCol($cole_sql1);
			if(!$all_core_pid){
				die("核心站的表没有数据了？");
			}
			//循环出每个pid=$v
			foreach($all_core_pid as $k=>$v){
				//新建每个产品的图片存放目录
				$local_sql1="select goods_id from  ".$GLOBALS['ecs']->table($local_table_goods)." where core_id=".$v;
				$local_goods_id=$GLOBALS['db']->getOne($local_sql1);
				if(!$local_goods_id){
					die("子站数据库的goods_id与核心code_id没有匹配的啊");
				}
				//目录是否存在
				$every_goods_dir=$img_dir."/".$v."_".$local_goods_id;
				if(!is_dir($every_goods_dir)){
					if(!mkdir($every_goods_dir)){
						die("创建产品目录失败！");
					}
				}else{
					die("操作取消，产品目录已经存在！");
				}
				//循环出每个pid的图片img=$v1
				$core_sql2="select imgAddress from ".$GLOBALS['ecs2']->table($core_table_goods)." where  Pid= ".$v;
				$all_core_img=$GLOBALS['db2']->getCol($core_sql2);
				foreach($all_core_img as $k1=>$v1 ){
					//原图重命名并且传输过来
					//核心url空格转化
					$trans_core_url=str_replace(" ","%20",$core_url.$v1);
					
					//生成本地图片原地址
					$houzhui=get_extension($core_url.$v1);
					//后缀名小写
					$houzhui=strtolower($houzhui);
					$m_time=md5(uniqid()); 
					$local_img=$v."_".$m_time."_".mt_rand(1111, 9999).".".$houzhui;
					$local_img_url=$every_goods_dir."/".$local_img;
					$all_core_img_trans[]=$local_img_url;
					//将原图复制过来了
					if(!copy($trans_core_url,$local_img_url)){
						echo "<br/><span style='color:red;'>copy failed！</span><br/>";
						die("传输过程失败！操作取消！");
					}else{
						//插入gallery中的img_original字段
						$local_sql2="select goods_id from  ".$GLOBALS['ecs']->table($local_table_goods)." where core_id=".$v;
						$goods_id=$GLOBALS['db']->getOne($local_sql2);
						//在goods表中找到pid所对应的goods_id，并插入相册字段
						$gallery_sql="INSERT INTO ".$GLOBALS['ecs']->table($local_table_gallery)." (goods_id,img_original)  VALUES (".$goods_id.",'".$local_img_url."')";
						//echo "<br/>".$gallery_sql;
						$GLOBALS['db']->query($gallery_sql);
						
						
						//首次操作是否增加添加缩略图
						
						if($zzw_is_thumb_1_w!='' && $zzw_is_thumb_1_h!=''){
							if(!file_exists($local_img_url)){
								die("获取不到原图 $v ，操作缩略图失败！");
							}else{
								$t_img_name=$local_img_url."_".$zzw_is_thumb_1_w."X".$zzw_is_thumb_1_h.".".$houzhui;
								ImageResize($local_img_url,$zzw_is_thumb_1_w,$zzw_is_thumb_1_h,$t_img_name);
							}
							
						}
			
						
						if($zzw_is_thumb_2_w!='' && $zzw_is_thumb_2_h!=''){
							if(!file_exists($local_img_url)){
								die("获取不到原图 $v ，操作缩略图失败！");
							}else{
								$t_img_name2=$local_img_url."_".$zzw_is_thumb_2_w."X".$zzw_is_thumb_2_h.".".$houzhui;
								ImageResize($local_img_url,$zzw_is_thumb_2_w,$zzw_is_thumb_2_h,$t_img_name2);
							}
						}
						
						//首次操作是否生成水印
						if($zzw_is_water1 !=''){
							if(!file_exists($local_img_url)){
								die("获取不到原图 $v ，操作水印图失败！");
							}else{
								$w_img_name2=$local_img_url."_w.".$houzhui;
								if(!copy($local_img_url,$w_img_name2))
								{
									die("操作取消，复制水印图片失败啊");
								}
								
								setWater($w_img_name2,$zzw_is_water1,5,'img');
							}
						}
						
						
					}
					
				}//里面的foreach结束
				
				 //插入goods中的original_img字段
				 $last_img_url=end($all_core_img_trans);
				 $sql002="select goods_id from  ".$GLOBALS['ecs']->table($local_table_goods)." where core_id=".$v;
				 $goods_id2=$GLOBALS['db']->getOne($sql002);
				 $goods_sql="UPDATE  ".$GLOBALS['ecs']->table($local_table_goods)." SET  original_img = '".$last_img_url."' "." where goods_id=".$goods_id2;
				 //echo $goods_sql;
				 $GLOBALS['db']->query($goods_sql);
			}//外面的foreach结束
			echo "<script>alert('功能一完成！！能走到这一步，算你不容易啦，图片处理成功咯，你可以选择再去生成缩略图或打水印，或者去睡觉！');</script>";
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		if($zzw_choose=='choose_2'){
			//增加缩略图
			if($zzw_checkbox_1=='action_1'){
				if($zzw_is_thumb_3_w=='' || $zzw_is_thumb_3_h==''){
					echo "<script>alert('艹，想增加缩略图，又不给高度或者宽度！');</script>";
					echo "<script>window.location.href='zzw_img_due.php'</script>";
					die();
				}
				$local_sql_001="select img_original from ".$GLOBALS['ecs']->table($local_table_gallery);
				$local_sql_001_r=$GLOBALS['db']->getCol($local_sql_001);
				if(!$local_sql_001_r){
					echo "<script>alert('图库没有数据，你确定你执行第一步了吗亲，就会给我添乱！');</script>";
					echo "<script>window.location.href='zzw_img_due.php'</script>";
					die();
				}else{
					//print_r($local_sql_001_r);
					//exit();
					foreach($local_sql_001_r as $k001 => $v001){
						//判断宽度和高度是否已经有了缩略图
						if(file_exists($v001)){
							$zzw_houzhui=get_extension($v001);
							//转为小写
							$zzw_houzhui=strtolower($zzw_houzhui);
							$temp_thumb=$v001."_".$zzw_is_thumb_3_w."X".$zzw_is_thumb_3_h.".".$zzw_houzhui;
							if(!file_exists()){
								//再次增加一张缩略图
								ImageResize($v001,$zzw_is_thumb_3_w,$zzw_is_thumb_3_h,$temp_thumb);
							}else{
								//缩略图已经存在了
								die("已经有这种尺寸的缩略图啦，尼玛的还增加，你嫌我空间太多了是吗");
							}
						}else{
							die("原图不存在啊亲，你还搞个毛线的缩略图啊");
						}
					}
					
				}
			}
			
			
			
			//重新打水印
			if($zzw_checkbox_1=='action_2'){
				if($zzw_is_water2==''){
						echo "<script>alert('艹，想打水印图，又不给水印图片的地址！');</script>";
						echo "<script>window.location.href='zzw_img_due.php'</script>";
						die();
				}
				$local_sql_001="select img_original from ".$GLOBALS['ecs']->table($local_table_gallery);
				$local_sql_001_r=$GLOBALS['db']->getCol($local_sql_001);
				if(!$local_sql_001_r){
					echo "<script>alert('图库没有数据，你确定你执行第一步了吗亲，就会给我添乱！');</script>";
					echo "<script>window.location.href='zzw_img_due.php'</script>";
					die();
				}else{
					//print_r($local_sql_001_r);
					//exit();
					foreach($local_sql_001_r as $k001 => $v001){
						
						if(file_exists($v001)){
							$zzw_houzhui=get_extension($v001);
							//转为小写
							$zzw_houzhui=strtolower($zzw_houzhui);
							$temp_thumb=$v001."_w.".$zzw_houzhui;
							if(file_exists($temp_thumb)){
								unlink($temp_thumb);
								if(!copy($v001,$temp_thumb))
								{
									die("操作取消，复制水印图片失败啊");
								}
								setWater($temp_thumb,$zzw_is_water2,5,'img');
							}else{
								if(!copy($v001,$temp_thumb))
								{
									die("操作取消，复制水印图片失败啊");
								}
								setWater($v001,$zzw_is_water2,5,'img');
							}
						}else{
							echo "<script>alert('原图不存在啊亲，你还搞个毛线的缩略图啊');</script>";
							echo "<script>window.location.href='zzw_img_due.php'</script>";
							die();
						}
					}
					
				}
				
				
				
			}
			//没有任何操作咯
			if($zzw_checkbox_1=='')
			{
				echo "<script>alert('艹，你没选择任何操作就按你妹的提交啊');</script>";
				echo "<script>window.location.href='zzw_img_due.php'</script>";
				die();
			}
			
		echo "<script>alert('功能二完成！！这一步是个人都能操作啦，别以为有多厉害哦');</script>";	
		}
		
		
	}
	
	
	
}
//各种工具函数开始
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

/**
 * 生成水印图
参数说明：
	$imgSrc：目标图片，可带相对目录地址，
	$markImg：水印图片，可带相对目录地址，支持PNG和GIF两种格式，如水印图片在执行文件mark目录下，可写成：mark/mark.gif
	$markText：给图片添加的水印文字
	$TextColor：水印文字的字体颜色
	$markPos：图片水印添加的位置，取值范围：0~9
	0：随机位置，在1~8之间随机选取一个位置
	1：顶部居左 2：顶部居中 3：顶部居右 4：左边居中
	5：图片中心 6：右边居中 7：底部居左 8：底部居中 9：底部居右
	$fontType：具体的字体库，可带相对目录地址
	$markType：图片添加水印的方式，img代表以图片方式，text代表以文字方式添加水印

代码注释：
第4~6行：获取目标图片的宽度和高度

第8~22行：根据图片类型调用不同的函数，获得操作图像标识符
GetImageSize函数知识点：GetImageSize不需要安装 GD度就可使用，其返回值数组有四个元素。索引值0是图片高度。索引值1是图片的宽度。索引值2是图片的文件格式，其值1为GIF格式、2为JPEG/JPG格式、3为PNG格式。索引值3为图片的高与宽字符串，height=xxx width=yyy。返回的图片宽度和高度单位都是像素(pixel)

第24~58行：当选择图片方式给目标图片添加水印时，获取水印图片的宽度和高度，通常情况都是网站的logo。如果目标图片比水印图片宽度或者高度小或者水印图片不存在，则跳出这个函数。

return语句知识点：直接return 表示什么都不返回，直接结束这个函数。也可以理解成返回 NULL。

第60~77行：当选择文字方式给目标图片添加水印时，首先设定水印文字的大小，默认我设置为16px，你可以根据需要自行调整字体大小。如果字体文件不存在，跳出函数，最后通过imagettfbbox函数获得此设定格式的文字的虚拟长宽。

imagettfbbox函数知识点：此函数返回一个含有8个单元的数组表示文本外框的四个角，索引值含义：0代表左下角 X 位置，1代表坐下角 Y 位置，2代表右下角 X 位置，3代表右下角 Y 位置，4代表右上角 X 位置，5代表右上角 Y 位置，6代表左上角 X 位置，7代表左上角 Y 位置。此函数同时需要GD 库和FreeType库的支持
max函数返回参数中数值最大的值。

第79~125行：根据设定的图片水印位置计算具体坐标值，你可以根据效果具体细化水印的位置。

第127~129行：新建一个和目标图片大小一致的图片。

注：由于imagecreatetruecolor函数范围的是一个黑色图片，所以如果你的目标图片是透明的，则生成的新图将不会是透明色。

第131~162行：根据图片或者文字方式，最终生成添加了水印的图片。


其他说明：

由于imagettftext和imagettfbbox函数需要GD库和FreeType库的支持，如果你的运行环境不支持GD库和FreeType库则文字方式就无法实现，你可以用imagestring函数实现给图片添加文字水印，同时设定下text方式下的$logow和$logoh值即可。

imagejpeg函数也可以设置合成的图片质量。

*/
function setWater($imgSrc,$markImg,$markPos,$markType)
{

    $srcInfo = @getimagesize($imgSrc);
	//print_r($srcInfo);
	//exit();
    $srcImg_w    = $srcInfo[0];
    $srcImg_h    = $srcInfo[1];
        
    switch ($srcInfo[2]) 
    { 
        case 1: 
            $srcim =imagecreatefromgif($imgSrc); 
            break; 
        case 2: 
            $srcim =imagecreatefromjpeg($imgSrc); 
            break; 
        case 3: 
            $srcim =imagecreatefrompng($imgSrc); 
            break; 
        default: 
            die("不支持的图片文件类型"); 
            exit; 
    }
        
    if(!strcmp($markType,"img"))
    {
        if(!file_exists($markImg) || empty($markImg))
        {
            die("水印图片不存在！");
        }
            
        $markImgInfo = @getimagesize($markImg);
        $markImg_w    = $markImgInfo[0];
        $markImg_h    = $markImgInfo[1];
            
        if($srcImg_w < $markImg_w || $srcImg_h < $markImg_h)
        {
            die(" $imgSrc 此图与水印图片的高度与宽度不适合");
        }
            
        switch ($markImgInfo[2]) 
        { 
            case 1: 
                $markim =imagecreatefromgif($markImg); 
                break; 
            case 2: 
                $markim =imagecreatefromjpeg($markImg); 
                break; 
            case 3: 
                $markim =imagecreatefrompng($markImg); 
                break; 
            default: 
                die("不支持的水印图片文件类型"); 
                exit; 
        }
            
        $logow = $markImg_w;
        $logoh = $markImg_h;
    }
    /*   
    if(!strcmp($markType,"text"))
    {
        $fontSize = 16;
        if(!empty($markText))
        {
            if(!file_exists($fontType))
            {
                return;
            }
        }
        else {
            return;
        }
            
        $box = @imagettfbbox($fontSize, 0, $fontType,$markText);
        $logow = max($box[2], $box[4]) - min($box[0], $box[6]);
        $logoh = max($box[1], $box[3]) - min($box[5], $box[7]);
    }
	*/
        
    if($markPos == 0)
    {
        $markPos = rand(1, 9);
    }
        
    switch($markPos)
    {
        case 1:
            $x = +5;
            $y = +5;
            break;
        case 2:
            $x = ($srcImg_w - $logow) / 2;
            $y = +5;
            break;
        case 3:
            $x = $srcImg_w - $logow - 5;
            $y = +15;
            break;
        case 4:
            $x = +5;
            $y = ($srcImg_h - $logoh) / 2;
            break;
        case 5:
            $x = ($srcImg_w - $logow) / 2;
            $y = ($srcImg_h - $logoh) / 2;
            break;
        case 6:
            $x = $srcImg_w - $logow - 5;
            $y = ($srcImg_h - $logoh) / 2;
            break;
        case 7:
            $x = +5;
            $y = $srcImg_h - $logoh - 5;
            break;
        case 8:
            $x = ($srcImg_w - $logow) / 2;
            $y = $srcImg_h - $logoh - 5;
            break;
        case 9:
            $x = $srcImg_w - $logow - 5;
            $y = $srcImg_h - $logoh -5;
            break;
        default: 
            die("此位置不支持"); 
            exit;
    }
        
    $dst_img = @imagecreatetruecolor($srcImg_w, $srcImg_h);
        
    imagecopy ( $dst_img, $srcim, 0, 0, 0, 0, $srcImg_w, $srcImg_h);
        
    if(!strcmp($markType,"img"))
    {
        imagecopy($dst_img, $markim, $x, $y, 0, 0, $logow, $logoh);
        imagedestroy($markim);
    }
     /*  
    if(!strcmp($markType,"text"))
    {
        $rgb = explode(',', $TextColor);
            
        $color = imagecolorallocate($dst_img, $rgb[0], $rgb[1], $rgb[2]);
        imagettftext($dst_img, $fontSize, 0, $x, $y, $color, $fontType,$markText);
    }
    */
    switch ($srcInfo[2]) 
    { 
        case 1:
            imagegif($dst_img, $imgSrc); 
            break; 
        case 2: 
            imagejpeg($dst_img, $imgSrc); 
            break; 
        case 3: 
            imagepng($dst_img, $imgSrc); 
            break;
        default: 
            die("不支持的水印图片文件类型"); 
            exit; 
    }
        
    imagedestroy($dst_img);
    imagedestroy($srcim);
}

//各种工具函数结束
?>
<div style="width:980px; margin:50px auto;">
	<form action="zzw_img_due.php?do=ok" method="post">
		<h1>功能一:<span style="font-size:15px;color:#999999;">如果从来没有享受过从核心库导入图片的乐趣，那就选择我吧，我会让你很爽很爽的哦！</span></h1>
		<label><input type="radio"  name="radio_1" checked="checked" value="choose_1"/>第一次导入数据到子站，猿们都必须选择这一项，可以在此步骤输入两张缩略图的规格，并且生成图片水印！</label><br />
			<div style="width:960px; margin:10px auto; padding-left:40px;">
				1.缩略图的规格（非必填，可以导入图片后再重新生成缩略图,请输入纯数字的宽与高，比如 200*200）：<br /><br />
				<input style="width:50px;" type="text" name="is_thumb_1_w" />px * <input style="width:50px;" type="text" name="is_thumb_1_h" />px<br /><br />
				<input style="width:50px;" type="text" name="is_thumb_2_w" />px * <input style="width:50px;" type="text" name="is_thumb_2_h" />px<br /><br />
				2.水印图片的地址（非必填）<br /><input style="width:300px;" type="text" name="is_water1" />(图片的路径必须正确填写，最好放在当前目录，然后直接输入图片名即可！)
			</div>
		<br />
		<br />
		<br />
		<h1>功能二:<span style="font-size:15px;color:#999999;">如果你的图片已经存在了，但是需要增加几张不同规格的缩略图，或者将图片的水印全部换成其他标识，那我可以帮助你！</span></h1>
		<label><input type="radio"  name="radio_1" value="choose_2"/>图片数据已经导入了，猿们要更新或者增加缩略图和水印可以选择这一项！</label>
			<div style="width:960px; margin:10px auto;padding-left:40px;">
				1.请选择要进行的操作：
				<label><input type="radio" name="thumb_or_water" value="action_1" />（1）增加缩略图</label>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<label><input type="radio" name="thumb_or_water" value="action_2" />（2）重新打水印</label><br /><br />
				您需要的缩略图尺寸规格：<input style="width:50px;" type="text" name="is_thumb_3_w" />px * <input style="width:50px;" type="text" name="is_thumb_3_h" />px(当（1）选中时，才有效)<br /><br />
				水印图的地址：	     <input style="width:300px;" type="text" name="is_water2" />(当（2）选中时，才有效，图片的路径必须正确填写，最好放在当前目录，然后直接输入图片名即可！)<br /><br />
			</div>
			<div style="width:960px; margin:30px auto; padding-left:50px;">
						<input style="height:50px;" type="submit"  name="submit" value="开始操作，不要手抖"/>
						<input style="height:50px;" type="reset"  name="reset" value="不，不，不，我没这个胆子操作！"/>
			</div>
	</form>
</div>
