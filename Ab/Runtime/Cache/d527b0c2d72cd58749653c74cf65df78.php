<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes" />
        <base target="_top" />
        <title>Sports</title>
        <link href="/Public/css/gamenav.css" rel="stylesheet" />
        <script src="/Public/js/jquery.js"></script>
    </head>
    <body>
<div id="gameNavMask"></div>
        <div class="game-nav">
            <h1>导航栏</h1>
            <ul id="gameList">
             
                <li><a href="__APP__/Sport/slist"><i class="ico-home"></i><span>首页</span></a></li>
                <li><a href="__APP__/Sport/slist/tt/FT"><i class="ico-002"></i><span>足球（单式）</span><em></em><b><?php echo ($ft['S_Show']); ?></b></a></li>
                <li><a href="__APP__/Sport/slist/tt/FT/sr/1"><i class="ico-001"></i><span>足球（滚球）</span><em></em><b><?php echo ($ft['RB_Show']); ?></b></a></li>
                <li><a href="__APP__/Sport/slist/tt/BK"><i class="ico-003"></i><span>篮球（单式）</span><em></em><b><?php echo ($bk['S_Show']); ?></b></a></li>
                <li><a href="__APP__/Sport/slist/tt/BK/sr/1"><i class="ico-003"></i><span>篮球（滚球）</span><em></em><b><?php echo ($bk['RB_Show']); ?></b></a></li>
                <?php
 if(!session('type')){ ?>
                <li><a href="<?=$referrer_url?>"><i class="ico-008"></i><span>彩票游戏</span><em></em></a></li>
                <?php
 } ?>
            </ul>
        </div>
       
        </div>
        <script>
            $(document).ready(function (){$("#gameNavMask").css({height: $(window).height()});});
        </script>
    </body>
</html>