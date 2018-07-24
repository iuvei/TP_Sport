<?php
function get_ip(){

    if($_SERVER['HTTP_X_FORWARDED_FOR']){

        $onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        $c_agentip=1;

    }elseif($_SERVER['HTTP_CLIENT_IP']){

        $onlineip = $_SERVER['HTTP_CLIENT_IP'];
        $c_agentip=1;

    }else{

        $onlineip = $_SERVER['REMOTE_ADDR'];
        $c_agentip=0;

    }
    //
    $ols=explode(',',$onlineip);
    $re=array();
    foreach($ols as $ip){
        $ip = trim($ip);
        $ip=long2ip(ip2long($ip));
        $re[]=$ip;
        if(!filter_var($ip,FILTER_VALIDATE_IP)){
            header("location:/");
            exit();
        }
    }
    return implode(',',$re);

}
//function get_ip() {
//    if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
//        $onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
//        $c_agentip = 1;
//    } elseif ($_SERVER['HTTP_CLIENT_IP']) {
//        $onlineip = $_SERVER['HTTP_CLIENT_IP'];
//        $c_agentip = 1;
//    } else {
//        $onlineip = $_SERVER['REMOTE_ADDR'];
//        $c_agentip = 0;
//    }
//    return substr($onlineip,0,15);
//}


function hidden_part($source, $l_length, $r_length, $fill, $is_chn) {
        if ($is_chn == 1) {
            $l_str = mb_substr($source, 0, $l_length);
            $r_length = -1 * $r_length;
            $r_str = mb_substr($source, $r_length);
        } else {
            $l_str = substr($source, 0, $l_length);

            $r_str = substr($source, -1 * $r_length);
        }

        $result_str = $l_str . $fill . $r_str;
        return $result_str;
    }

function change_rate($c_type, $c_rate) {
    switch ($c_type) {
	case 'A':
		$t_rate='0';
		break;
	case 'B':
		$t_rate='0';
		break;
	case 'C':
		$t_rate='0';
		break;
	case 'D':
		$t_rate='0';
            break;
    }
    if ($c_rate != '' and $c_rate != '0') {
        $change_rate = number_format($c_rate, 2);
        if ($change_rate <= 0 and $change_rate >= -0.02) {
            $change_rate = '';
        }
    } else {
        $change_rate = '';
    }
    return $change_rate;
}

function num_rate($c_type, $c_rate) {
    switch ($c_type) {
        case 'A':
            $t_rate = '0';
            break;
        case 'B':
            $t_rate = '0';
            break;
        case 'C':
            $t_rate = '0';
            break;
        case 'D':
            $t_rate = '0';
            break;
    }
    if ($c_rate != '') {
        $num_rate = number_format($c_rate - $t_rate, 2);
        if ($num_rate <= 0) {
            $num_rate = '';
        }
    } else {
        $num_rate = '';
    }
    return $num_rate;
}

function singleset($ptype, $model) {
    //var_dump($model);
    $sql = "select $ptype as P3,R,MAX from web_system_data where ID=1";
    $result = $model->query($sql);
    $row = current($result);
    $p = $row['P3'];
    $pmax = $row['MAX'];
    return array($p, $pmax);
}

function filiter_team($repteam) {
   $repteam=trim(str_replace("[H]","",$repteam));
	$repteam=trim(str_replace("[主]","",$repteam));
	$repteam=trim(str_replace("[中]","",$repteam));
	$repteam=trim(str_replace("[主]","",$repteam));
	$repteam=trim(str_replace("[中]","",$repteam));
	$repteam=trim(str_replace("[Home]","",$repteam));
	$repteam=trim(str_replace("[Mid]","",$repteam));
    $filiter_team = $repteam;
    return $filiter_team;
}

function fileter0($rate) {
    for ($i = 1; $i < strlen($rate); $i++) {
        if (substr($rate, -$i, 1) <> '0') {
            if (substr($rate, -$i, 1) == '.') {
                $fileter0 = substr($rate, 0, strlen($rate) - $i);
            } else {
                $fileter0 = substr($rate, 0, strlen($rate) - $i + 1);
            }
            break;
        }
    }
    return $fileter0;
}

function Decimal_point($tmpior, $show) {
    $sign = "";
    $sign = (($tmpior < 0) ? "Y" : "N");
    $tmpior = (floor(abs($tmpior) * $show + 1 / $show)) / $show;
    return ($tmpior * (($sign == "Y") ? -1 : 1));
}

