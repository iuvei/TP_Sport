<?php

// 本类由系统自动生成，仅供测试用途
class BethisAction extends HloginAction {

    /**
     * @todo 显示帐号明细
     * @param null
     */
    private $_host;

    public function __construct() {
        parent::__construct();
        $this->_host = $_SERVER['SERVER_NAME'];
    }

    public function index() {
        $lang1 = get_lang1();
        extract($lang1);
        $model = new Model();
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $mtype = $_REQUEST['mtype'];
        $chk_cw = $_REQUEST['chk_cw'];
        $sql = "select * from web_member_data where Oid='$uid' and Status<2";
        $result = $model->query($sql);
        $cou = count($result);
        $row = current($result);
        $name = $row['UserName'];
        $this->assign('money', array('Money'=>$row['Money'],'UserName'=>$_SESSION['username']));
        if ($chk_cw == '' or $chk_cw == 'Y' or $chk_cw == 'chk_cw') {
            $chk_cw = 'N';
            $ncancel = " and Cancel=0 and M_Result='' ";
            $caption = $Tod_Watch_Canceled_Wagers;
        } else {
            $chk_cw = 'Y';
            $ncancel = " and Cancel=1 and M_Date='" . date('Y-m-d') . "'";
            $caption = $Tod_Watch_Normal_Wagers;
        }
        if (empty($_REQUEST['qt'])) {
            $sql = "select count(*) as cut,Active from web_report_data where M_Name='$name' " . $ncancel . " and Active in(1,2) group by Active";
            $result = $model->query($sql);
            foreach ($result as $cut) {
                if ($cut['Active'] == 1) {
                    $ft = $cut['cut'];
                } else {
                    $bk = $cut['cut'];
                }
            }
            $ft = empty($ft) ? 0 : $ft;
            $bk = empty($bk) ? 0 : $bk;
            $this->assign('ft', $ft);
            $this->assign('bk', $bk);
            $this->display('Bethis:bet');
            exit();
        }
        $mDate = date('Y-m-d');
        $sql = "select ID,LineType,Active,M_Date,BetTime,Middle,BetType,BetScore,Gwin,OddsType,Cancel,Danger,Confirmed from web_report_data where M_Name='$name' " . $ncancel . " and Active={$_REQUEST['qt']} order by orderby,BetTime desc";
        $result = $model->query($sql);
        $cou = count($result);
        $tod_num = 1;
        $tod_bet = 0;
        $tod_win = 0;
        if ($cou == 0) {
            $rrr = '<div>&nbsp;</div>';
        }
        foreach ($result as $row) {
            $time = strtotime($row['BetTime']);
            $times = date("m", $time) . '月' . date("d", $time) . '日' . ',' . date("H:i:s", $time);
            switch ($row['Active']) {
                case 1:
                    $Title = $Tod_Soccer;
                    break;
                case 11:
                    $Title = $Tod_Soccer;
                    break;
                case 2:
                    $Title = $Tod_Basketball;
                    break;
                case 22:
                    $Title = $Tod_Basketball;
                    break;
            }

            switch ($row['OddsType']) {
                case 'H':
                    $Odds = '(' . $Tod_HK . ')';
                    break;
            }
            if ($row['Confirmed'] < 0) {
                $zttmp = 20 + abs($row['Confirmed']);
                $zttmp = 'Score' . $zttmp;
                $zt = $$zttmp;
            }
            if ($row['M_Date'] > $mDate) {
                $tDate = '<b>' . $row['M_Date'] . '</b>';
                if ($row['LineType'] == 7 or $row['LineType'] == 8) {
                    $middle = "<font color=#000000>" . $tDate . "</font>&nbsp;&nbsp;&nbsp;" . $row['Middle'];
                } else {
                    if ($row['active'] != 6) {
                        $data1 = explode("<br>", $row['Middle']);
                        $middle = $data1[0] . '<br>';
                        $middle = $middle . "<font color=#000000>$tDate</font>&nbsp;&nbsp;&nbsp;";
                        for ($j = 1; $j < sizeof($data1); $j++) {
                            $middle = $middle . $data1[$j] . '<br>';
                        }
                    } else {
                        $data1 = explode("<br>", $row['Middle']);

                        $middle = "<font color=#000000>$tDate</font>&nbsp;&nbsp;&nbsp;";
                        for ($j = 0; $j < sizeof($data1); $j++) {
                            $middle = $middle . $data1[$j] . '<br>';
                        }
                    }
                }
                $mor = '_mor';
            } else {
                $mor = '';
                $middle = $row['Middle'];
            }
            if ($row['Danger'] == 1 or $row['LineType'] == 9 or $row['LineType'] == 19 or $row['LineType'] == 10 or $row['LineType'] == 20 or $row['LineType'] == 21 or $row['LineType'] == 31 and $row['Cancel'] == 0) {
                if ($row['Danger'] == 1 and $row['Cancel'] == 0) {
                    $type = "<br><div class='danger'><span class='dan_green'>危险球 - 待确认</span></div>";
                } else if ($row['Danger'] == 0 and $row['Cancel'] == 0) {
                    $type = "<br><div class='danger'><span class='dan_green'>危险球 - 已确认</span></div>";
                }
            } else if ($row['Danger'] == 0) {
                $type = '';
            }
            if ($row['Cancel'] == 1) {
                $status = "<font color=#cc0000><b>" . $zt . "</b></font>";
            } else {
                $status = '未结算';
            }
            $rrr[] = "<div>
        <div>{$Title}    {$row['BetType']}</div>
        <div>时间：{$times}</div>
        <div>单号：<span class='his_wag'>" . show_voucher($row['LineType'], $row['ID'], $model) . "</span>{$Odds}</div>
        <div>{$middle}{$type}</div>
        <div>投注：{$row['BetScore']}</div>
        <div>可赢：" . number_format($row['Gwin'], 2) . "</div></div>";
            $tod_win = $tod_win + $row['Gwin'];
            $tod_num = $tod_num + 1;
            $tod_bet = $tod_bet + $row['BetScore'];
            $tDate = '';
        }
        $this->assign('rrr', $rrr);
        $this->assign('qt', $_REQUEST['qt']);
        $this->display();
    }

