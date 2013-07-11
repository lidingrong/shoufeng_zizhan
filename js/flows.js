/*!
 * 购物车  flow.php
 * Powered by 小龙	
 * yinpoo@126.com
 * 处理购物车和提交订单的所有js验证和ajax验证
 * Date: 2012-06-30 22：23
*/

//Ajax 验证Email
function checkmail(){
	
	var email = document.getElementById('mem_Email').value;
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if(!myreg.test(email)){
		   document.getElementById('email_notice').style.display = 'block';
			document.getElementById('email_notice').innerHTML = 'ヒント\n\n有効なメールアドレスをご入力ください。';
			return false;
		}else{
			document.getElementById('email_notice').style.display = 'none';
			
		}
		
		if(document.getElementById('yes_register')){
			if(document.getElementById('yes_register').checked){
				document.getElementById('mail_hid').value = email;
				if(email != '' && email != 'undifind'){
					Ajax.call('/user.php?act=check_email', 'email=' + email, act_callback, 'GET', 'JSON' );
				}
			}
		}else{
			document.getElementById('mail_hid').value = email;
				if(email != '' && email != 'undifind'){
					Ajax.call('/user.php?act=check_email', 'email=' + email, act_callback, 'GET', 'JSON' );
				}
			
			
		}
}

//验证Email AJAX 回调函数
function act_callback(result){
	document.getElementById('email_notice').style.display = 'block';

	if (result.error == 'ok' )
	  {
		 
		document.getElementById('email_notice').innerHTML = "登録可能なメールアドレスです。";
		document.forms['formUser'].elements['Submit'].disabled = '';
	  }
	  else
	  { 
		document.getElementById('email_notice').innerHTML = " このメールアドレスが既に登録されています。他のメールアドレスをご入力ください";
		document.forms['formUser'].elements['Submit'].disabled = 'disabled';
	  }
}

// 用户注册购买 或是不注册购买 验证
function checkinfo_new(){
	var msg ='';
	var email = document.getElementById('mem_Email').value;
		if(email == ''){
			msg = 'メールアドレスをご入力ください。\n';	
	}
	if(document.getElementById('yes_register').checked){
		var email = document.getElementById('mail_hid').value;
		var email2 = document.getElementById('mem_ConfirmEmail');
		if(email2 != ''){
			if(email != email2.value){
				msg = 'メールアドレスが間違っているのかご確認ください。\n';
			}
		}
		
		//验证 确认密码
		var pass = document.getElementById('mem_Password').value;
		var pass2 = document.getElementById('mem_ConfirmPassword').value;
		if(pass != ''){
			if(pass2 != ''){
				if(pass != pass2){
					 msg += '入力された二つのパスワードが一致していません。\n';
				}
			}else{
				msg += 'パスワードをご入力ください。\n';
			}
		}else{
			msg += 'パスワードをご入力ください。\n';
		}
		
	}
	
	
	//验证是否填写
	var firstname = document.getElementById('mem_FirstName').value;
	var lastname = document.getElementById('mem_LastName').value;
	var firstname2 = document.getElementById('mem_FirstName_2').value;
	var lastname2 = document.getElementById('mem_LastName_2').value;
	var postalcode = document.getElementById('mem_PostalCode').value;
	var state = document.getElementById('mem_State').value;
	var city = document.getElementById('mem_City').value;
	var addressline = document.getElementById('mem_AddressLine').value;
	var phone = document.getElementById('mem_Phone').value;
    var room = document.getElementById('mem_Room').value;
	if(document.getElementById('no_register').checked){
		 var is_register = document.getElementById('no_register').value;
	}
	
	//姓
	if(firstname == ''){
		msg += '姓をご入力ください。\n';
	}
	//名
	if(lastname == ''){
		msg += '名をご入力ください。\n';
	}
	//姓的拼音
	if(firstname2 == ''){
		msg += '姓のフリガナをご入力ください。\n';	
	}
	//名的拼音
	if(lastname2 == ''){
		msg += '名のフリガナをご入力ください。\n';
	}
	//邮编
	if(postalcode == ''){
		msg += '郵便番号をご入力ください。\n';
	}
	//省
	if(state == ''){
		msg += '都道府県名をご入力ください。\n';
	}
	//市
	if(city == ''){
		msg += '市区町村名をご入力ください。\n';
	}
	//街道
	if(addressline == ''){
		msg += '町名・番地をご入力ください。\n';	
	}
	//电话
	if(phone == ''){
		msg += '電話番号をご入力ください。\n';
	}
	if(msg){
		alert(msg);
		return false;
	}
	
}

