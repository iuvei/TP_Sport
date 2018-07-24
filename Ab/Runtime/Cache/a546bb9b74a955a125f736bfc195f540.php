<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width" />
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link href="/Public/css/mobileCss.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/css.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/custim-theme.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/Extends.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/datetime.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/js/jquery.js" ></script>
<script src="/Public/js/date.js"></script>
<script src="/Public/js/iscroll.js"></script>
<script src="/Public/js/login/lazyload.js"></script>
<script type="text/javascript">$(document).on("mobileinit", function () {$.mobile.ajaxEnabled = false;});</script>
<script src="/Public/js/login/router.min.js"></script>
<script src="/Public/js/login/jquerymobile.js"></script>
<script src="/Public/js/login/jqueryval.js"></script>
<script src="/Public/js/login/underscore.js"></script>
<script src="/Public/js/login/backbone.js"></script>
    <script src="/Public/mgpopup.js"></script>
    <title>额度转换</title>
    <style type="text/css">

          .wen{width:100%;height:100%;margin-top:82px;}
          .changemoney a{padding: 5px 10px;
        background-color: #ac2e0e;
        border-radius: 3px;
        color: white;
        margin-right: 10px;
    }
    .wen  p{
            margin-left: 27px;margin-top:10px;
        }    
        .wen  p i{color: #ac2e0e;}
        .ui-page-theme-a .ui-body-inherit{
            width:65%;
            display:inline-block;
        }
        .ui-select{
            width: 65%;
        background-color: white;
    margin-left: 72px;
    margin-top: -23px;
        }
        .ui-page-theme-a .ui-body-inherit input{
            background-color:white;
            min-height:21px;
            height:21px;
            width:100%;
            font-size: 14px;
    font-family: fantasy;
        }
#bank_id-button{
width: 50%;
    background-color: #d6d6ba;
    margin-left: 73px;
    margin-top: -23px;
    border-radius: 0em;
    height: 26px;
    padding: 0;
    line-height: 25px;
}
#foottijiao a{padding: 5px 10px;
    background-color: #ac2e0e;
    border-radius: 3px;
    color: white;
    margin-right: 10px;
    margin-top:10px;
}
#sumbit{padding: 5px 10px;
    background-color: #ac2e0e;
    border-radius: 3px;
    color: white;
      margin-left: 39%;
    margin-top: 3%;

}
#select-4-button{
    padding:5px;
}
#select-4-button span{
    margin-left:0px;
}
#chgtype-button{
    padding:0;
    text-align: left;
    }
    </style>

</head>
<body>
       <div data-role="header" data-theme="b" style="position: fixed;width: 100%;">
            <div class="pageMasker" style="display:none">
                <div style="text-align:center; margin-top:50%">
                </div>
                <p class="tcolor-5" style="text-align:center">读取平台数据中, 请稍后......</p>
            </div>
            <a data-iconpos="notext" data-role="buton" data-icon="flat-back" data-theme="a" href="/index.php/Main"></a>
            <h1 class="h3">额度转换</h1>
            <div class="header-right-user"></div>
        </div>

        <div class='wen'>

           <fieldset style="width: 80%; margin: 0 auto;border: 2px solid #d7d7bc;padding:5%;">
                        <legend style="margin-left: 10px;color: #ac2e0e;">额度转换</legend>
                         <div>
                            <p >
                                <span  style="font-size: 15px;color: black;">体育额度 : </span>
                                 <span id="hg_money"><?=$tymoney?></span>
                             </p>
                         </div>
                         <div> 
                                <p >
                                     <span style="font-size: 15px;color: black;">彩票额度 : </span> 
                                     <span id="cp_money"><?php echo ($cpmoney); ?></span>
                                </p>
                          </div>
                         <div>
                            <p style="margin-bottom:10px"> 
                                 <span style="font-size: 15px;color: black;">转换额度 : </span> 
                                 <input type="" id="gold" placeholder="请输入转换金额" pattern="[0-9]*"  name="gold" value="" style="background-color: white; width: 64%;text-decoration: none;border: none;height: 23px;line-height: 23px;outline: none;border-radius:0;">
                            </p>
                        </div>
                        <div>   
                        <p style="margin-bottom:10px "> 
                           <span style="font-size: 15px;color: black;">转换方式 :</span>
                            <select id="chgtype" name="chgtype">
                                <option value="0">体育转彩票</option>
                                <option value="1">彩票转体育</option>
                            </select>
                        </p>
                        </div>
                         <div>
                            <a id="sumbit" href="javascript:chg_money();">确认转账</a>
                        </div>
                 </div>
           </fieldset>
      </div>
      <script>
</script>
</body>
<script type="text/javascript">

    function chg_money(){
     var type = $("#chgtype").val();
      //转换金额
      var money = $("#gold").val();
      if(money==''){
        alert('转换金额不能为空！');
        return false;
      }
      if(!/^[0-9]+$/.test(money)){
          alert("转换金额只能为整数!");
          return false;
      }
      $.ajax({
        url:"__APP__/WyMoney/ajax_money",
        data:{
          type:type,
          money:money,
        },
        type:'post',
        dataType:'json',
        success:function(res){
            if(res.code==1001){
                alert(res.msg);
                window.location.reload();
            }else{
                alert(res.msg);
                return false;
            }
        },

      });


    }
</script>

</html>