    public function history() {
        $lang1 = get_lang1();
        extract($lang1);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $mtype = $_REQUEST['mtype'];
        $sumall = 0;
        $rsumall = 0;
        $model = new Model();
        $sql = "select ID,UserName,Money from web_member_data where Oid='$uid' and status<2";
        $result = $model->query($sql);
        $cou = count($result);
        $row = current($result);
        $memname = $_SESSION['username'];
        $mid = $row['ID'];

        $this->assign('money', array('Money'=>$row['Money'],'UserName'=>$_SESSION['username']));

        $gtype = strtoupper($_REQUEST['gtype']);
        if ($gtype == '' or $gtype == 'ALL') {
            $gtype = 'ALL';
            $style = '_fu';
            $active = '';
        } else if ($gtype == 'FT') {
            $style = '';
            $active = ' and (Active=1 or Active=11)';
        } else {
            $style = '_' . strtolower($gtype);
            switch ($gtype) {
                case 'BK':
                    $active = ' and (active=2 or active=22)';
                    break;
                case 'BS':
                    $active = ' and (active=3 or active=33)';
                    break;
                case 'TN':
                    $active = ' and (active=4 or active=44)';
                    break;
                case 'VB':
                    $active = ' and (active=5 or active=55)';
                    break;
                case 'FS':
                    $active = ' and active=6';
                    break;
            }
        }
        $this->assign('gtype', $gtype);
        $gdate = $_REQUEST['gdate'];
        $gdate1 = $_REQUEST['gdate1'];
        if ($gdate == '' or $gdate == '') {
            $gdate1 = date('Y-m-d');
            $gdate = date('Y-m-d', strtotime('-7 day'));
        }
        $xq = array("$His_Week_Sun", "$His_Week_Mon", "$His_Week_Tue", "$His_Week_Wed", "$His_Week_Thu", "$His_Week_Fri", "$His_Week_Sat");
        $dd = 24 * 60 * 60;
        $t = time();
        $table = '';
        if ($gdate > $gdate1) {
            $t1 = $gdate;
            $gdate = $gdate1;
            $gdate1 = $t1;
        }
        $option = '';
        for ($i = 6; $i >= 0; $i--) {
            $today = date('Y-m-d', $t - $i * $dd);
            if ($gdate == $today) {
                $option.= "<option value='$today' selected>" . $today . "</option>";
            } else {
                $option.= "<option value='$today'>" . $today . "</option>";
            }
        }
        for ($i = 6; $i >= 0; $i--) {
            $today = date('Y-m-d', $t - $i * $dd);
            if ($gdate1 == $today) {
                $option1.= "<option value='$today' selected>" . $today . "</option>";
            } else {
                $option1.= "<option value='$today'>" . $today . "</option>";
            }
        }
        $this->assign('option', $option);
        $this->assign('option1', $option1);
        $Date_List_1 = explode("-", $gdate1);
        $Date_List_2 = explode("-", $gdate);
        $d1 = mktime(0, 0, 0, $Date_List_1[1], $Date_List_1[2], $Date_List_1[0]);
        $d2 = mktime(0, 0, 0, $Date_List_2[1], $Date_List_2[2], $Date_List_2[0]);
        $days = round(($d1 - $d2) / 3600 / 24);
        $table = array();
        for ($i = $days; $i >= 0; $i--) {
            $t = $d2 + $i * $dd;
            $today = date('m-d', $t) . ' ' . $xq[date("w", $t)];
            $sql = "select sum(vgold) as vgold,sum(betscore) as betscore,sum(m_result) as m_result from web_report_data where m_result!='' and m_date='" . date('Y-m-d', $d2 + $i * $dd) . "' and m_name='$memname'" . $active;
            $result = $model->query($sql);
            $row = current($result);
            $sum = $row['betscore'] + 0;
            $rsum = number_format($row['m_result'] + 0, 2);
            $rsum1 = number_format($row['vgold'] + 0, 2);

            $aa = $aa + $row['betscore'];
            $bb = $bb + $row['m_result'];
            $vgold = $vgold + $row['vgold'];

            if ($sum > 0) {
                $link = "/index.php/Bethis/hview/tmp_flag/N/today_gmt/" . date('Y-m-d', $t) . "/gtype/$gtype";
                $none = '';
            } else {
                $link = "#";
                $none = '_none';
            }
            $table[] = array('link' => $link, 'sum' => $sum, 'rsum' => $rsum, 'vgold' => $rsum1, 'today' => $today);
        }
        $this->assign('tables', $table);
        $this->assign('aa', $aa);
        $this->assign('bb', $bb);
        $this->assign('vgold', $vgold);
        $this->display();
    }