//已经登录 提交订单处理
function checkinfo_login(){
	
	var msg ='';
    //验证是否填写
	var login_email = document.getElementById('login_email').value;
	var login_ShippingFirstName = document.getElementById('login_ShippingFirstName').value;
	var login_ShippingLastName = document.getElementById('login_ShippingLastName').value;
	var login_ShippingFirstName_2 = document.getElementById('login_ShippingFirstName_2').value;
	var login_ShippingLastName_2 = document.getElementById('login_ShippingLastName_2').value;
	var login_ShippingState = document.getElementById('login_ShippingState').value;
	var login_ShippingCity = document.getElementById('login_ShippingCity').value;
	var login_ShippingAddressLine = document.getElementById('login_ShippingAddressLine').value;
	var login_ShippingRoom = document.getElementById('login_ShippingRoom').value;
    var login_ShippingPhone = document.getElementById('login_ShippingPhone').value;
    var login_ShippingPostalCode = document.getElementById('login_ShippingPostalCode').value;
    var address_id = document.getElementById('address_id').value;
    if(login_email == ''){
        	msg += 'メールアドレスをご入力ください。\n';
    }
    if(login_ShippingFirstName == ''){
        	msg += '姓をご入力ください。\n';
    }
    if(login_ShippingLastName == ''){
        	msg += '名をご入力ください。\n';
    }
    if(login_ShippingFirstName_2 == ''){
        	msg += '姓のフリガナをご入力ください。\n';
    }
    if(login_ShippingLastName_2 == ''){
        	msg += '名のフリガナをご入力ください。\n';
    }
    if(login_ShippingState == ''){
        	msg += '都道府県名をご入力ください。\n';
    }
    if(login_ShippingCity == ''){
        	msg += '市区町村名をご入力ください。\n';
    }
    if(login_ShippingAddressLine == ''){
        	msg += '町名・番地をご入力ください。\n';
    }
    if(login_ShippingPhone == ''){
        	msg += '電話番号をご入力ください。\n';
    }
    if(login_ShippingPostalCode == ''){
        	msg += '郵便番号をご入力ください。\n';
    }

    
    
    if(msg){
		alert(msg);
		return false;
	}
}

function checkinfo(frm,userid){ 

    var login_email = document.getElementById('login_email').value;
    var men_email = document.getElementById('mem_Email').value;
    
    if(userid=="0" && login_email=="" && men_email=="" ){
      
        var msg = '';
        if (frm.do_Email.value == ''  )
        {            
            msg = 'メールアドレスをご入力ください。\n';
        }else if(frm.do_Email.value.replace(/\s+/g,"").search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)==-1){                       
            msg = 'メールアドレスの形式が正しくありません。\n'; 
        }
        
        if(frm.do_FirstName.value == ''){
            msg += '姓をご入力ください。\n';
        }
        if(frm.do_LastName.value == ''){
            msg += '名をご入力ください。\n';
        }
        if(frm.do_FirstName_2.value == ''){
            msg += '姓をご入力ください。\n';     
        }
        if(frm.do_LastName_2.value == ''){
            msg += '名をご入力ください。\n';
        }
        if(frm.do_PostalCode.value == ''){
            msg += '郵便番号をご入力ください。\n';   
        }
        if(frm.do_State.value == ''){
            msg += '都道府県名をご入力ください。\n';  
        }
        if(frm.do_City.value == ''){
            msg += '市区町村名をご入力ください。\n';  
        }
        if(frm.do_AddressLine.value == ''){
            msg += '町名・番地をご入力ください。\n';  
        }
        if(frm.do_Phone.value == ''){
            msg += '電話番号をご入力ください。\n';  
        }

        
            if(msg){
                alert(msg);
                return false;
            }
  
    }else{
        return flase;

        
    }
    
    return true;
      
}
    

