var cp = {};
cp.set = {};
cp.set.action = 'b';
cp.query = 0;
function gm_cp() {
    if (cp.query == 1) {
        return;
    }
    cp.set.action = 'b';
    cp.query = 1;
    $.ajax({
        url: '/index.php/Lottery/gm',
        type: 'POST',
        dataType: 'json',
        data: cp.set,
        success: function (ret) {
            if (ret.err != '1025') {
                try {
                    $('#cp_money').html(ret.cp);
                    $('#cp_money2').html(ret.cp);
                    $('#hg_money').html(ret.hg);
                    cp.set.token = ret.token;
                } catch (e) {
                }
            } else {
                document.location.href = '/';
            }
            $('#ref_img3').attr('src', '/Public/images/refresh5.gif');
        },
        error: function () {
           
            $('#ref_img3').attr('src', '/Public/images/refresh5.gif');
        }
    });
    cp.query = 0;
}
