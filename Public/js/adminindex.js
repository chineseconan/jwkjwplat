
//检测用户是否登录
function checkLogin(){
	//检测用户是否登录
	$.ajax({
		type:'post',
		url:'__CONTROLLER__/checkLogin',
		dataType:'json',
		success:function(data){
			if(data.code>0){
				return true;
			}else{
				return false;
			}
		}
	})
}