function Is_register(){
	if(document.getElementById('email_notice')){
		
		document.getElementById('email_notice').style.display = 'none';
	}
	
	if(document.getElementById('no_register').checked){
		if(document.getElementById('is_ConfirmEmail')){
			document.getElementById('is_ConfirmEmail').style.display = 'none';
		}
		 if(document.getElementById('is_Password')){
			document.getElementById('is_Password').style.display = 'none';
		}
		if(document.getElementById('is_ConfirmPassword')){
			document.getElementById('is_ConfirmPassword').style.display = 'none';
		}
//		if(document.getElementById('true_register')){
//			document.getElementById('true_register').style.display = 'none';
//		}
		var email = document.getElementById('mem_Email').value;
		var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
		if(!myreg.test(email)){
		   document.getElementById('email_notice').style.display = 'block';
			document.getElementById('email_notice').innerHTML = 'ヒント\n\n有効なメールアドレスをご入力ください。';
			
			return false;
		}
		
	}else{
		
		if(document.getElementById('is_ConfirmEmail')){
			document.getElementById('is_ConfirmEmail').style.display = 'block';
		}
		 if(document.getElementById('is_Password')){
			document.getElementById('is_Password').style.display = 'block';
		}
		if(document.getElementById('is_ConfirmPassword')){
			document.getElementById('is_ConfirmPassword').style.display = 'block';
		}
//		if(document.getElementById('true_register')){
//			document.getElementById('true_register').style.display = 'block';
//		}
		checkmail()
	}



}




//确认全部
function checkall(){
	var msg ='';
	var email = document.getElementById('mem_Email').value;
		if(email == ''){
			msg = 'メールアドレスをご入力ください。\n';	
	}
	if(document.getElementById('yes_register').checked){
		var email = document.getElementById('mail_hid').value;
		var email2 = document.getElementById('mem_ConfirmEmail');
		if(email2 != ''){
			if(email != email2.value){
				/*document.getElementById('msg').style.display = 'block';
				document.getElementById('msg').innerHTML = 'メールアドレスをご確認ください。';
			}else{
				document.getElementById('msg').style.display = 'none';*/
				msg = 'メールアドレスが間違っているのかご確認ください。\n';
			}
		}
		
		//验证 确认密码
		var pass = document.getElementById('mem_Password').value;
		var pass2 = document.getElementById('mem_ConfirmPassword').value;
		if(pass != ''){
			if(pass2 != ''){
				if(pass != pass2){
					/*document.getElementById('msg').style.display = 'block';
					document.getElementById('msg').innerHTML = '入力された二つのパスワードが一致していません。';	
				}else{
					document.getElementById('msg').style.display = 'none';	*/
					 msg += '入力された二つのパスワードが一致していません。\n';
				}
			}else{
				/*document.getElementById('msg').style.display = 'block';
				document.getElementById('msg').innerHTML = 'パスワードをご入力ください。';*/
				msg += 'パスワードをご入力ください。\n';
			}
		}else{
			/*document.getElementById('msg').style.display = 'block';
			document.getElementById('msg').innerHTML = 'パスワードをご入力ください。';*/
			msg += 'パスワードをご入力ください。\n';
		}
		
	}
	
	
	//验证是否填写
	var firstname = document.getElementById('mem_FirstName').value;
	var lastname = document.getElementById('mem_LastName').value;
	var firstname2 = document.getElementById('mem_FirstName_2').value;
	var lastname2 = document.getElementById('mem_LastName_2').value;
	var postalcode = document.getElementById('mem_PostalCode').value;
	var state = document.getElementById('mem_State').value;
	var city = document.getElementById('mem_City').value;
	var addressline = document.getElementById('mem_AddressLine').value;
	var phone = document.getElementById('mem_Phone').value;
    var room = document.getElementById('mem_Room').value;
	if(document.getElementById('no_register').checked){
		 var is_register = document.getElementById('no_register').value;
	}
	
	//姓
	if(firstname == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '姓をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '姓をご入力ください。\n';
	}
	//名
	if(lastname == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '名をご入力ください。';*
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '名をご入力ください。\n';
	}
	//姓的拼音
	if(firstname2 == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '姓のフリガナをご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '姓のフリガナをご入力ください。\n';	
	}
	//名的拼音
	if(lastname2 == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '名のフリガナをご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '名のフリガナをご入力ください。\n';
	}
	//邮编
	if(postalcode == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '郵便番号をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '郵便番号をご入力ください。\n';
	}
	//省
	if(state == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '都道府県名をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '都道府県名をご入力ください。\n';
	}
	//市
	if(city == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '市区町村名をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '市区町村名をご入力ください。\n';
	}
	//街道
	if(addressline == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '町名・番地をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '町名・番地をご入力ください。\n';	
	}
	//电话
	if(phone == ''){
		/*document.getElementById('msg').style.display = 'block';
		document.getElementById('msg').innerHTML += '電話番号をご入力ください。';
	}else{
		document.getElementById('msg').style.display = 'none';*/
		msg += '電話番号をご入力ください。\n';
	}
	if(msg){
		alert(msg);
		return false;
	}
	Ajax.call('/flow.php?step=login', 'email=' + email + '&password=' + pass + '&firstname=' + firstname + '&lastname=' + lastname + '&firstname2=' + firstname2 + '&lastname2=' + lastname2 + '&postalcode=' + postalcode + '&state=' + state + '&city=' + city + '&addressline=' + addressline + '&room=' + room + '&phone=' + phone+ '&act=signup'+'&is_register=' + is_register, re_callback, 'POST', 'TEXT', true, true );
	
}

