<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes" />
        <title><?php if($tt=='FT'){ echo '足球';}else{echo '篮球';} ?></title>
        <link href="/Public/css/global.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/base.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/main.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/main_color_001.css"/>
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_color_001.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_mq.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_pad_color_001.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_pad_mq.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_pad_mq.css" />
        <script src="/Public/js/jquery.js"></script>
        <script src="/Public/js/global.js"></script>
       <style type="text/css">
            .a{
            display: block;
            width: 30px;
            height:30px;
            text-decoration: none;
            text-align: center;
            line-height: 30px;
            font-weight: bold;
            color:red;
            position: absolute;
            top:20px;
        }
        .gun_ball_one{
            background-position: -1887px 0px!important;

        }
         .today_ball_two{
            background-position: -1948px 0px!important;

        }
           .gametu em{
               font-size: 17px;
           }
        
       </style>
    </head>
    <script>
        function rel(){
            location.reload();
        }
        $( document ).ready(function() {
            setTimeout(rel,60000);
        });
    </script>
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
        <div class="ui-main" id="Main">
            <div class="ui-header" id="Header">
                <a id="gameNavBtn" href="javascript:showGameMenu();"></a>
                <a class="logoa" href="__APP__/Main"></a>
                <?php
if(session('username')){ ?>
<a class="haslogin"  href="javascript:void(0);"><span></span><strong id="dqje"><?php echo ($money["Money"]); ?></strong></a>
<?php }?>
            </div>
            <div id="div_topNav" class="nav2 relative clear" style="">
                <div>
                    <a href="__APP__/Main"> <div class="icon-back"></div></a>
                </div>
                <div class="nav2-grid" data-bind="attr: {class: tournSelect() ? 'nav2-grid02' : 'nav2-grid'}">
                    <div>
                        <div class="nav2-title" data-bind="text: Nav"><?php if($ttt=='6'){ echo '滚球';}else if($ttt=="5"){echo '今日';}else if($ttt=="4"){echo '篮球滚球';}else if($ttt=="3"){echo '篮球';}else if($ttt=="2"){echo '足球滚球';}else { echo '足球';} ?></div>
                    </div>
                </div>
            </div>
           <div class="game1" style="position: relative;">
                <div class="gamemenu">
                    <div class="gametu" style="transition: all 1s; ">
                          <a class="game2 namemenu <?php if($ttt=='6'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/FT/sr/1/wc/1"><em><?php echo ($ft['RB_WC_Show']); ?></em><strong class="gun_ball_one">滚球</strong></a>
                          <a class="game2 namemenu <?php if($ttt=='5'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/FT/wc/1"><em><?php echo ($ft['WC_Show']); ?></em><strong class="today_ball_two">今日</strong></a>
                          <a class="game2 namemenu <?php if($ttt=='1'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/FT"><em><?php echo ($ft['S_Show']+$ft['RB_Show']); ?></em><strong>足球</strong></a>
                          <a class="game2 namemenu <?php if($ttt=='2'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/FT/sr/1"><em><?php echo ($ft['RB_Show']); ?></em><strong>足球滚球</strong></a>
                          <a class="game3 namemenu <?php if($ttt=='3'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/BK"><em><?php echo ($bk['S_Show']+$bk['RB_Show']); ?></em><strong>篮球</strong></a>
                          <a class="game3 namemenu <?php if($ttt=='4'){echo 'on_game';} ?>" href="__APP__/Sport/slist/tt/BK/sr/1"><em><?php echo ($bk['RB_Show']); ?></em><strong>篮球滚球</strong></a>
                    </div>
                </div>
           </div>
            <div class="main-content-wrapper">
                <section class="main-content">
                    <section class="info-main">
                        <div class="info-main-inner padding-content border-bottom-light">
                            <section id="sec_Idxlv" class="topic" style="">
                                <div id="div_IdxLv_00001">
                                    <div class="category-wrap" id="div_Idxlv_00001">
                                        <div class="sys-template">
                                            <div class="sys-template table-wrap bg-00001">
                                                <?php if(is_array($rbsports)): $i = 0; $__LIST__ = $rbsports;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sport1): $mod = ($i % 2 );++$i;?><a href="__APP__/Sport/srate/id/<?php echo ($sport1["MID"]); ?>/tt/<?php echo ($sport1["Type"]); ?>/ror/<?php echo ($sport1["Ror"]); ?>">
                                                        <div class="row-wrap clear">
                                                            <span class="icon">N</span>
                                                            <div class="td4 td4-0 left"><?php echo $leg[$sport1['M_League']]?$leg[$sport1['M_League']]:$sport1['M_League']; ?></div>
                                                            <div class="td4 td4-1 left" style="text-align: center;">
                                                                <?php
