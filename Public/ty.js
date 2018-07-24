var ty = {};
ty.set = {};
ty.set.action = 'b';
ty.query = 0;
function ty_gm() {
    if (ty.query == 1) {
        return;
    }
    $('#ref_img1').attr('src','/Public/images/refresh6.gif');
    ty.set.action = 'b';
    ty.query = 1;
    $.ajax({
        url: '/index.php/Money/getMoney',
        type: 'POST',
        dataType: 'json',
        data: ty.set,
        success: function (ret) {
            if(ret.err==0){
                try{
                    $('#ty_money').html(ret.msg);
                }
                catch(e){}
            }
            else{
                document.location.href='/';
            }
            $('#ref_img1').attr('src','/Public/images/refresh5.gif');
        },
        error: function () {
            
            $('#ref_img1').attr('src','/Public/images/refresh5.gif');
        }
    });
    ty.query = 0;
}