    public function hview() {
        $lang1 = get_lang1();
        extract($lang1);
        $uid = $_SESSION['uid'];
        $langx = $_SESSION['langx'];
        $gtype = $_REQUEST['gtype'];
        $mDate = $_REQUEST['today_gmt'];
        $model = new Model();
        $username = $_SESSION['username'];
        $view_date = explode('-', $mDate);
        $abc = date('d') - $view_date[2];
        $t = time() - $abc * 24 * 60 * 60;
        $xq = array("$His_Week_Sun", "$His_Week_Mon", "$His_Week_Tue", "$His_Week_Wed", "$His_Week_Thu", "$His_Week_Fri", "$His_Week_Sat");
        if ($gtype == 'ALL') {
            $gtype = 'FT';
        }
        $no = 1;
        $quinella = 0;
        $m_result = 0;
        $sql = "select ID,MID,Active,LineType,Mtype,M_Date,BetTime,BetType,Middle,BetScore,ShowType,M_Place,M_Rate,OddsType,M_Result,betid,Cancel,Confirmed from web_report_data where M_Name='$username' and M_Date='$mDate' and M_Result!='' and Active in(1,2,11,22)  order by orderby,bettime desc";
        $result = $model->query($sql);
        foreach ($result as $row) {
            $time = strtotime($row['BetTime']);
            $times = date("m", $time) . '月' . date("d", $time) . '日' . ',' . date("H:i:s", $time);
            switch ($row['Active']) {
                case 1:
                    $Title = $His_Soccer;
                    $data = 'match_foot';
                    break;
                case 11:
                    $Title = $His_Soccer;
                    $data = 'match_foot';
                    break;
                case 2:
                    $Title = $His_Baseketball;
                    $data = 'match_bask';
                    break;
                case 22:
                    $Title = $His_Baseketball;
                    $data = 'match_bask';
                    break;
            }

            switch ($row['OddsType']) {
                case 'H':
                    $Odds = '(' . $Tod_HK . ')';
                    break;
            }

            if ($row['LineType'] == 9 or $row['LineType'] == 19 or $row['LineType'] == 10 or $row['LineType'] == 20 or $row['LineType'] == 21 or $row['LineType'] == 31) {
                $class = '_even';
            } else {
                $class = '_first';
            }
            $tbl = '';
            $tbl.="<div>";
            $tbl.="<div>{$Title}{$row['BetType']}<br/>时间:{$times}<br/>单号:" . show_voucher($row['LineType'], $row['ID'], $model) . "{$Odds}</div>";
            $tbl.="<div>{$row['Middle']}</div>";
            $tbl.="<div><span class='fin_gold'>投注：{$row['BetScore']}</span><br/>派彩：" . number_format($row['M_Result'], 2) . "</div>";
            $tbl.="<div>";
            if ($row['Cancel'] == 1) {
                if ($row['Confirmed'] < 0) {
                    $zttmp = 20 + abs($row['Confirmed']);
                    $zttmp = 'Score' . $zttmp;
                    $zt = $$zttmp;
                }
            }

            if ($row['LineType'] == 8) {
                $midd = explode('<br>', $row['Middle']);
                $mid = explode(',', $row['MID']);
                $show = explode(',', $row['ShowType']);
                $mtype = explode(',', $row['Mtype']);
                for ($t = 0; $t < (sizeof($midd) - 1) / 3; $t++) {
                    $mysql = "select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from match_sports where MID=" . $mid[$t];
                    $result1 = $model->query($mysql);
                    $row1 = current($result1);
                    if ($row1["MB_Inball"] == '-1') {
                        $sc = 'Score' . abs($row1["MB_Inball"]);
                        $ty = $$sc;
                        $font_a3 = '<font color="#009900"><b>' . $ty . '</b></font>&nbsp;';
                        $font_a4 = '<font color="#009900"><b>' . $ty . '</b></font>&nbsp;';
                    } else {
                        $font_a3 = '<font color="#009900"><b>' . $row1["TG_Inball"] . ':' . $row1["MB_Inball"] . '</b></font>&nbsp;';
                        $font_a4 = '<font color="#009900"><b>' . $row1["MB_Inball"] . ':' . $row1["TG_Inball"] . '</b></font>&nbsp;';
                    }
                    if ($show[$t] == 'C' and ( $mtype[$t] == 'RH' or $mtype[$t] == 'RC') and $row['LineType'] == 8) {
                        $tbl.= "半场:" . $font_a1 . "<br>";
                        $tbl.= "全场:" . $font_a3 . "<div class='statement_textbox2'></div>";
                    } else {
                        $tbl.= "半场:" . $font_a2 . "<br>";
                        $tbl.= "全场:" . $font_a4 . "<div class='statement_textbox2'></div>";
                    }
                }
            } else {
                $mysql = "select MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR from match_sports where MID=" . $row['MID'];
                $result1 = $model->query($mysql);
                $row1 = current($result1);

                if ($row1["MB_Inball"] == '-1') {
                    if ($row1["MB_Inball_HR"] == '-1' and $row1["MB_Inball"] == '-1') {
                        $sc = 'Score' . abs($row1["MB_Inball"]);
                        $font_a1 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                        $font_a2 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                        $font_a3 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                        $font_a4 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                    } else {
                        $sc = 'Score' . abs($row1["MB_Inball"]);
                        $font_a2 = '<font color="#009900"><b>' . $row1["MB_Inball_HR"] . ':' . $row1["TG_Inball_HR"] . '</b></font>&nbsp;';
                        $font_a1 = '<font color="#009900"><b>' . $row1["TG_Inball_HR"] . ':' . $row1["MB_Inball_HR"] . '</b></font>&nbsp;';
                        $font_a3 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                        $font_a4 = '<font color="#009900"><b>' . $$sc . '</b></font>&nbsp;';
                    }
                } else {
                    $font_a3 = '<font color="#009900"><b>' . $row1["TG_Inball"] . ':' . $row1["MB_Inball"] . '</b></font>&nbsp;';
                    $font_a4 = '<font color="#009900"><b>' . $row1["MB_Inball"] . ':' . $row1["TG_Inball"] . '</b></font>&nbsp;';
                    $font_a1 = '<font color="#009900"><b>' . $row1["TG_Inball_HR"] . ':' . $row1["MB_Inball_HR"] . '</b></font>&nbsp;';
                    $font_a2 = '<font color="#009900"><b>' . $row1["MB_Inball_HR"] . ':' . $row1["TG_Inball_HR"] . '</b></font>&nbsp;';
                }
                if ($row['M_Result'] == 0 and $row['Cancel'] == 1) {
                    $tbl.= $zt . '<br>';
                } else if ($row['M_Result'] > 0 and $row['Cancel'] == 0) {
                    $tbl.= '赢<br>';
                } else if ($row['M_Result'] < 0 and $row['Cancel'] == 0) {
                    $tbl.= '输<br>';
                }
                if ($row['LineType'] == 11 or $row['LineType'] == 12 or $row['LineType'] == 13 or $row['LineType'] == 14 or $row['LineType'] == 19 or $row['LineType'] == 20 or $row['LineType'] == 31) {
                    if ($row['ShowType'] == 'C' and ( $row['LineType'] == 12 or $row['LineType'] == 19)) {
                        $tbl.= "半场:" . $font_a1 . "<br>";
                    } else {
                        $tbl.= "半场:" . $font_a2 . "<br>";
                    }
                } else {
                    if ($row['ShowType'] == 'C' and ( $row['LineType'] == 2 or $row['LineType'] == 9)) {
                        $tbl.= "全场:" . $font_a3;
                    } else {
                        $tbl.= "全场:" . $font_a4;
                    }
                }
            }

            $tbl.="</div>";
            $tbls[] = $tbl;
            $no++;
            $quinella+=$row['BetScore'];
            $m_result+=$row['M_Result'];
        }
        $money = current($model->query("select Money,UserName from web_member_data where UserName='{$_SESSION['username']}'"));
        $this->assign('money', $money);
        $this->assign('tbls', $tbls);
        $this->display();
    }
}