function show_voucher($line, $id, $model) {
    $sql = "select OUID,DTID,PMID from web_system_data";
    $result = $model->query($sql);
    $row = current($result);
    $ouid = $row['OUID'];
    $dtid = $row['DTID'];
    $pmid = $row['PMID'];
    switch ($line) {
        case 1:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 2:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 3:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 4:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 5:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 6:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 7:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 8:
            $show_voucher = 'PM' . ($id + $pmid);
            break;
        case 9:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 10:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 11:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 12:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 13:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 14:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 15:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 16:
            $show_voucher = 'DT' . ($id + $dtid);
            break;
        case 19:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 20:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 21:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
        case 31:
            $show_voucher = 'OU' . ($id + $ouid);
            break;
    }
    return $show_voucher;
}

function get_other_ioratio($odd_type, $iorH, $iorC, $showior) {
    $out = Array();
    if ($iorH != "" || $iorC != "") {
        $out = chg_ior($odd_type, $iorH, $iorC, $showior);
    } else {
        $out[0] = $iorH;
        $out[1] = $iorC;
    }
    return $out;
}

function chg_ior($odd_f, $iorH, $iorC, $showior) {
    $ior = Array();
    if ($iorH < 3)
        $iorH *=1000;
    if ($iorC < 3)
        $iorC *=1000;
    $iorH = $iorH;
    $iorC = $iorC;
    switch ($odd_f) {
        case "H": //香港變盤(輸水盤)
            $ior = get_HK_ior($iorH, $iorC);
            break;
        case "M": //馬來盤
            $ior = get_MA_ior($iorH, $iorC);
            break;
        case "I" : //印尼盤
            $ior = get_IND_ior($iorH, $iorC);
            break;
        case "E": //歐洲盤
            $ior = get_EU_ior($iorH, $iorC);
            break;
        default: //香港盤
            $ior[0] = $iorH;
            $ior[1] = $iorC;
    }
    $ior[0]/=1000;
    $ior[1]/=1000;
    $ior[0] = Decimal_point($ior[0], $showior);
    $ior[1] = Decimal_point($ior[1], $showior);
    //$ior[0]=number(Decimal_point($ior[0],$showior),3);
    //$ior[1]=number(Decimal_point($ior[1],$showior),3);
    return $ior;
}

function get_HK_ior($H_ratio, $C_ratio) {
    $out_ior = Array();
    $line = "";
    $lowRatio = "";
    $nowRatio = "";
    $highRatio = "";
    $nowType = "";
    if ($H_ratio <= 1000 && $C_ratio <= 1000) {
        $out_ior[0] = $H_ratio;
        $out_ior[1] = $C_ratio;
        return $out_ior;
    }
    $line = 2000 - ( $H_ratio + $C_ratio );
    if ($H_ratio > $C_ratio) {
        $lowRatio = $C_ratio;
        $nowType = "C";
    } else {
        $lowRatio = $H_ratio;
        $nowType = "H";
    }
    if (((2000 - $line) - $lowRatio) > 1000) {
        //對盤馬來盤
        $nowRatio = ($lowRatio + $line) * (-1);
    } else {
        //對盤香港盤
        $nowRatio = (2000 - $line) - $lowRatio;
    }
    if ($nowRatio < 0) {
        $highRatio = (abs(1000 / $nowRatio) * 1000);
    } else {
        $highRatio = (2000 - $line - $nowRatio);
    }
    if ($nowType == "H") {
        $out_ior[0] = $lowRatio;
        $out_ior[1] = $highRatio;
    } else {
        $out_ior[0] = $highRatio;
        $out_ior[1] = $lowRatio;
    }
    return $out_ior;
}

function get_MA_ior($H_ratio, $C_ratio) {
    $out_ior = Array();
    $line = "";
    $lowRatio = "";
    $highRatio = "";
    $nowType = "";
    if (($H_ratio <= 1000 && $C_ratio <= 1000)) {
        $out_ior[0] = $H_ratio;
        $out_ior[1] = $C_ratio;
        return $out_ior;
    }
    $line = 2000 - ( $H_ratio + $C_ratio );
    if ($H_ratio > $C_ratio) {
        $lowRatio = $C_ratio;
        $nowType = "C";
    } else {
        $lowRatio = $H_ratio;
        $nowType = "H";
    }
    $highRatio = ($lowRatio + $line) * (-1);
    if ($nowType == "H") {
        $out_ior[0] = $lowRatio;
        $out_ior[1] = $highRatio;
    } else {
        $out_ior[0] = $highRatio;
        $out_ior[1] = $lowRatio;
    }
    return $out_ior;
}

