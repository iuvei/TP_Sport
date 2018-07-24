<?php

// 本类由系统自动生成，仅供测试用途
class BetAction extends HloginAction
{

    /**
     * @todo 显示帐号明细
     * @param null
     */
    private $_host;
    protected $max_bet;

    public function __construct()
    {
        parent::__construct();
        $this->_host = $_SERVER['SERVER_NAME'];
        $this->max_bet = 500000;
    }

    public function index()
    {

    }

    public function chkmax_money($money){
        if($money>$this->max_bet){
            $this->alert_msg('单注最高下注金额500000');
        }
    }

    public function alert_msg($msg, $code='')
    {

        if (empty($msg)) {
            $msg = '赛事关闭。';
        }
        echo '<script>alert("' . $msg . ' ' . $code . '");try{clearTimeout(odtimeout);clearTimeout(tentime);}catch(e){};</script>';
        exit;
    }

    public function Ft_re_bet()
    {
        $this->assign('bak_url', '/index.php/Ft/team/g/' . $_SESSION['_step']['team']);
        if (!$this->chk_bet()) {

        }
        $Draw = '和局';
        $model = new Model();
        $mb_team = 'MB_Team';
        $tg_team = 'TG_Team';
        $m_league = 'M_League';
        $bettype = 'BetType';
        $middle = 'Middle';
        $message = 'Message';
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = (int)$_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $ioradio_r_h = $_REQUEST['ioradio_r_h'];
        $gold = (int)$_REQUEST['gold'];
        $active = $_REQUEST['active'];
        $line = $_REQUEST['line_type'];
        $restcredit = $_REQUEST['restcredit'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $memrow = current($result);
        $open = $memrow['OpenType'];
        $pay_type = $memrow['Pay_Type'];
        $memname = $memrow['UserName'];
        $agents = $memrow['Agents'];
        $world = $memrow['World'];
        $corprator = $memrow['Corprator'];
        $super = $memrow['Super'];
        $admin = $memrow['Admin'];
        $w_ratio = $memrow['ratio'];
        $HMoney = $memrow['Money'];
        $low_odds = $memrow['low_odds'];
        $this->assign('money', array('Money' => $HMoney, 'UserName' => $_SESSION['username']));

        if ($HMoney < $gold) {
            $this->alert_msg('余额不足。');
        }
        if ($gold < 10) {
            $this->alert_msg('请输入正确的金额。');
        }
        $this->chkmax_money($gold);//最高限额500000

        $w_current = $memrow['CurType'];
        $havemoney = $HMoney - $gold;
        $memid = $memrow['ID'];

        $mysql = "select datasite,uid from web_system_data where id=1";
        $result = $model->query($mysql);
        $row = current($result);
        $site = $row['datasite'];
        $suid = $row['uid'];
        import('@.Util.Team.Curlhttp');
        $curl = &new Curlhttp();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        $curl->set_referrer("" . $site . "/app/member/FT_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
        switch ($line) {
            case '10':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                break;
            case '9':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                break;
            case '21':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_rm.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                break;
        }
        preg_match("/name=\"gold\"/Usi", $html_data, $m_temp);
        if (!$m_temp) {
            $this->alert_msg('赛事关闭','1000');
        }
        $mysql = "select * from `match_sports` where Type='FT' and `MID`='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Inball='' and TG_Inball=''";
        $result = $model->query($mysql);
        $cou = count($result);
        $row = current($result);
        if ($cou == 0) {
            $this->alert_msg('赛事关闭','1001');
        }
        $test1 = date('h:ia', strtotime($row['M_Start']));
        $test2 = $row['M_Time'] . 'm';
        $w_tg_team = $row['TG_Team'];
        $w_tg_team_tw = $row['TG_Team_tw'];
        $w_mb_team = $row['MB_Team'];
        $w_mb_team_tw = $row['MB_Team_tw'];
        $now_play = $row['now_play'];
        if ($now_play) {
            $type1 = explode('||', $now_play);
            $type2 = $type1[1];
        }

        $w_mb_team = filiter_team(trim($row['MB_Team']));
        $w_tg_team = filiter_team(trim($row['TG_Team']));
        $w_mb_team_tw = filiter_team(trim($row['MB_Team_tw']));
        $w_tg_team_tw = filiter_team(trim($row['TG_Team_tw']));
        $s_mb_team = filiter_team($row[$mb_team]);
        $s_tg_team = filiter_team($row[$tg_team]);
        $m_date = $row["M_Date"];
        $showtype = $row["ShowTypeRB"];
        $bettime = date('Y-m-d H:i:s');
        $m_start = strtotime($row['M_Start']);
        $datetime = time();
        if ($datetime - $m_start < 120) {
            $this->alert_msg();
        }
        if ($row[$m_sleague] == '') {
            $w_sleague = $row['M_League'];
            $w_sleague_tw = $row['M_League_tw'];
            $s_sleague = $row[$m_league];
        }

        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        $inball1 = $inball;
        $mb_ball = $row['MB_Ball'];
        $tg_ball = $row['TG_Ball'];
        switch ($line) {
            case 21:
                $bet_type = '滚球独赢';
                $bet_type_tw = '滾球獨贏';
                $caption = $Order_FT . $Order_Running_1_x_2_betting_order;
                $turn_rate = "FT_Turn_M";
                $turn = "FT_Turn_M";
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = num_rate($open, $row["MB_Win_Rate_RB"]);
                        $w_gtype = 'RMH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = num_rate($open, $row["TG_Win_Rate_RB"]);
                        $w_gtype = 'RMC';
                        break;
                    case "N":
                        $w_m_place = "和局";
                        $w_m_place_tw = "和局";
                        $s_m_place = $Draw;
                        $w_m_rate = num_rate($open, $row["M_Flat_Rate_RB"]);
                        $w_gtype = 'RMN';
                        break;
                }
                $Sign = "VS.";
                $grape = $type;
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'RM';
                break;
            case 9:
                $bet_type = '滚球让球';
                $bet_type_tw = "滾球讓球";
                $caption = $Order_FT . $Order_Running_Ball_betting_order;
                $turn_rate = "FT_Turn_RE_" . $open;
                $MB_LetB_Rate_RB = change_rate($open, $row["MB_LetB_Rate_RB"]);
                $TG_LetB_Rate_RB = change_rate($open, $row["TG_LetB_Rate_RB"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB, $TG_LetB_Rate_RB, 100);
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'RRH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'RRC';
                        break;
                }
                $Sign = $row['M_LetB_RB'];
                $grape = $Sign;
                if (strtoupper($showtype) == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;
                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                    $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                    $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;
                $turn = "FT_Turn_RE";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'RE';
                break;
            case 10:
                $bet_type = '滚球大小';
                $bet_type_tw = "滾球大小";
                $caption = $Order_FT . $Order_Running_Ball_Over_Under_betting_order;
                $turn_rate = "FT_Turn_OU_" . $open;
                $MB_Dime_Rate_RB = change_rate($open, $row["MB_Dime_Rate_RB"]);
                $TG_Dime_Rate_RB = change_rate($open, $row["TG_Dime_Rate_RB"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB, $TG_Dime_Rate_RB, 100);
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime_RB"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime_RB"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime_RB"];
                        $s_m_place = $row["MB_Dime_RB"];
                        $s_m_place_str = str_replace('O', '', $s_m_place);
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $peiqiu = str_replace('O', '', $row["MB_Dime_RB"]);
                        $peiqius = explode('/', $peiqiu);
                        $min = $peiqius[0] + 0;
                        if (($mb_ball + $tg_ball) > $min) {
                            $this->alert_msg();
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'ROUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime_RB"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime_RB"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime_RB"];
                        $s_m_place = $row["TG_Dime_RB"];
                        $s_m_place_str = str_replace('U', '', $s_m_place);
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'ROUC';
                        break;
                }
                //大小球盘口匹配 jason 2016-12-09 15:40 add
                preg_match('/<tt class=\"RedWord fatWord\">([0-9\.]{1,8}) \/ ([0-9\.]{1,8})<\/tt>/Usi', $html_data, $rates);
                if (empty($rates)) {
                    preg_match('/<tt class=\"RedWord fatWord\">([0-9\.]{1,8})<\/tt>/Usi', $html_data, $rates);
                }
                $s_m_place_str = str_replace(' ', '', str_replace('/', '', $s_m_place_str));
                $rates[0] = $rates[1] . $rates[2];
                if ($s_m_place_str != $rates[0]) {
                    $this->alert_msg('盘口变动请重新下注！');
                }
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "FT_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'ROU';
                break;
        }

        $this->line_type_check($line, $w_m_rate, $low_odds);
        if ($w_m_rate == '' or $grape == '') {
            $this->alert_msg('赛事关闭','1002');
        }
        if ($w_m_rate != $ioradio_r_h) {
            $this->alert_msg('赛事关闭','1003');
        }
        if ($s_m_place == '' or $w_m_place == '' or $w_m_place_tw == '') {
            $this->alert_msg('赛事关闭','1004');
        }
        if ($line == 9 or $line == 10) {
            $oddstype = $odd_f_type;
        } else {
            $oddstype = '';
        }
        $w_mb_mid = $row['MB_MID'];
        $w_tg_mid = $row['TG_MID'];

        $lines = $row['M_League'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team . "&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines = $lines . "<FONT color=#cc0000>$w_m_place</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";

        $lines_tw = $row['M_League_tw'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team_tw . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team_tw . "&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines_tw = $lines_tw . "<FONT color=#cc0000>$w_m_place_tw</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";

        $ip_addr = get_ip();
        $m_turn = $a_rate = $b_rate = $c_rate = $d_rate = $b_point = $c_point = $d_point = 0;
        $a_point = 100;
		
		//RB 拦截
		import('@.Util.RbFuntion');
		$RbFuntion = new RbFuntion();
		if($RbFuntion->is_intercept($gid,1,1))
		{
			$RB_sql = "INSERT INTO RB_intercept_order (`MID`,`Type`,`LineType`,`Mtype`,`M_Date`,`BetTime`,`BetScore`,`BetType`,`M_Place`,`M_Rate`,`M_Name`,`Gwin`,`ShowType`,`MB_MID`,`TG_MID`,`MB_Ball`,`TG_Ball`) VALUES ('$gid','FT1','$line','$w_gtype','$m_date','$bettime','$gold','$bet_type','$grape','$w_m_rate','$memname','$gwin','$showtype','$w_mb_mid','$w_tg_mid','$mb_ball','$tg_ball')";
			$model->execute($RB_sql);
			$this->alert_msg('赛事关闭','1005');
		}		

        $sql = "INSERT INTO web_report_data(QQ83068506,danger,MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,TurnRate,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Rate,B_Rate,C_Rate,D_Rate,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball,IsPhone,LastBetMoney,type) values ('$inball1','1','$gid','$active','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$m_turn','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_rate','$b_rate','$c_rate','$d_rate','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','FT','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball',1,'$HMoney','$type2')";
        $model->execute($sql);
        $ouid = $model->getLastInsID();
        if ($model->getDbError()) {
            $this->alert_msg("操作失败。");
        }
        $this->snap($ouid);

        $sql = "update web_member_data set Money='$havemoney' where UserName='$memname'";
        $model->query($sql);
        if ($model->getDbError()) {
            $this->alert_msg("操作失败。");
        }
        $_SESSION['bet_block'] = false;
        $this->assign('bak_url', '/index.php/Ft/team/g/' . $_SESSION['_step']['team']);
        $this->assign('ft_ouid', show_voucher($line, $ouid, $model));
        $this->assign('s_sleague', $s_sleague);
        $this->assign('bet_type', $bet_type);
        $this->assign('s_mb_team', $s_mb_team);
        $this->assign('Sign', $Sign);
        $this->assign('s_tg_team', $s_tg_team);
        $this->assign('s_m_place', $s_m_place);
        $this->assign('w_m_rate', $w_m_rate);
        $this->assign('gold', $gold);
        $this->assign('gwin', $gwin);
        $this->assign('havemoney', $havemoney);
        $this->display();
    }

    public function Ft_hre_bet()
    {
        $this->assign('bak_url', '/index.php/Ft/team/g/' . $_SESSION['_step']['team']);
        if (!$this->chk_bet()) {

        }
        $Draw = '和局';
        $model = new Model();
        $mb_team = 'MB_Team';
        $tg_team = 'TG_Team';
        $m_league = 'M_League';
        $bettype = 'BetType';
        $middle = 'Middle';
        $message = 'Message';
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = (int)$_REQUEST['gid'];
        $gnum = $_REQUEST['gnum'];
        $type = $_REQUEST['type'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = 'H';
        $ioradio_r_h = $_REQUEST['ioradio_r_h'];
        $gold = (int)$_REQUEST['gold'];
        $active = $_REQUEST['active'];
        $line = $_REQUEST['line_type'];
        $restcredit = $_REQUEST['restcredit'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $memrow = current($result);
        $open = $memrow['OpenType'];
        $pay_type = $memrow['Pay_Type'];
        $memname = $memrow['UserName'];
        $agents = $memrow['Agents'];
        $world = $memrow['World'];
        $corprator = $memrow['Corprator'];
        $super = $memrow['Super'];
        $admin = $memrow['Admin'];
        $w_ratio = $memrow['ratio'];
        $HMoney = $memrow['Money'];
        $low_odds = $memrow['low_odds'];
        $this->assign('money', array('Money' => $HMoney, 'UserName' => $_SESSION['username']));
        if ($HMoney < $gold) {
            $this->alert_msg("余额不足。");
        }
        if ($gold < 10) {
            $this->alert_msg("请输入正确的金额。");
        }
        $this->chkmax_money($gold);//最高限额500000
        
        $w_current = $memrow['CurType'];
        $havemoney = $HMoney - $gold;
        $memid = $memrow['ID'];

        $mysql = "select datasite,uid from web_system_data where id=1";
        $result = $model->query($mysql);
        $row = current($result);
        $site = $row['datasite'];
        $suid = $row['uid'];
        import('@.Util.Team.Curlhttp');
        $curl = &new Curlhttp();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        $curl->set_referrer("" . $site . "/app/member/FT_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
        switch ($line) {
            case '20':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_hrou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                break;
            case '19':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_hre.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                break;
            case '31':
                $html_data = $curl->fetch_url("" . $site . "/app/member/FT_order/FT_order_hrm.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                break;
        }
        preg_match("/name=\"gold\"/Usi", $html_data, $m_temp);
        if (!$m_temp) {
            $this->alert_msg();
        }
        $test1 = date('h:ia', strtotime($row['M_Start']));
        $test2 = $row['M_Time'] . 'm';
        $sgid = $gid - 1;
        $mysql = "select * from `match_sports` where Type='FT' and `MID`='$sgid' and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        $result = $model->query($mysql);
        $cou = count($result);
        $row = current($result);
        if ($cou == 0) {
            $this->alert_msg();
        }
        //主客队伍名称
        $w_tg_team = $row['TG_Team'];
        $w_tg_team_tw = $row['TG_Team_tw'];
        $w_mb_team = $row['MB_Team'];
        $w_mb_team_tw = $row['MB_Team_tw'];
        $now_play = $row['now_play'];
        if ($now_play) {
            $type1 = explode('||', $now_play);
            $type2 = $type1[1];
        }

        $w_mb_team = filiter_team(trim($row['MB_Team']));
        $w_tg_team = filiter_team(trim($row['TG_Team']));
        $w_mb_team_tw = filiter_team(trim($row['MB_Team_tw']));
        $w_tg_team_tw = filiter_team(trim($row['TG_Team_tw']));
        $s_mb_team = filiter_team($row[$mb_team]);
        $s_tg_team = filiter_team($row[$tg_team]);
        $m_date = $row["M_Date"];
        $showtype = $row["ShowTypeHRB"];
        $bettime = date('Y-m-d H:i:s');
        $m_start = strtotime($row['M_Start']);
        $datetime = time();
        if ($datetime - $m_start < 120) {
            $this->alert_msg();
        }
        //联盟
        if ($row[$m_sleague] == '') {
            $w_sleague = $row['M_League'];
            $w_sleague_tw = $row['M_League_tw'];
            $s_sleague = $row[$m_league];
        }

        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        $inball1 = $inball;
        $mb_ball = $row['MB_Ball'];
        $tg_ball = $row['TG_Ball'];
        switch ($line) {
            case 31:
                $bet_type = '半场滚球独赢';
                $bet_type_tw = "半場滾球獨贏";
                $btype = "-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption = $Order_FT . $Order_1st_Half_Running_1_x_2_betting_order;
                $turn_rate = "FT_Turn_M";
                $turn = "FT_Turn_M";
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $row[$mb_team];
                        $w_m_rate = num_rate($open, $row["MB_Win_Rate_RB_H"]);
                        $w_gtype = 'VRMH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $row[$tg_team];
                        $w_m_rate = num_rate($open, $row["TG_Win_Rate_RB_H"]);
                        $w_gtype = 'VRMC';
                        break;
                    case "N":
                        $w_m_place = "和局";
                        $w_m_place_tw = "和局";
                        $s_m_place = $Draw;
                        $w_m_rate = num_rate($open, $row["M_Flat_Rate_RB_H"]);
                        $w_gtype = 'VRMN';
                        break;
                }
                $Sign = "VS.";
                $grape = $type;
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'VRM';
                break;
            case 19:
                $bet_type = '半场滚球让球';
                $bet_type_tw = "半場滾球讓球";
                $btype = "-<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption = $Order_FT . $Order_1st_Half_Running_Ball_betting_order;
                $turn_rate = "FT_Turn_RE_" . $open;
                $MB_LetB_Rate_RB_H = change_rate($open, $row["MB_LetB_Rate_RB_H"]);
                $TG_LetB_Rate_RB_H = change_rate($open, $row["TG_LetB_Rate_RB_H"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB_H, $TG_LetB_Rate_RB_H, 100);
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'VRRH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'VRRC';
                        break;
                }

                $Sign = $row['M_LetB_RB_H'];
                $grape = $Sign;

                if (strtoupper($showtype) == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;
                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                    $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                    $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;
                $turn = "FT_Turn_RE";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'VRE';
                break;
            case 20:
                $bet_type = '半场滚球大小';
                $bet_type_tw = "半場滾球大小";
                $btype = "- <font color=red><b>[$Order_1st_Half]</b></font>";
                $caption = $Order_FT . $Order_1st_Half_Running_Ball_Over_Under_betting_order;
                $turn_rate = "FT_Turn_OU_" . $open;
                $MB_Dime_Rate_RB_H = change_rate($open, $row["MB_Dime_Rate_RB_H"]);
                $TG_Dime_Rate_RB_H = change_rate($open, $row["TG_Dime_Rate_RB_H"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB_H, $TG_Dime_Rate_RB_H, 100);
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime_RB_H"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime_RB_H"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime_RB_H"];
                        $s_m_place = $row["MB_Dime_RB_H"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $peiqiu = str_replace('O', '', $row["MB_Dime_RB_H"]);
                        $peiqius = explode('/', $peiqiu);
                        $min = $peiqius[0] + 0;
                        if (($mb_ball + $tg_ball) > $min) {
                            $this->alert_msg();
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'VROUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime_RB_H"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime_RB_H"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime_RB_H"];
                        $s_m_place = $row["TG_Dime_RB_H"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'VROUC';
                        break;
                }

                $Sign = "VS.";
                $grape = $m_place;
                $turn = "FT_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'VROU';
                break;
        }

        $this->line_type_check($line, $w_m_rate, $low_odds);
        if ($gold < 10) {
            $this->alert_msg();
        }

        if ($w_m_rate == '' or $grape == '') {
            $this->alert_msg();
        }
        if ($w_m_rate != $ioradio_r_h) {
            $this->alert_msg();
        }
        if ($s_m_place == '' or $w_m_place == '' or $w_m_place_tw == '') {
            $this->alert_msg();
        }
        if ($line == 19 or $line == 20) {
            $oddstype = $odd_f_type;
        } else {
            $oddstype = '';
        }

        $bottom1 = "&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $bottom1_tw = "&nbsp;-&nbsp;<font color=#666666>[上半]</font>";
        $w_mb_mid = $row['MB_MID'];
        $w_tg_mid = $row['TG_MID'];
        $lines = $row['M_League'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team . "&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines = $lines . "<FONT color=#cc0000>$w_m_place</FONT>" . $bottom1 . "&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";
        $lines_tw = $row['M_League_tw'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team_tw . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team_tw . "&nbsp;&nbsp;<FONT color=red><b>$inball</b></FONT><br>";
        $lines_tw = $lines_tw . "<FONT color=#cc0000>$w_m_place_tw</FONT>" . $bottom1_tw . "&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";
        $ip_addr = get_ip();
        $m_turn = $a_rate = $b_rate = $c_rate = $d_rate = $b_point = $c_point = $d_point = 0;
        $a_point = 100;
		//RB 拦截
		import('@.Util.RbFuntion');
		$RbFuntion = new RbFuntion();
		if($RbFuntion->is_intercept($sgid,1,1))
		{
			$RB_sql = "INSERT INTO RB_intercept_order (`MID`,`Type`,`LineType`,`Mtype`,`M_Date`,`BetTime`,`BetScore`,`BetType`,`M_Place`,`M_Rate`,`M_Name`,`Gwin`,`ShowType`,`MB_MID`,`TG_MID`,`MB_Ball`,`TG_Ball`) VALUES ('$sgid','FT1','$line','$w_gtype','$m_date','$bettime','$gold','$bet_type','$grape','$w_m_rate','$memname','$gwin','$showtype','$w_mb_mid','$w_tg_mid','$mb_ball','$tg_ball')";
			$model->execute($RB_sql);
			$this->alert_msg('赛事关闭','1005');
		}

        $sql = "INSERT INTO web_report_data(QQ83068506,danger,MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,TurnRate,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Rate,B_Rate,C_Rate,D_Rate,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball,IsPhone,LastBetMoney,type) values ('$inball1','1','$sgid','$active','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$m_turn','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_rate','$b_rate','$c_rate','$d_rate','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','FT','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball',1,'$HMoney','$type2')";
        $model->execute($sql);
        $ouid = $model->getLastInsID();
        $this->snap($ouid);
        $sql = "update web_member_data set Money='$havemoney' where UserName='$memname'";
        $model->execute($sql);
        $_SESSION['bet_block'] = false;
        $this->assign('ft_ouid', show_voucher($line, $ouid, $model));
        $this->assign('s_sleague', $s_sleague);
        $this->assign('bet_type', $bet_type);
        $this->assign('s_mb_team', $s_mb_team);
        $this->assign('Sign', $Sign);
        $this->assign('s_tg_team', $s_tg_team);
        $this->assign('s_m_place', $s_m_place);
        $this->assign('w_m_rate', $w_m_rate);
        $this->assign('gold', $gold);
        $this->assign('gwin', $gwin);
        $this->assign('havemoney', $havemoney);
        $this->display();
    }

    public function Ft_bet()
    {
        $this->assign('bak_url', '/index.php/Ft/team/g/' . $_SESSION['_step']['team']);
        if (!$this->chk_bet()) {

        }
        $Draw = '和局';
        $model = new Model();
        $mb_team = 'MB_Team';
        $tg_team = 'TG_Team';
        $m_league = 'M_League';
        $bettype = 'BetType';
        $middle = 'Middle';
        $message = 'Message';
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gold = (int)$_REQUEST['gold'];
        $active = $_REQUEST['active'];
        $strong = $_REQUEST['strong'];
        $line = $_REQUEST['line_type'];
        $gid = (int)$_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $rtype = $_REQUEST['rtype'];
        $gnum = $_REQUEST['gnum'];
        $ioradio_r_h = $_REQUEST['ioradio_r_h'];
        $ioradio_pd = $_REQUEST['ioradio_r_h'];
        $ioradio_f = $_REQUEST['ioradio_r_h'];
        $odd_f_type = 'H';
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $memrow = current($result);
        $open = $memrow['OpenType'];
        $pay_type = $memrow['Pay_Type'];
        $memname = $memrow['UserName'];
        $agents = $memrow['Agents'];
        $world = $memrow['World'];
        $corprator = $memrow['Corprator'];
        $super = $memrow['Super'];
        $admin = $memrow['Admin'];
        $w_ratio = $memrow['ratio'];
        $HMoney = $memrow['Money'];
        $low_odds = $memrow['low_odds'];
        $this->assign('money', array('Money' => $HMoney, 'UserName' => $_SESSION['username']));
        if ($HMoney < $gold) {
            $this->alert_msg('余额不足。');
        }
        if ($line == 4 && $gold < 50) { //波胆限制最低50元
            $this->alert_msg('波胆最低投注50元');
        } else if ($gold < 10) {
            $this->alert_msg('波胆最低投注50元');
        }
        $this->chkmax_money($gold);//最高限额500000
        
        $w_current = $memrow['CurType'];
        $havemoney = $HMoney - $gold;
        $memid = $memrow['ID'];

        $mysql = "select * from match_sports where Type IN ('FT','FU') and `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!='' "; //判断此赛程是否已经关闭：取出此场次信息
        $result = $model->query($mysql);
        $cou = count($result);
        $row = current($result);
        if ($cou == 0) {
            $this->alert_msg();
        }
        $test1 = date('h:ia', strtotime($row['M_Start']));
        $test2 = $row['M_Time'] . 'm';
        $w_tg_team = $row['TG_Team'];
        $w_tg_team_tw = $row['TG_Team_tw'];
        $w_mb_team = filiter_team(trim($row['MB_Team']));
        $w_mb_team_tw = filiter_team(trim($row['MB_Team_tw']));
        $w_mb_mid = $row['MB_MID'];
        $w_tg_mid = $row['TG_MID'];
        $s_mb_team = filiter_team($row[$mb_team]);
        $s_tg_team = filiter_team($row[$tg_team]);
        $s_sleague = $row[$m_league];
        $m_date = $row["M_Date"];
        $showtype = $row["ShowTypeR"];
        $bettime = date('Y-m-d H:i:s');
        switch ($line) {
            case 1:
                $bet_type = '独赢';
                $bet_type_tw = '獨贏';
                $caption = $Order_FT . $Order_1_x_2_betting_order;
                $turn_rate = "FT_Turn_M";
                $turn = "FT_Turn_M";
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = num_rate($open, $row["MB_Win_Rate"]);
                        $mtype = 'MH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = num_rate($open, $row["TG_Win_Rate"]);
                        $mtype = 'MC';
                        break;
                    case "N":
                        $w_m_place = "和局";
                        $w_m_place_tw = "和局";
                        $s_m_place = $Draw;
                        $w_m_rate = num_rate($open, $row["M_Flat_Rate"]);
                        $mtype = 'MN';
                        break;
                }
                $Sign = "VS.";
                $grape = "";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'M';
                break;
            case 2:
                $bet_type = '让球';
                $bet_type_tw = "讓球";
                $caption = $Order_FT . $Order_Handicap_betting_order;
                $turn_rate = "FT_Turn_R_" . $open;
                $MB_LetB_Rate = change_rate($open, $row["MB_LetB_Rate"]);
                $TG_LetB_Rate = change_rate($open, $row["TG_LetB_Rate"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate, $TG_LetB_Rate, 100);
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'RH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'RC';
                        break;
                }
                $Sign = $row['M_LetB'];
                $grape = $Sign;
                if ($showtype == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;
                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;

                $turn = "FT_Turn_R";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'R';
                break;
            case 3:
                $bet_type = '大小';
                $bet_type_tw = "大小";
                $caption = $Order_FT . $Order_Over_Under_betting_order;
                $turn_rate = "FT_Turn_OU_" . $open;
                $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate, $TG_Dime_Rate, 100);
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime"];
                        $s_m_place = $row["MB_Dime"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'OUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime"];
                        $s_m_place = $row["TG_Dime"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }

                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'OUC';
                        break;
                }
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "FT_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'OU';
                break;
            case 4:
                $bet_type = '波胆';
                $bet_type_tw = "波膽";
                $caption = $Order_FT . $Order_Correct_Score_betting_order;
                $turn_rate = "FT_Turn_PD";
                if ($rtype != 'OVH') {
                    $rtype = str_replace('C', 'TG', str_replace('H', 'MB', $rtype));
                    $w_m_rate = $row[$rtype];
                } else {
                    $w_m_rate = $row['UP5'];
                }
                if ($rtype == "OVH") {
                    $s_m_place = $Order_Other_Score;
                    $w_m_place = '其它比分';
                    $w_m_place_tw = '其它比分';
                    $Sign = "VS.";
                } else {
                    $M_Place = "";
                    $M_Sign = $rtype;
                    $M_Sign = str_replace("MB", "", $M_Sign);
                    $M_Sign = str_replace("TG", ":", $M_Sign);
                    $Sign = $M_Sign . "";
                }
                $grape = "";
                $turn = "FT_Turn_PD";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'PD';
                $mtype = $rtype;
                break;
            case 5:
                $bet_type = '单双';
                $bet_type_tw = "單雙";
                $caption = $Order_FT . $Order_Odd_Even_betting_order;
                $turn_rate = "FT_Turn_EO_" . $open;
                switch ($rtype) {
                    case "ODD":
                        $w_m_place = '单';
                        $w_m_place_tw = '單';
                        $s_m_place = '(' . $Order_Odd . ')';
                        $w_m_rate = num_rate($open, $row["S_Single_Rate"]);
                        break;
                    case "EVEN":
                        $w_m_place = '双';
                        $w_m_place_tw = '雙';
                        $s_m_place = '(' . $Order_Even . ')';
                        $w_m_rate = num_rate($open, $row["S_Double_Rate"]);
                        break;
                }
                $Sign = "VS.";
                $turn = "FT_Turn_EO";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'EO';
                $mtype = $rtype;
                break;
            case 6:
                $bet_type = '总入球';
                $bet_type_tw = "總入球";
                $caption = $Order_FT . $Order_Total_Goals_betting_order;
                $turn_rate = "FT_Turn_T";
                switch ($rtype) {
                    case "0~1":
                        $w_m_place = '0~1';
                        $w_m_place_tw = '0~1';
                        $s_m_place = '(0~1)';
                        $w_m_rate = $row["S_0_1"];
                        break;
                    case "2~3":
                        $w_m_place = '2~3';
                        $w_m_place_tw = '2~3';
                        $s_m_place = '(2~3)';
                        $w_m_rate = $row["S_2_3"];
                        break;
                    case "4~6":
                        $w_m_place = '4~6';
                        $w_m_place_tw = '4~6';
                        $s_m_place = '(4~6)';
                        $w_m_rate = $row["S_4_6"];
                        break;
                    case "OVER":
                        $w_m_place = '7up';
                        $w_m_place_tw = '7up';
                        $s_m_place = '(7up)';
                        $w_m_rate = $row["S_7UP"];
                        break;
                }
                $turn = "FT_Turn_T";
                $Sign = "VS.";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'T';
                $mtype = $rtype;
                break;
            case 7:
                $bet_type = '半全场';
                $bet_type_tw = "半全場";
                $caption = $Order_FT . $Order_Half_Full_Time_betting_order;
                $turn_rate = "FT_Turn_F";
                switch ($rtype) {
                    case "FHH":
                        $w_m_place = $w_mb_team . '&nbsp;/&nbsp;' . $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw . '&nbsp;/&nbsp;' . $w_mb_team_tw;
                        $s_m_place = $row[$mb_team] . '&nbsp;/&nbsp;' . $row[$mb_team];
                        $w_m_rate = $row["MBMB"];
                        break;
                    case "FHN":
                        $w_m_place = $w_mb_team . '&nbsp;/&nbsp;和局';
                        $w_m_place_tw = $w_mb_team_tw . '&nbsp;/&nbsp;和局';
                        $s_m_place = $row[$mb_team] . '&nbsp;/&nbsp;' . $Draw;
                        $w_m_rate = $row["MBFT"];
                        break;
                    case "FHC":
                        $w_m_place = $w_mb_team . '&nbsp;/&nbsp;' . $w_tg_team;
                        $w_m_place_tw = $w_mb_team_tw . '&nbsp;/&nbsp;' . $w_tg_team_tw;
                        $s_m_place = $row[$mb_team] . '&nbsp;/&nbsp;' . $row[$tg_team];
                        $w_m_rate = $row["MBTG"];
                        break;
                    case "FNH":
                        $w_m_place = '和局&nbsp;/&nbsp;' . $w_mb_team;
                        $w_m_place_tw = '和局&nbsp;/&nbsp;' . $w_mb_team_tw;
                        $s_m_place = $Draw . '&nbsp;/&nbsp;' . $row[$mb_team];
                        $w_m_rate = $row["FTMB"];
                        break;
                    case "FNN":
                        $w_m_place = '和局&nbsp;/&nbsp;和局';
                        $w_m_place_tw = '和局&nbsp;/&nbsp;和局';
                        $s_m_place = $Draw . '&nbsp;/&nbsp;' . $Draw;
                        $w_m_rate = $row["FTFT"];
                        break;
                    case "FNC":
                        $w_m_place = '和局&nbsp;/&nbsp;' . $w_tg_team;
                        $w_m_place_tw = '和局&nbsp;/&nbsp;' . $w_tg_team_tw;
                        $s_m_place = $Draw . '&nbsp;/&nbsp;' . $row[$tg_team];
                        $w_m_rate = $row["FTTG"];
                        break;
                    case "FCH":
                        $w_m_place = $w_tg_team . '&nbsp;/&nbsp;' . $w_mb_team;
                        $w_m_place_tw = $w_tg_team_tw . '&nbsp;/&nbsp;' . $w_mb_team_tw;
                        $s_m_place = $row[$tg_team] . '&nbsp;/&nbsp;' . $row[$mb_team];
                        $w_m_rate = $row["TGMB"];
                        break;
                    case "FCN":
                        $w_m_place = $w_tg_team . '&nbsp;/&nbsp;和局';
                        $w_m_place_tw = $w_tg_team_tw . '&nbsp;/&nbsp;和局';
                        $s_m_place = $row[$tg_team] . '&nbsp;/&nbsp;' . $Draw;
                        $w_m_rate = $row["TGFT"];
                        break;
                    case "FCC":
                        $w_m_place = $w_tg_team . '&nbsp;/&nbsp;' . $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw . '&nbsp;/&nbsp;' . $w_tg_team_tw;
                        $s_m_place = $row[$tg_team] . '&nbsp;/&nbsp;' . $row[$tg_team];
                        $w_m_rate = $row["TGTG"];
                        break;
                }
                $Sign = "VS.";
                $turn = "FT_Turn_F";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'F';
                $mtype = $rtype;
                break;
            case 11:
                $bet_type = '半场独赢';
                $bet_type_tw = "半場獨贏";
                $btype = "-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption = $Order_FT . $Order_1st_Half_1_x_2_betting_order;
                $turn_rate = "FT_Turn_M";
                $turn = "FT_Turn_M";
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $row[$mb_team];
                        $w_m_rate = num_rate($open, $row["MB_Win_Rate_H"]);
                        $mtype = 'VMH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $row[$tg_team];
                        $w_m_rate = num_rate($open, $row["TG_Win_Rate_H"]);
                        $mtype = 'VMC';
                        break;
                    case "N":
                        $w_m_place = "和局";
                        $w_m_place_tw = "和局";
                        $s_m_place = $Draw;
                        $w_m_rate = num_rate($open, $row["M_Flat_Rate_H"]);
                        $mtype = 'VMN';
                        break;
                }
                $Sign = "VS.";
                $grape = "";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'VM';
                break;
            case 12:
                $bet_type = '半场让球';
                $bet_type_tw = "半場讓球";
                $btype = "-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $caption = $Order_FT . $Order_1st_Half_Handicap_betting_order;
                $turn_rate = "FT_Turn_R_" . $open;
                $MB_LetB_Rate_H = change_rate($open, $row["MB_LetB_Rate_H"]);
                $TG_LetB_Rate_H = change_rate($open, $row["TG_LetB_Rate_H"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_H, $TG_LetB_Rate_H, 100);
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $row[$mb_team];
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'VRH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $row[$tg_team];
                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'VRC';
                        break;
                }
                $Sign = $row['M_LetB_H'];
                $grape = $Sign;
                if ($showtype == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;

                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;
                $turn = "FT_Turn_R";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'VR';
                break;
            case 13:
                $bet_type = '半场大小';
                $bet_type_tw = "半場大小";
                $caption = $Order_FT . $Order_1st_Half_Over_Under_betting_order;
                $btype = "-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $turn_rate = "FT_Turn_OU_" . $open;
                $MB_Dime_Rate_H = change_rate($open, $row["MB_Dime_Rate_H"]);
                $TG_Dime_Rate_H = change_rate($open, $row["TG_Dime_Rate_H"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_H, $TG_Dime_Rate_H, 100);
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime_H"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime_H"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime_H"];
                        $s_m_place = $row["MB_Dime_H"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'VOUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime_H"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime_H"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime_H"];
                        $s_m_place = $row["TG_Dime_H"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'VOUC';
                        break;
                }
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "FT_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'VOU';
                break;
            case 14:
                $bet_type = '半场波胆';
                $bet_type_tw = "半場波膽";
                $caption = $Order_FT . $Order_1st_Half_Correct_Score_betting_order;
                $btype = "-&nbsp;<font color=red><b>[$Order_1st_Half]</b></font>";
                $turn_rate = "FT_Turn_PD";
                if ($rtype != 'OVH') {
                    $rtype = str_replace('C', 'TG', str_replace('H', 'MB', $rtype));
                    $w_m_rate = $row[$rtype . H];
                } else {
                    $w_m_rate = $row['UP5H'];
                }
                if ($rtype == "OVH") {
                    $s_m_place = $Order_Other_Score;
                    $w_m_place = '其它比分';
                    $w_m_place_tw = '其它比分';
                    $Sign = "VS.";
                } else {
                    $M_Place = "";
                    $M_Sign = $rtype;
                    $M_Sign = str_replace("MB", "", $M_Sign);
                    $M_Sign = str_replace("TG", ":", $M_Sign);
                    $Sign = $M_Sign . "";
                }
                $grape = "";
                $turn = "FT_Turn_PD";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'VPD';
                $mtype = $rtype;
                break;
        }

        if ($line == 11 or $line == 12 or $line == 13 or $line == 14) {
            $bottom1_cn = "-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
            $bottom1_tw = "-&nbsp;<font color=#666666>[上半]</font>&nbsp;";
        }
        $this->line_type_check($line, $w_m_rate, $low_odds);

        if ($line == 2 or $line == 3 or $line == 12 or $line == 13) {
            if ($w_m_rate != $ioradio_r_h) {
                $this->alert_msg();
            }
            $oddstype = $odd_f_type;
        } else {
            $oddstype = '';
        }
        $s_m_place = filiter_team(trim($s_m_place));

        $w_mid = "<br>[" . $row['MB_MID'] . "]vs[" . $row['TG_MID'] . "]<br>";
        $lines = $row['M_League'] . $w_mid . $w_mb_team . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team . "<br>";
        $lines = $lines . "<FONT color=#cc0000>" . $w_m_place . "</FONT>&nbsp;" . $bottom1_cn . "@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";
        $lines_tw = $row['M_League_tw'] . $w_mid . $w_mb_team_tw . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team_tw . "<br>";
        $lines_tw = $lines_tw . "<FONT color=#cc0000>" . $w_m_place_tw . "</FONT>&nbsp;" . $bottom1_tw . "@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";
        $ip_addr = get_ip();
        $m_turn = $a_rate = $b_rate = $c_rate = $d_rate = $b_point = $c_point = $d_point = 0;
        $a_point = 100;

        $sql = "INSERT INTO web_report_data(MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,TurnRate,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Rate,B_Rate,C_Rate,D_Rate,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,MB_Ball,TG_Ball,IsPhone,LastBetMoney) values ('$gid','$active','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$m_turn','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_rate','$b_rate','$c_rate','$d_rate','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','FT','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$mb_ball','$tg_ball',1,'$HMoney')";
        $model->execute($sql);
        $ouid = $model->getLastInsID();
        $this->snap($ouid);
        $sql = "update web_member_data set Money='{$havemoney}' where UserName='{$memname}'";
        $model->execute($sql);

        if ($active == 11) {
            $caption = str_replace($Order_FT, $Order_FT . $Order_Early_Market, $caption);
        }
        $_SESSION['bet_block'] = false;
        $this->assign('ft_ouid', show_voucher($line, $ouid, $model));
        $this->assign('s_sleague', $s_sleague);
        $this->assign('bet_type', $bet_type);
        $this->assign('s_mb_team', $s_mb_team);
        $this->assign('Sign', $Sign);
        $this->assign('s_tg_team', $s_tg_team);
        $this->assign('s_m_place', $s_m_place);
        $this->assign('w_m_rate', $w_m_rate);
        $this->assign('gold', $gold);
        $this->assign('gwin', $gwin);
        $this->assign('havemoney', $havemoney);
        $this->display();
    }

    public function Bk_re_bet()
    {
        $this->assign('bak_url', '/index.php/Bk/team/g/' . $_SESSION['_step']['team']);
        if (!$this->chk_bet()) {

        }
        $Draw = '和局';
        $model = new Model();
        $mb_team = 'MB_Team';
        $tg_team = 'TG_Team';
        $m_league = 'M_League';
        $bettype = 'BetType';
        $middle = 'Middle';
        $message = 'Message';
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = (int)$_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = 'H';
        $ioradio_r_h = $_REQUEST['ioradio_r_h'];
        $gold = (int)$_REQUEST['gold'];
        $active = $_REQUEST['active'];
        $line = $_REQUEST['line_type'];
        $restcredit = $_REQUEST['restcredit'];
        $r_num = 0;
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $memrow = current($result);
        $open = $memrow['OpenType'];
        $pay_type = $memrow['Pay_Type'];
        $memname = $memrow['UserName'];
        $agents = $memrow['Agents'];
        $world = $memrow['World'];
        $corprator = $memrow['Corprator'];
        $super = $memrow['Super'];
        $admin = $memrow['Admin'];
        $w_ratio = $memrow['ratio'];
        $HMoney = $memrow['Money'];
        $low_odds = $memrow['low_odds'];
        $mem_id = $memrow['ID'];
        $bk_r34_limit = 0;
        //后台会员锁定篮球无法下注（第三、四节）
        $lock_sql = "select BK from web_member_data_lock where mem_id='$mem_id'";
        $lock_res = $model->query($lock_sql);
        if(count($lock_res) > 0){
            $bk_r34 = current($lock_res);
            $bk_r34_limit = $bk_r34['BK'];
        }
        
        $this->assign('money', array('Money' => $HMoney, 'UserName' => $_SESSION['username']));
        if ($HMoney < $gold) {
            $this->alert_msg('可用的余额不足。');
        }
        if ($gold < 10) {
            $this->alert_msg('请输入正确的金额。');
        }
        $this->chkmax_money($gold);//最高限额500000
        
        $w_current = $memrow['CurType'];
        $havemoney = $HMoney - $gold;
        $memid = $memrow['ID'];
        $checksql = "select id,BetTime from web_report_data_basketball where m_name = '{$memname}' and isturn=0";
        $checkres = $model->query($checksql);
        if (count($checkres) > 0) {
            $niao = current($checkres);
            if ((time() - strtotime($niao['BetTime'])) > 5) {
                $model->query("update web_report_data_basketball set isturn=-1 where id=" . $niao['id']);
            }
            $this->alert_msg('请稍后下注。');
        }
        $mysql = "select datasite,uid from web_system_data where id=1";
        $result = $model->query($mysql);
        $row = current($result);
        $site = $row['datasite'];
        $suid = $row['uid'];
        import('@.Util.Team.Curlhttp');
        $curl = &new Curlhttp();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        $curl->set_referrer("" . $site . "/app/member/BK_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
        switch ($line) {
            case '10':
                $bk_close = 'bk_rou_closetime';
                //$html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                //preg_match('/<strong class="light" id="ioradio_id">([\d\.]+)<\/strong>/U', $html_data, $m_temp);
                $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
                //preg_match("/<input id=\"gold\"/Usi",$html_data,$m_temp);
                preg_match("/\"gold\"/Usi", $html_data, $m_temp);
                if (!$m_temp) {
                    $this->alert_msg();
                }
                //exit;
                //preg_match('/<strong class="light" id="ioradio_id">([\d\.]+)<\/strong>/U', $html_data, $rates1);
                //preg_match("/<span  id=\"ioradio_id\" class=\"redFatWord\">([\d\.\/]+)<\/span>/U", $html_data, $rates2);
                preg_match('/<strong class="light" id="ioradio_id">([\d\.]+)<\/strong>/U', $html_data, $rates1);
                preg_match("/<span class=\"radio\">([\d\.\/]+)<\/span>/U", $html_data, $rates2);

                //echo $html_data;
                $MB_Dime_RB = 'O' . $rates2[1];
                $TG_Dime_RB = 'U' . $rates2[1];
                //echo "MB_Dime_RB=".$MB_Dime_RB."<br>";
                //echo "TG_Dime_RB=".$TG_Dime_RB."<br>";
                //exit;
                //$model->query("update match_sports set MB_Dime_RB='{$MB_Dime_RB}',TG_Dime_RB='{$TG_Dime_RB}' where Type='BK' and `MID`=$gid and Cancel!=1 and Open=1 and $mb_team!=''");
                break;
            case '9':
                $bk_close = 'bk_re_closetime';
                $html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
                preg_match("/<input id=\"gold\"/Usi", $html_data, $m_temp);
                if (!$m_temp) {
                    $this->alert_msg();
                }
                preg_match('/<strong class="light" id="ioradio_id">([\d\.]+)<\/strong>/U', $html_data, $rates1);
                preg_match("/<span class=\"radio\">([\d\.\/]+)<\/span>/U", $html_data, $rates2);
                $M_LetB_RB = $rates2[1];
                mysql_query("update match_sports set M_LetB_RB='{$M_LetB_RB}' where Type='BK' and `MID`=$gid and Cancel!=1 and Open=1 and $mb_team!=''");
                break;
        }


        $mysql = "select * from `match_sports` where Type='BK' and `MID`='$gid' and Open=1 and MB_Team!='' and MB_Team_tw!='' and MB_Inball='' and TG_Inball=''";
        $result = $model->query($mysql);
        $cou = count($result);
        $row = current($result);
        if ($cou == 0) {
            $this->alert_msg();
        }
        $sql = "select value from web_params  where name='{$bk_close}'";
        $result1 = $model->query($sql);
        $rowr = current($result1);
        $param_value = $rowr['value'];

        $arr_nowplay = explode("^", $row['now_play']);

        if (($arr_nowplay[0] == "Q4" or $arr_nowplay[0] == "OT") and (int)$arr_nowplay[1] < (int)$param_value) {
            $this->alert_msg();
        }
        $tmp_mleague = str_replace(' ', '', $row['M_League']);
        if ($bk_r34_limit && (!preg_match('/NBA/is', $tmp_mleague)) && ($arr_nowplay[0] == "H2" or $arr_nowplay[0] == "Q3" or $arr_nowplay[0] == "Q4" or $arr_nowplay[0] == "OT")) {
            $this->alert_msg();
        }
        $test1 = date('h:ia', strtotime($row['M_Start']));
        $test2 = $row['M_Time'] . 'm';
        $djj = $row['now_play'];
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        $inball1 = $inball;
        $mb_ball = $row['MB_Ball'];
        $tg_ball = $row['TG_Ball'];
        $w_tg_team = $row['TG_Team'];
        $w_tg_team_tw = $row['TG_Team_tw'];
        $w_mb_team = $row['MB_Team'];
        $w_mb_team_tw = $row['MB_Team_tw'];
        $w_mb_team = filiter_team(trim($row['MB_Team']));
        $w_tg_team = filiter_team(trim($row['TG_Team']));
        $w_mb_team_tw = filiter_team(trim($row['MB_Team_tw']));
        $w_tg_team_tw = filiter_team(trim($row['TG_Team_tw']));
        $s_mb_team = filiter_team($row[$mb_team]);
        $s_tg_team = filiter_team($row[$tg_team]);
        $m_date = $row["M_Date"];
        $showtype = $row["ShowTypeRB"];
        $bettime = date('Y-m-d H:i:s');
        $m_start = strtotime($row['M_Start']);
        $datetime = time();
        if ($datetime - $m_start < 120) {
            $this->alert_msg();
        }
        if ($row[$m_sleague] == '') {
            $w_sleague = $row['M_League'];
            $w_sleague_tw = $row['M_League_tw'];
            $s_sleague = $row[$m_league];
        }
        switch ($line) {
            case 9:
                $bet_type = '滚球让球';
                $bet_type_tw = "滾球讓球";
                $caption = $Order_Basketball . $Order_Running_Ball_betting_order;
                $turn_rate = "BK_Turn_RE_" . $open;
                $MB_LetB_Rate_RB = change_rate($open, $row["MB_LetB_Rate_RB"]);
                $TG_LetB_Rate_RB = change_rate($open, $row["TG_LetB_Rate_RB"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB, $TG_LetB_Rate_RB, 100);
                if ($rate[0] - $r_num > 1.5 or $rate[1] - $r_num > 1.5) {
                    $this->alert_msg();
                }
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'RRH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'RRC';
                        break;
                }
                //$Sign = $row['M_LetB_RB'];
                $Sign = $row['M_LetB_RB'] == "" ? $row['T_LetB_RB'] : $row['M_LetB_RB'];
                $grape = $Sign;
                if (strtoupper($showtype) == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;
                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                    $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                    $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;
                $turn = "BK_Turn_RE";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'RE';
                $w_wtype = 'R';
                break;
            case 10:
                $bet_type = '滚球大小';
                $bet_type_tw = "滾球大小";
                $caption = $Order_Basketball . $Order_Running_Ball_Over_Under_betting_order;
                $turn_rate = "BK_Turn_OU_" . $open;
                $MB_Dime_Rate_RB = change_rate($open, $row["MB_Dime_Rate_RB"]);
                $TG_Dime_Rate_RB = change_rate($open, $row["TG_Dime_Rate_RB"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB, $TG_Dime_Rate_RB, 100);
                if ($rate[0] - $r_num > 1.5 or $rate[1] - $r_num > 1.5) {
                    $this->alert_msg();
                }
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime_RB"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime_RB"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime_RB"];
                        $s_m_place = $row["MB_Dime_RB"];
                        $s_m_place_str = str_replace('O', '', $s_m_place);
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $w_gtype = 'ROUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime_RB"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime_RB"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime_RB"];
                        $s_m_place = $row["TG_Dime_RB"];
                        $s_m_place_str = str_replace('U', '', $s_m_place);
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[1], 3);
                        $w_gtype = 'ROUC';
                        break;
                }
                //大小球盘口匹配 jason 2016-12-09 15:40 add
                preg_match('/<tt class=\"RedWord fatWord\">([0-9\.]{1,8}) \/ ([0-9\.]{1,8})<\/tt>/Usi', $html_data, $rates);
                if (empty($rates)) {
                    preg_match('/<tt class=\"RedWord fatWord\">([0-9\.]{1,8})<\/tt>/Usi', $html_data, $rates);
                }
                $s_m_place_str = str_replace(' ', '', str_replace('/', '', $s_m_place_str));
                $rates[0] = $rates[1] . $rates[2];
                if ($s_m_place_str!=$rates[0]) {
                    $this->alert_msg('盘口变动，请重新下注！');
                }
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "BK_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'ROU';
                $w_wtype = 'R';
                break;
        }

        $sgrape = str_replace(array('O', 'U', 'o', 'u', ' '), '', $grape);
        $this->line_type_check($line, $w_m_rate, $low_odds);
        if ($gold < 10) {
            $this->alert_msg();
        }

        if ($w_m_rate == '' or $grape == '' || empty($sgrape)) {
            $this->alert_msg();
        }

        if (empty($ioradio_r_h) || abs($w_m_rate - $ioradio_r_h) >= 0.02) {
            //$str=$w_m_rate."  ".$ioradio_r_h;
            $this->alert_msg();
        }
        if ($s_m_place == '' or $w_m_place == '' or $w_m_place_tw == '') {
            $this->alert_msg();
        }
        if ($line == 9 or $line == 10) {
            $oddstype = $odd_f_type;
        } else {
            $oddstype = '';
        }
        $team = strip_tags($row["MB_Team"]);
        $place = explode("-", $team);
        if ($place[1] == "") {
            $s_w_place = "";
        } else {
            $s_w_place = "<font color=gray> - " . $place[1] . "</font>";
        }
        $w_mb_mid = $row['MB_MID'];
        $w_tg_mid = $row['TG_MID'];

        $lines = $row['M_League'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team . "<FONT color=red><b>$inball</b></FONT><br>";
        $lines = $lines . "<FONT color=#cc0000>" . $w_m_place . $s_w_place . "</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";

        $lines_tw = $row['M_League_tw'] . "<br>[" . $row['MB_MID'] . ']vs[' . $row['TG_MID'] . "]<br>" . $w_mb_team_tw . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team_tw . "<FONT color=red><b>$inball</b></FONT><br>";
        $lines_tw = $lines_tw . "<FONT color=#cc0000>" . $w_m_place_tw . $s_w_place . "</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";

        $ip_addr = get_ip();
        $m_turn = $a_rate = $b_rate = $c_rate = $d_rate = $b_point = $c_point = $d_point = 0;
        $a_point = 100;
		
		//RB 拦截
		import('@.Util.RbFuntion');
		$RbFuntion = new RbFuntion();
		if($RbFuntion->is_intercept($gid,2,1,$mb_ball,$tg_ball))
		{
			$RB_sql = "INSERT INTO RB_intercept_order (`MID`,`Type`,`LineType`,`Mtype`,`M_Date`,`BetTime`,`BetScore`,`BetType`,`M_Place`,`M_Rate`,`M_Name`,`Gwin`,`ShowType`,`MB_MID`,`TG_MID`,`MB_Ball`,`TG_Ball`) 
			VALUES ('$gid','BK1','$line','$w_gtype','$m_date','$bettime','$gold','$bet_type','$grape','$w_m_rate','$memname','$gwin','$showtype','$w_mb_mid','$w_tg_mid','$mb_ball','$tg_ball')";
			$model->execute($RB_sql);
			$this->alert_msg('盘口变动，请重新下注！','1005');
		}

        $sql = "INSERT INTO web_report_data(MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,TurnRate,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Rate,B_Rate,C_Rate,D_Rate,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,IsPhone,LastBetMoney,Type) 
		values ('$gid','$active','$line','$w_gtype','$m_date','$bettime','$gold','$lines','$lines_tw','','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$m_turn','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_rate','$b_rate','$c_rate','$d_rate','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order',1,'$HMoney','$djj')";
        $model->execute($sql);
        $ouid = $model->getLastInsID();
        $this->snap($ouid);
        $sql = "update web_member_data set Money='$havemoney' where UserName='$memname'";
        $model->execute($sql);
        $_SESSION['bet_block'] = false;
        $this->assign('ft_ouid', show_voucher($line, $ouid, $model));
        $this->assign('s_sleague', $s_sleague);
        $this->assign('bet_type', $bet_type);
        $this->assign('s_mb_team', $s_mb_team);
        $this->assign('Sign', $Sign);
        $this->assign('s_tg_team', $s_tg_team);
        $this->assign('s_m_place', $s_m_place);
        $this->assign('s_w_place', $s_w_place);
        $this->assign('w_m_rate', $w_m_rate);
        $this->assign('gold', $gold);
        $this->assign('gwin', $gwin);
        $this->assign('havemoney', $havemoney);
        $this->display();
    }

    public function Bk_bet()
    {
        $this->assign('bak_url', '/index.php/Bk/team/g/' . $_SESSION['_step']['team']);
        if (!$this->chk_bet()) {

        }
        $Draw = '和局';
        $model = new Model();
        $mb_team = 'MB_Team';
        $tg_team = 'TG_Team';
        $m_league = 'M_League';
        $bettype = 'BetType';
        $middle = 'Middle';
        $message = 'Message';
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gold = (int)$_REQUEST['gold'];
        $active = $_REQUEST['active'];
        $strong = $_REQUEST['strong'];
        $line = $_REQUEST['line_type'];
        $gid = (int)$_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $rtype = $_REQUEST['rtype'];
        $wtype = $_REQUEST['wtype'];
        $gnum = $_REQUEST['gnum'];
        $ioradio_r_h = $_REQUEST['ioradio_r_h'];
        $ioradio_pd = $_REQUEST['ioradio_r_h'];
        $ioradio_f = $_REQUEST['ioradio_r_h'];
        $odd_f_type = 'H';
        //下注时的赔率：应该根据盘口进行转换后，与数据库中的赔率进行比较。若不相同，返回下注。
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $memrow = current($result);
        $open = $memrow['OpenType'];
        $pay_type = $memrow['Pay_Type'];
        $memname = $memrow['UserName'];
        $agents = $memrow['Agents'];
        $world = $memrow['World'];
        $corprator = $memrow['Corprator'];
        $super = $memrow['Super'];
        $admin = $memrow['Admin'];
        $w_ratio = $memrow['ratio'];
        $HMoney = $memrow['Money'];
        $low_odds = $memrow['low_odds'];
        $this->assign('money', array('Money' => $HMoney, 'UserName' => $_SESSION['username']));
        if ($HMoney < $gold) {
            $this->alert_msg('余额不足。');
        }
        if ($gold < 10) {
            $this->alert_msg('请输入正确的金额。');
        }
        $this->chkmax_money($gold);//最高限额500000
        
        $test1 = date('h:ia', strtotime($row['M_Start']));
        $test2 = $row['M_Time'] . 'm';
        $w_current = $memrow['CurType'];
        $havemoney = $HMoney - $gold;
        $memid = $memrow['ID'];


        $mysql = "select * from `match_sports` where Type IN ('BK','BU') and `M_Start`>now() and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!=''"; //判断此赛程是否已经关闭：取出此场次信息
        $result = $model->query($mysql);
        $cou = count($result);
        $row = current($result);
        if ($cou == 0) {
            $this->alert_msg();
        }
        $w_tg_team = filiter_team(trim($row['TG_Team']));
        $w_tg_team_tw = filiter_team(trim($row['TG_Team_tw']));
        $w_mb_team = filiter_team(trim($row['MB_Team']));
        $w_mb_team_tw = filiter_team(trim($row['MB_Team_tw']));

        $w_mb_mid = $row['MB_MID'];
        $w_tg_mid = $row['TG_MID'];
        $s_mb_team = filiter_team($row[$mb_team]);
        $s_tg_team = filiter_team($row[$tg_team]);
        $s_sleague = $row[$m_league];
        $m_date = $row["M_Date"];
        $showtype = $row["ShowTypeR"];
        $bettime = date('Y-m-d H:i:s');
        switch ($line) {
            case 1:
                $bet_type = '独赢';
                $bet_type_tw = '獨贏';
                $caption = $Order_Basketball . $Order_1_x_2_betting_order;
                $turn_rate = "BK_Turn_M";
                $turn = "BK_Turn_M";
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = num_rate($open, $row["MB_Win_Rate"]);
                        $mtype = 'MH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = num_rate($open, $row["TG_Win_Rate"]);
                        $mtype = 'MC';
                        break;
                    case "N":
                        $w_m_place = "和局";
                        $w_m_place_tw = "和局";
                        $s_m_place = $Draw;
                        $w_m_rate = num_rate($open, $row["M_Flat_Rate"]);
                        $mtype = 'MN';
                        break;
                }
                $Sign = "VS.";
                $grape = "";
                $gwin = ($w_m_rate - 1) * $gold;
                $ptype = 'M';
                break;
            case 2:
                $bet_type = '让球';
                $bet_type_tw = "讓球";
                $caption = $Order_Basketball . $Order_Handicap_betting_order;
                $turn_rate = "BK_Turn_R_" . $open;
                $MB_LetB_Rate = change_rate($open, $row["MB_LetB_Rate"]);
                $TG_LetB_Rate = change_rate($open, $row["TG_LetB_Rate"]);
                $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate, $TG_LetB_Rate, 100);
                switch ($type) {
                    case "H":
                        $w_m_place = $w_mb_team;
                        $w_m_place_tw = $w_mb_team_tw;
                        $s_m_place = $s_mb_team;
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'RH';
                        break;
                    case "C":
                        $w_m_place = $w_tg_team;
                        $w_m_place_tw = $w_tg_team_tw;
                        $s_m_place = $s_tg_team;
                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'RC';
                        break;
                }
                $Sign = $row['M_LetB'];
                $grape = $Sign;
                if ($showtype == "H") {
                    $l_team = $s_mb_team;
                    $r_team = $s_tg_team;
                    $w_l_team = $w_mb_team;
                    $w_l_team_tw = $w_mb_team_tw;
                    $w_r_team = $w_tg_team;
                    $w_r_team_tw = $w_tg_team_tw;
                } else {
                    $r_team = $s_mb_team;
                    $l_team = $s_tg_team;
                    $w_r_team = $w_mb_team;
                    $w_r_team_tw = $w_mb_team_tw;
                    $w_l_team = $w_tg_team;
                    $w_l_team_tw = $w_tg_team_tw;
                }
                $s_mb_team = $l_team;
                $s_tg_team = $r_team;
                $w_mb_team = $w_l_team;
                $w_mb_team_tw = $w_l_team_tw;
                $w_tg_team = $w_r_team;
                $w_tg_team_tw = $w_r_team_tw;

                $turn = "BK_Turn_R";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'R';
                break;
            case 3:
                $bet_type = '大小';
                $bet_type_tw = "大小";
                $caption = $Order_Basketball . $Order_Over_Under_betting_order;
                $turn_rate = "BK_Turn_OU_" . $open;
                $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
                $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate, $TG_Dime_Rate, 100);
                switch ($type) {
                    case "C":
                        $w_m_place = $row["MB_Dime"];
                        $w_m_place = str_replace('O', '大&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["MB_Dime"];
                        $w_m_place_tw = str_replace('O', '大&nbsp;', $w_m_place_tw);
                        $m_place = $row["MB_Dime"];
                        $s_m_place = $row["MB_Dime"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[0], 3);
                        $mtype = 'OUH';
                        break;
                    case "H":
                        $w_m_place = $row["TG_Dime"];
                        $w_m_place = str_replace('U', '小&nbsp;', $w_m_place);
                        $w_m_place_tw = $row["TG_Dime"];
                        $w_m_place_tw = str_replace('U', '小&nbsp;', $w_m_place_tw);
                        $m_place = $row["TG_Dime"];
                        $s_m_place = $row["TG_Dime"];
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $s_m_place);
                        }
                        $w_m_rate = number_format($rate[1], 3);
                        $mtype = 'OUC';
                        break;
                }
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "BK_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = 'OU';
                break;
            case 5:
                $bet_type = '单双';
                $bet_type_tw = "單雙";
                $caption = $Order_Basketball . $Order_Odd_Even_betting_order;
                $turn_rate = "BK_Turn_EO_" . $open;
                switch ($rtype) {
                    case "ODD":
                        $w_m_place = '单';
                        $w_m_place_tw = '單';
                        $s_m_place = '(' . $Order_Odd . ')';
                        $w_m_rate = num_rate($open, $row["S_Single_Rate"]);
                        break;
                    case "EVEN":
                        $w_m_place = '双';
                        $w_m_place_tw = '雙';
                        $s_m_place = '(' . $Order_Even . ')';
                        $w_m_rate = num_rate($open, $row["S_Double_Rate"]);
                        break;
                }
                $Sign = "VS.";
                $turn = "BK_Turn_EO";
                $order = 'B';
                $gwin = ($w_m_rate - 1) * $gold;
                if ($gwin < 0) {
                    $this->alert_msg();
                }
                $ptype = 'EO';
                $mtype = $rtype;
                break;
            case 33:
                $caption = $Order_Basketball . $Order_Over_Under_betting_order;
                $turn_rate = "BK_Turn_OU_" . $open;
                $ior_OUHO = change_rate($open, $row["ior_OUHO"]);
                $ior_OUHU = change_rate($open, $row["ior_OUHU"]);
                $ior_OUCO = change_rate($open, $row["ior_OUCO"]);
                $ior_OUCU = change_rate($open, $row["ior_OUCU"]);
                $rate_o = get_other_ioratio($odd_f_type, $ior_OUHO, $ior_OUCO, 100);
                $rate_u = get_other_ioratio($odd_f_type, $ior_OUHU, $ior_OUCU, 100);

                switch ($type) {
                    case "O":
                        if ($wtype == "OUH") {
                            $bet_type = '主队大小';
                            $bet_type_tw = "主队大小";
                            $m_place = $row['ratio_ouho'];
                            $w_m_rate = number_format($rate_o[0], 2);
                            $W_Place = "主队 - 大小";
                            $bet_team = $s_mb_team;
                            $bet_team_tw = $w_mb_team_tw;
                            $mtype = 'OUHO';
                        } elseif ($wtype == "OUC") {
                            $bet_type = '客队大小';
                            $bet_type_tw = "客队大小";
                            $m_place = $row['ratio_ouco'];
                            $w_m_rate = number_format($rate_o[1], 2);
                            $W_Place = "客队 - 大小";
                            $bet_team = $s_tg_team;
                            $bet_team_tw = $w_tg_team_tw;
                            $mtype = 'OUCO';
                        }

                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('O', '大&nbsp;', $m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('O', '大&nbsp;', $m_place);
                        }
                        break;
                    case "U":
                        if ($wtype == "OUH") {
                            $bet_type = '主队大小';
                            $bet_type_tw = "主队大小";
                            $m_place = $row['ratio_ouhu'];
                            $w_m_rate = number_format($rate_u[0], 2);
                            $W_Place = "主队 - 大小";
                            $bet_team = $s_mb_team;
                            $bet_team_tw = $w_mb_team_tw;

                            $mtype = 'OUHU';
                        } elseif ($wtype == "OUC") {
                            $bet_type = '客队大小';
                            $bet_type_tw = "客队大小";
                            $m_place = $row['ratio_oucu'];
                            $w_m_rate = number_format($rate_u[1], 2);
                            $W_Place = "客队 - 大小";
                            $bet_team = $s_tg_team;
                            $bet_team_tw = $w_tg_team_tw;
                            $mtype = 'OUCU';
                        }
                        if ($langx == "zh-cn") {
                            $s_m_place = str_replace('U', '小&nbsp;', $m_place);
                        } else if ($langx == "zh-tw") {
                            $s_m_place = str_replace('U', '小&nbsp;', $m_place);
                        }
                        break;
                }
                $w_m_place = $bet_team . "&nbsp;&nbsp;" . $s_m_place;
                $w_m_place_tw = $bet_team_tw . "&nbsp;&nbsp;" . $s_m_place;
                $Sign = "VS.";
                $grape = $m_place;
                $turn = "BK_Turn_OU";
                $gwin = ($w_m_rate) * $gold;
                $ptype = $wtype;
                break;
        }
        $this->line_type_check($line, $w_m_rate, $low_odds);
        if ($line == 2 or $line == 3 or $line == 33) {
            if ($w_m_rate != $ioradio_r_h) {
                $this->alert_msg();
            }
            $oddstype = $odd_f_type;
        } else {
            $oddstype = '';
        }
        $team = strip_tags($row["MB_Team"]);
        $place = explode("-", $team);
        if ($place[1] == "") {
            $s_w_place = "";
        } else {
            $s_w_place = "<font color=gray> - " . $place[1] . "</font>";
        }
        $s_m_place = filiter_team(trim($s_m_place));

        $w_mid = "<br>[" . $row['MB_MID'] . "]vs[" . $row['TG_MID'] . "]<br>";
        $lines = $row['M_League'] . $w_mid . $w_mb_team . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team . "<br>";
        $lines = $lines . "<FONT color=#cc0000>" . $w_m_place . $s_w_place . "</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";
        $lines_tw = $row['M_League_tw'] . $w_mid . $w_mb_team_tw . "&nbsp;&nbsp;<FONT COLOR=#0000BB><b>" . $Sign . "</b></FONT>&nbsp;&nbsp;" . $w_tg_team_tw . "<br>";
        $lines_tw = $lines_tw . "<FONT color=#cc0000>" . $w_m_place_tw . $s_w_place . "</FONT>&nbsp;@&nbsp;<FONT color=#cc0000><b>" . $w_m_rate . "</b></FONT>";

        $ip_addr = get_ip();
        $m_turn = $a_rate = $b_rate = $c_rate = $d_rate = $b_point = $c_point = $d_point = 0;
        $a_point = 100;
        $sql = "INSERT INTO web_report_data	(MID,Active,LineType,Mtype,M_Date,BetTime,BetScore,Middle,Middle_tw,Middle_en,BetType,BetType_tw,BetType_en,M_Place,M_Rate,M_Name,Gwin,TurnRate,OpenType,OddsType,ShowType,Agents,World,Corprator,Super,Admin,A_Rate,B_Rate,C_Rate,D_Rate,A_Point,B_Point,C_Point,D_Point,BetIP,Ptype,Gtype,CurType,Ratio,MB_MID,TG_MID,Pay_Type,Orderby,MB_Ball,TG_Ball,IsPhone,LastBetMoney) values ('$gid','$active','$line','$mtype','$m_date','$bettime','$gold','$lines','$lines_tw','','$bet_type','$bet_type_tw','$bet_type_en','$grape','$w_m_rate','$memname','$gwin','$m_turn','$open','$oddstype','$showtype','$agents','$world','$corprator','$super','$admin','$a_rate','$b_rate','$c_rate','$d_rate','$a_point','$b_point','$c_point','$d_point','$ip_addr','$ptype','BK','$w_current','$w_ratio','$w_mb_mid','$w_tg_mid','$pay_type','$order','$mb_ball','$tg_ball',1,'$HMoney')";
        $model->execute($sql);
        $ouid = $model->getLastInsID();
        $sql = "update web_member_data set Money='$havemoney' where UserName='$memname'";
        $model->execute($sql);

        $this->snap($ouid);

        if ($active == 22) {
            $caption = str_replace($Order_Basketball, $Order_Basketball . $Order_Early_Market, $caption);
        }
        $_SESSION['bet_block'] = false;
        $this->assign('ft_ouid', show_voucher($line, $ouid, $model));
        $this->assign('s_sleague', $s_sleague);
        $this->assign('bet_type', $bet_type);
        $this->assign('s_mb_team', $s_mb_team);
        $this->assign('Sign', $Sign);
        $this->assign('s_tg_team', $s_tg_team);
        $this->assign('s_m_place', $s_m_place);
        $this->assign('w_m_rate', $w_m_rate);
        $this->assign('gold', $gold);
        $this->assign('gwin', $gwin);
        $this->assign('havemoney', $havemoney);
        $this->display();
    }

    public function line_type_check($line, $w_m_rate, $low_odds)
    {
        $line_array = array(1, 5, 11, 21, 31);    //1,独赢;5,单双;11,半场独赢
        if (in_array($line, $line_array)) {
            if ($w_m_rate < (1 + $low_odds)) {
                $this->alert_msg('您好,投注赔率低于' . $low_odds . '请重新下注,谢谢！');
                $_SESSION['bet_block'] = false;
                exit();
            }
        } else {
            if ($w_m_rate < $low_odds) {
                $this->alert_msg('您好,投注赔率低于' . $low_odds . '请重新下注,谢谢！');
                $_SESSION['bet_block'] = false;
                exit();
            }
        }
    }

    public function snap($id)
    {

    }

    private function chk_bet()
    {
        if ($_SESSION['bet_block'] == true) {//下注锁定
            $this->alert_msg('您好,请勿重复下注,谢谢！');
        }
        $_SESSION['bet_block'] = true;
        foreach (session('_step') as $bettype) {
            if ($bettype == '') {
                return false;
            }
        }
        return true;
    }

    public function __destruct()
    {//析构，把下注锁定解开
        parent::__destruct();
        $_SESSION['bet_block'] = false;
    }

}