function re_callback(result){

    document.getElementById('login_email').value = result.email;
    document.getElementById('login_ShippingFirstName').value = result.FirstName;
    document.getElementById('login_ShippingLastName').value = result.LastName;
    document.getElementById('login_ShippingFirstName_2').value = result.FirstName_2;
    document.getElementById('login_ShippingLastName_2').value = result.LastName_2;
    document.getElementById('login_ShippingState').value = result.State;
    document.getElementById('login_ShippingCity').value = result.City;
    document.getElementById('login_ShippingAddressLine').value = result.AddressLine;
    document.getElementById('login_ShippingRoom').value = result.Room;
    document.getElementById('login_ShippingPhone').value = result.Phone;
    document.getElementById('login_ShippingPostalCode').value = result.PostalCode; 
    document.getElementById('address_id').value = result.address_id;
      

    
    if(document.getElementById('logo-div-box')){
	 	document.getElementById('logo-div-box').style.display = 'none';
	}
	 if(document.getElementById('new_regieter')){
	 	document.getElementById('new_regieter').style.display = 'none';
	}
    if(document.getElementById('logo-div-box2')){
	 	document.getElementById('logo-div-box2').style.display = 'block';
	}
	
	
	
    
}





//修改用户地址信息
function loginCheckAll(){
    var msg ='';
    //验证是否填写
	var login_email = document.getElementById('login_email').value;
	var login_ShippingFirstName = document.getElementById('login_ShippingFirstName').value;
	var login_ShippingLastName = document.getElementById('login_ShippingLastName').value;
	var login_ShippingFirstName_2 = document.getElementById('login_ShippingFirstName_2').value;
	var login_ShippingLastName_2 = document.getElementById('login_ShippingLastName_2').value;
	var login_ShippingState = document.getElementById('login_ShippingState').value;
	var login_ShippingCity = document.getElementById('login_ShippingCity').value;
	var login_ShippingAddressLine = document.getElementById('login_ShippingAddressLine').value;
	var login_ShippingRoom = document.getElementById('login_ShippingRoom').value;
    var login_ShippingPhone = document.getElementById('login_ShippingPhone').value;
    var login_ShippingPostalCode = document.getElementById('login_ShippingPostalCode').value;
    var address_id = document.getElementById('address_id').value;
    if(login_email == ''){
        	msg += 'メールアドレスをご入力ください。\n';
    }
    if(login_ShippingFirstName == ''){
        	msg += '姓をご入力ください。\n';
    }
    if(login_ShippingLastName == ''){
        	msg += '名をご入力ください。\n';
    }
    if(login_ShippingFirstName_2 == ''){
        	msg += '姓のフリガナをご入力ください。\n';
    }
    if(login_ShippingLastName_2 == ''){
        	msg += '名のフリガナをご入力ください。\n';
    }
    if(login_ShippingState == ''){
        	msg += '都道府県名をご入力ください。\n';
    }
    if(login_ShippingCity == ''){
        	msg += '市区町村名をご入力ください。\n';
    }
    if(login_ShippingAddressLine == ''){
        	msg += '町名・番地をご入力ください。\n';
    }
    if(login_ShippingPhone == ''){
        	msg += '電話番号をご入力ください。\n';
    }
    if(login_ShippingPostalCode == ''){
        	msg += '郵便番号をご入力ください。\n';
    }

    
    
    if(msg){
		alert(msg);
		return false;
	}
    
    Ajax.call('/flow.php?step=editAddress', 'email=' + login_email + '&firstname=' + login_ShippingFirstName + '&lastname=' + login_ShippingLastName + '&firstname2=' + login_ShippingFirstName_2 + '&lastname2=' + login_ShippingLastName_2 + '&postalcode=' + login_ShippingPostalCode + '&state=' + login_ShippingState + '&city=' + login_ShippingCity + '&addressline=' + login_ShippingAddressLine + '&phone=' + login_ShippingPhone+ '&room=' + login_ShippingRoom+ '&address_id=' + address_id, login_callback, 'POST', 'TEXT', true, true );
	
}




