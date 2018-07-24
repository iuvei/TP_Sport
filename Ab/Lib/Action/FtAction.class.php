<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FtAction
 *
 * @author Administrator
 */
class FtAction extends SportAction {

    //put your code here
    public function __construct() {
        parent::__construct();
        $this->_sel_gt = 'FT';
        $this->assign('_sel_gt', ucfirst(strtolower($this->_sel_gt)));
    }

    protected function rate_RM($model) {
        $lang = get_lang();
        extract($lang);
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'];
        $gnum = $_REQUEST['gnum'];
        $type = $_REQUEST['type'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);

        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('M', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_M_Scene'];
        $GSINGLE_CREDIT = $row['FT_M_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Win_Rate_RB,TG_Win_Rate_RB,M_Flat_Rate_RB,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = num_rate($open, $row["MB_Win_Rate_RB"]);
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = num_rate($open, $row["TG_Win_Rate_RB"]);
                break;
            case "N":
                $M_Place = $Draw;
                $M_Rate = num_rate($open, $row["M_Flat_Rate_RB"]);
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=21 and Active=1";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League'";
        $result = $model->query($sql);
        $league = current($result);
        $bettop = $GSINGLE_CREDIT;

        if ($M_Rate == 0) {
            $this->alert_msg(NULL);
        }

        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function rate_RE($model) {
        $lang = get_lang();
        extract($lang);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'];
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
        $btset = singleset('RE', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_RE_Scene'];
        $GSINGLE_CREDIT = $row['FT_RE_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeRB,M_LetB_RB,MB_LetB_Rate_RB,TG_LetB_Rate_RB,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $Sign = $row['M_LetB_RB'];
        $MB_LetB_Rate_RB = change_rate($open, $row["MB_LetB_Rate_RB"]);
        $TG_LetB_Rate_RB = change_rate($open, $row["TG_LetB_Rate_RB"]);
        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB, $TG_LetB_Rate_RB, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = $rate[0];
                $mtype = 'RRH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = $rate[1];
                $mtype = 'RRC';
                break;
        }
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        if ($row['ShowTypeRB'] == 'C') {
            $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=9 and Mtype='$mtype' and Active=1";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
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
        $count = 0;
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

    protected function rate_ROU($model) {
        $lang = get_lang();
        extract($lang);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'];
        $gnum = $_REQUEST['gnum'];
        $type = $_REQUEST['type'];
        $odd_f_type = 'H';
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
        $GMAX_SINGLE = $row['FT_ROU_Scene'];
        $GSINGLE_CREDIT = $row['FT_ROU_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime_RB,TG_Dime_RB,MB_Dime_Rate_RB,TG_Dime_Rate_RB,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`=$gid and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);

        $MB_Dime_Rate_RB = change_rate($open, $row["MB_Dime_Rate_RB"]);
        $TG_Dime_Rate_RB = change_rate($open, $row["TG_Dime_Rate_RB"]);

        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB, $TG_Dime_Rate_RB, 100);
        switch ($type) {
            case "C":
                $M_Place = $row["MB_Dime_RB"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "en-us" or $langx == "th-tis") {
                    $M_Place = str_replace('O', 'over&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'ROUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime_RB"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'ROUC';
                break;
        }
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        if ($row['ShowTypeR'] == 'C') {
            $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=10 and Mtype='$mtype' and Active=1";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
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
        $count = 0;
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

    protected function rate_HRM($model) {
        $lang = get_lang();
        extract($lang);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'] + 1;
        $gnum = $_REQUEST['gnum'];
        $type = $_REQUEST['type'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='$uid' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('M', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_M_Scene'];
        $GSINGLE_CREDIT = $row['FT_M_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $sgid = $gid - 1;
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Win_Rate_RB_H,TG_Win_Rate_RB_H,M_Flat_Rate_RB_H,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`='$sgid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = num_rate($open, $row["MB_Win_Rate_RB_H"]);
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = num_rate($open, $row["TG_Win_Rate_RB_H"]);
                break;
            case "N":
                $M_Place = $Draw;
                $M_Rate = num_rate($open, $row["M_Flat_Rate_RB_H"]);
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$sgid' and LineType=31 and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League'";
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

    protected function rate_HRE($model) {
        $lang = get_lang();
        extract($lang);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'] + 1;
        $gnum = $_REQUEST['gnum'];
        $type = $_REQUEST['type'];
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
        $btset = singleset('RE', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_RE_Scene'];
        $GSINGLE_CREDIT = $row['FT_RE_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $sgid = $gid - 1;
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeHRB,M_LetB_RB_H,MB_LetB_Rate_RB_H,TG_LetB_Rate_RB_H,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`='$sgid' and cancel!=1 and Open=1 and MB_Team!='' and mb_team_tw!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $TG_Team = filiter_team($TG_Team);
        $Sign = $row['M_LetB_RB_H'];
        $MB_LetB_Rate_RB_H = change_rate($open, $row["MB_LetB_Rate_RB_H"]);
        $TG_LetB_Rate_RB_H = change_rate($open, $row["TG_LetB_Rate_RB_H"]);
        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_RB_H, $TG_LetB_Rate_RB_H, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'VRRH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'VRRC';
                break;
        }
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];

        if ($row['ShowTypeHRB'] == 'C') {
            $inball = $row['TG_Ball'] . ":" . $row['MB_Ball'];
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$sgid' and LineType=19 and Mtype='$mtype' and Active=1";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League'";
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
        $count = 0;
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

    /**
     * @param $model
     * @return string
     */
    protected function rate_HROU($model) {
        $lang = get_lang();
        extract($lang);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gid = $_REQUEST['gid'] + 1;
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $odd_f_type = 'H';
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
        $GMAX_SINGLE = $row['FT_ROU_Scene'];
        $GSINGLE_CREDIT = $row['FT_ROU_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $sgid = $gid - 1;
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime_RB_H,TG_Dime_RB_H,MB_Dime_Rate_RB_H,TG_Dime_Rate_RB_H,MB_Ball,TG_Ball from `match_sports` where Type='FT' and `MID`='$sgid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $TG_Team = filiter_team($TG_Team);

        $MB_Dime_Rate_RB_H = change_rate($open, $row["MB_Dime_Rate_RB_H"]);
        $TG_Dime_Rate_RB_H = change_rate($open, $row["TG_Dime_Rate_RB_H"]);

        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_RB_H, $TG_Dime_Rate_RB_H, 100);
        switch ($type) {
            case "C":
                $M_Place = $row["MB_Dime_RB_H"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } 
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'VROUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime_RB_H"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                } 
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'VROUC';
                break;
        }
        $inball = $row['MB_Ball'] . ":" . $row['TG_Ball'];
        $ratio_id = $M_Rate;
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$sgid' and LineType=20 and Mtype='$mtype' and Active=1";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League'";
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
        $count = 0;
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

    protected function rate_M($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $odd_f_type = 'H';
        $change = $_REQUEST['change'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('M', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_M_Scene'];
        $GSINGLE_CREDIT = $row['FT_M_Bet'];
        $open = $row['OpenType'];
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='{$gid}' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        //var_dump($row);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_1_x_2_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_1_x_2_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = num_rate($open, $row["MB_Win_Rate"]);
                $mtype = 'MH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = num_rate($open, $row["TG_Win_Rate"]);
                $mtype = 'MC';
                break;
            case "N":
                $M_Place = $Draw;
                $M_Rate = num_rate($open, $row["M_Flat_Rate"]);
                $mtype = 'MN';
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='{$memname}' and MID='{$gid}' and linetype=1 and Mtype='{$mtype}' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;
        $bettop = $GSINGLE_CREDIT;
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function rate_HM($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $odd_f_type = 'H';
        $change = $_REQUEST['change'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('M', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_M_Scene'];
        $GSINGLE_CREDIT = $row['FT_M_Bet'];
        $open = $row['OpenType'];
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H from `match_sports` where Type IN ('FT','FU') and `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_1st_Half_1_x_2_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_1st_Half_1_x_2_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = num_rate($open, $row["MB_Win_Rate_H"]);
                $mtype = 'VMH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = num_rate($open, $row["TG_Win_Rate_H"]);
                $mtype = 'VMC';
                break;
            case "N":
                $M_Place = $Draw;
                $M_Rate = num_rate($open, $row["M_Flat_Rate_H"]);
                $mtype = 'VMN';
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=11 and Mtype='$mtype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;
        $bettop = $GSINGLE_CREDIT;
        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function rate_R($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $GMAX_SINGLE = $row['FT_R_Scene'];
        $GSINGLE_CREDIT = $row['FT_R_Bet'];
        $open = $row['OpenType'];
        $btset = singleset('R', $model);
        $GMIN_SINGLE = $btset[0];

        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeR,MB_LetB_Rate,TG_LetB_Rate,M_LetB from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);

        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_Handicap_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_Handicap_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $Sign = $row['M_LetB'];
        $MB_LetB_Rate = change_rate($open, $row["MB_LetB_Rate"]);
        $TG_LetB_Rate = change_rate($open, $row["TG_LetB_Rate"]);
        //var_dump($MB_LetB_Rate,$TG_LetB_Rate);
        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate, $TG_LetB_Rate, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'RH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'RC';
                break;
        }
        //var_dump($rate);
        if ($row['ShowTypeR'] == 'C') {
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=2 and Mtype='$mtype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
        $result = $model->query($sql);
        $league = current($result);
        $mmb_team = explode("[", $row['MB_Team']);
        if ($mmb_team[1] == $Special0 or $mmb_team[1] == $Special1 or $mmb_team[1] == $Special2 or $mmb_team[1] == $Special3 or $mmb_team[1] == $Special4) {
            $bettop = $league['CS'];
        } else {
            $bettop = $GSINGLE_CREDIT;
        }
        if ($M_Rate == 0 or $Sign == '') {
            $this->alert_msg();
        }
        $count = 0;

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

    protected function rate_HR($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $strong = $_REQUEST['strong'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('R', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_R_Scene'];
        $GSINGLE_CREDIT = $row['FT_R_Bet'];
        $open = $row['OpenType'];

        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_1st_Half_Handicap_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_1st_Half_Handicap_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        $Sign = $row['M_LetB_H'];

        $MB_LetB_Rate_H = change_rate($open, $row["MB_LetB_Rate_H"]);
        $TG_LetB_Rate_H = change_rate($open, $row["TG_LetB_Rate_H"]);

        $rate = get_other_ioratio($odd_f_type, $MB_LetB_Rate_H, $TG_LetB_Rate_H, 100);
        switch ($type) {
            case "H":
                $M_Place = $MB_Team;
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'VRH';
                break;
            case "C":
                $M_Place = $TG_Team;
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'VRC';
                break;
        }

        if ($row['ShowTypeHR'] == 'C') {
            $Team = $MB_Team;
            $MB_Team = $TG_Team;
            $TG_Team = $Team;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=12 and Mtype='$mtype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
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
        $count = 0;
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

    protected function rate_OU($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];
        $odd_f_type = 'H';
        $error_flag = $_REQUEST['error_flag'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = count($result);
        if ($cou == 0) {
            echo "<script>window.open('/','_top')</script>";
            exit;
        }
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('OU', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_OU_Scene'];
        $GSINGLE_CREDIT = $row['FT_OU_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_Over_Under_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_Over_Under_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);

        $MB_Dime_Rate = change_rate($open, $row["MB_Dime_Rate"]);
        $TG_Dime_Rate = change_rate($open, $row["TG_Dime_Rate"]);

        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate, $TG_Dime_Rate, 100);
        switch ($type) {
            case "C":
                $M_Place = $row['MB_Dime'];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'OUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'OUC';
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where M_Name='$memname' and MID='$gid' and LineType=3 and Mtype='$mtype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
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
        $count = 0;
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

    protected function rate_HOU($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $type = $_REQUEST['type'];
        $gnum = $_REQUEST['gnum'];$odd_f_type = 'H';
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
        $GMAX_SINGLE = $row['FT_OU_Scene'];
        $GSINGLE_CREDIT = $row['FT_OU_Bet'];
        $open = $row['OpenType'];
        if ($error_flag == 1) {
            $bet_title = "<tt>" . $Order_Odd_changed_please_bet_again . "</tt>";
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_1st_Half_Over_Under_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_1st_Half_Over_Under_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);

        $MB_Dime_Rate_H = change_rate($open, $row["MB_Dime_Rate_H"]);
        $TG_Dime_Rate_H = change_rate($open, $row["TG_Dime_Rate_H"]);

        $rate = get_other_ioratio($odd_f_type, $MB_Dime_Rate_H, $TG_Dime_Rate_H, 100);
        switch ($type) {
            case "C":
                $M_Place = $row['MB_Dime_H'];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('O', '大&nbsp;&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[0], 2);
                $mtype = 'VOUH';
                break;
            case "H":
                $M_Place = $row["TG_Dime_H"];
                if ($langx == "zh-cn") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                } else if ($langx == "zh-tw") {
                    $M_Place = str_replace('U', '小&nbsp;&nbsp;', $M_Place);
                }
                $M_Rate = number_format($rate[1], 2);
                $mtype = 'VOUC';
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=13 and Mtype='$mtype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
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
        $count = 0;
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

    protected function rate_EO($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $rtype = $_REQUEST['rtype'];
        $odd_f_type = 'H';
        $change = $_REQUEST['change'];

        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = count($result);
        if ($cou == 0) {
            echo "<script>window.open('/','_top')</script>";
            exit;
        }
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('T', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE1 = $row['FT_EO_Scene'];
        $GSINGLE_CREDIT1 = $row['FT_EO_Bet'];
        $GMAX_SINGLE2 = $row['FT_T_Scene'];
        $GSINGLE_CREDIT2 = $row['FT_T_Bet'];
        $open = $row['OpenType'];
        if ($change == 1) {
            $bet_title = $nobettitle;
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `match_sports` where Type IN ('FT','FU') and `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $like = $Order_FT;
        } else {
            $active = 11;
            $class = "OFU";
            $like = $Order_FT . $Order_Early_Market;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        switch ($rtype) {
            case "ODD":
                $M_Place = $Order_Odd . "&nbsp;";
                $M_Rate = num_rate($open, $row["S_Single_Rate"]);
                $GMAX_SINGLE = $GMAX_SINGLE1;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                $caption = $Order_FT . $Order_Odd_Even_betting_order;
                $linetype = 5;
                break;
            case "EVEN":
                $M_Place = $Order_Even . "&nbsp;";
                $M_Rate = num_rate($open, $row["S_Double_Rate"]);
                $GMAX_SINGLE = $GMAX_SINGLE1;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT1;
                $caption = $Order_FT . $Order_Odd_Even_betting_order;
                $linetype = 5;
                break;
            case "0~1":
                $M_Place = "0~1";
                $M_Rate = $row["S_0_1"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_FT . $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "2~3":
                $M_Place = "2~3";
                $M_Rate = $row["S_2_3"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_FT . $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "4~6":
                $M_Place = "4~6";
                $M_Rate = $row["S_4_6"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_FT . $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "OVER":
                $M_Place = "7UP";
                $M_Rate = $row["S_7UP"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_FT . $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        if ($rtype == 'ODD' or $rtype == 'EVEN') {
            $sql = "select * from match_league where  M_League='$M_League'";
            $result = $model->query($sql);
            $league = current($result);
            //$bettop=$league['EO'];
            $bettop = $GSINGLE_CREDIT;
        } else {
            $sql = "select * from match_league where  M_League='$M_League'";
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

    protected function rate_PD($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $rtype = $_REQUEST['type'];
        $odd_f_type = 'H';
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = count($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('PD', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_PD_Scene'];
        $GSINGLE_CREDIT = $row['FT_PD_Bet'];
        $open = $row['OpenType'];

        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4 from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!='' and MB_Team_tw!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = mysql_num_rows($result);
        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_Correct_Score_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_Correct_Score_betting_order;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);

        if ($rtype == "OVH") {
            $M_Place = $Order_Other_Score;
            $M_Sign = "VS.";
            $M_Rate = $row['UP5'];
        } else {
            $M_Place = "";
            $M_Sign = $rtype;
            $M_Sign = str_replace("H", "", $M_Sign);
            $M_Sign = str_replace("C", ":", $M_Sign);
            $M_Sign = $M_Sign;
            $M_Rate = str_replace("H", "MB", $rtype);
            $M_Rate = str_replace("C", "TG", $M_Rate);
            $M_Rate = $row[$M_Rate];
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=4 and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
        $result = $model->query($sql);
        $league = current($result);
        $bettop = $GSINGLE_CREDIT;

        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function rate_T($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $rtype = $_REQUEST['rtype'];
        $odd_f_type = 'H';
        $change = $_REQUEST['change'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = count($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('T', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE1 = $row['FT_EO_Scene'];
        $GSINGLE_CREDIT1 = $row['FT_EO_Bet'];
        $GMAX_SINGLE2 = $row['FT_T_Scene'];
        $GSINGLE_CREDIT2 = $row['FT_T_Bet'];
        $open = $row['OpenType'];
        if ($change == 1) {
            $bet_title = $nobettitle;
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP from `match_sports` where Type IN ('FT','FU') and `m_start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);

        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $like = $Order_FT;
        } else {
            $active = 11;
            $class = "OFU";
            $like = $Order_FT . $Order_Early_Market;
        }
        $M_League = $row['M_League'];
        $MB_Team = $row["MB_Team"];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($MB_Team);
        switch ($rtype) {
            case "0~1":
                $M_Place = "0~1";
                $M_Rate = $row["S_0_1"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "2~3":
                $M_Place = "2~3";
                $M_Rate = $row["S_2_3"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "4~6":
                $M_Place = "4~6";
                $M_Rate = $row["S_4_6"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
            case "OVER":
                $M_Place = "7UP";
                $M_Rate = $row["S_7UP"];
                $GMAX_SINGLE = $GMAX_SINGLE2;
                $GSINGLE_CREDIT = $GSINGLE_CREDIT2;
                $caption = $Order_Total_Goals_betting_order;
                $text = $Order_The_maximum_payout_is_x_per_bet . '<br>';
                $linetype = 6;
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype='$linetype' and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;
        $sql = "select * from match_league where  M_League='$M_League'";
        $result = $model->query($sql);
        $league = current($result);
        $bettop = $GSINGLE_CREDIT;

        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

    protected function rate_F($model) {
        $lang = get_lang();
        extract($lang);
        $gid = $_REQUEST['gid'];
        $rtype = $_REQUEST['rtype'];
        $odd_f_type = 'H';
        $change = $_REQUEST['change'];
        $sql = "select * from web_member_data where Oid='{$_SESSION['uid']}' and Status=0";
        $result = $model->query($sql);
        $row = current($result);
        $cou = count($result);
        $memname = $row['UserName'];
        $credit = $row['Money'];
        $curtype = $row['CurType'];
        $pay_type = $row['Pay_Type'];
        $btset = singleset('F', $model);
        $GMIN_SINGLE = $btset[0];
        $GMAX_SINGLE = $row['FT_F_Scene'];
        $GSINGLE_CREDIT = $row['FT_F_Bet'];
        $open = $row['OpenType'];
        if ($change == 1) {
            $bet_title = $nobettitle;
        }
        $mysql = "select M_Date,M_Time,MB_Team,TG_Team,M_League,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG from `match_sports` where Type IN ('FT','FU') and `M_Start`>now() and `MID`='$gid' and Cancel!=1 and Open=1 and MB_Team!=''";
        $result = $model->query($mysql);
        $row = current($result);
        $cou = count($result);

        if ($row['M_Date'] == date('Y-m-d')) {
            $active = 1;
            $class = "OFT";
            $caption = $Order_FT . $Order_Half_Full_Time_betting_order;
        } else {
            $active = 11;
            $class = "OFU";
            $caption = $Order_FT . $Order_Early_Market . $Order_Half_Full_Time_betting_order;
        }
        $M_League = $row['M_League'];
        $TG_Team = $row["TG_Team"];
        $MB_Team = filiter_team($row["MB_Team"]);
        switch ($rtype) {
            case "FHH":
                $M_Place = $MB_Team . '&nbsp;/&nbsp;' . $MB_Team;
                $M_Rate = $row["MBMB"];
                break;
            case "FHN":
                $M_Place = $MB_Team . '&nbsp;/&nbsp;' . $Draw;
                $M_Rate = $row["MBFT"];
                break;
            case "FHC":
                $M_Place = $MB_Team . '&nbsp;/&nbsp;' . $TG_Team;
                $M_Rate = $row["MBTG"];
                break;
            case "FNH":
                $M_Place = $Draw . '&nbsp;/&nbsp;' . $MB_Team;
                $M_Rate = $row["FTMB"];
                break;
            case "FNN":
                $M_Place = $Draw . '&nbsp;/&nbsp;' . $Draw;
                $M_Rate = $row["FTFT"];
                break;
            case "FNC":
                $M_Place = $Draw . '&nbsp;/&nbsp;' . $TG_Team;
                $M_Rate = $row["FTTG"];
                break;
            case "FCH":
                $M_Place = $TG_Team . '&nbsp;/&nbsp;' . $MB_Team;
                $M_Rate = $row["TGMB"];
                break;
            case "FCN":
                $M_Place = $TG_Team . '&nbsp;/&nbsp;' . $Draw;
                $M_Rate = $row["TGFT"];
                break;
            case "FCC":
                $M_Place = $TG_Team . '&nbsp;/&nbsp;' . $TG_Team;
                $M_Rate = $row["TGTG"];
                break;
        }
        $havesql = "select sum(BetScore) as BetScore from web_report_data where m_name='$memname' and MID='$gid' and linetype=7 and (Active=1 or Active=11)";
        $result = $model->query($havesql);
        $haverow = current($result);
        $have_bet = $haverow['BetScore'] + 0;

        $sql = "select * from match_league where  M_League='$M_League' and Type='FT'";
        $result = $model->query($sql);
        $league = current($result);
        $bettop = $GSINGLE_CREDIT;

        if ($bettop < $GSINGLE_CREDIT) {
            $bettop_money = $bettop;
        } else {
            $bettop_money = $GSINGLE_CREDIT;
        }
        $this->assigns(get_defined_vars());
        return $M_Rate;
    }

}