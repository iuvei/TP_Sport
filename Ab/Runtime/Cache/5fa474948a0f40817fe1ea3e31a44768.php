<?php if (!defined('THINK_PATH')) exit();?><html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes" />
<meta name="screen-orientation" content="portrait"><!-- UC浏览器强制竖屏 -->
<meta name="x5-orientation" content="portrait"><!-- QQ浏览器强制竖屏 -->
<meta name="x5-orientation" content="portrait">
        <title>welcome</title>
        <link href="/Public/images/114.png" rel="apple-touch-icon-precomposed">
        <link href="/Public/images/57.png" sizes="114x114" rel="apple-touch-icon-precomposed">
        <link href="/Public/css/global.css?a=123456" rel="stylesheet" />
        <style>button,input,optgroup,select,textarea { color:inherit;font:inherit;margin:0;-webkit-appearance:none;-moz-appearance:none;appearance:none;outline:none;}
button { overflow:visible;}
button,select { text-transform:none;}
button,html input[type="button"],input[type="reset"],input[type="submit"] { -webkit-appearance:button;cursor:pointer;}
button[disabled],html input[disabled] { cursor:default;}
button::-moz-focus-inner,input::-moz-focus-inner { border:0;padding:0;}
#tagWin { position:fixed;z-index:2000;left:65px;bottom:15px;padding:6px 8px 0px 8px;width:164px;height:54px;border:1px solid #dadcd9;background:#fff;box-shadow:1px 1px 5px rgba(0,0,0,0.2); }
#tagWin dl { width:100%;margin:0px; }
#tagWin dl dt { float:left;margin:0px;width:60px;height:52px; }
#tagWin dl dt img { float:left;width:48px;height:48px;border-radius:10px; }
#tagWin dl dd { float:left;margin:0px; }
#tagWin dl dd p { margin:0px;font-size:12px; }
#tagWin a { display:block;position:absolute;right:10px;top:8px;z-index:2001;width:11px;height:11px;background:url(/Public/images/ico-close.png) no-repeat;overflow:hidden;text-indent:-100px; }
#tagWin em { display:block;position:absolute;left:110px;bottom:-28px;z-index:2001;font-size:0;line-height:0;border-width:14px;border-style:solid dashed dashed;border-color:#dadcd9 transparent transparent;}
#tagWin span { display:block;position:absolute;bottom:-24px;left:112px;z-index:2002;font-size:0;line-height:0;border-width:12px;border-style:solid dashed dashed;border-color:#FFF transparent transparent;}
#fullWindows { display:none;position:absolute;z-index:1000;top:0px;left:0px;width:100%;overflow:hidden;}
#fullWindows iframe { float:left;width:100%;border:0px;}
#fullWindows #fullWinBtn { display:block;width:45px;height:45px;line-height:45px;background:url(images/close-btn-bg.png) no-repeat;position:absolute;z-index:1002;top:5px;right:5px;font-size:38px;color:#fff;text-decoration:none;overflow:hidden;text-align:center;}
#fullWindows #fullWinBtn:hover {color:#ffff00;}
    #edzh{position: absolute;
    z-index: 1;
    width: 90%;
    box-sizing: border-box;
    outline: 0;
    background-color: white;
   /* height: 30%;*/
    left: 5%;
    top: 25%;}
    #footer{width:100%;height: 52px;background-color:#ad2e0e;z-index:100;position: absolute;left:0;}