function get_IND_ior($H_ratio, $C_ratio) {
    $out_ior = Array();
    $out_ior = get_HK_ior($H_ratio, $C_ratio);
    $H_ratio = $out_ior[0];
    $C_ratio = $out_ior[1];
    $H_ratio /= 1000;
    $C_ratio /= 1000;
    if ($H_ratio < 1) {
        $H_ratio = (-1) / $H_ratio;
    }
    if ($C_ratio < 1) {
        $C_ratio = (-1) / $C_ratio;
    }
    $out_ior[0] = $H_ratio * 1000;
    $out_ior[1] = $C_ratio * 1000;
    return $out_ior;
}

function get_EU_ior($H_ratio, $C_ratio) {
    $out_ior = Array();
    $out_ior = get_HK_ior($H_ratio, $C_ratio);
    $H_ratio = $out_ior[0];
    $C_ratio = $out_ior[1];
    $out_ior[0] = $H_ratio + 1000;
    $out_ior[1] = $C_ratio + 1000;
    return $out_ior;
}

function get_lang() {
    return array(
        'Soccer' => '足球',
        'Soccer_Early' => '足球早餐',
        'BK_NFL' => '篮球/美足',
        'BK_NFL_Early' => '篮球/美足早餐',
        'Straight_All' => '全部',
        'Quarter' => '单节',
        'Straight' => '单式',
        'half_1st' => '上半场',
        'Running_Ball' => '',
        'Correct_Score' => '波胆',
        'Half_1st_Correct_Score' => '上半波胆',
        'Total_Goals' => '1 x 2 & 入球数',
        'Half_Full_Time' => '半全场',
        'Mix_Parlay' => '综合过关',
        'Parlay' => '标准过关',
        'Handicap_Parlay' => '让分过关',
        'Results' => '比赛结果',
        'Single_Game_Limit' => "单场限额",
        'Single_Bet_Limit' => "单注限额",
        'Mem_Soccer' => '足球',
        'Mem_Baseketball' => '篮球',
        'Mem_Tennis' => '网球',
        'Mem_VolleyBall' => '排球',
        'Mem_BaseBall' => '棒球',
        'Mem_Other' => '其他',
        'Mem_Handicap' => '单式让球',
        'Mem_Over_Under' => '单式大小',
        'Mem_1_x_2' => '单式独赢',
        'Mem_Running_Ball' => '滚球',
        'Mem_R_B_Over_Under' => '滚球大小',
        'Mem_R_B_1_x_2' => '滚球独赢',
        'Mem_1_x_2_Parlay' => '标准过关',
        'Mem_Handicap_Parlay' => '让球过关',
        'Mem_Mix_Parlay' => '综合过关',
        'Mem_Correct_Score' => '波胆',
        'Mem_Total_Goals' => '入球数',
        'Mem_Odd_Even' => '单双',
        'Mem_Half_Full_Time' => '半全场',
        'His_Game_account_history' => '赛事历史资料',
        'His_Account_history' => '回历史资料',
        'His_Betting_Time' => '交易时间',
        'His_Wager_No' => '方式',
        'His_Explain' => '内容',
        'His_Quinella' => '投注',
        'Tod_Soccer' => '足球',
        'Tod_Basketball' => '篮球',
        'Refresh' => '更新',
        'Full' => '全场',
        'Half_1st' => '上半场',
        'WIN' => '独赢',
        'HDP' => '让球',
        'OU' => '大小',
        'OE' => '单双',
        'Home' => '主队',
        'Away' => '客队',
        'Handicap' => '让球',
        'Over_Under' => '大小',
        'Odd_Even' => '单双',
        'Res_Soccer' => '足球',
        'Res_Basketball' => '篮球',
        'Res_Results' => '赛事结果',
        'Res_Time' => '时间',
        'Res_NO' => '场次',
        'Res_Half' => '半场',
        'Res_Final' => '全场',
        'Res_Game' => '完赛(局)',
        'Res_Point' => '完赛(盘)',
        'Res_Set' => '完赛(局)',
        'Res_1st_Half' => '上半',
        'Res_2nd_Half' => '下半',
        'Res_Extra_Time' => '加时',
        'Order_FT' => '足球',
        'Order_Other' => '其他',
        'Order_TN' => '网球',
        'Order_VB' => '排球',
        'Order_BS' => '棒球',
        'Order_Basketball' => '篮球',
        'Order_Early_Market' => '早餐',
        'Order_1_x_2_betting_order' => '单式独赢交易单',
        'Order_Handicap_betting_order' => '单式让球交易单',
        'Order_Over_Under_betting_order' => '单式大小交易单',
        'Order_Odd_Even_betting_order' => '单双交易单',
        'Order_1st_Half_1_x_2_betting_order' => '上半场独赢交易单',
        'Order_1st_Half_Handicap_betting_order' => '上半场让球交易单',
        'Order_1st_Half_Over_Under_betting_order' => '上半场大小交易单',
        'Order_Running_1_x_2_betting_order' => '滚球独赢交易单',
        'Order_Running_Ball_betting_order' => '滚球交易单',
        'Order_Running_Ball_Over_Under_betting_order' => '滚球大小交易单',
        'Order_1st_Half_Running_1_x_2_betting_order' => '上半场滚球独赢交易单',
        'Order_1st_Half_Running_Ball_betting_order' => '上半滚球交易单',
        'Order_1st_Half_Running_Ball_Over_Under_betting_order' => '足球上半滚球大小交易单',
        'Order_Correct_Score_betting_order' => '波胆交易单',
        'Order_1st_Half_Correct_Score_betting_order' => '上半波胆交易单',
        'Order_Total_Goals_betting_order' => '入球数交易单',
        'Order_Half_Full_Time_betting_order' => '半全场交易单',
        'Order_Mix_Parlay_betting_order' => '综合过关交易单',
        'Order_1_x_2_Parlay_betting_order' => '标准过关交易单',
        'Order_Handicap_Parlay_betting_order' => '让分过关交易单',
        'Order_betting_order' => '交易单',
        'Order_Login_Name' => '帐户名称：',
        'Order_Credit_line' => '可用额度：',
        'Order_Currency' => '使用币别：',
        'Order_1st_Half' => '上半',
        'Order_2nd_Half' => '下半',
        'Order_1st_Quarter' => '第一节',
        'Order_2nd_Quarter' => '第二节',
        'Order_3rd_Quarter' => '第三节',
        'Order_4th_Quarter' => '第四节',
        'Order_Odd' => '单',
        'Order_Even' => '双',
        'Order_This_odd_is_the_latest' => '此为最新赔率',
        'Order_Other_Score' => '其它比分',
        'Special0' => 'To Kick Off]',
        'Special1' => 'No. of Corners]',
        'Special2' => 'No. of Yellow Cards]',
        'Special3' => '1st Corner]',
        'Special4' => '第一張黃牌]',
        'Special5' => '第一張紅牌]',
        'Special6' => 'No. of Substitutions]',
        'Special7' => '1st Substitution]',
        'Special8' => 'last Substitutions]',
        'Special9' => ']',
        'Special10' => ']',
        'Special11' => '1st To Score]',
        'Special12' => 'Last to Score]',
        'Special13' => 'Total Assists]',
        'Special14' => 'Total Three Point Shots]',
        'Special15' => 'Total Rebounds]',
        'Special16' => 'Total Steals]',
        'Special17' => 'Total Blocked Shots]',
        'Special18' => ']',
        'Special19' => ']',
        'Special10' => ']',
        'Draw' => '和局'
    );
}

