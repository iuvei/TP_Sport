<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BkAction
 *
 * @author Administrator
 */
class BkAction extends SportAction {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->_sel_gt = 'BK';
        $this->assign('_sel_gt', ucfirst(strtolower($this->_sel_gt)));
    }

    protected function bk_rate_R($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $GMAX_SINGLE = $row['BK_R_Scene'];
        $GSINGLE_CREDIT = $row['BK_R_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $btset = singleset('R', $model);
        $GMIN_SINGLE = $btset[0];

        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeR,MB_LetB_Rate,TG_LetB_Rate,M_LetB from `match_sports` where Type IN ('BK','BU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 2;
            $class = "OBK";
            $caption = $Order_Basketball . $Order_Handicap_betting_order;
        } else {
            $active = 22;
            $class = "OBU";
            $caption = $Order_Basketball . $Order_Early_Market . $Order_Handicap_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        $Sign = $row['M_LetB'];
        $MB_LetB_Rate = change_rate($open, $row["MB_LetB_Rate"]);
        $TG_LetB_Rate = change_rate($open, $row["TG_LetB_Rate"]);
        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate, $TG_LetB_Rate, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = number_format($rate[0], 3);
                $mtype = 'RH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = number_format($rate[1], 3);
                $mtype = 'RC';
                break;
        }
        if ($row['ShowTypeR'] == 'C') {
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "";
        } else {
            $W_Place = "<font color=gray> - " . $Place[1] . "</font>";
        }

        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=2 and Mtype='$mtype' and (Active=2 or Active=22)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special0 or $mmb_team[1] == $Special1 or $mmb_team[1] == $Special2 or $mmb_team[1] == $Special3 or $mmb_team[1] == $Special4) {
            $bettop = $league['CS'];
        } else {
            //$bettop=$league['R'];
            $bettop = $GSINGLE_CREDIT;
        }
        if ($M_Rate == 0 or $Sign == '') {
            $this->alert_msg();
        }

        if ($odd_f_type == 'E') {
            $count = 1;
        } else {
            $count = 0;
        }
        if ($GSINGLE_CREDIT >= 500) {
            if ($M_Rate - $count <= 1) {
                $num = 1;
            } else if ($M_Rate - $count > 1 and $M_Rate - $count <= 1.05) {
                $num = 0.95;
            } else if ($M_Rate - $count > 1.05 and $M_Rate - $count <= 1.1) {
                $num = 0.9;
            } else if ($M_Rate - $count > 1.1 and $M_Rate - $count <= 1.15) {
                $num = 0.85;
            } else if ($M_Rate - $count > 1.15 and $M_Rate - $count <= 1.2) {
                $num = 0.8;
            } else if ($M_Rate - $count > 1.2 and $M_Rate - $count <= 1.25) {
                $num = 0.75;
            } else if ($M_Rate - $count > 1.25 and $M_Rate - $count <= 1.3) {
                $num = 0.7;
            } else if ($M_Rate - $count > 1.3 and $M_Rate - $count <= 1.35) {
                $num = 0.65;
            } else if ($M_Rate - $count > 1.35 and $M_Rate - $count <= 1.4) {
                $num = 0.6;
            } else if ($M_Rate - $count > 1.4 and $M_Rate - $count <= 1.45) {
                $num = 0.55;
            } else if ($M_Rate - $count > 1.45 and $M_Rate - $count <= 1.5) {
                $num = 0.5;
            } else if ($M_Rate - $count > 1.5) {
                $num = 0.45;
            }
            $number = 100;
        } else {
            $num = 1;
            $number = 1;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop * $num;
        } else {
            $bettop_money = floor($GSINGLE_CREDIT * $num / $number) * $number;
        }

        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_OU($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('OU', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['BK_OU_Scene'];
        $GSINGLE_CREDIT = $row['BK_OU_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate from `match_sports` where Type IN ('BK','BU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 2;
            $class = "OBK";
            $caption = $Order_Basketball . $Order_Over_Under_betting_order;
        } else {
            $active = 22;
            $class = "OBU";
            $caption = $Order_Basketball . $Order_Early_Market . $Order_Over_Under_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
        $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);
        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate, $TG_Dime_Rate, 100);
        switch ($type) {
            case "C":
                $M_Place = $row['MB_Dime'];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                } 
                $M_Rate = number_format($rate[0], 3);
                $mtype = 'OUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                } 
                $M_Rate = number_format($rate[1], 3);
                $mtype = 'OUC';
                break;
        }

        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "";
        } else {
            $W_Place = "<font color=gray> - " . $Place[1] . "</font>";
        }

        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=3 and Mtype='$mtype' and (Active=2 or Active=22)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special1) {
            $bettop = $league['CS'];
        } else {
            $bettop = $GSINGLE_CREDIT;
        }

        if ($M_Rate == 0 or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
            $this->alert_msg();
        }

        if ($odd_f_type == 'E') {
            $count = 1;
        } else {
            $count = 0;
        }
        if ($GSINGLE_CREDIT >= 500) {
            if ($M_Rate - $count <= 1) {
                $num = 1;
            } else if ($M_Rate - $count > 1 and $M_Rate - $count <= 1.05) {
                $num = 0.95;
            } else if ($M_Rate - $count > 1.05 and $M_Rate - $count <= 1.1) {
                $num = 0.9;
            } else if ($M_Rate - $count > 1.1 and $M_Rate - $count <= 1.15) {
                $num = 0.85;
            } else if ($M_Rate - $count > 1.15 and $M_Rate - $count <= 1.2) {
                $num = 0.8;
            } else if ($M_Rate - $count > 1.2 and $M_Rate - $count <= 1.25) {
                $num = 0.75;
            } else if ($M_Rate - $count > 1.25 and $M_Rate - $count <= 1.3) {
                $num = 0.7;
            } else if ($M_Rate - $count > 1.3 and $M_Rate - $count <= 1.35) {
                $num = 0.65;
            } else if ($M_Rate - $count > 1.35 and $M_Rate - $count <= 1.4) {
                $num = 0.6;
            } else if ($M_Rate - $count > 1.4 and $M_Rate - $count <= 1.45) {
                $num = 0.55;
            } else if ($M_Rate - $count > 1.45 and $M_Rate - $count <= 1.5) {
                $num = 0.5;
            } else if ($M_Rate - $count > 1.5) {
                $num = 0.45;
            }
            $number = 100;
        } else {
            $num = 1;
            $number = 1;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop * $num;
        } else {
            $bettop_money = floor($GSINGLE_CREDIT * $num / $number) * $number;
        }

        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_EO($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $rtype = $_REQUEST['rtype'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $change = $_REQUEST['change'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('T', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['BK_EO_Scene'];
        $GSINGLE_CREDIT = $row['BK_EO_Bet'];
        $open = $row['OpenType'];

        if ($change == 1) {
            $bet_title = $nobettitle;
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,S_Single_Rate,S_Double_Rate from `match_sports` where Type IN ('BK','BU') and `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);

        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 2;
            $class = "OBK";
            $like = $Order_Basketball;
        } else {
            $active = 22;
            $class = "OBU";
            $like = $Order_Basketball . $Order_Early_Market;
        }
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        switch ($rtype) {
            case "ODD":
                $M_Place = "(" . $Order_Odd . ")";
                $M_Rate = num_rate($open, $row["S_Single_Rate"]);
                $GMAX_SINGLE = $GMAX_SINGLE;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT;
                $caption = $Order_Odd_Even_betting_order;
                $linetype = 5;
                break;
            case "EVEN":
                $M_Place = "(" . $Order_Even . ")";
                $M_Rate = num_rate($open, $row["S_Double_Rate"]);
                $GMAX_SINGLE = $GMAX_SINGLE;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT;
                $caption = $Order_Odd_Even_betting_order;
                $linetype = 5;
                break;
        }
        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "";
        } else {
            $W_Place = "<font color=gray> - " . $Place[1] . "</font>";
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=2 or Active=22)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        if ($rtype == 'ODD' or $rtype == 'EVEN') {
            $sql = "select * from match_league where  M_League='$M_League' and Type='BK'";
            $result = $model->query($sql);
            $league = current($result);
            $bettop = $GSINGLE_CREDIT;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }

        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_OUH($model) {
        $gid = (int) $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $wtype = $_REQUEST['wtype'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('OU', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['BK_OU_Scene'];
        $GSINGLE_CREDIT = $row['BK_OU_Bet'];
        $open = $row['OpenType'];

        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ratio_ouho,ratio_ouhu,ior_OUHO,ior_OUHU,ratio_ouco,ratio_oucu,ior_OUCO,ior_OUCU from `match_sports` where Type IN ('BK','BU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 2;
            $class = "OBK";
            $caption = $Order_Basketball . $Order_Over_Under_betting_order;
        } else {
            $active = 22;
            $class = "OBU";
            $caption = $Order_Basketball . $Order_Early_Market . $Order_Over_Under_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        $ior_OUHO = change_rate($open, $row["ior_OUHO"]);
        $ior_OUHU = change_rate($open, $row["ior_OUHU"]);
        $ior_OUCO = change_rate($open, $row["ior_OUCO"]);
        $ior_OUCU = change_rate($open, $row["ior_OUCU"]);
        $rate_o = get_other_ioratio($odd_f_type, $ior_OUHO, $ior_OUCO, 100);
        $rate_u = get_other_ioratio($odd_f_type, $ior_OUHU, $ior_OUCU, 100);
        switch ($type) {
            case "O":
                if ($wtype == "OUH") {
                    $M_Place = $row['ratio_ouho'];
                    $M_Rate = number_format($rate_o[0], 2);
                    $W_Place = "主队 - 大小";
                    $mtype = 'OUHO';
                } elseif ($wtype == "OUC") {  //客队大
                    $M_Place = $row['ratio_ouco'];
                    $M_Rate = number_format($rate_o[1], 2);
                    $W_Place = "客队 - 大小";
                    $mtype = 'OUCO';
                }
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                }
                break;
            case "U":
                if ($wtype == "OUH") {
                    $M_Place = $row['ratio_ouhu'];
                    $M_Rate = number_format($rate_u[0], 2);
                    $W_Place = "主队 - 大小";
                    $mtype = 'OUHU';
                } elseif ($wtype == "OUC") {
                    $M_Place = $row['ratio_oucu'];
                    $M_Rate = number_format($rate_u[1], 2);
                    $W_Place = "客队 - 大小";
                    $mtype = 'OUCU';
                }
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                } 
                break;
        }

        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "全场 -" . $W_Place;
        } else {
            $W_Place = "<span class='game_hr'>" . $Place[1] . "</span>" . $W_Place;
        }


        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=3 and Mtype='$mtype' and (Active=2 or Active=22)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  $m_league='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special1) {
            $bettop = $league['CS'];
        } else {
            $bettop = $GSINGLE_CREDIT;
        }

        if ($M_Rate == 0 or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
            $this->alert_msg();
        }

        if ($odd_f_type == 'E') {
            $count = 1;
        } else {
            $count = 0;
        }
        if ($GSINGLE_CREDIT >= 500) {
            if ($M_Rate - $count <= 1) {
                $num = 1;
            } else if ($M_Rate - $count > 1 and $M_Rate - $count <= 1.05) {
                $num = 0.95;
            } else if ($M_Rate - $count > 1.05 and $M_Rate - $count <= 1.1) {
                $num = 0.9;
            } else if ($M_Rate - $count > 1.1 and $M_Rate - $count <= 1.15) {
                $num = 0.85;
            } else if ($M_Rate - $count > 1.15 and $M_Rate - $count <= 1.2) {
                $num = 0.8;
            } else if ($M_Rate - $count > 1.2 and $M_Rate - $count <= 1.25) {
                $num = 0.75;
            } else if ($M_Rate - $count > 1.25 and $M_Rate - $count <= 1.3) {
                $num = 0.7;
            } else if ($M_Rate - $count > 1.3 and $M_Rate - $count <= 1.35) {
                $num = 0.65;
            } else if ($M_Rate - $count > 1.35 and $M_Rate - $count <= 1.4) {
                $num = 0.6;
            } else if ($M_Rate - $count > 1.4 and $M_Rate - $count <= 1.45) {
                $num = 0.55;
            } else if ($M_Rate - $count > 1.45 and $M_Rate - $count <= 1.5) {
                $num = 0.5;
            } else if ($M_Rate - $count > 1.5) {
                $num = 0.45;
            }
            $number = 100;
        } else {
            $num = 1;
            $number = 1;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop * $num;
        } else {
            $bettop_money = floor($GSINGLE_CREDIT * $num / $number) * $number;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_RE($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];

        $uid = $_SESSION['uid'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = mysql_num_rows($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('RE', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['BK_RE_Scene'];
        $GSINGLE_CREDIT = $row['BK_RE_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select datasite,uid from web_system_data";
        $result = $model->query($mysql);
        $row = current($result);
        $site = $row['datasite'];
        $suid = $row['uid'];
        import('@.Util.Team.Curlhttp');
        $curl = &new Curlhttp();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        $curl->set_referrer("" . $site . "/app/member/BK_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
        //$html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_re.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&strong=$strong&odd_f_type=$odd_f_type");
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeRB,M_LetB_RB,MB_LetB_Rate_RB,TG_LetB_Rate_RB from `match_sports` where Type='BK' and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = mysql_num_rows($result);
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        $Sign = $row['M_LetB_RB'];
        $MB_LetB_Rate_RB = change_rate($open, $row["MB_LetB_Rate_RB"]);
        $TG_LetB_Rate_RB = change_rate($open, $row["TG_LetB_Rate_RB"]);
        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB, $TG_LetB_Rate_RB, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = number_format($rate[0], 3);
                $mtype = 'RRH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = number_format($rate[1], 3);
                $mtype = 'RRC';
                break;
        }

        if ($row['ShowTypeRB'] == 'C') {
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }

        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "";
        } else {
            $W_Place = "<font color=gray> - " . $Place[1] . "</font>";
        }
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=9 and Mtype='$mtype' and Active=2";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special1) {
            $bettop = $league['CS'];
        } else {
            $bettop = $GSINGLE_CREDIT;
        }

        if ($M_Rate == 0 or $Sign == '') {
            $this->alert_msg();
        }

        if ($odd_f_type == 'E') {
            $count = 1;
        } else {
            $count = 0;
        }
        if ($GSINGLE_CREDIT >= 500) {
            if ($M_Rate - $count <= 1) {
                $num = 1;
            } else if ($M_Rate - $count > 1 and $M_Rate - $count <= 1.05) {
                $num = 0.95;
            } else if ($M_Rate - $count > 1.05 and $M_Rate - $count <= 1.1) {
                $num = 0.9;
            } else if ($M_Rate - $count > 1.1 and $M_Rate - $count <= 1.15) {
                $num = 0.85;
            } else if ($M_Rate - $count > 1.15 and $M_Rate - $count <= 1.2) {
                $num = 0.8;
            } else if ($M_Rate - $count > 1.2 and $M_Rate - $count <= 1.25) {
                $num = 0.75;
            } else if ($M_Rate - $count > 1.25 and $M_Rate - $count <= 1.3) {
                $num = 0.7;
            } else if ($M_Rate - $count > 1.3 and $M_Rate - $count <= 1.35) {
                $num = 0.65;
            } else if ($M_Rate - $count > 1.35 and $M_Rate - $count <= 1.4) {
                $num = 0.6;
            } else if ($M_Rate - $count > 1.4 and $M_Rate - $count <= 1.45) {
                $num = 0.55;
            } else if ($M_Rate - $count > 1.45 and $M_Rate - $count <= 1.5) {
                $num = 0.5;
            } else if ($M_Rate - $count > 1.5) {
                $num = 0.45;
            }
            $number = 100;
        } else {
            $num = 1;
            $number = 1;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop * $num;
        } else {
            $bettop_money = floor($GSINGLE_CREDIT * $num / $number) * $number;
        }

        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_ROU($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $uid = $_SESSION['uid'];
        $type = $_REQUEST['type'];
        $odd_f_type = $_REQUEST['odd_f_type'];
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('ROU', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['BK_ROU_Scene'];
        $GSINGLE_CREDIT = $row['BK_ROU_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select datasite,uid from web_system_data";
        $result = $model->query($mysql);
        $row = current($result);
        $site = $row['datasite'];
        $suid = $row['uid'];
        import('@.Util.Team.Curlhttp');
        $curl = &new Curlhttp();
        $curl->store_cookies("cookies.txt");
        $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
        $curl->set_referrer("" . $site . "/app/member/BK_index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
        //$html_data = $curl->fetch_url("" . $site . "/app/member/BK_order/BK_order_rou.php?gid=$gid&uid=$suid&type=$type&gnum=$gnum&odd_f_type=$odd_f_type");
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime_RB,TG_Dime_RB,MB_Dime_Rate_RB,TG_Dime_Rate_RB from `match_sports` where Type='BK' and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = mysql_num_rows($result);
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);
        $MB_Dime_Rate_RB = change_rate($open, $row["MB_Dime_Rate_RB"] + 0.01);
        $TG_Dime_Rate_RB = change_rate($open, $row["TG_Dime_Rate_RB"] + 0.01);
        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB, $TG_Dime_Rate_RB, 100);
        switch ($type) {
            case "C":
                $M_Place = $row["MB_Dime_RB"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[0], 3);
                $mtype = 'ROUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime_RB"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[1], 3);
                $mtype = 'ROUC';
                break;
        }

        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "";
        } else {
            $W_Place = "<font color=gray> - " . $Place[1] . "</font>";
        }
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=10 and Mtype='$mtype' and Active=2";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special1) {
            $bettop = $league['CS'];
        } else {
            $bettop = $GSINGLE_CREDIT;
        }

        if ($M_Rate == 0 or $M_Place == '' or $M_Place == 'O0' or $M_Place == 'U0') {
            $this->alert_msg();
        }

        if ($odd_f_type == 'E') {
            $count = 1;
        } else {
            $count = 0;
        }
        if ($GSINGLE_CREDIT >= 500) {
            if ($M_Rate - $count <= 1) {
                $num = 1;
            } else if ($M_Rate - $count > 1 and $M_Rate - $count <= 1.05) {
                $num = 0.95;
            } else if ($M_Rate - $count > 1.05 and $M_Rate - $count <= 1.1) {
                $num = 0.9;
            } else if ($M_Rate - $count > 1.1 and $M_Rate - $count <= 1.15) {
                $num = 0.85;
            } else if ($M_Rate - $count > 1.15 and $M_Rate - $count <= 1.2) {
                $num = 0.8;
            } else if ($M_Rate - $count > 1.2 and $M_Rate - $count <= 1.25) {
                $num = 0.75;
            } else if ($M_Rate - $count > 1.25 and $M_Rate - $count <= 1.3) {
                $num = 0.7;
            } else if ($M_Rate - $count > 1.3 and $M_Rate - $count <= 1.35) {
                $num = 0.65;
            } else if ($M_Rate - $count > 1.35 and $M_Rate - $count <= 1.4) {
                $num = 0.6;
            } else if ($M_Rate - $count > 1.4 and $M_Rate - $count <= 1.45) {
                $num = 0.55;
            } else if ($M_Rate - $count > 1.45 and $M_Rate - $count <= 1.5) {
                $num = 0.5;
            } else if ($M_Rate - $count > 1.5) {
                $num = 0.45;
            }
            $number = 100;
        } else {
            $num = 1;
            $number = 1;
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop * $num;
        } else {
            $bettop_money = floor($GSINGLE_CREDIT * $num / $number) * $number;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function bk_rate_M($model) {
        $lang = get_lang();
        extract($lang);
        $gid = (int) $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $row = current($result);

        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $GMAX_SINGLE = $row['FT_M_Scene'];
        $GSINGLE_CREDIT = $row['FT_M_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $btset = singleset('M',$model);
        $GMIN_SINGLE = $btset[0];
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate from match_sports where Type IN ('BK','BU') and M_Start>now() and MID='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);

        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 2;
            $class = "OBK";
            $caption = $Order_Basketball . $Order_Handicap_betting_order;
        } else {
            $active = 22;
            $class = "OBU";
            $caption = $Order_Basketball . $Order_Early_Market . $Order_Handicap_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = filiter_team($row["MB_Team"]);
        $TG_Team = filiter_team($row["TG_Team"]);

        $MB_Win_Rate = $row["MB_Win_Rate"];
        $TG_Win_Rate = $row["TG_Win_Rate"];

        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = num_rate($open, $MB_Win_Rate);
                $mtype = 'MH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = num_rate($open, $TG_Win_Rate);
                $mtype = 'MC';
                break;
        }

        $team = strip_tags($row["MB_Team"]);
        $Place = explode("-", $team);
        if ($Place[1] == "") {
            $W_Place = "全场 - 独赢";
        } else {
            $W_Place = "<span class='game_hr'>" . $Place[1] . "</span> - 独赢";
        }

        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=1 and Mtype='$mtype' and (Active=2 or Active=22)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;
        $sql = "select * from match_league where  $m_league='$M_League' and Type='BK'";
        $result = $model->query($sql);
        $league = current($result);
        $bettop = $GSINGLE_CREDIT;
        if ($M_Rate == 0) {
            $this->alert_msg();
        }
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }

        $this->assigns(get_defined_vars());
        return $M_Rate;
    }
}