#footer ul li{float:left;width:25%;text-align:center;margin-top:6px;}
#footer ul li a{display: inline-block;}
#footer ul li a span{width:25px;height:25px;}
#footer ul li a span img{width:25px;height:25px;}
#footer ul li a p{font-size:14px;color:white;padding-top:3px;}
.channel-nav li{
    width:30%;
    height:90px;
    margin-top:10px;
}
.channel-nav li p{
    line-height: 25px;
    margin-top:20px;
}
.channel-nav li span{
    width:60px;
}
@media screen and (max-width: 320px){
    
}
            .world_cup_area{
                width: 100%;
                height: 50.5px;
                /*background: blue;*/
                background:#f1f1db;
                display: flex;
            }
            .world_cup_bg{
                width: 50%;
                height: 100%;
                background: url(/Public/images/mobile_world_cup.png) no-repeat;
                background-size: 100% 100%;
                margin-right: 1px;

            }
            .match_list{
                width: 50%;
                height: 100%;
            }
            .match_list>div{
                height: 50%;
                width: 100%;
                display: flex;
                background-size: 100% 100%;
                align-items: center;
            }
        .match_list>div:nth-child(1) a{
            width: 50%;
            height: 100%;
            background: #e0cfae;
            display: flex;
            justify-content: center;
            align-items: center;
            color: black;
        }
        .match_list>div:nth-child(1) a:nth-child(1){
            margin-right:1px;
        }
        .match_list>div:nth-child(2) a{
            width: 50%;
            height: 100%;
            background: url(/Public/images/world_cup_bg.png) no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #e0cfae;
        }
        .match_list>div:nth-child(2) a:nth-child(1){
            margin-right:1px;
        }

</style>
        <script src="/Public/js/jquery.js"></script>
        <script src="/Public/js/jquery.touchSlider.js"></script>
        <script src="/Public/js/global.js"></script>
        <script src="/Public/js/home.js"></script>
    </head>
    <body>
        <div id="gameNav">
          <iframe src="__APP__/Game/gamelist" frameborder="0"></iframe>
        </div>
        <?php
 if(session('username')){ ?>
        <div id="accountNav">
            <iframe src="__APP__/Money/Index" frameborder="0"></iframe>
        </div>
        <?php }?>
        <div class="ui-main" id="Main" style="height: 100%">
            <div class="ui-header" id="Header">
                <a id="gameNavBtn" href="javascript:showGameMenu();"></a>
                <a class="logoa" href="__APP__/Main"></a>
                <?php
