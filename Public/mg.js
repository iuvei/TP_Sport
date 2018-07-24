var mg = {};
mg.set = {};
mg.set.action = 'b';
mg.query = 0;
function gm() {
	if (mg.query == 1) {
		return;
	}
	$('#ref_img2').attr('src','/Public/images/refresh6.gif');
    mg.set.action = 'b';
    mg.query = 1;
	$.ajax({
		url: '/index.php/Mg/gm',
		type: 'POST',
		dataType: 'json',
		data: mg.set,
		success: function (ret) {
			console.log(ret);
			if(ret.err!='1025'){
				try{
				$('#mg_money').html(ret.mg);
				$('#mg_money2').html(ret.mg);
				$('#hg_money').html(ret.hg);
                    mg.set.token=ret.token;
				}
				catch(e){}
			}
			else{
				document.location.href='/';
			}
	$('#ref_img2').attr('src','/Public/images/refresh5.gif');
		},
		error: function () {
			
	$('#ref_img2').attr('src','/Public/images/refresh5.gif');
		}
	});
    mg.query = 0;
}

function cm(t1) {
	if (mg.query == 1) {
		return;
	}
    mg.set.action = 'a';
    mg.query = 1;
    mg.set.f = (t1 == 'mg' ? 'mg' : 'hg');
    mg.set.t = (t1 == 'mg' ? 'hg' : 'mg');
    mg.set.b=$('#gold').val();
	if(mg.set.b==''||mg.set.b==0){
		popup('请输入金额。');
        mg.query = 0;
		return false;
	}
	$.ajax({
		url: '/index.php/Mg/cm',
		type: 'POST',
		dataType: 'json',
		data: mg.set,
		success: function (ret) {
			if(ret.err=='1025'){
				document.location.href='/';
			}
			else {
				$('#gold').val('');
                mg.set.token=ret.token;
				gm();
				popup(ret.msg);
			}
		},
		error: function (ii,jj,kk) {
			
		}
	});
    mg.query = 0;
}

function tg() {
	if (mg.query == 1) {
		return;
	}
    mg.set.action = 'f';
    mg.query = 1;
	$.ajax({
		url: '/index.php/Mg/tg',
		type: 'POST',
		dataType: 'json',
		data: mg.set,
		success: function (ret) {
			
			if(ret.err==0){
				document.location.href=ret.msg;
			}
			else if(ret.err=='1025'){
				document.location.href='/';
			}
			else{
                mg.set.token=ret.token;
			}
		},
		error: function (ii,jj,kk,ll) {
			var tm=1;
		}
	});
    mg.query = 0;
}