//在购物车里面登录
function login_in_cart(){
    
	var login_name = document.getElementById('login_Email').value;
	var login_pass = document.getElementById('login_Password').value;
	if(login_name != '' && login_pass != ''){
		Ajax.call('/flow.php?step=login', 'username=' + login_name + '&password=' + login_pass + '&act=signin', login_callback, 'POST', 'TEXT',true,true );	
	}else if(login_name == ''){
		
	   alert("ユーザー名をご入力ください。");
	}else if(login_pass == ''){
	   alert("パスワードをご入力ください。");
   
	}
}

function login_callback(result){
    
   var dataObj = eval("("+result+")");//转换为json对象
    if( dataObj.error == '1'){
		document.getElementById('login_email').value = dataObj.email;
		document.getElementById('login_ShippingFirstName').value = dataObj.FirstName;
		document.getElementById('login_ShippingLastName').value = dataObj.LastName;
		document.getElementById('login_ShippingFirstName_2').value = dataObj.FirstName_2;
		document.getElementById('login_ShippingLastName_2').value = dataObj.LastName_2;
		document.getElementById('login_ShippingState').value = dataObj.State;
		document.getElementById('login_ShippingCity').value = dataObj.City;
		document.getElementById('login_ShippingAddressLine').value = dataObj.AddressLine;
		document.getElementById('login_ShippingRoom').value = dataObj.Room;
		document.getElementById('login_ShippingPhone').value = dataObj.Phone;
		document.getElementById('login_ShippingPostalCode').value = dataObj.PostalCode; 
		document.getElementById('address_id').value = dataObj.address_id;
		 

		 
		if(document.getElementById('logo-div-box')){
			document.getElementById('logo-div-box').style.display = 'none';
		}
		if(document.getElementById('new_regieter')){
			document.getElementById('new_regieter').style.display = 'none';
		}
		
		if(document.getElementById('logo-div-box2')){	
			document.getElementById('logo-div-box2').style.display = 'block';
		}
		var notice ='変更しました。';
		
		
		if(document.getElementById('flow_userinfo')){	
			document.getElementById('flow_userinfo').innerHTML = dataObj.content; 
		}
		
   	   
    }else if( dataObj.error == '0' ){
		//error= 0 密码错误
        var notice ='パスワードが間違っています。';
		document.getElementById("login_Password").style.background = '#eeeeee';
		document.getElementById("login_Email").style.background = '#fff';
		
    }else{//error= 2  帐号错误
	 	var notice ='ユーザー名が間違っています。';
		document.getElementById("login_Email").style.background = '#eeeeee';
		document.getElementById("login_Password").style.background = '#fff';
		
	}
	
   
    	document.getElementById('smg').style.display = 'block';
        document.getElementById('smg').innerHTML = notice;
}
/* *
 *  修改附言
 */
function postscript(){
    
}


/* *
 * 改变配送方式
 */
function selectShipping(totalPrice,price)
{
	/* start by xiaolong */
	Ajax.call('/flow.php?step=select_ship', 'shipping_price=' + price.toJSONString()+'&totalPrice='+totalPrice.toJSONString(),orderShippingSelectedResponse2, 'GET', 'JSON');
 // Ajax.call('flow.php?step=select_ship', 'shipping_price='+price, 'GET', 'JSON'); //修改 by xiaolong
}

function orderShippingSelectedResponse2(result){
	
 	
	
	if(document.getElementById('Shipping_Charges2')){
		document.getElementById('Shipping_Charges2').innerHTML = result.shipping_price;
	}
	if(document.getElementById('Grand_Total2')){
		document.getElementById('Grand_Total2').innerHTML = result.totalPrice;
	}
	document.getElementById('Shipping_Charges').innerHTML = result.shipping_price;
	document.getElementById('Grand_Total').innerHTML = result.totalPrice;
	document.getElementById('price_Total').value = result.totalPrice2;
	document.getElementById('Shipping_price').value = result.shipping_price;
}


