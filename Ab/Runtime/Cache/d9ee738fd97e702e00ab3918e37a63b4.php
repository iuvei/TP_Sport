<?php if (!defined('THINK_PATH')) exit(); $sports=$sport; $sport=current($sports); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes" />
        <title><?php echo ($sport["MB_Team"]); ?> vs <?php echo ($sport["TG_Team"]); ?></title>
        <link href="/Public/css/global.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/base.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/main.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/main_color_001.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_color_001.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_mq.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_pad_color_001.css" />
        <link rel="stylesheet" type="text/css" href="/Public/css/sp/sports_pad_mq.css" />
        <script src="/Public/js/jquery.js"></script>
        <script src="/Public/js/global.js"></script>
    </head>
    <body>
        <!--游戏导航-->
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
                <!--a class="logo" href="__APP__/Main"></a-->
                <?php
if(session('username')){ ?>
<a class="haslogin"  href="javascript:void(0);"><span></span><strong id="dqje"><?php echo ($money["Money"]); ?></strong></a>
<?php }?>
            </div>

            <div id="div_topNav" class="nav2 relative clear" style="">
                <div>
                    <a href="__APP__/Sport/slist"><div class="icon-back"></div></a>
                </div>
                <div class="nav2-grid" data-bind="attr: {class: tournSelect() ? 'nav2-grid02' : 'nav2-grid'}">
                    <div>
                        <div class="nav2-title" data-bind="text: Nav"><?php echo ($sport["MB_Team"]); ?> vs <?php echo ($sport["TG_Team"]); ?></div>
                    </div>
                </div>
            </div>
            <div class="inplay-content <?php if(strtoupper($sport['Type'])=='FT'){echo 'bg-00001';}else{echo 'bg-00002';} ?>" id="div_inPlay" style="">
                <div id="div_LS_00002_4" class="live-wrap <?php if(strtoupper($sport['Type'])=='FT'){echo 'bg-00001';}else{echo 'bg-00002';} ?>" style="">
                    <div class="stage">
                        <div class="game-status">
                            <time><?php echo ($sport["M_Start"]); ?></time>
                            <div class="game"><?php echo ($sport["M_League"]); ?></div>
                        </div>
                        <div class="live-main-wrap">
                            <div class="score-wrap">                
                                <div class="live-score">
                                    <div class="score left"><?php echo $sport['RB_Show']?$sport['MB_Ball']:'-'; ?></div>
                                    <div class="score-mid">
                                        VS
                                        <div class="icon-live"><?php echo $sport['RB_Show']?'LIVE':'未开始'; ?></div>
                                    </div>
                                    <div class="score right"><?php echo $sport['RB_Show']?$sport['TG_Ball']:'-'; ?></div>
                                </div>
                            </div>
                           <div class="gamename"><span class="icon-live-6"></span>
                            <time class="time" sc="00002" dt=" 中场休息" mst="中场休息" stop="false">
                            <?php
 if($sport['RB_Show']==0){ echo '未开始'; }else{ $timeset=explode('^',$sport['now_play']); if($timeset[0]==""){ echo '-'; }elseif($timeset[0]=="Start"){ echo '-'; }elseif(strpos($timeset[0],"MT")!==false){ echo $timeset[1]; }elseif(strpos($sport['now_play'],"HT")!==false){ echo "半场"; }else{ if(strpos($timeset[0],"1H")!==false){ echo '上半场'.$timeset[1].'\''; } if(strpos($timeset[0],"2H")!==false){ echo '下半场'.$timeset[1].'\''; } if(strpos($timeset[0],"Q1")!==false){ $fen = substr('00'.floor($timeset[1]/60),-2); $miao = substr('00'.($timeset[1]%60),-2); echo "第一节 ".$fen.' '.miao; } if(strpos($timeset[0],"Q2")!==false){ $fen = substr('00'.floor($timeset[1]/60),-2); $miao = substr('00'.($timeset[1]%60),-2); echo "第二节 ".$fen.' '.miao; } if(strpos($timeset[0],"Q3")!==false){ $fen = substr('00'.floor($timeset[1]/60),-2); $miao = substr('00'.($timeset[1]%60),-2); echo "第三节 ".$fen.' '.miao; } if(strpos($timeset[0],"Q4")!==false){ $fen = substr('00'.floor($timeset[1]/60),-2); $miao = substr('00'.($timeset[1]%60),-2); echo "第四节 ".$fen.' '.miao; } } } ?>
                                        
                            </time>
                           </div>
                            <?php
 if(strtoupper($sport['Type'])=='FT'){ ?>
                            <div class="live-point column_4 clear">
                                <div class="mid-wrap clear">
                                    <ul class="one">
                                        <li></li>
                                        <li>
                                            <div>主队</div>
                                        </li>
                                        <li>
                                            <div>客队</div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><span class="icon-live-1"></span></li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><span class="icon-live-2"></span></li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><span class="icon-live-3"></span></li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['MB_Card']:'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['TG_Card']:'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><div>总分</div></li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['MB_Ball']:'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['TG_Ball']:'-'; ?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php
 } else{ ?>
                            <div class="live-point column_6 clear">
                                <div class="mid-wrap clear">
                                    <ul class="one">
                                        <li></li>
                                        <li>
                                            <div>主队</div>
                                        </li>
                                        <li>
                                            <div>客队</div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>1</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>2</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>半场</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>3</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li>4</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?'-':'-'; ?></div>
                                        </li>
                                        <li><?php echo $sport['RB_Show']?'-':'-'; ?></li>
                                    </ul>
                                    <ul>
                                        <li>总分</li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['MB_Ball']:'-'; ?></div>
                                        </li>
                                        <li>
                                            <div><?php echo $sport['RB_Show']?$sport['TG_Ball']:'-'; ?></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php
 } ?>
                        </div>
                    </div>
                </div>
                <?php
 if(strtoupper($sport['Type'])=='FT' && $sport['S_Show']){ ?>
                <section class="topic" id="sec_BetEveCon">
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB']!=''||$sport['TG_LetB']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_001" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/R/type/H/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB'] != ''): echo ($sport["MG_LetB"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/R/type/C/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB'] != ''): echo ($sport["TG_LetB"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB_H']!=''||$sport['TG_LetB_H']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HR/type/H/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate_H"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB_H'] != ''): echo ($sport["MG_LetB_H"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HR/type/C/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate_H"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB_H'] != ''): echo ($sport["TG_LetB_H"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Dime']!=''||$sport['TG_Dime']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/OU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/OU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if(!empty($sport['S_Single_Rate'])&&!empty($sport['S_Double_Rate'])){ ?>	    
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">单/双</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/EO/odd_f_type/H/rtype/ODD/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">单</span><del class="redtxt right"><?php echo ($sport["S_Single_Rate"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/EO/rtype/EVEN/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">双</span><del class="redtxt right"><?php echo ($sport["S_Double_Rate"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Dime_Rate_H']!=''||$sport['TG_Dime_Rate_H']!=''){ ?>	 
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_004" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">大/小 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HOU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate_H"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime_H"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HOU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate_H"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime_H"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Win_Rate']!=''||$sport['TG_Win_Rate']!=''){ ?>	
                    <div class="pre-match-wrap clear open template1" id="div_content_005" style="display:block;">
                        <header class="header-date clear">
                            <h2 class="one">独赢</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/M/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_Win_Rate"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/M/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_Win_Rate"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/M/type/N/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和局</span><del class="redtxt right"><?php echo ($sport["M_Flat_Rate"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Win_Rate_H']!=''||$sport['TG_Win_Rate_H']!=''){ ?>	
                    <div class="pre-match-wrap clear open template1" id="div_content_006" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">独赢 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HM/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_Win_Rate_H"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HM/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_Win_Rate_H"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HM/type/N/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和局</span><del class="redtxt right"><?php echo ($sport["M_Flat_Rate_H"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>

                    <?php
 foreach($sports as $sport){ if($sport['PD_Show']==1&&$sport['UP5']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">波胆</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H1C0/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">1：0</span><del class="redtxt right"><?php echo ($sport["MB1TG0"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H0C1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0：1</span><del class="redtxt right"><?php echo ($sport["MB0TG1"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H2C0/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2：0</span><del class="redtxt right"><?php echo ($sport["MB2TG0"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H0C2/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0：2</span><del class="redtxt right"><?php echo ($sport["MB0TG2"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H3C0/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">3：0</span><del class="redtxt right"><?php echo ($sport["MB3TG0"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H0C3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0：3</span><del class="redtxt right"><?php echo ($sport["MB0TG3"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H4C0/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4：0</span><del class="redtxt right"><?php echo ($sport["MB4TG0"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H0C4/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0：4</span><del class="redtxt right"><?php echo ($sport["MB0TG4"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H2C1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2：1</span><del class="redtxt right"><?php echo ($sport["MB2TG1"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                 <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H1C2/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">1：2</span><del class="redtxt right"><?php echo ($sport["MB1TG2"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H3C1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">3：1</span><del class="redtxt right"><?php echo ($sport["MB3TG1"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H1C3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">1：3</span><del class="redtxt right"><?php echo ($sport["MB1TG3"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H3C2/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">3：2</span><del class="redtxt right"><?php echo ($sport["MB3TG2"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H2C3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2：3</span><del class="redtxt right"><?php echo ($sport["MB2TG3"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H4C1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4：1</span><del class="redtxt right"><?php echo ($sport["MB4TG1"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H1C4/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">1：4</span><del class="redtxt right"><?php echo ($sport["MB1TG4"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H4C2/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4：2</span><del class="redtxt right"><?php echo ($sport["MB4TG2"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H2C4/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2：4</span><del class="redtxt right"><?php echo ($sport["MB2TG4"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H4C3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4：3</span><del class="redtxt right"><?php echo ($sport["MB4TG3"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H3C4/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">3：4</span><del class="redtxt right"><?php echo ($sport["MB3TG4"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H0C0/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0：0</span><del class="redtxt right"><?php echo ($sport["MB0TG0"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H1C1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">1：1</span><del class="redtxt right"><?php echo ($sport["MB1TG1"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H2C2/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2：2</span><del class="redtxt right"><?php echo ($sport["MB2TG2"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H3C3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">3：3</span><del class="redtxt right"><?php echo ($sport["MB3TG3"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/H4C4/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4：4</span><del class="redtxt right"><?php echo ($sport["MB4TG4"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/PD/type/OVH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">其它</span><del class="redtxt right"><?php echo ($sport["UP5"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>

                    <?php
 foreach($sports as $sport){ if($sport['T_Show']==1&&$sport['S_0_1']!=''&&$sport['S_2_3']!=''&&$sport['S_4_6']!=''&&$sport['S_7UP']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">总入球</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/T/rtype/0~1/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">0~1</span><del class="redtxt right"><?php echo ($sport["S_0_1"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/T/rtype/2~3/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">2~3</span><del class="redtxt right"><?php echo ($sport["S_2_3"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/T/rtype/4~6/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">4~6</span><del class="redtxt right"><?php echo ($sport["S_4_6"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/T/rtype/OVER/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">7UP</span><del class="redtxt right"><?php echo ($sport["S_7UP"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>

                    <?php
 foreach($sports as $sport){ if($sport['F_Show']==1&&$sport['MBMB']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">半全场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FHH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">主 / 主</span><del class="redtxt right"><?php echo ($sport["MBMB"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FHN/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">主 / 和</span><del class="redtxt right"><?php echo ($sport["MBFT"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FHC/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">主 / 客</span><del class="redtxt right"><?php echo ($sport["MBTG"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FNH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和 / 主</span><del class="redtxt right"><?php echo ($sport["FTMB"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FNN/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和 / 和</span><del class="redtxt right"><?php echo ($sport["FTFT"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FNC/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和 / 客</span><del class="redtxt right"><?php echo ($sport["FTTG"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FCH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">客 / 主</span><del class="redtxt right"><?php echo ($sport["TGMB"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FCN/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">客 / 和</span><del class="redtxt right"><?php echo ($sport["TGFT"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/F/rtype/FCC/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">客 / 客</span><del class="redtxt right"><?php echo ($sport["TGTG"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                </section>
                <?php
 } elseif(strtoupper($sport['Type'])=='BK' && $sport['S_Show']){ ?>
                <section class="topic" id="sec_BetEveCon">
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB']!=''||$sport['TG_LetB']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_001" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/R/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB'] != ''): echo ($sport["MG_LetB"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/R/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB'] != ''): echo ($sport["TG_LetB"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Dime_Rate']!=''||$sport['TG_Dime_Rate']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">总分：大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Win_Rate']!=''||$sport['TG_Win_Rate']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">独赢</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/M/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_Win_Rate"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/M/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_Win_Rate"]); ?></del><i class="hl-txt right"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['S_Single_Rate']!=''||$sport['S_Double_Rate']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">总分：单/双</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/EO/rtype/ODD/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">单</span><del class="redtxt right"><?php echo ($sport["S_Single_Rate"]); ?></del><i class="hl-txt right">单</i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/EO/rtype/EVEN/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">双</span><del class="redtxt right"><?php echo ($sport["S_Double_Rate"]); ?></del><i class="hl-txt right">双</i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['ior_OUHU']!=''&&$sport['ior_OUHO']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one"><?php echo ($sport["MB_Team"]); ?>得分：大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OUH/type/O/wtype/OUH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["ior_OUHO"]); ?></del><i class="hl-txt right"><?php echo ($sport["ratio_ouho"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OUH/type/U/wtype/OUH/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["ior_OUHU"]); ?></del><i class="hl-txt right"><?php echo ($sport["ratio_ouhu"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['ior_OUCO']!=''&&$sport['ior_OUCU']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one"><?php echo ($sport["TG_Team"]); ?>得分：大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OUH/type/O/wtype/OUC/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["ior_OUCO"]); ?></del><i class="hl-txt right"><?php echo ($sport["ratio_ouco"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/OUH/type/U/wtype/OUC/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["ior_OUCU"]); ?></del><i class="hl-txt right"><?php echo ($sport["ratio_oucu"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                </section>
                <?php
 } if(strtoupper($sport['Type'])=='FT' && $sport['RB_Show']){ ?>
                <section class="topic" id="sec_BetEveCon">
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB_RB']!=''||$sport['TG_LetB_RB']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_001" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/RE/type/H/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate_RB"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB_RB'] != ''): echo ($sport["MG_LetB_RB"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/RE/type/C/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate_RB"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB_RB'] != ''): echo ($sport["TG_LetB_RB"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB_RB_H']!=''||$sport['TG_LetB_RB_H']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HRE/type/H/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate_RB_H"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB_RB_H'] != ''): echo ($sport["MG_LetB_RB_H"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HRE/type/C/strong/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate_RB_H"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB_RB_H'] != ''): echo ($sport["TG_LetB_RB_H"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                    <?php
 foreach($sports as $sport){ if($sport['MB_Dime_RB']!=''||$sport['TG_Dime_RB']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_003" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/ROU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate_RB"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime_RB"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/ROU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate_RB"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime_RB"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} foreach($sports as $sport){ if($sport['MB_Dime_RB_H']!=''||$sport['TG_Dime_RB_H']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_004" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">大/小 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HROU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate_RB_H"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime_RB_H"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HROU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate_RB_H"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime_RB_H"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} foreach($sports as $sport){ if($sport['MB_Win_Rate_RB']!=''||$sport['TG_Win_Rate_RB']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_005" style="display:block;">
                        <header class="header-date clear">
                            <h2 class="one">独赢</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/RM/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_Win_Rate_RB"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/RM/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_Win_Rate_RB"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/RM/type/N/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和局</span><del class="redtxt right"><?php echo ($sport["M_Flat_Rate_RB"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} foreach($sports as $sport){ if($sport['MB_Win_Rate_RB_H']!=''||$sport['TG_Win_Rate_RB_H']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_006" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">独赢 - 上半场</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HRM/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_Win_Rate_RB_H"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HRM/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_Win_Rate_RB_H"]); ?></del></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Ft/rate/t/HRM/type/N/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">和局</span><del class="redtxt right"><?php echo ($sport["M_Flat_Rate_RB_H"]); ?></del></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                </section>
                <?php
 } if(strtoupper($sport['Type'])=='BK' && $sport['RB_Show']){ ?>
                <section class="topic" id="sec_BetEveCon">
                    <?php
 foreach($sports as $sport){ if($sport['MG_LetB_RB']!=''||$sport['TG_LetB_RB']!=''){ ?>
                    <div class="pre-match-wrap clear open template1" id="div_content_001" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">让球</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="one-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/RE/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["MB_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["MB_LetB_Rate_RB"]); ?></del><i class="hl-txt right"><?php if($sport['MG_LetB_RB'] != ''): echo ($sport["MG_LetB_RB"]); endif; ?></i></a>
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/RE/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word"><?php echo ($sport["TG_Team"]); ?></span><del class="redtxt right"><?php echo ($sport["TG_LetB_Rate_RB"]); ?></del><i class="hl-txt right"><?php if($sport['TG_LetB_RB'] != ''): echo ($sport["TG_LetB_RB"]); endif; ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <?php
 }} foreach($sports as $sport){ if($sport['MB_Dime_Rate_RB']!=''||$sport['TG_Dime_Rate_RB']!=''){ ?>
                    <div class="pre-match-wrap style4 clear open template2" id="div_content_002" style="display: block;">
                        <header class="header-date clear">
                            <h2 class="one">总分：大/小</h2>
                            <div class="toggle-icon"></div>
                        </header>
                        <div class="two-column">
                            <ul class="clear sys-template">
                                <li>
                                    <div class="click-wrap">
                                        <a class="click" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/ROU/type/C/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">大</span><del class="redtxt right"><?php echo ($sport["MB_Dime_Rate_RB"]); ?></del><i class="hl-txt right"><?php echo ($sport["MB_Dime_RB"]); ?></i></a>
                                    </div>
                                </li>
                                <li>
                                    <div class="click-wrap">
                                        <a class="click odds" href="javascript:void(0);" onclick="javascript:showOrderWin('__APP__/Bk/rate/t/ROU/type/H/gid/<?php echo ($sport["MID"]); ?>');"><span class="word">小</span><del class="redtxt right"><?php echo ($sport["TG_Dime_Rate_RB"]); ?></del><i class="hl-txt right"><?php echo ($sport["TG_Dime_RB"]); ?></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php
 }} ?>
                </section>
                <?php
 } ?>
            </div>
            <!-- 交易单 -->
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
            <a id="goTop" href="">刷新</a>
        </div>
    </body>
<!--     <script src="/javascripts/application.js" type="text/javascript" charset="utf-8" async defer></script> -->
<!-- <script type="text/javascript" charset="utf-8">
    $("#winTit span").onclick=function(){
        alert(11111);
         $("#accountNavBtn").html($(".left strong").html());
         console.log($(".left strong").html());
    }

</html>