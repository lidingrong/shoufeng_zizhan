<?php

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
$main="";
/*
*
*商品品牌静态开始
*
*
*/
$sql="select re_id,url from ". $ecs->table("url") ."   where type=1 and rank=1  ";
$all = $db->getAll($sql);
foreach ($all as $rs){
$main.="
rewrite ^/".$rs['url']."-attr([^-]*)-min([0-9]+)-max([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)/$    /category.php?id=".$rs['re_id']."&filter_attr=$1&price_min=$2&price_max=$3&page=$4&sort=$5&order=$6 last; 
rewrite ^/".$rs['url']."-attr([^-]*)-min([0-9]+)-max([0-9]+)/$                              /category.php?id=".$rs['re_id']."&filter_attr=$1&price_min=$2&price_max=$3 last;
rewrite ^/".$rs['url']."-([0-9]+)-(.+)-([a-zA-Z]+)/$                                        /category.php?id=".$rs['re_id']."&page=$1&sort=$2&order=$3 last;
rewrite ^/".$rs['url']."-([0-9]+)/$                                                         /category.php?id=".$rs['re_id']."&page=$1 last;
rewrite ^/".$rs['url']."/$                                                                  /category.php?id=".$rs['re_id']." last;
rewrite ^/".$rs['url']."$                                                                   /category.php?id=".$rs['re_id']." last;
";
}
/*
*
*商品品牌静态结束
*
*
*
*
*商品系列静态开始
*/


$sql="select a.re_id,a.url,b.parent_id from ". $ecs->table("url") ." a INNER JOIN ". $ecs->table("category") ." b ON a.re_id=b.cat_id   where a.type=1 and a.rank=2  ";
$all = $db->getAll($sql);
foreach ($all as $rs){
	$sql2="select url from ". $ecs->table("url") ." where re_id='".$rs['parent_id']."' and type=1 and rank=1";
	$url=$db->getOne($sql2);

	$main.="
rewrite ^/".$url."/".$rs['url']."-attr([^-]*)-min([0-9]+)-max([0-9]+)-([0-9]+)-(.+)-([a-zA-Z]+)/$   /category.php?id=".$rs['re_id']."&filter_attr=$1&price_min=$2&price_max=$3&page=$4&sort=$5&order=$6 last; 
rewrite ^/".$url."/".$rs['url']."-attr([^-]*)-min([0-9]+)-max([0-9]+)/$                             /category.php?id=".$rs['re_id']."&filter_attr=$1&price_min=$2&price_max=$3 last;
rewrite ^/".$url."/".$rs['url']."-([0-9]+)-(.+)-([a-zA-Z]+)/$                                       /category.php?id=".$rs['re_id']."&page=$1&sort=$2&order=$3 last;
rewrite ^/".$url."/".$rs['url']."-([0-9]+)/$                                                        /category.php?id=".$rs['re_id']."&page=$1 last;
rewrite ^/".$url."/".$rs['url']."/$                                                                 /category.php?id=".$rs['re_id']." last;
rewrite ^/".$url."/".$rs['url']."$                                                                  /category.php?id=".$rs['re_id']." last;
"; 
}

/*
*
*
*商品系列静态结束
*/
//商品页
$sql="select url from ". $ecs->table("url") ." where type=2 and rank=3 and re_id=0";
$url=$db->getOne($sql);
$main.="
rewrite ^/(.*)/(.*)/".$url."-([0-9]+)\.html$                                  /goods.php?yi_url=$1&er_url=$2&id=$3 last;
rewrite ^/(.*)/".$url."-([0-9]+)\.html$                        				   /goods.php?yi_url=$1&id=$2 last;
";


//文章分类页
$sql="select url from ". $ecs->table("url") ." where type=3 and rank=0";
$article_fenlei=$db->getOne($sql);
$main.="
rewrite ^/".$article_fenlei."/$                                  /article_fenlei.php last;
rewrite ^/".$article_fenlei."$                                  /article_fenlei.php last;
";
//文章列表页
$sql="select re_id,url from ". $ecs->table("url") ." where type=4 and rank=1  ";
$all = $db->getAll($sql);
foreach ($all as $rs){
$main.="
rewrite ^/".$article_fenlei."/".$rs['url']."-([0-9]+)-(.+)-([a-zA-Z]+)/$  /article_cat.php?id=".$rs['re_id']."&page=$1&sort=$2&order=$3 last;
rewrite ^/".$article_fenlei."/".$rs['url']."-([0-9]+)-(.+)/$              /article_cat.php?id=".$rs['re_id']."&page=$1&keywords=$2 last;
rewrite ^/".$article_fenlei."/".$rs['url']."-([0-9]+)/$                   /article_cat.php?id=".$rs['re_id']."&page=$1 last;
rewrite ^/".$article_fenlei."/".$rs['url']."/$                            /article_cat.php?id=".$rs['re_id']." last;
rewrite ^/".$article_fenlei."/".$rs['url']."$                             /article_cat.php?id=".$rs['re_id']." last;
";
}
//文章页
$sql="select url from ". $ecs->table("url") ." where type=5 and rank=3 and re_id=0";
$url=$db->getOne($sql);
$main.="
rewrite ^/".$article_fenlei."/(.*)/".$url."-([0-9]+)\.html$               /article.php?url=$1&id=$2 last;
";


//Tags列表页
$sql="select url,re_id from ". $ecs->table("url") ." where type=6 and rank=0 ";
$tags_list=$db->GetRow($sql);
$main.="
rewrite ^/".$tags_list['url']."-([0-9]+)-(.+)-([a-zA-Z]+)/$  /tags_cat.php?id=".$tags_list['re_id']."&page=$1&sort=$2&order=$3 last;
rewrite ^/".$tags_list['url']."-([0-9]+)-(.+)/$              /tags_cat.php?id=".$tags_list['re_id']."&page=$1&keywords=$2 last;
rewrite ^/".$tags_list['url']."-([0-9]+)/$                   /tags_cat.php?id=".$tags_list['re_id']."&page=$1 last;
rewrite ^/".$tags_list['url']."/$                            /tags_cat.php?id=".$tags_list['re_id']." last;
rewrite ^/".$tags_list['url']."$                             /tags_cat.php?id=".$tags_list['re_id']." last;
";
//Tags页
$sql="select url from ". $ecs->table("url") ." where type=7 and rank=3 and re_id=0";
$url=$db->getOne($sql);
$main.="
rewrite ^/".$tags_list['url']."/".$url."-([0-9]+)\.html$               /tags.php?id=$1   last;
";

//Help分类页
$sql="select url from ". $ecs->table("url") ." where type=8 and rank=0";
$help_fenlei=$db->getOne($sql);
$main.="
rewrite ^/".$help_fenlei."/$                                  /help_fenlei.php last;
rewrite ^/".$help_fenlei."$                                  /help_fenlei.php last;
";
//Help列表页
$sql="select re_id,url from ". $ecs->table("url") ." where type=9 and rank=1  ";
$all = $db->getAll($sql);
foreach ($all as $rs){
$main.="
rewrite ^/".$help_fenlei."/".$rs['url']."-([0-9]+)-(.+)-([a-zA-Z]+)/$  /help_cat.php?id=".$rs['re_id']."&page=$1&sort=$2&order=$3 last;
rewrite ^/".$help_fenlei."/".$rs['url']."-([0-9]+)-(.+)/$              /help_cat.php?id=".$rs['re_id']."&page=$1&keywords=$2 last;
rewrite ^/".$help_fenlei."/".$rs['url']."-([0-9]+)/$                   /help_cat.php?id=".$rs['re_id']."&page=$1 last;
rewrite ^/".$help_fenlei."/".$rs['url']."/$                            /help_cat.php?id=".$rs['re_id']." last;
rewrite ^/".$help_fenlei."/".$rs['url']."$                             /help_cat.php?id=".$rs['re_id']." last;
";
}
//Help页
$sql="select url from ". $ecs->table("url") ." where type=10 and rank=3 and re_id=0";
$url=$db->getOne($sql);
$main.="
rewrite ^/".$help_fenlei."/(.*)/".$url."-([0-9]+)\.html$               /help.php?url=$1&id=$2 last;
";
















$header='if (!-e $request_filename)
{
rewrite ^/index\.html /index.php last;
rewrite ^/category$  /index.php last; 

';
$footer='}';




$zifu='';
$zifu.=$header;
$zifu.=$main;
$zifu.=$footer;

$str=fopen('../.htaccess','w+');
if(fwrite($str, $zifu))
{
 
 echo '<font color="#00CC33">生成.htaccess伪静态文件【成功】</font>';
} 
else {
 
 echo "生成.htaccess伪静态文件【失败】";
}
fclose($str);








	
?>