if(session('username')){ ?>
<a class="haslogin"  href="javascript:void(0);"><span></span><strong id="dqje"><?php echo ($money["Money"]); ?></strong></a>
<?php }?>
            </div>
            <div class="touchslider">
                <div class="touchslider-viewport">
                    <ul>
                        <li><a  href="#" /><img id="sh" src="/Public/images/shouji6.png" style="width:100%;height:100%;position:absolute;left:50%;margin-left:-206px;" /></a></li>
                    </ul>
                </div>

                <div class="touchslider-nav">
                    <a class="touchslider-nav-item touchslider-nav-item-current" href="#" ></a>
                    <a class="touchslider-nav-item" href="#"  ></a>
                    <a class="touchslider-nav-item" href="#"  ></a>
                </div>

            </div>
            <!--世界杯活动开始-->
            <div class="world_cup_area">
                <div class="world_cup_bg">
                </div>
                <div class="match_list">
                    <div>
                        <a href="__APP__/Sport/slist/tt/FT/sr/1/wc/1">滚球</a>
                        <a href="__APP__/Sport/slist/tt/FT/wc/1">今日</a>
                    </div>
                    <div>
                        <a id="gun_ball" href="__APP__/Sport/slist/tt/FT/sr/1/wc/1"></a>
                        <a id="today_ball" href="__APP__/Sport/slist/tt/FT/wc/1"></a>
                    </div>
                </div>


            </div>
            <!-- 公告栏 -->

 <div class="channel style2" id="scroll" style="height:452px;overflow-y: scroll;">
                <ul class="channel-nav">
                    <li>
                        <a href="__APP__/Sport/slist/tt/FT/sr/1">
                            <span><img src="/Public/images/mgdz.png" /></span>
                            <p>足球滚球</p>
                        </a>
                    </li>
                    <li>
                        <a href="__APP__/Sport/slist/tt/FT" >
                            <span><img src="/Public/images/mgdz.png" /></span>
                            <p>足球单式</p>
                        </a>
                    </li>
                    <?php
 if(!session('type')){ ?>
                    <li>
                        <a href="<?=$referrer_url?>">
                            <span><img src="/Public/images/icon-channel-12.png" /></span>
                            <p>彩票游戏</p>
                        </a>
                    </li>
                    <?php
 } ?>
                    <li>
                        <a href="__APP__/Sport/slist/tt/BK/sr/1">
                            <span><img src="/Public/images/mgdz.png" /></span>
                            <p>篮球滚球</p>
                        </a>
                    </li>
                    <li>
                        <a href="__APP__/Sport/slist/tt/BK">
                            <span><img src="/Public/images/mgdz.png" /></span>
                            <p>篮球单式</p>
                        </a>
                    </li>
                    <li>
                        <?php
 $agentarr = ['dailiv5','dailiv6','tianen1']; if(!in_array($money['Agents'],$agentarr)){?>
                        <a href="#"  onclick="location.href='__APP__/WyMoney'">
                            <span><img src="/Public/images/icon-channel-6.png" /></span>
                            <p>额度转换</p>
                        </a>
                          <?php }else{?>
                            <a href="#"  onclick="tishi()">
                            <span><img src="/Public/images/icon-channel-6.png" /></span>
                            <p>额度转换</p>
                        </a>
                          <?php }?>
                    </li>
                    <li>
                        <?php
 if($money['Agents']!='demo111'){?>
                        <a href="#"  onclick="location.href='__APP__/WyMoney/saveList'">
                            <span><img src="/Public/images/icon-channel-8.png"/></span>
                            <p>转换记录</p>
                        </a>
                        <?php }else{?>
                        <a href="#" onclick="tishi()">
                            <span><img src="/Public/images/icon-channel-8.png"/></span>
                            <p>转换记录</p>
                        </a>
                        <?php }?>
                    </li>
                    <li>
                        <?php if($money['Agents']!='demo111'){?>
                        <a href="#"  class="jiathis_style_m" onclick="location.href='__APP__/Bethis'">
                            <span><img src="/Public/images/icon-channel-share.png" /></span>
                            <p>注单记录</p>
                        </a>
                         <?php }else{?>
                         <a href="#"  class="jiathis_style_m " onclick="tishi()">
                            <span><img src="/Public/images/icon-channel-share.png" /></span>
                            <p>注单记录</p>
                        </a>
                        <?php }?>
                    </li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>

<div id="footer" class="footer">
        <ul>
            <li>

             <a href="#">
            <span>
                <!-- <img src="/Public/images/agent.png" alt=""> -->
            </span>
           
            </a>
            </li>
            <li>
                <a href="#" >
            <span>
               <!--  /*/*/*<img src="/Public/images/exchange.png" alt="" style="width:33px">*/*/*/ -->
            </span>
           
            </a>
            </li>
           <li>
             <a href="#"  >
                    <span>
                        <!-- <img src="/Public/images/msg.png" alt="" > -->
                    </span>
                    
                </a>
            </li>
            <li>
                 <a href="#"  >
                    <span>
                        <!-- <img src="/Public/images/csservice.png" alt=""> -->
                    </span>
                    
                </a>
            </li>
        </ul>
    </div>


        <div id="orderWin">
    <div id="winTit"><em id="Countdown">10</em><dl><dt>交易单</dt><dd>目前余额:</dd></dl><span onclick='hide();'>×</span></div>
    <form name="orderForm" id="orderForm" method="post" action="">
        <div class="order-info">
            <h2></h2>
            <br /> <strong></strong>
            <br /> @ <strong></strong>
        </div>
        <div class="bet-info">
            <dl>
                <dt><input type="number" value="" placeholder="投注额" /></dt>
                <dd>可赢金额:<br /><strong>0</strong></dd>
            </dl>
            <div>单注最高: <strong></strong></div>
            <div><div class="left">单注最低: <strong></strong></div><label class="right">自动接受较佳赔率<input type="checkbox" value="" class="order" checked /></label></div>
        </div>
        <input type="button" value="确认交易" name="betBtn" id="betBtn" class="bet-btn" />
    </form>
