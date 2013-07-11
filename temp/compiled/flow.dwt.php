<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Generator" content="ECSHOP v2.7.3" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="Keywords" content="<?php echo $this->_var['keywords']; ?>" />
<meta name="Description" content="<?php echo $this->_var['description']; ?>" />

<title><?php echo $this->_var['page_title']; ?></title>

<link rel="shortcut icon" href="favicon.ico" />
<link rel="icon" href="animated_favicon.gif" type="image/gif" />

<link href="<?php echo $this->_var['ecs_css_path']; ?>" rel="stylesheet" type="text/css" />
<link href="/themes/default/css/global.css" rel="stylesheet" type="text/css" />
<link href="/themes/default/css/cart.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/themes/default/js/jquery-min.js"></script>
<script type="text/javascript" src="/themes/default/js/tab.js"></script>


<?php echo $this->smarty_insert_scripts(array('files'=>'common.js,flows.js,transport.js,utils.js')); ?>
</head>
<body>

<div class="cart-box">
	<div class="member" id="flow_userinfo"><?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?></div>
	<div class="cart-nav"> <?php echo $this->fetch('library/ur_here.lbi'); ?> </div>
	<?php if ($this->_var['step'] == "cart"): ?>

    	<form id="formCart" name="formCart" method="post" action="flow.php">
        <div class="cart-list">
            
             <?php if ($this->_var['total']['real_goods_count'] < 1): ?>
            <div class="cart-none">
                <p class="cart-none-title"><strong>申し訳ございません。お買い物かごに商品は入ていません。</strong></p>
                <p><a href="/" title="">ホームページへ戻ります。</a></p>
                <p>商品を追加しても買い物かごには何の商品も入らない場合は、ごブラウザには有効な「クッキー」が持っていない可能性がありますので、クッキーを許可するようにブラウザを調整してください。</p>
                <p>買い物続くにはこちらに<a href="" title="">クリック</a>してください。</p>
            </div>
            <div class="fr"><a href="/">買い物を続ける</a><a href="javascript:void(0);" onclick="jQuery('#cart_list_form').submit();">注文手続きへ</a></div>
             <div class="ext-info"> <strong>買い物かごヒント:</strong><br>
                1. 買い物かごに商品を10000個まで追加することができます。<br>
                2. 商品はずっと買い物かごに保存します。<br>
                3. ログインしなくても商品を追加することができる。 
            </div>
             <?php else: ?>
             
            <dl>
                <dt>
                    <div class="w1">イメージ</div>
                    <div class="w2">商品名</div>
                    <div class="w3">単価</div>
                    <div class="w4">数量</div>
                    <div class="w5">合計</div>
                    <div class="w6"></div>
                </dt>
                 <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                <dd>
                    <div class="w1"><a href="goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank" title=""><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="90" height="90" alt=""/></a></div>
                    <div class="w2"><a href="javascript:void(0)" onclick="setSuitShow(<?php echo $this->_var['goods']['goods_id']; ?>)" class="f6"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
                    <div class="w3"><?php echo $this->_var['goods']['goods_price']; ?></div>
                    <div class="w4"><a href="javascript:minus_num(document.getElementById('formCart'), <?php echo $this->_var['goods']['rec_id']; ?>, <?php echo $this->_var['goods']['goods_id']; ?>);"><img src="/themes/default/images/bag_close.gif" border=0></a><input type="text" onKeyDown='if(event.keyCode == 13) event.returnValue = false' name="goods_number[<?php echo $this->_var['goods']['rec_id']; ?>]" readonly="readonly" id="goods_number_<?php echo $this->_var['goods']['rec_id']; ?>" value="<?php echo $this->_var['goods']['goods_number']; ?>" size="4" class="inputBg" style="text-align:center;" onblur="formSubmit(this.form)"/><input type="hidden" id="hidden_<?php echo $this->_var['goods']['rec_id']; ?>" value="<?php echo $this->_var['goods']['goods_number']; ?>"><a href="javascript:add_num(document.getElementById('formCart'),<?php echo $this->_var['goods']['rec_id']; ?>,<?php echo $this->_var['goods']['goods_id']; ?>)" ><img src="/themes/default/images/bag_open.gif" border=0></a>
                    </div>
                    <div class="w5" id="subtotal_<?php echo $this->_var['goods']['rec_id']; ?>"><?php echo $this->_var['goods']['subtotal']; ?></div>
                    <div class="w6">
                    <a href="javascript:if (confirm('<?php echo $this->_var['lang']['drop_goods_confirm']; ?>')) location.href='flow.php?step=drop_goods&amp;id=<?php echo $this->_var['goods']['rec_id']; ?>'; " class="cart-del"><?php echo $this->_var['lang']['drop']; ?></a>
                    </div>
                </dd>
                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </dl>
            <div class="progress">
                <div class="progress-0">                	
                    <a href="flow.php?step=clear" >買い物かごを空にする</a>
                </div>
            </div>
            <div class="cart-allprice"><span id="total_price"><?php echo $this->_var['shopping_money']; ?></span></div>
            <div class="cart-btn">
                <a href="/"><img src="/themes/default/images/cart/btn-cart3.png" /></a>
                <a href="flow.php?step=check"><img src="/themes/default/images/cart/btn-jixu.png" /></a>
            </div>
            <input type="hidden" name="step" value="update_cart" />
            <div class="ext-info"> <strong>買い物かごヒント:</strong><br>
                1. 買い物かごに商品を10000個まで追加することができます。<br>
                2. 商品はずっと買い物かごに保存します。<br>
                3. ログインしなくても商品を追加することができる。 
            </div>
        </div>
     	</form>
     	<?php endif; ?>
    <?php endif; ?>
    <?php if ($this->_var['step'] == "check"): ?>
    	<div class="cart-list">
        	<div class="progress">
                <ul class="progress-1">
                    <li class="step-1"><b></b>1.送付先入力</li>
                    <li class="step-1"><b></b>2.注文内容確認</li>
                    <li>3.完了</li>
                </ul>
            </div>
            <dl>
                <dt>
                    <div class="c1">イメージ</div>
                    <div class="c2">商品名</div>
                    <div class="c3">単価</div>
                    <div class="c4">数量</div>
                    <div class="c5">合計</div>
                   
                </dt>
                 <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                <dd>
                    <div class="c1"><a href="goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank" title=""><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="90" height="90" alt=""/></a></div>
                    <div class="c2"><a href="javascript:void(0)" onclick="setSuitShow(<?php echo $this->_var['goods']['goods_id']; ?>)" class="f6"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
                    <div class="c3"><?php echo $this->_var['goods']['goods_price']; ?></div>
                    <div class="c4"><?php echo $this->_var['goods']['goods_number']; ?></div>
                    <div class="c5" id="subtotal_<?php echo $this->_var['goods']['rec_id']; ?>"><?php echo $this->_var['goods']['subtotal']; ?>円</div>
                   
                </dd>
                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </dl>
            <div class="cart-allprice"><span id="total_price"><?php echo $this->_var['shopping_money']; ?></span></div>
    	</div>
        
        
    	<div class="cart-list2">
        <div class="cr_box">
            <div class="biaodan">
                <?php if ($_SESSION['user_id'] == 0): ?>
                <div class="logo-div-box" id="logo-div-box">
                    <div class="cart_mem">アカウントを持っている方は下記でログインしてください:</div>
                    <div class="login_div">
                        <div class="l">Ｅメール :</div>
                        <div class="r">
                            <input size="30" id="login_Email" name="username" class="f_text"  type="text">
                        </div></span>
                        <div class="clear"></div>
                    </div>
                    <div class="login_div">
                        <div class="l">パスワード :</div>
                        <div class="r">
                            <input value="" size="30" id="login_Password" name="password" class="f_text" type="password">
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                    <div class="r">
                        <input class="form_button" value="ログイン" id="login_cart" onclick="return login_in_cart()" type="button"> 
                    </div>
                    <div class="clear"></div>
                    <span id="smg" style="margin-left:100px;"></span>
                    
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>

            <div class="biaodan" id="new_regieter">
            <form id="formCart" name="formCart" method="post" onsubmit="return checkinfo_new(this,<?php echo $_SESSION['user_id']; ?>)"  action="flow.php">
                <div class="cart_mem">アカウントを持っていない方は下記の項目を入力してください。</div>
                    <dl>
                        <dt>メールアドレス : </dt>
                        <dd>
                            <input size="40" id="mem_Email" name="mem_Email" onblur="return checkmail()" type="text" value="">
                            <input id="mail_hid" name="mail_hid" type="hidden" />
                            <font class="fc_red">*</font><div id="email_notice" style="display:none"></div>
                            
                            </dd>
                    </dl>
                    <dl>
                        <dt>新規会員を登録しますか？ : </dt>
                        <dd>
                       	  <p>
                        	  <label>
                        	    <input type="radio" name="is_register" value="1" onclick="Is_register()"  checked="checked" id="yes_register" />
                        	    登録します</label>
                        	  
                        	  <label>
                        	    <input type="radio" name="is_register" value="2"  onclick="Is_register()" id="no_register" />
                        	    登録しません</label>
                        	  <br />
                      	  </p>
                        </dd>
                    </dl>
                    
                    <dl id="is_ConfirmEmail">
                      <dt>メールアドレス確認 : </dt>
                        <dd>
                            <input size="40" id="mem_ConfirmEmail" name="mem_ConfirmEmail" type="text">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <dl id="is_Password">
                        <dt>パスワード : </dt>
                        <dd>
                            <input value="" size="40" id="mem_Password" name="mem_Password" type="password">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <dl id="is_ConfirmPassword">
                        <dt>パスワード確認 : </dt>
                        <dd>
                            <input value="" size="40" id="mem_ConfirmPassword" name="mem_ConfirmPassword" type="password">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <div class="cart-name-box">
                        <dl>
                            <dt>姓（氏名） : </dt>
                            <dd>
                                <input size="20" id="mem_FirstName" name="mem_FirstName" type="text">
                                <font class="fc_red">*</font></dd>
                        </dl>
                        <dl>
                            <dt>名（氏名） : </dt>
                            <dd>
                                <input size="20" id="mem_LastName" name="mem_LastName" type="text">
                                <font class="fc_red">*</font></dd>
                        </dl>
                        <dl>
                            <dt>姓（フリガナ） : </dt>
                            <dd>
                                <input size="20" id="mem_FirstName_2" name="mem_FirstName_2" type="text">
                                <font class="fc_red">*</font></dd>
                        </dl>
                        <dl>
                            <dt>名（フリガナ） : </dt>
                            <dd>
                                <input size="20" id="mem_LastName_2" name="mem_LastName_2" type="text">
                                <font class="fc_red">*</font></dd>
                        </dl>
                    </div>
                    <dl>
                        <dt>郵便番号 : </dt>
                        <dd>
                            <input size="20" id="mem_PostalCode" name="mem_PostalCode" type="text">
                            <font class="fc_red">*</font><br>
                            <input name="zipcode_area" value="住所を検索" class="form_button" onclick="zipcode('mem_PostalCode', 'mem_State', 'mem_City', 'mem_AddressLine');" type="button">
                            半角数字、7文字　例：1066182</dd>
                    </dl>
                    <dl>
                        <dt>都道府県 : </dt>
                        <dd>
                            <input size="20" id="mem_State" name="mem_State" type="text">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <dl>
                        <dt>市区町村 : </dt>
                        <dd>
                            <input size="40" id="mem_City" name="mem_City" type="text">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <dl>
                        <dt>町名・番地 : </dt>
                        <dd>
                            <input size="40" id="mem_AddressLine" name="mem_AddressLine" type="text">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <dl>
                        <dt>ビル・マンション名 : </dt>
                        <dd>
                            <input size="40" id="mem_Room" name="mem_Room" type="text">
                        </dd>
                    </dl>
                    <dl>
                        <dt>電話番号 : </dt>
                        <dd>
                            <input size="20" id="mem_Phone" name="mem_Phone" type="text">
                            <font class="fc_red">*</font></dd>
                    </dl>
                    <!--<dl id="true_register">
                        <dt>&nbsp;</dt>
                        <dd>
                            <span id="men_smg"></span>
                            <input value="登録" class="form_button" id="mem_btn" onclick="return checkall()" type="button">
                        </dd>
                    </dl>-->
                    <div class="clear"></div>
                    
                    <div class="clear"></div>
                <div class="txt"> ・いまご登録されるといろいろな特典があります！オンライショップでのお買い
                    
                    物などの際に入力の手間が省けて便利です。しかもごアカウントで複数の届け先を保存することとご注文を追跡することも可
                    
                    能です。 <br>
                    ・登録したくない方は必要な情報を入力しておけば、オンライショップで購入することもで
                    
                    きます。ごボラウザには訪問データが残っていますので、一度ご情報をご入力されると登録しなくてもショッピングができま
                    
                    す。
               </div>
               
               
               <div class="cr_box">
                <div class="cr_t">クイック選択:</div>
                <ul class="express">
                    <li>
                        <input value="1" id="ShippingSId" name="ShippingSId" data-price="0.00"  onclick="selectShipping(<?php echo $this->_var['total']['amount']; ?>,0.00)" checked="checked" type="radio">
                        <b>国際小包</b> - <span>￥0.00</span> <i>( 7～14営業日以内に到着できます )</i>
                    </li>
                    <li>
                        <input value="2" id="ShippingSId"  name="ShippingSId" data-price="500.00" onclick="selectShipping(<?php echo $this->_var['total']['amount']; ?>,500.00)" type="radio">
                        <b>EMS（国際速達郵便）</b> - <span>￥500.00</span> <i>( 4～7営業日以内にお宅に到着できます )</i>
                   </li>
                </ul>
            </div>
            <div class="cr_box">
                <div class="cr_t">注文内容確認:</div>
                <div class="total_price">
                    <?php echo $this->fetch('library/order_total_flows.lbi'); ?>	
                        
                    <input type="hidden" name="amount_formated" value="<?php echo $this->_var['total']['amount']; ?>" /> 
                    <input type="hidden" name="step" id="price_Total" value="" />
                    <input type="hidden" name="Shipping_price" id="Shipping_price" value="" />
                </div>
            </div>
            <div class="cart-btn">
            	
                <input name="imageField" src="/themes/default/images/cart/btn-cart2.png" type="image" title="お支払い手続き へ">
            </div>
            <div class="ext-info"> <strong>買い物かごヒント:</strong><br>
                1. 買い物かごに商品を10000個まで追加することができます。<br>
                2. 商品はずっと買い物かごに保存します。<br>
                3. ログインしなくても商品を追加することができる。 
            </div>
            <input type="hidden" name="consignee_post" value="1" />
            <input type="hidden" name="step" value="done" /> 
            </form>
            </div>
            
            
            <?php else: ?>
            
            <?php endif; ?>
            <div class="logo-div-box2" id="logo-div-box2" <?php if ($_SESSION['user_id']): ?> style="display:block;"<?php else: ?> style="display:none;" <?php endif; ?>>
            <form id="formCart" name="formCart" method="post" onsubmit="return checkinfo_login(this,<?php echo $_SESSION['user_id']; ?>)"  action="flow.php">
                <div class="cr_box">
                    <div class="cr_t">お届け情報入力:</div>
                    <div class="txt"> ご入力した情報によって、商品を配送いたしますので、下記のフォームを確認してください。お届け情報が間違っていると、商品を正確にお届けできない場合は本店は責任を負っておりません。 <br>
                        <br>
                        ご利用したことがある方はメールボクスをご入力すると、お届け情報が自動的に入力されます。 </div>
                    <div class="biaodan">
                        <dl>
                            <dt>メールアドレス : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['email']; ?>" id="login_email" name="Email" type="text" readonly="readonly"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>姓（氏名） : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['FirstName']; ?>" id="login_ShippingFirstName" name="ShippingFirstName" type="text" ><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>名（氏名） : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['LastName']; ?>" id="login_ShippingLastName" name="ShippingLastName" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>姓（フリガナ） : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['FirstName_2']; ?>" id="login_ShippingFirstName_2" name="ShippingFirstName_2" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>名（フリガナ） : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['LastName_2']; ?>" id="login_ShippingLastName_2" name="ShippingLastName_2" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>郵便番号 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['PostalCode']; ?>" id="login_ShippingPostalCode" name="ShippingPostalCode" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>都道府県 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['State']; ?>" id="login_ShippingState" name="ShippingState" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>市区町村 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['City']; ?>" id="login_ShippingCity" name="ShippingCity" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>町名・番地 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['AddressLine']; ?>" id="login_ShippingAddressLine" name="ShippingAddressLine" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>
                        <dl>
                            <dt>ビル・マンション名 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['Room']; ?>" id="login_ShippingRoom" name="ShippingRoom" type="text">
                            </dd>
                        </dl>
                        <dl>
                            <dt>電話番号 : </dt>
                            <dd>
                                <input value="<?php echo $this->_var['consignee']['Phone']; ?>" id="login_ShippingPhone" name="ShippingPhone" type="text"><font class="fc_red">*</font>
                            </dd>
                        </dl>                                       
                        <div class="clear"><input type="hidden" name="address_id" id="address_id" value="<?php echo $this->_var['consignee']['address_id']; ?>"/></div>
                        <div class="q_link">
                            <a  href="javascript:loginCheckAll()">アドレス編集</a>  <div id="smg"></div>                                               
                        </div>  
                    </div>
                </div>
                <div class="clear"></div>
                <div class="txt"> ・いまご登録されるといろいろな特典があります！オンライショップでのお買い
                    
                    物などの際に入力の手間が省けて便利です。しかもごアカウントで複数の届け先を保存することとご注文を追跡することも可
                    
                    能です。 <br>
                    ・登録したくない方は必要な情報を入力しておけば、オンライショップで購入することもで
                    
                    きます。ごボラウザには訪問データが残っていますので、一度ご情報をご入力されると登録しなくてもショッピングができま
                    
                    す。
               </div>
               
               
               <div class="cr_box">
                <div class="cr_t">クイック選択:</div>
                <ul class="express">
                    <li>
                        <input value="1" id="ShippingSId" name="ShippingSId" data-price="0.00"  onclick="selectShipping(<?php echo $this->_var['total']['amount']; ?>,0.00)" checked="checked" type="radio">
                        <b>国際小包</b> - <span>￥0.00</span> <i>( 7～14営業日以内に到着できます )</i>
                    </li>
                    <li>
                        <input value="2" id="ShippingSId"  name="ShippingSId" data-price="500.00" onclick="selectShipping(<?php echo $this->_var['total']['amount']; ?>,500.00)" type="radio">
                        <b>EMS（国際速達郵便）</b> - <span>￥500.00</span> <i>( 4～7営業日以内にお宅に到着できます )</i>
                   </li>
                </ul>
            </div>
            <div class="cr_box">
                <div class="cr_t">注文内容確認:</div>
                <div class="total_price">
                   <table border="0" cellpadding="0" cellspacing="8" width="350">
                        <tbody>
                            <tr>
                                <td align="right" width="50%">小計 :</td>
                                <td width="50%"><span class="red"><?php echo $this->_var['total']['amount_formated']; ?></span></td>
                                
                            </tr>
                            <tr>
                                <td align="right">送料 :</td>
                               
                                <td><span class="red" id="Shipping_Charges2">0円</span></td>
                               
                            </tr>
                            <tr>
                                <td align="right">合計 :</td>
                                <td><span class="red" id="Grand_Total2"><?php echo $this->_var['total']['amount_formated']; ?></span></td>
                            </tr>
                        </tbody>
                    </table> 
                        
                    <input type="hidden" name="amount_formated" value="<?php echo $this->_var['total']['amount']; ?>" /> 
                    <input type="hidden" name="step" id="price_Total" value="" />
                    <input type="hidden" name="Shipping_price" id="Shipping_price" value="" />
                </div>
            </div>
            <div class="cart-btn">
            	
                <input name="imageField" src="/themes/default/images/cart/btn-cart2.png" type="image" title="お支払い手続き へ">
            </div>
            <div class="ext-info"> <strong>買い物かごヒント:</strong><br>
                1. 買い物かごに商品を10000個まで追加することができます。<br>
                2. 商品はずっと買い物かごに保存します。<br>
                3. ログインしなくても商品を追加することができる。 
            </div>
            <input type="hidden" name="login_post" value="1" />
            <input type="hidden" name="step" value="done" /> 
            </div>
            </form>

         </div> 
             
             
             
    	</div>
    <?php endif; ?>
    
    
    <?php if ($this->_var['step'] == "toOrder"): ?>
            <form id="formOrder" name="formOrder" method="post" action="flow.php">
            <div class="cart-list">
        		<div class="progress">
        			<ul class="progress-2">
        				<li class="step-1"><b></b>1.送付先入力</li>
        				<li><b></b>2.注文内容確認</li>
        				<li>3.完了</li>
        			</ul>
        		</div>
        		<dl>
        			<dt>
                        <div class="c1">イメージ</div>
                        <div class="c2">商品名</div>
                        <div class="c3">単価</div>
                        <div class="c4">数量</div>
                        <div class="c5">合計</div>
                       
                    </dt>
                     <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                    <dd>
                        <div class="c1"><a href="goods.php?id=<?php echo $this->_var['goods']['goods_id']; ?>" target="_blank" title=""><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="90" height="90" alt=""/></a></div>
                        <div class="c2"><a href="javascript:void(0)" onclick="setSuitShow(<?php echo $this->_var['goods']['goods_id']; ?>)" class="f6"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
                        <div class="c3"><?php echo $this->_var['goods']['goods_price']; ?></div>
                        <div class="c4"><?php echo $this->_var['goods']['goods_number']; ?></div>
                        <div class="c5" id="subtotal_<?php echo $this->_var['goods']['rec_id']; ?>"><?php echo $this->_var['goods']['total_price']; ?></div>
                       
                    </dd>
                     <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        		</dl>
        		<div class="cart-allprice">总計：<span><?php echo $this->_var['total']['price']; ?>円</span></div>
        		
        	</div>
            <div class="logo-div-box2">
				<div class="cr_box">
					<div class="cr_t">お届け情報入力:</div>
					<div class="txt"> ご入力した情報によって、商品を配送いたしますので、下記のフォームを確認してください。お届け情報が間違っていると、商品を正確にお届けできない場合は本店は責任を負っておりません。 <br>
						<br>
						ご利用したことがある方はメールボクスをご入力すると、お届け情報が自動的に入力されます。 </div>
					<div class="biaodan">
						<dl>
							<dt>メールアドレス : </dt>
							<dd><?php echo $this->_var['order']['email']; ?></dd>
						</dl>
						<dl>
							<dt>姓名（氏名） : </dt>
							<dd><?php echo $this->_var['order']['consignee']; ?>
							</dd>
						</dl>
						<dl>
							<dt>郵便番号 : </dt>
							<dd><?php echo $this->_var['order']['PostalCode']; ?>
							</dd>
						</dl>
						<dl>
							<dt>都道府県 : </dt>
							<dd><?php echo $this->_var['order']['State']; ?>
							</dd>
						</dl>
						<dl>
							<dt>市区町村 : </dt>
							<dd><?php echo $this->_var['order']['City']; ?>
							</dd>
						</dl>
						<dl>
							<dt>町名・番地 : </dt>
							<dd><?php echo $this->_var['order']['AddressLine']; ?>
							</dd>
						</dl>
						<dl>
							<dt>ビル・マンション名 : </dt>
							<dd><?php echo $this->_var['order']['Room']; ?>
							</dd>
						</dl>
						<dl>
							<dt>電話番号 : </dt>
							<dd><?php echo $this->_var['order']['Phone']; ?>
							</dd>
						</dl>
						<dl>
							<dt>コメント : </dt>
							<dd>
								<textarea cols="56" rows="5" id="postscript" name="postscript"></textarea>
							</dd>
						</dl>
						<div class="clear"></div>
						
					 
				</div>
			</div>
            <div class="cr_box">
    			<div class="cr_t">クイック選択:</div>
    			<ul class="express">
                <?php if ($this->_var['order'] [ 'shipping_id' ] == 1): ?>
    				<li>
    					<input value="1"  checked="checked" name="ShippingSId" data-price="0.00"  type="radio">
    					<b>国際小包</b> - <span>￥0.00</span> <i>( 7～14営業日以内に到着できます 
    					
    					)</i></li><?php endif; ?>
                        <?php if ($this->_var['order'] [ 'shipping_id' ] == 2): ?>
    				<li>
    					<input value="2"  checked="checked"  name="ShippingSId" data-price="500.00" type="radio">
    					<b>EMS（国際速達郵便）</b> - <span>￥500.00</span> <i>( 4～7営業日以内に
    					
    					お宅に到着できます )</i></li><?php endif; ?>
    			</ul>
    		</div>
    		<div class="cr_box">
    			<div class="cr_t">注文内容確認:</div>
    			<div class="total_price">
    				<table border="0" cellpadding="0" cellspacing="8" width="350">
    					<tbody>
    						<tr>
    							<td align="right" width="50%">小計 :</td>
    							<td width="50%"><span class="red"><?php echo $this->_var['total']['price']; ?>.00円</span></td>
    						</tr>
    						<tr>
    							<td align="right">送料 :</td>
    							<td><span class="red" id="Shipping_Charges"><?php echo $this->_var['order']['shipping_fee']; ?>円</span></td>
    						</tr>
    						<tr>
    							<td align="right">合計 :</td>
    							<td><span class="red" id="Grand_Total"><?php echo $this->_var['total']['total']; ?>.00円</span></td>
    						</tr>
    					</tbody>
    				</table>
    			</div>
    		</div>
    		<div class="cart-btn">
    			<input name="imageField" src="/themes/default/images/cart/btn-cart2.png" type="image" title="お支払い手続きへ">
    		</div>
            <input type="hidden" name="step" value="over" />
            <input type="hidden" name="order_id" value="<?php echo $this->_var['order']['order_id']; ?>" />
            </form>
            <?php endif; ?>
    		
            
            
             <?php if ($this->_var['step'] == "over"): ?>
            <div class="cart-list">
        		<div class="progress">
        			<ul class="progress-3">
        				<li class="step-2"><b></b>1.送付先入力</li>
        				<li class="step-2"><b></b>2.注文内容確認</li>
        				<li class="step-1">3.完了</li>
        			</ul>
        		</div>
        		<div id="lib_order_complete">
        			<div class="order_info">注文番号:<?php echo $this->_var['order']['order_sn']; ?>&nbsp;&nbsp;&nbsp;<em>注文日付け:<?php echo $this->_var['ordertime']; ?></em></div>
        			<div class="blank12"></div>
        			<div class="contents">
        				<div class="cart-title"><?php echo $this->_var['order']['email']; ?>:</div>
        				<div class="cart3-infor">
        				当社商品をご注文いただき誠にありがとうございます。<br><br>
        				
        		ご注文詳細はアカウントに保留されます。お支払いを確認したすぐにご登録したメールボックスに送信いたしますので（受信ボックスに届かない場合はスパム
        		ボックスをチェックしてください）、ごアカウントを登録して或いはＥメールをチェックし、ご注文を確認してください。<br><br>
        				また、当社は便利な支払方法を提供いたします。ごアカウントの注文管理でお支払い方法の選択や確認することができます。<a href="user.php" title="こちら" style="color:#c00; font-weight:bold; text-decoration:underline;">こちら</a>をクリックして、注文管理画面へ入ります。<br><br>
        				お支払い手続きが完了次第、発送の手配をさせていただきます。もし他に何か質問がございましたら、ぜひカスタマーセンターにご連絡ください。Ｅメール: service@brandhandbag2013.com<br><br>
        				またライブチャットで当社のサービススタッフにもお問い合わせいただけます。<br><br>
        				快適にお買い物を楽しんでください。
        				</div>
        			</div>
                    <div class="blank12"></div>
                    <div class="cart-btn">
                        <a href="/"><img src="/themes/default/images/cart/btn-cart3.png" /></a>
                    </div>
        		</div>
        	</div>
                    

            
            <?php endif; ?>
    
</div>


</body>
</html>
