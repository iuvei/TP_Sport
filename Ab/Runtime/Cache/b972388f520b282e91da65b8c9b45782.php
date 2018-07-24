<?php if (!defined('THINK_PATH')) exit();?><div id="topContener">
<div id="winTit"><dl><dt>交易成功</dt></dl><span onclick='hide();'>×</span></div>
<form name="orderForm" id="orderForm" onsubmit="return false;">
    <div class="order-info">
        <h2>单号：<?php echo ($ft_ouid); ?></h2>
        <?php echo ($s_sleague); ?>
        <br /><?php echo ($s_mb_team); ?> <strong><?php echo ($Sign); ?></strong> <?php echo ($s_tg_team); ?>
        <br /><?php echo ($s_m_place); ?> @ <strong><?php echo ($w_m_rate); ?></strong>
    </div>
    <div class="bet-info">
        <dl>
            <dt>交易金额：<?php echo ($gold); ?></dt>
        </dl>
        <dl>
            <dt>可赢：<strong><?php echo ($gwin); ?></strong></dt>
        </dl>
        <div class="left">账号余额： <strong id="ymoney"><?php echo ($havemoney); ?></strong></div>
    </div>
    <input type="button" value="交易成功" name="betBtn" id="betBtn" class="bet-btn"/>
</form>
</div>
<script>
    parent.document.getElementById("dqje").innerHTML=document.getElementById("ymoney").innerHTML;
    parent.document.getElementById("orderWin").innerHTML=document.getElementById("topContener").innerHTML;
</script>