/* *
 * 检查收货地址信息表单中填写的内容
 */
 
    
//邮政编码处理
function request_data(){
	var axc=false;
	if(window.XMLHttpRequest){	//Mozilla浏览器
		axc=new XMLHttpRequest();
		(axc.overrideMimeType) && (axc.overrideMimeType('text/xml'));	//设置MiME类别
	}else if(window.ActiveXObject){	//IE浏览器
		try{
			axc=new ActiveXObject('Msxml3.XMLHTTP');
		}catch(e){ 
			try{ 
				axc=new ActiveXObject('Msxml2.XMLHTTP'); 
			}catch(e){
				try{
					axc=new ActiveXObject('Microsoft.XMLHTTP');
				}catch(e){}
			}
		}
	}
	return axc;
}
function zipcode(PostalCode, State, City, AddressLine){
	
	var zip=document.getElementById(PostalCode).value

	if(zip==''){
		alert('郵便番号をご入力ください。');
		return false;
	}
	
	zipcode_obj=new request_data();
	zipcode_obj.open('GET', '/zipcode.php?zipcode='+zip+'&r='+Math.random(), true);
	zipcode_obj.onreadystatechange=function(){zipcode_result(State, City, AddressLine)};
	zipcode_obj.send(null);
}
function zipcode_result(State, City, AddressLine){
	if(zipcode_obj.readyState==4){ // 判断对象状态
		var txt=zipcode_obj.responseText;
		if(txt!=null && txt!=''){
			if(txt=='error'){
				alert('該当する住所は見つかりませんでした。');
			}else{
				s=txt.split(' ');
				document.getElementById(State).value=s[0];
				document.getElementById(City).value=s[1];
				document.getElementById(AddressLine).value=s[2];
			}
		}
	}
}


function add_num(f, rec_id,goods_id)
	 {
	  
	     
		document.getElementById("goods_number_"+rec_id+"").value++;
		var number = document.getElementById("goods_number_"+rec_id+"").value;
		Ajax.call('/flow.php', 'step=update_group_cart&rec_id=' + rec_id +'&number=' + number+'&goods_id=' + goods_id, changePriceResponse, 'GET', 'JSON');
		//f.submit();
	 }

	function minus_num(f, rec_id,goods_id)
	{
		if (document.getElementById("goods_number_"+rec_id+"").value>1)
		{
			document.getElementById("goods_number_"+rec_id+"").value--;
		}
		var number = document.getElementById("goods_number_"+rec_id+"").value;
		Ajax.call('/flow.php', 'step=update_group_cart&rec_id=' + rec_id +'&number=' + number+'&goods_id=' + goods_id, changePriceResponse, 'GET', 'JSON');
		//f.submit();
	}

function change_price(rec_id,goods_id)
{
	var r = /^[1-9]+[0-9]*]*$/;
	var number = document.getElementById("goods_number_"+rec_id+"").value;
	if (!r.test(number))
	{
		alert("您输入的格式不正确！");
		document.getElementById("goods_number_"+rec_id+"").value=document.getElementById("hidden_"+rec_id+"").value;
	}
	else
	{
		Ajax.call('/flow.php','step=update_group_cart&rec_id=' + rec_id +'&number=' + number+'&goods_id=' + goods_id, changePriceResponse, 'GET', 'JSON');
	}
}

function changePriceResponse(result)
{
   
if(result.error == 1)
{
	alert(result.content);
	document.getElementById("goods_number_"+result.rec_id+"").value =result.number;
	document.getElementById("hidden_"+result.rec_id+"").value =result.number;
}
else
{
    
	document.getElementById("hidden_"+result.rec_id+"").value =result.number;
	document.getElementById('subtotal_'+result.rec_id).innerHTML = result.subtotal;//商品总价
	document.getElementById('total_price').innerHTML = result.cart_amount_desc;//购物车商品总价说明
	document.getElementById('market_amount_desc').innerHTML = result.market_amount_desc;//购物车商品总市价说明
	show_div_text = "恭喜您！ 商品数量修改成功！ ";
	showdiv(document.getElementById("goods_number_"+result.rec_id));
}

}