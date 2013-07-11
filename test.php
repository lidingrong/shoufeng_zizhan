<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(dirname(__FILE__) . '/includes/lib_zzw_relate.php');


print_r(zzw_goods_cat_link('gcat_to_article',1,60));

?>