</div>
<script type="text/javascript">
    $(function () {
        var colHeader = $(".header-date");
        colHeader.click(function () {
            $(this).next().toggle().parent().toggleClass("open");
        });
    });
    var odtimeout;
    var moneytmp;
    var tentime;
    var intime = false;
    function hide() {
        var orderWin = $("#orderWin");
        orderWin.slideUp(200);
        clearTimeout(odtimeout);
        clearTimeout(tentime);
        intime = false;
    }
    //交易单
    function getOrderhtml(u) {
        clearTimeout(odtimeout);
        clearTimeout(tentime);
        intime = false;
        moneytmp = getmoney();
        var orderWin = $("#orderWin");
        $.ajax({
            url: u,
            dataType: 'html',
            success: function (ret) {
                orderWin.html(ret);
                $('#orderForm').find('div.bet-info').find('dd').find('strong').html(get_val(moneytmp));
                $('#placeholder').val(moneytmp).keyup(function () {
                    $('#orderForm').find('div.bet-info').find('dd').find('strong').html(get_val($('#placeholder').val()));
                });
                orderWin.slideDown(100);
                if (!intime) {
                    tentime = gettentime();
                }
            }
        });
        odtimeout = setTimeout("getOrderhtml('" + u + "')", 10000);
    }

    function get_val(m) {
        var m_rate = $('#ioradio_r_h').val();
        var line = $('#orderForm').find("input[name='line_type']").val();
        if (line == 1 || line == 5 || line == 11 || line == 21 || line == 31) {
            m_rate = m_rate - 1;
        }
        var can_win = m * m_rate;
        return can_win.toFixed(1);
    }
    function gettentime() {
        intime = true;
        tentime = setTimeout("gettentime()", 1000);
        var tmp = $('#Countdown');
        if (!tmp) {
            return;
        }
        var str = $('#Countdown').html();
        var int = parseInt(str);
        int--;
        $('#Countdown').html(int);
    }
    function getmoney() {
        if ($('#placeholder').val()) {
            return $('#placeholder').val();
        }
        return '';
    }
    function showOrderWin(u) {
        var orderWin = $("#orderWin");
        var closeBtn = orderWin.children().first().children().last();
        getOrderhtml(u);
        closeBtn.click(function () {
            orderWin.slideUp(200);
            clearTimeout(odtimeout);
            clearTimeout(tentime);
            intime = false;
        });
    }

    function bet() {
        // var zdje = parseInt($("#placeholder").val());
        // var ylje = parseInt($("#dqje").html());
        // var syje = ylje-zdje;
        // $("#dqje").html(syje);
        // // console.log(syje);
        clearTimeout(odtimeout);
        clearTimeout(tentime);
        intime = false;
        $('#orderForm').submit();

    }
</script>
        <div id="fullWindows">
            <a id="fullWinBtn" href="javascript:closeFullWin();">&nbsp;</a>
            <iframe src="" frameborder="0" marginheight="0" marginwidth="0" frameborder="0" id="winIframe" name="winIframe"></iframe>
        </div>


<style>
    .jiathis_style_32x32 a img{
        width:19%;
    }
</style>

    <script>
        $(function () {
            $.ajax({
                url:"/index.php/Sport/countwc",
                dataType:"json",
                success:function (data) {
                    var json = data;
                    var re = json.re;
                    var today = json.today;
                    $('#gun_ball').html(re);
                    $('#today_ball').html(today);
                }
            })
        })

    var wdwidth = $(window).width();