$timeset = explode('^', $sport1['now_play']); if ($timeset[0] == "") { echo '<div style="line-height: 42px">-'; } elseif ($timeset[0] == "Start") { echo '<div style="line-height: 42px">-'; } elseif (strpos($timeset[0], "MT") !== false) { echo '<div style="line-height: 42px">'.$timeset[1]; } elseif (strpos($sport['now_play'], "HT") !== false) { echo '<div style="line-height: 42px">半场'; } else { if (strpos($timeset[0], "1H") !== false) { echo '<div style="line-height: 21px">上半场<br/>' . $timeset[1] ."'"; } elseif (strpos($timeset[0], "2H") !== false) { echo '<div style="line-height: 21px">下半场<br/>' . $timeset[1] . "'"; } elseif (strpos($timeset[0], "Q1") !== false) { $fen = substr('00' . floor($timeset[1] / 60), -2); $miao = substr('00' . ($timeset[1] % 60), -2); echo '<div style="line-height: 21px">第一节<br/>' . $fen . ':' . $miao; } elseif (strpos($timeset[0], "Q2") !== false) { $fen = substr('00' . floor($timeset[1] / 60), -2); $miao = substr('00' . ($timeset[1] % 60), -2); echo '<div style="line-height: 21px">第二节<br/>' . $fen . ':' . $miao; } elseif (strpos($timeset[0], "Q3") !== false) { $fen = substr('00' . floor($timeset[1] / 60), -2); $miao = substr('00' . ($timeset[1] % 60), -2); echo '<div style="line-height: 21px">第三节<br/>' . $fen . ':' . $miao; } elseif (strpos($timeset[0], "Q4") !== false) { $fen = substr('00' . floor($timeset[1] / 60), -2); $miao = substr('00' . ($timeset[1] % 60), -2); echo '<div style="line-height: 21px">第四节<br/>' . $fen . ':' . $miao; } else{ echo '<div style="line-height: 21px">'; } } ?></div></div>
                                                            <div class="td4 td4-2 left">
                                                                <?php
 if(!$sport1['RB_Show']){ ?>
                                                                <div class=""><span><?php echo ($sport1["MB_Team"]); ?></span><span class="redcard" style="display: none;">未开始</span></div>
                                                                <div class=""><span><?php echo ($sport1["TG_Team"]); ?></span><span class="redcard" style="display: none;">未开始</span></div>
                                                                <?php
 } elseif($sport1['RB_Show']){ ?>
                                                                <div class=""><span><?php echo ($sport1["MB_Team"]); ?></span><span class="redcard" style="display: none;"><?php echo ($sport1["MB_Ball"]); ?></span></div>
                                                                <div class=""><span><?php echo ($sport1["TG_Team"]); ?></span><span class="redcard" style="display: none;"><?php echo ($sport1["TG_Ball"]); ?></span></div>
                                                                <?php
 } ?>
                                                            </div>
                                                            <div class="td4 td4-4 right">
                                                                <div class="icon-next"></div>
                                                            </div>
                                                            <div class="td4 td4-3 right sys-template">
                                                                <ul class="grades1 left">
                                                                    <li class="grades_wks"> <?php echo ($sport1["MB_Ball"]); ?>:<?php echo ($sport1["TG_Ball"]); ?>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                                           <?php if(is_array($sports)): $i = 0; $__LIST__ = $sports;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sport): $mod = ($i % 2 );++$i;?><a href="__APP__/Sport/srate/id/<?php echo ($sport["MID"]); ?>/tt/<?php echo ($sport["Type"]); ?>/ror/<?php echo ($sport["Ror"]); ?>">
                                                        <div class="row-wrap clear">
                                                            <span class="icon">N</span>
                                                            <div class="td4 td4-0 left"><?php echo $leg[$sport['M_League']]?$leg[$sport['M_League']]:$sport['M_League']; ?></div>
                                                            <div class="td4 td4-1 left"><div style="line-height: 42px"><?php echo ($sport["M_Time"]); ?></div></div>
                                                            <div class="td4 td4-2 left">
                                                                <?php
 if(!$sport['RB_Show']){ ?>
                                                                <div class=""><span><?php echo ($sport["MB_Team"]); ?></span><span class="redcard" style="display: none;">未开始</span></div>
                                                                <div class=""><span><?php echo ($sport["TG_Team"]); ?></span><span class="redcard" style="display: none;">未开始</span></div>
                                                                <?php
 } elseif($sport['RB_Show']){ ?>
                                                                <div class=""><span><?php echo ($sport["MB_Team"]); ?></span><span class="redcard" style="display: none;"><?php echo ($sport["MB_Ball"]); ?></span></div>
                                                                <div class=""><span><?php echo ($sport["TG_Team"]); ?></span><span class="redcard" style="display: none;"><?php echo ($sport["TG_Ball"]); ?></span></div>
                                                                <?php
 } ?>
                                                            </div>
                                                            <div class="td4 td4-4 right">
                                                                <div class="icon-next"></div>
                                                            </div>
                                                            <div class="td4 td4-3 right sys-template">
                                                                <ul class="grades1 left">
                                                                    <li class="grades_wks"> <?php
 if(!$sport['RB_Show']){ ?>未开始<?php
 } elseif($sport['RB_Show']){ echo ($sport["MB_Ball"]); ?>:<?php echo ($sport["TG_Ball"]); } ?></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </a><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </section>
                </section>
            </div>
            <a id="goTop" href="">刷新</a>
        </div>
    </body>
</html>