function get_lang1(){
    return array('mb_team'=>'MB_Team',
    'tg_team'=>'TG_Team',
    'm_league'=>'M_League',
    'm_item'=>'M_Item',
    'bettype'=>'BetType',
    'middle'=>'Middle',
    'message'=>'Message',
    'Chg_Setting_Login_ID'=>'设置登录帐号',
    'Chg_Setting_Guide'=>'设置指引',
    'Chg_Setting_Rules'=>'设置规则',
    'Chg_Login_ID'=>'登录帐号',
    'Chg_Check'=>'检查',
    'Chg_Note'=>'注意',
    'Chg_The_setting_cannot_be_modified'=>'设置后将无法修改',
    'Chg_Submit'=>'确认',
    'Chg_Cancel'=>'取消',
    'Chg_After_completion'=>'如阁下的「登录帐号」设置完成后',
    'Chg_members_have_to_use_the_new_Login_ID_to_login'=>'须以「登录帐号」登入会员端，',
    'Chg_and_the_original_Members_ID_is_only_for_identification_purpose'=>'原「会员帐号」只供识别身份使用，不可登入。',
    'Mem_Login_ID_has_not_input'=>'帐号未输入!!',
    'Mem_This_ID_has_been_used'=>'此帐号已有人使用',
    'Mem_You_can_using_this_ID'=>'此帐号无人使用',
    'Sel_Check_your_Account'=>'请检视您的帐户',
    'Sel_Login_Name'=>'会员帐号：',
    'Sel_Login_ID'=>'登录帐号：',
    'Sel_Credit_Line'=>'信用额度：',
    'Sel_Currency'=>'使用币别：',
    'Sel_Please_select_team_you_want_to_bet'=>'请选择交易队伍&raquo;',
    'Sel_Lastest_10_sheet'=>'最新十笔交易状况',
    'Top_Please_refresh_after_the_last_transaction_to_check_your_bet_status'=>'请在交易后按更新查询最新交易状态!!',
    'Sel_Open_Record'=>'开启下注纪录',
    'Sel_Refresh'=>'更新',
    'System_Optimizing_Wait_a_moment_please'=>'<fontcolor=red>系统最佳化中请稍后..</font>',
    'Est'=>'美东',
    'Information'=>'资讯',
    'Rules'=>'规则说明',
    'Antivirus'=>'防毒软件设置说明',
    'Odds_Conversion'=>'盘口使用方法',
    'Data'=>'会员资料',
    'Wap'=>'手机交易',
    'Saft_internet'=>'网路安全工具',
    'Cust_Service'=>'服务中心',
    'LogOut'=>'回首页',
    'Live_TV'=>'现场直播',
    'Account_History'=>'帐户历史',
    'Transaction_Record'=>'交易状况',
    'Soccer'=>'足球',
    'Soccer_Early'=>'足球早餐',
    'Other'=>'其他',
    'Other_Early'=>'其他早餐',
    'Tennis'=>'网球',
    'VolleyBall'=>'排球',
    'BS'=>'棒球',
    'BK_NFL'=>'篮球/美足',
    'Early'=>'早餐种类',
    'TN_Early'=>'网球早餐',
    'VB_Early'=>'排球早餐',
    'BS_Early'=>'棒球早餐',
    'BK_NFL_Early'=>'篮球/美足早餐',
    'Outright'=>'冠军',
    'HK_Odds'=>'香港盘',
    'Malay_Odds'=>'马来盘',
    'Indo_Odds'=>'印尼盘',
    'Euro_Odds'=>'欧洲盘',
    'Straight_All'=>'全部',
    'Quarter'=>'单节',
    'Straight'=>'单式',
    'half_1st'=>'上半场',
    'Running_Ball'=>'滚球',
    'Correct_Score'=>'波胆',
    'Half_1st_Correct_Score'=>'上半波胆',
    'Total_Goals'=>'1x2&入球数',
    'Half_Full_Time'=>'半全场',
    'Mix_Parlay'=>'综合过关',
    'Parlay'=>'标准过关',
    'Handicap_Parlay'=>'让分过关',
    'Index'=>'金融',
    'Outright_Results'=>'冠军结果',
    'Index_Results'=>'金融结果',
    'Results'=>'比赛结果',
    'Edit_Password'=>'修改密码',
    'Real_news'=>'即时资讯',
    'Login_Name'=>'会员帐号',
    'Credit_Line'=>'信用额度',
    'Single_Game_Limit'=>'单场限额',
    'Single_Bet_Limit'=>'单注限额',
    'Mem_Soccer'=>'足球',
    'Mem_Baseketball'=>'篮球',
    'Mem_Tennis'=>'网球',
    'Mem_VolleyBall'=>'排球',
    'Mem_BaseBall'=>'棒球',
    'Mem_Other'=>'其他',
    'Mem_Handicap'=>'单式让球',
    'Mem_Over_Under'=>'单式大小',
    'Mem_1_x_2'=>'单式独赢',
    'Mem_Running_Ball'=>'滚球',
    'Mem_R_B_Over_Under'=>'滚球大小',
    'Mem_R_B_1_x_2'=>'滚球独赢',
    'Mem_1_x_2_Parlay'=>'标准过关',
    'Mem_Handicap_Parlay'=>'让球过关',
    'Mem_Mix_Parlay'=>'综合过关',
    'Mem_Correct_Score'=>'波胆',
    'Mem_Total_Goals'=>'入球数',
    'Mem_Odd_Even'=>'单双',
    'Mem_Half_Full_Time'=>'半全场',
    'His_All'=>'全部',
    'His_Soccer'=>'足球',
    'His_Baseketball'=>'篮球',
    'His_Tennis'=>'网球',
    'His_VolleyBall'=>'排球',
    'His_BaseBall'=>'棒球',
    'His_Outright'=>'冠军',
    'His_Other'=>'其他',
    'His_Search'=>'查询',
    'His_Date'=>'日期',
    'His_Bet_Amount'=>'交易金额',
    'His_Landing'=>'结果',
    'His_Valid_Amount'=>'有效金额',
    'His_Week_Mon'=>'星期一',
    'His_Week_Tue'=>'星期二',
    'His_Week_Wed'=>'星期三',
    'His_Week_Thu'=>'星期四',
    'His_Week_Fri'=>'星期五',
    'His_Week_Sat'=>'星期六',
    'His_Week_Sun'=>'星期日',
    'His_Week_Total'=>'本周小计',
    'His_Date'=>'日期',
    'His_year'=>'年',
    'His_month'=>'月',
    'His_date'=>'日',
    'His_Game_account_history'=>'赛事历史资料',
    'His_Account_history'=>'回历史资料',
    'His_Betting_Time'=>'交易时间',
    'His_Wager_No'=>'方式',
    'His_Explain'=>'内容',
    'His_Quinella'=>'投注',
    'His_Result'=>'结果',
    'Tod_Soccer'=>'足球',
    'Tod_Other'=>'其他',
    'Tod_Tennis'=>'网球',
    'Tod_VolleyBall'=>'排球',
    'Tod_Baseball'=>'棒球',
    'Tod_Basketball'=>'篮球',
    'Tod_Outright'=>'冠军',
    'Tod_Index'=>'指数',
    'Tod_Watch_Canceled_Wagers'=>'观看取消交易单',
    'Tod_Watch_Normal_Wagers'=>'观看有效交易单',
    'Tod_text'=>'本日并无交易资料，或者您今日交易已有结果。',
    'Tod_Betting_Time'=>'交易时间',
    'Tod_Wager_No'=>'方式',
    'Tod_Explain'=>'内容',
    'Tod_Quinella'=>'投注',
    'Tod_Estimated_Payout'=>'可赢',
    'Tod_HK'=>'香港盘',
    'Tod_Malay'=>'马来盘',
    'Tod_Indo'=>'印尼盘',
    'Tod_Euro'=>'欧洲盘',
    'Tod_Confirmed'=>'正在确认中',
    'Tod_Cancel'=>'取消',
    'Chg_Please_Key_in_Password'=>'请输入密码',
    'Chg_Password'=>'密码：',
    'Chg_Confirm_Password'=>'确认密码：',
    'Chg_Submit'=>'确认',
    'Chg_Cancel'=>'取消',
    'Chg_Password_must_involve_numbers_and_letters'=>'密码需使用字母加上数字。',
    'Chg_Password_must_include_6_12_words'=>'密码必须至少6个字元长，最多12个字元长。',
    'Scr_Number'=>'编号',
    'Scr_Time'=>'时间',
    'Scr_News'=>'公告内容',
    'Early_Market'=>'早餐',
    'News_History'=>'历史讯息',
    'Refresh'=>'更新',
    'second_auto_update_Odds_type'=>'secondautoupdateOddstype',
    'Odds_type'=>'盘口设定',
    'Date'=>'日期',
    'S_EM'=>'特早',
    'League'=>'选择联盟',
    'All'=>'全部',
    'ALL'=>'全部',
    'Times'=>'时间',
    'Home_Away'=>'主客队伍',
    'Full'=>'全场',
    'Half_1st'=>'上半场',
    'WIN'=>'独赢',
    'HDP'=>'让球',
    'OU'=>'大小',
    'OE'=>'单双',
    'o'=>'单',
    'e'=>'双',
    'Home'=>'主队',
    'Away'=>'客队',
    'Handicap'=>'让球',
    'Over_Under'=>'大小',
    'Odd_Even'=>'单双',
    'Score'=>'比数',
    'Others'=>'其它',
    'Draw'=>'和局',
    'more'=>'多种玩法',
    'str_even'=>'和局',
    'str_submit'=>'确认',
    'str_reset'=>'重设',
    'HH'=>'主/主',
    'HD'=>'主/和',
    'HA'=>'主/客',
    'DH'=>'和/主',
    'DD'=>'和/和',
    'DA'=>'和/客',
    'AH'=>'客/主',
    'AD'=>'客/和',
    'AA'=>'客/客',
    'second_auto_update'=>'秒自动更新',
    'manual_update'=>'手动更新',
    'Correct_Score_maximum_FT'=>'每注最高派彩以RMB1000000为上限(其它:非表格所列之比分)',
    'Correct_Score_maximum_OP'=>'每注最高派彩以RMB1000000为上限(其它:非表格所列之比分)',
    'Correct_Score_maximum_TN'=>'每注最高派彩以RMB1000000为上限',
    'Correct_Score_maximum_VB'=>'每注最高派彩以RMB1000000为上限',
    'Correct_Score_maximum_BS'=>'每注最高派彩以RMB1000000为上限(+9up:净赢9或以上)',
    'Total_Goals_maximum'=>'每注最高派彩以RMB1000000为上限(7up:总入球7球或以上)',
    'Half_Full_Time_maximum'=>'每注最高派彩以RMB1000000为上限',
    'Mix_Parlay_maximum'=>'每注最高派彩以RMB1000000为上限',
    'Body_Select_League'=>'选择联盟',
    'Body_Enter'=>'决定',
    'Body_Back'=>'返回',
    'Body_Select_All'=>'全部选取',
    'Res_Soccer'=>'足球',
    'Res_Other'=>'其他',
    'Res_Tennis'=>'网球',
    'Res_VolleyBall'=>'排球',
    'Res_Baseball'=>'棒球',
    'Res_Basketball'=>'篮球',
    'Res_Outright'=>'冠军',
    'Res_Index'=>'指数',
    'Res_Results'=>'赛事结果',
    'Res_yesterday'=>'昨日',
    'Res_tomorrow'=>'明日',
    'Res_Time'=>'时间',
    'Res_NO'=>'场次',
    'Res_Teams'=>'比赛队伍',
    'Res_Game_teams'=>'比赛队伍',
    'Res_Half'=>'半场',
    'Res_Final'=>'全场',
    'Res_Game'=>'完赛(局)',
    'Res_Point'=>'完赛(盘)',
    'Res_Set'=>'完赛(局)',
    'Res_1st_Half'=>'上半',
    'Res_2nd_Half'=>'下半',
    'Res_Extra_Time'=>'加时',
    'Res_League'=>'联盟玩法',
    'Res_Win'=>'胜出',
    'Res_Search'=>'查询',
    'Order_FT'=>'足球',
    'Order_Other'=>'其他',
    'Order_TN'=>'网球',
    'Order_VB'=>'排球',
    'Order_BS'=>'棒球',
    'Order_Basketball'=>'篮球',
    'Order_Early_Market'=>'早餐',
    'Order_1_x_2_betting_order'=>'单式独赢交易单',
    'Order_Handicap_betting_order'=>'单式让球交易单',
    'Order_Over_Under_betting_order'=>'单式大小交易单',
    'Order_Odd_Even_betting_order'=>'单双交易单',
    'Order_1st_Half_1_x_2_betting_order'=>'上半场独赢交易单',
    'Order_1st_Half_Handicap_betting_order'=>'上半场让球交易单',
    'Order_1st_Half_Over_Under_betting_order'=>'上半场大小交易单',
    'Order_Running_1_x_2_betting_order'=>'滚球独赢交易单',
    'Order_Running_Ball_betting_order'=>'滚球交易单',
    'Order_Running_Ball_Over_Under_betting_order'=>'滚球大小交易单',
    'Order_1st_Half_Running_1_x_2_betting_order'=>'上半场滚球独赢交易单',
    'Order_1st_Half_Running_Ball_betting_order'=>'上半滚球交易单',
    'Order_1st_Half_Running_Ball_Over_Under_betting_order'=>'足球上半滚球大小交易单',
    'Order_Correct_Score_betting_order'=>'波胆交易单',
    'Order_1st_Half_Correct_Score_betting_order'=>'上半波胆交易单',
    'Order_Total_Goals_betting_order'=>'入球数交易单',
    'Order_Half_Full_Time_betting_order'=>'半全场交易单',
    'Order_Mix_Parlay_betting_order'=>'综合过关交易单',
    'Order_1_x_2_Parlay_betting_order'=>'标准过关交易单',
    'Order_Handicap_Parlay_betting_order'=>'让分过关交易单',
    'Order_betting_order'=>'交易单',
    'Order_Login_Name'=>'帐户名称：',
    'Order_Credit_line'=>'可用额度：',
    'Order_Currency'=>'使用币别：',
    'Order_The_maximum_payout_is_x_per_bet'=>'每注最高派彩以RMB 1000000为上限',
    'Order_There_is_a_maximum_wager_limit_on_this_game_x_restriction'=>'本场有单注最高<B><*****></B>限制!!',
    'Order_1st_Half'=>'上半',
    'Order_2nd_Half'=>'下半',
    'Order_1st_Quarter'=>'第一节',
    'Order_2nd_Quarter'=>'第二节',
    'Order_3rd_Quarter'=>'第三节',
    'Order_4th_Quarter'=>'第四节',
    'Order_Odd'=>'单',
    'Order_Even'=>'双',
    'Order_This_odd_is_the_latest'=>'此为最新赔率',
    'Order_Other_Score'=>'其它比分',
    'Order_Mode'=>'模式：',
    'Order_Delete'=>'删除',
    'Order_single_wager'=>'单注',
    'Order_Bet_Amount'=>'交易金额：',
    'Order_Estimated_Payout'=>'可赢金额：',
    'Order_Minimum'=>'最低限额：',
    'Order_Single_bet_limit'=>'单注限额：',
    'Order_Maximum'=>'单场最高：',
    'Order_Cancel'=>'取消交易',
    'Order_Confirm'=>'确定交易',
    'Order_Bet_success'=>'交易成功单号：',
    'Order_Quit'=>'离开',
    'Order_Print'=>'列印',
    'Order_Pending'=>'正在确认中',
    'Order_Confirmed'=>'确认',
    'Order_Please_check_transaction_record'=>'请至交易状况查询',
    'Order_This_match_is_closed_Please_try_again'=>'赛程已关闭,无法进行交易!!',
    'Order_This_match_is_turned_to_Running_Ball_Please_wager_in_Running_Ball'=>'本场次已转至走地盘口,请至走地!!',
    'Order_The_game_is_covered_same_teams_Please_reset_again'=>'赛事重覆，请重新选择!!',
    'Order_Odd_changed_please_bet_again'=>'赔率已变动,请重新下注!!',
    'Order_Running_Ball_is_temporary_not_accepted_wagering'=>'暂时停止交易(走地)',
    'Score1'=>'取消',
    'Score2'=>'赛事腰斩',
    'Score3'=>'赛事改期',
    'Score4'=>'赛事延期',
    'Score5'=>'赛事延赛',
    'Score6'=>'赛事取消',
    'Score7'=>'赛事无PK加时',
    'Score8'=>'球员弃权',
    'Score9'=>'队名错误',
    'Score10'=>'主客场错误',
    'Score11'=>'先发投手更换',
    'Score12'=>'选手更换',
    'Score13'=>'联赛名称错',
    'Score19'=>'提前开赛',
    'Score20'=>'[注单确认]',
    'Score21'=>'[取消]',
    'Score22'=>'[赛事腰斩]',
    'Score23'=>'[赛事改期]',
    'Score24'=>'[赛事延期]',
    'Score25'=>'[赛事延赛]',
    'Score26'=>'[赛事取消]',
    'Score27'=>'[赛事无PK加时]',
    'Score28'=>'[球员弃权]',
    'Score29'=>'[队名错误]',
    'Score30'=>'[主客场错误]',
    'Score31'=>'[先发投手更换]',
    'Score32'=>'[选手更换]',
    'Score33'=>'[联赛名称错误]',
    'Score34'=>'[盘口错误]',
    'Score35'=>'[提前开赛]',
    'Score36'=>'[赛果错误]',
    'Score37'=>'[未接受注单]',
    'Score38'=>'[进球取消]',
    'Score39'=>'[红卡取消]',
    'Score40'=>'[非正常投注]',
    'Score41'=>'[赔率错误]',
    'Special0'=>'先开球]',
    'Special1'=>'角球数]',
    'Special2'=>'黄卡数]',
    'Special3'=>'第一颗角球]',
    'Special4'=>'第一张黄牌]',
    'Special5'=>'第一张红牌]',
    'Special6'=>'替换球员数]',
    'Special7'=>'先替换球员]',
    'Special8'=>'最后替换球员]',
    'Special9'=>']',
    'Special10'=>']',
    'Special11'=>'先得分]',
    'Special12'=>'最后得分]',
    'Special13'=>'总助攻球数]',
    'Special14'=>'总三分球数]',
    'Special15'=>'总篮板球数]',
    'Special16'=>'总截球数]',
    'Special17'=>'总盖帽数]',
    'Special18'=>']',
    'Special19'=>']',
    'Special10'=>']',
    'index3'=>'减少垃圾邮件',
    'index4'=>'移动访问',
    'index5'=>'超大空间',
    'index6'=>'注册Easymail',
    'index7'=>'关于Easymail',
    'index8'=>'新增功能！',
    'index9'=>'?2008Easy',
    'index10'=>'用于组织的Easymail',
    'index11'=>'隐私政策',
    'index12'=>'计划政策',
    'index13'=>'使用条款',
    'index14'=>'防毒软件设置说明',
    'index15'=>'需登入才能使用服务。',
    'index16'=>'可在<span>移动平台</span>使用');
}

function get_type($type,$type1){
    $t=array(
        'FT'=>'足球',
        'BK'=>'篮球',
        'TN'=>'网球',
        'VB'=>'排球',
        'BS'=>'棒球',
        'OP'=>'其他'
    );
    $t1=array(
        'RB'=>'滚球',
        'S'=>'单式',
        'PD'=>'波胆',
        'HPD'=>'上半波胆',
        'T'=>'总入球',
        'F'=>'半全场',
        'RQ'=>'单节'
    );

    return $t1[strtoupper($type1)];
}

function checkLogin(){
    if(isset($_SESSION['uid']) && isset($_SESSION['username'])){
        return true;
    }else{
        return false;
    }
}