if(wdwidth<="414"){
    $("#sh").css({'left':'0','margin-left':'0'});
    $("#sho").css({'left':'0','margin-left':'0'});
      $("#shoo").css({'left':'0','margin-left':'0'});
        $("#shooo").css({'left':'0','margin-left':'0'});
    console.log($("#sho"));
}else{
    $("#sh").css({'width':'412px','left':'50%','margin-left':'-206px'});
      $("#sho").css({'width':'412px','left':'50%','margin-left':'-206px'});
        $("#shoo").css({'width':'412px','left':'50%','margin-left':'-206px'});
          $("#shooo").css({'width':'412px','left':'50%','margin-left':'-206px'});
}
//显示全屏窗口
    function closeFullWin(){
            $("#winIframe").attr('src','');
            $("#fullWindows").hide();
    }
        var nav = navigator,
    isIDevice = (/iphone|ipod|ipad/gi).test(nav.platform),
    isIPad = (/ipad/gi).test(nav.platform),
    isRetina = 'devicePixelRatio' in window && window.devicePixelRatio > 1,
    isSafari = nav.appVersion.match(/Safari/gi),
    hasHomescreen = 'standalone' in nav && isIDevice,
    isStandalone = hasHomescreen && nav.standalone,
    OSVersion = nav.appVersion.match(/OS \d+_\d+/g),
    platform = nav.platform.split(' ')[0],
    language = nav.language.replace('-', '_');
    if (hasHomescreen && !isStandalone && isSafari) {document.getElementById("tagWin").style.display="block";}
        function closeTagWin(){
            document.getElementById("tagWin").style.display="none";
        }
    </script>
    <div style="display:none">
<script type="text/javascript">
/*var sc_project=11020006;
var sc_invisible=0;
var sc_security="9001cbb3";
var scJsHost = (("https:" == document.location.protocol) ?"https://secure." : "http://www.");
document.write("<sc"+"ript type='text/javascript' src='" +scJsHost+"statcounter.com/counter/counter.js'></"+"script>");*/

</script>
        </div></div>
        <div id="motai" style="position: absolute;left:0;top: 0;width: 100%;height: 100%; display: none;z-index: 100">
    <div style="position: absolute;left:0;top: 0;width: 100%;height: 100%;background-color: black;opacity: 0.5;"></div>
    <div id="edzh" style="height: 25%;border-radius: 20px;">
         <span style="font-size: 20px; float: left; margin-top:40px; margin-left: 40px;">分享到:</span>

        </div>
</div>
<!-- <script type="text/javascript" src="/Public/js/jia.js" charset="utf-8"></script> -->
 <script type="text/javascript" src="/Public/js/NativeShare.js"></script>

<script>
// window.onload = function() {
//   hengshuping();
// };
// function hengshuping(){
//     console.log('shouji');
//     if(window.orientation==180||window.orientation==0){
//         console.log('竖屏状态');
//         alert('竖屏状态');
//     }
//     if(window.orientation==90||window.orientation==-90){
//     console.log('横屏状态');
//     alert('横屏状态');
//     }
// }

var jiathis_config={
    summary:"",
    shortUrl:false,
    hideMore:false
}

var closechange = document.getElementById("closechange");
closechange.onclick = function(){
     $("#motai").hide();
}
        var height = document.documentElement.clientHeight;
        var scroll = document.getElementById("scroll");
    if(height<=568){
        scroll.style.height = "285px";
        // document.getElementById('footer').style.position = "fixed";
        // document.getElementById('footer').style.bottom = "2px";
     }else if(height<=667&&height>568){
            scroll.style.height = "383px"
    }else if(height<=736&&height>667){
     scroll.style.height = "452px";
     }else if(height>736){
         scroll.style.height = "490px";
     }
    window.addEventListener("orientationchange", function() {
        // alert(window.orientation);
        if(window.orientation==-90|| window.orientation==90){
              // scroll.style.height = "430px";
            $(".footer").css({
                'position':'fixed',
            });
        }
        if(window.orientation==180||window.orientation==0){
               $(".footer").css({
                'position':'absolute',
            });
                }
        console.log(window.orientation);
        }, false);
function tishi(){
    alert('尚未开通此功能！');
    return false;
}

    </script>
    </body>
</html>
<!-- JiaThis Button BEGIN -->

<!-- JiaThis Button END -->