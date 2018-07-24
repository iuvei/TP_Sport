<?php if (!defined('THINK_PATH')) exit();?><div id="winTit"><em id="Countdown">10</em><dl><dt>交易单</dt><dd>目前余额:<?php echo ($credit); ?></dd></dl><span onclick='hide();'>×</span></div>
<form name="orderForm" id="orderForm" method="post" action="/index.php/Bet/Ft_bet" target="betorderform">
    <div class="order-info">
        <h2>足球[上半] 大小</h2>
        <?php echo ($M_League); ?>
        <br /><?php echo ($MB_Team); ?> <strong><?php if($Sign != ''): echo ($Sign); else: ?> V.S.<?php endif; ?></strong> <?php echo ($TG_Team); ?>
        <br /><?php echo (str_replace(array('O','U'),array('大','小'),$M_Place)); ?> @ <strong><?php echo ($M_Rate); ?></strong>
    </div>
    <div class="bet-info">
        <dl>
            <dt><input type="text" name="gold" id="placeholder" value="" pattern="[0-9]*" placeholder="投注额" /></dt>
            <dd>可赢金额:<br /><strong>0</strong></dd>
        </dl><div class="left">单注最低: <strong><?php echo ($GMIN_SINGLE); ?></strong></div>
        <div><div>单注最高: <strong>500000</strong></div><label class="right">自动接受较佳赔率<input type="checkbox" value="" class="order" checked /></label></div>
    </div>
    <input type="button" value="确认交易" name="betBtn" id="betBtn" class="bet-btn" onclick="bet();" />
    <input type="hidden" name="uid" value="<?php echo ($uid); ?>" />
    <input type="hidden" name="active" value="<?php echo ($active); ?>">
    <input type="hidden" name="line_type" value="13">
    <input type="hidden" name="gid" value="<?php echo ($gid); ?>">
    <input type="hidden" name="type" value="<?php echo ($type); ?>">
    <input type="hidden" name="gnum" value="<?php echo ($gnum); ?>">
    <input type="hidden" name="concede_h" value="1">
    <input type="hidden" name="radio_h" value="0">
    <input type="hidden" id="ioradio_r_h" name="ioradio_r_h" value="<?php echo ($M_Rate); ?>">
    <input type="hidden" name="gmax_single" value="<?php echo ($bettop_money); ?>">
    <input type="hidden" name="gmin_single" value="<?php echo ($GMIN_SINGLE); ?>">
    <input type="hidden" name="singlecredit" value="<?php echo ($GMAX_SINGLE); ?>">
    <input type="hidden" name="singleorder" value="<?php echo ($GSINGLE_CREDIT); ?>">
    <input type="hidden" name="restsinglecredit" id="restsinglecredit" value="<?php echo ($have_bet); ?>">
    <input type="hidden" name="wagerstotal" value="<?php echo ($GMAX_SINGLE); ?>">
    <input type="hidden" name="restcredit" id="restcredit" value="<?php echo ($credit); ?>">
    <input type="hidden" name="pay_type" value="<?php echo ($pay_type); ?>">
    <input type="hidden" name="odd_f_type" value="H">
</form>
<iframe name="betorderform" id="betorderform" style=" margin: 0px; padding: 0px; display: none;"></iframe>