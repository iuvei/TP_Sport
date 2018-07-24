<?php

class Teamrate {

    private $_param;

    public function __construct($param = array()) {
        $this->_param = $param;
    }

    public function ft_rate($param = array()) {
        $this->_param = $param;
        $gmid = '';
        $ids=explode(',',  $this->_param['id']);
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];
        if ($this->_param['more'] == 'r') {
            $sql_ip = "select * from web_url_control where type='zh-cn' and canuse=1 and url='web1' order by rand() limit 1";
            $row = current($model->query($sql_ip));
            if (!empty($row['c_uid']) && !empty($row['c_url'])) {//检测是否有更新过uid。
                $suid = $row['c_uid'];
                $site = $row['c_url'];
            }
            import('@.Util.Team.Curlhttp');
            $curl = &new Curlhttp();
            $curl->store_cookies("cookies.txt");
            $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            $curl->set_referrer("" . $site . "/app/member/FT_browse/index.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
            $html_data = $curl->fetch_url("" . $site . "/app/member/FT_browse/body_var.php?rtype=re&uid=$suid&langx=zh-cn&mtype=4");
            $a = array("if(self == top)", "<script>", "</script>", "new Array()", "parent.GameFT=new Array();", "\n\n");
            $b = array("", "", "", "", "", "");
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a, $b, $html_data);
            preg_match_all("/g\(\[(.+?)\]\);/is", $msg, $matches);
            $cou = sizeof($matches[1]);
            $page_count = floor($cou / 60) + 1;
            for ($i = 0; $i < $cou; $i++) {
                $messages = $matches[1][$i];
                $messages = str_replace("g([", "", $messages);
                $messages = str_replace("'", "", $messages);
                $messages = str_replace("]);", "", $messages);
                $datainfo = explode(",", $messages);
                if (!in_array($datainfo[0], $ids)) {
                    continue;
                }
                $opensql = "select Open,MID,M_Time from match_sports where  MID='$datainfo[0]' and Type='FT'";
                $openrow = current($model->query($opensql));
                if ($openrow['Open'] == 1) {
                    $sql = "update match_sports set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',MB_Dime_Rate_RB='$datainfo[14]',TG_Dime_Rate_RB='$datainfo[13]',ShowTypeHRB='$datainfo[21]',M_LetB_RB_H='$datainfo[22]',MB_LetB_Rate_RB_H='$datainfo[23]',TG_LetB_Rate_RB_H='$datainfo[24]',MB_Dime_RB_H='$datainfo[25]',TG_Dime_RB_H='$datainfo[26]',MB_Dime_Rate_RB_H='$datainfo[28]',TG_Dime_Rate_RB_H='$datainfo[27]',MB_Ball='$datainfo[18]',TG_Ball='$datainfo[19]',MB_Card='$datainfo[29]',TG_Card='$datainfo[30]',MB_Red='$datainfo[31]',TG_Red='$datainfo[32]',MB_Win_Rate_RB='$datainfo[33]',TG_Win_Rate_RB='$datainfo[34]',M_Flat_Rate_RB='$datainfo[35]',MB_Win_Rate_RB_H='$datainfo[36]',TG_Win_Rate_RB_H='$datainfo[37]',M_Flat_Rate_RB_H='$datainfo[38]',Eventid='$datainfo[39]',Hot='$datainfo[40]',Play='$datainfo[41]',RB_Show=1,S_Show=0 where MID=$datainfo[0] and Type='FT'";
                    $model->execute($sql);
                    if ($datainfo[9] != '') {
                        $datainfo[9] = change_rate('D', $datainfo[9]);
                        $datainfo[10] = change_rate('D', $datainfo[10]);
                    }
                    if ($datainfo[13] != '') {
                        $datainfo[13] = change_rate('D', $datainfo[13]);
                        $datainfo[14] = change_rate('D', $datainfo[14]);
                    }
                    if ($datainfo[23] != '') {
                        $datainfo[23] = change_rate('D', $datainfo[23]);
                        $datainfo[24] = change_rate('D', $datainfo[24]);
                    }
                    if ($datainfo[28] != '') {
                        $datainfo[28] = change_rate('D', $datainfo[28]);
                        $datainfo[27] = change_rate('D', $datainfo[27]);
                    }
                    $datainfo[19] = $datainfo[19] + 0;
                    $datainfo[18] = $datainfo[18] + 0;

                    if ($gmid == '') {
                        $gmid = $datainfo[0];
                    } else {
                        $gmid = $gmid . ',' . $datainfo[0];
                    }

                    if ($datainfo[7] == 'H') {
                        $MG_LetB_RB = $datainfo[8];
                        $TG_LetB_RB = '';
                    } else {
                        $MG_LetB_RB = '';
                        $TG_LetB_RB = $datainfo[8];
                    }
                    if ($datainfo[21] == 'H') {
                        $MG_LetB_RB_H = $datainfo[22];
                        $TG_LetB_RB_H = '';
                    } else {
                        $MG_LetB_RB_H = '';
                        $TG_LetB_RB_H = $datainfo[22];
                    }
                    $LetB_Rate_RB = array();
                    $LetB_Rate_RB = get_other_ioratio('H', $datainfo[9], $datainfo[10], 100);

                    $Dime_Rate_RB = array();
                    $Dime_Rate_RB = get_other_ioratio('H', $datainfo[14], $datainfo[13], 100);

                    $LetB_Rate_RB_H = array();
                    $LetB_Rate_RB_H = get_other_ioratio('H', $datainfo[23], $datainfo[24], 100);

                    $Dime_Rate_RB_H = array();
                    $Dime_Rate_RB_H = get_other_ioratio('H', $datainfo[28], $datainfo[27], 100);
                    $rate[] = array(
                        'MID' => $datainfo[0],
                        'M_M_M' => $datainfo[1],
                        'M_Time' => $openrow['M_Time'],
                        'MB_Team' => $datainfo[5],
                        'TG_Team' => $datainfo[6],
                        'ShowTypeRB' => $datainfo[7],
                        'M_LetB_RB' => $datainfo[8],
                        'MB_LetB_Rate_RB' => $LetB_Rate_RB[0],
                        'TG_LetB_Rate_RB' => $LetB_Rate_RB[1],
                        'MB_Dime_RB' => $datainfo[11],
                        'TG_Dime_RB' => $datainfo[12],
                        'MB_Dime_Rate_RB' => $Dime_Rate_RB[0],
                        'TG_Dime_Rate_RB' => $Dime_Rate_RB[1],
                        'ShowTypeHRB' => $datainfo[21],
                        'M_LetB_RB_H' => $datainfo[22],
                        'MB_LetB_Rate_RB_H' => $LetB_Rate_RB_H[0],
                        'TG_LetB_Rate_RB_H' => $LetB_Rate_RB_H[1],
                        'MB_Dime_RB_H' => $datainfo[25],
                        'TG_Dime_RB_H' => $datainfo[26],
                        'MB_Dime_Rate_RB_H' => $Dime_Rate_RB_H[0],
                        'TG_Dime_Rate_RB_H' => $Dime_Rate_RB_H[1],
                        'MB_Ball' => $datainfo[18],
                        'TG_Ball' => $datainfo[19],
                        'MB_Card' => $datainfo[29],
                        'TG_Card' => $datainfo[30],
                        'MB_Red' => $datainfo[31],
                        'TG_Red' => $datainfo[32],
                        'MB_Win_Rate_RB' => $datainfo[33],
                        'TG_Win_Rate_RB' => $datainfo[34],
                        'M_Flat_Rate_RB' => $datainfo[35],
                        'MB_Win_Rate_RB_H' => $datainfo[36],
                        'TG_Win_Rate_RB_H' => $datainfo[37],
                        'M_Flat_Rate_RB_H' => $datainfo[38],
                        'Eventid' => $datainfo[39],
                        'Hot' => $datainfo[40],
                        'Play' => $datainfo[41],
                        'MG_LetB_RB' => $MG_LetB_RB,
                        'TG_LetB_RB' => $TG_LetB_RB,
                        'MG_LetB_RB_H' => $MG_LetB_RB_H,
                        'TG_LetB_RB_H' => $TG_LetB_RB_H,
                        'RB_Show' => 1,
                        'Type' => 'FT');
                    //}
                }
            }
            /*$sql = "update match_sports set RB_Show=0 where RB_Show=1 and locate(MID,'$gmid')<1";
            $model->execute($sql);*/
        } elseif ($this->_param['more'] == 's' || $this->_param['more'] == 'b') {
            $mysql = "select MID,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,MB_Win_Rate,TG_Win_Rate,M_Flat_Rate,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,ShowTypeHR,M_LetB_H,MB_LetB_Rate_H,TG_LetB_Rate_H,MB_Dime_H,TG_Dime_H,MB_Dime_Rate_H,TG_Dime_Rate_H,MB_Win_Rate_H,TG_Win_Rate_H,M_Flat_Rate_H,PD_Show,HPD_Show,T_Show,F_Show,Eventid,Hot,Play,MB1TG0,MB2TG0,MB2TG1,MB3TG0,MB3TG1,MB3TG2,MB4TG0,MB4TG1,MB4TG2,MB4TG3,MB0TG0,MB1TG1,MB2TG2,MB3TG3,MB4TG4,UP5,MB0TG1,MB0TG2,MB1TG2,MB0TG3,MB1TG3,MB2TG3,MB0TG4,MB1TG4,MB2TG4,MB3TG4,S_Single_Rate,S_Double_Rate,S_0_1,S_2_3,S_4_6,S_7UP,MBMB,MBFT,MBTG,FTMB,FTFT,FTTG,TGMB,TGFT,TGTG,MB1TG0H,MB2TG0H,MB2TG1H,MB3TG0H,MB3TG1H,MB3TG2H,MB4TG0H,MB4TG1H,MB4TG2H,MB4TG3H,MB0TG0H,MB1TG1H,MB2TG2H,MB3TG3H,MB4TG4H,UP5H,MB0TG1H,MB0TG2H,MB1TG2H,MB0TG3H,MB1TG3H,MB2TG3H,MB0TG4H,MB1TG4H,MB2TG4H,MB3TG4H,PD_Show from match_sports where Type='FT' and M_Start>now() AND S_Show=1 and MB_Team!='' and Open=1 and MID in ({$this->_param['id']})";
            //echo $mysql;exit();
            $rows = $model->query($mysql);
            foreach ($rows as $row) {
                $MB_Win_Rate = num_rate('D', $row["MB_Win_Rate"]);
                $TG_Win_Rate = num_rate('D', $row["TG_Win_Rate"]);
                $M_Flat_Rate = num_rate('D', $row["M_Flat_Rate"]);

                $MB_LetB_Rate = change_rate('D', $row['MB_LetB_Rate']);
                $TG_LetB_Rate = change_rate('D', $row['TG_LetB_Rate']);
                $tmp = array();
                $tmp = get_other_ioratio('H', $MB_LetB_Rate, $TG_LetB_Rate, 100);
                $MB_LetB_Rate = $tmp[0];
                $TG_LetB_Rate = $tmp[1];

                $MB_Dime_Rate = change_rate('D', $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate('D', $row["TG_Dime_Rate"]);
                $tmp = array();
                $tmp = get_other_ioratio('H', $MB_Dime_Rate, $TG_Dime_Rate, 100);
                $MB_Dime_Rate = $tmp[0];
                $TG_Dime_Rate = $tmp[1];

                $S_Single_Rate = num_rate('D', $row['S_Single_Rate']);
                $S_Double_Rate = num_rate('D', $row['S_Double_Rate']);
                $MB_Win_Rate_H = num_rate('D', $row["MB_Win_Rate_H"]);
                $TG_Win_Rate_H = num_rate('D', $row["TG_Win_Rate_H"]);
                $M_Flat_Rate_H = num_rate('D', $row["M_Flat_Rate_H"]);

                $MB_LetB_Rate_H = change_rate('D', $row['MB_LetB_Rate_H']);
                $TG_LetB_Rate_H = change_rate('D', $row['TG_LetB_Rate_H']);
                $tmp = array();
                $tmp = get_other_ioratio('H', $MB_LetB_Rate_H, $TG_LetB_Rate_H, 100);
                $MB_LetB_Rate_H = $tmp[0];
                $TG_LetB_Rate_H = $tmp[1];

                $MB_Dime_Rate_H = change_rate('D', $row["MB_Dime_Rate_H"]);
                $TG_Dime_Rate_H = change_rate('D', $row["TG_Dime_Rate_H"]);
                $tmp = array();
                $tmp = get_other_ioratio('H', $MB_Dime_Rate_H, $TG_Dime_Rate_H, 100);
                $MB_Dime_Rate_H = $tmp[0];
                $TG_Dime_Rate_H = $tmp[1];

                if ($row['HPD_Show'] == 1 and $row['PD_Show'] == 1 and $row['T_Show'] == 1 and $row['F_Show'] == 1) {
                    $show = 4;
                } else if ($row['PD_Show'] == 1 and $row['T_Show'] == 1 and $row['F_Show'] == 1) {
                    $show = 3;
                } else {
                    $show = 0;
                }
                if ($row['M_Type'] == 1) {
                    $Running = "<br><font color=red>滚球</font>";
                } else {
                    $Running = "";
                }
                if ($S_Single_Rate == '' and $S_Double_Rate == '') {
                    $odd = '';
                    $even = '';
                } else {
                    $odd = "$o";
                    $even = "$e";
                }

                if ($row['ShowTypeR'] == 'H') {
                    $row['MG_LetB'] = $row['M_LetB'];
                    $row['TG_LetB'] = '';
                } else {
                    $row['MG_LetB'] = '';
                    $row['TG_LetB'] = $row['M_LetB'];
                }

                if ($row['ShowTypeHR'] == 'H') {
                    $row['MG_LetB_H'] = $row['M_LetB_H'];
                    $row['TG_LetB_H'] = '';
                } else {
                    $row['MG_LetB_H'] = '';
                    $row['TG_LetB_H'] = $row['M_LetB_H'];
                }

                $rate[] = array(
                    'MID' => $row['MID'],
                    'M_Time' => $row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'],
                    'M_LetB' => $row['M_LetB'],
                    'MG_LetB' => $row['MG_LetB'],
                    'TG_LetB' => $row['TG_LetB'],
                    'MG_LetB_H' => $row['MG_LetB_H'],
                    'TG_LetB_H' => $row['TG_LetB_H'],
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'MB_Win_Rate' => $MB_Win_Rate,
                    'TG_Win_Rate' => $TG_Win_Rate,
                    'M_Flat_Rate' => $M_Flat_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate,
                    'M_LetB_H' => $row['M_LetB_H'],
                    'MB_LetB_Rate_H' => $MB_LetB_Rate_H,
                    'TG_LetB_Rate_H' => $TG_LetB_Rate_H,
                    'MB_Dime' => $row['MB_Dime'],
                    'TG_Dime' => $row['TG_Dime'],
                    'MB_Dime_H' => $row['MB_Dime_H'],
                    'TG_Dime_H' => $row['TG_Dime_H'],
                    'TG_Dime_Rate_H' => $TG_Dime_Rate_H,
                    'MB_Dime_Rate_H' => $MB_Dime_Rate_H,
                    'MB_Win_Rate_H' => $MB_Win_Rate_H,'TG_Win_Rate_H' => $TG_Win_Rate_H,'M_Flat_Rate_H' => $M_Flat_Rate_H,'RB_Show' => 0, 'S_Show' => 1, 'PD_Show' => $row['PD_Show'], 'T_Show' => $row['T_Show'], 'F_Show' => $row['F_Show'],'Type' => 'FT', 'MB1TG0' => $row['MB1TG0'], 'MB2TG0' => $row['MB2TG0'], 'MB2TG1' => $row['MB2TG1'], 'MB3TG0' => $row['MB3TG0'], 'MB3TG1' => $row['MB3TG1'], 'MB3TG2' => $row['MB3TG2'], 'MB4TG0' => $row['MB4TG0'], 'MB4TG1' => $row['MB4TG1'], 'MB4TG2' => $row['MB4TG2'], 'MB4TG3' => $row['MB4TG3'], 'MB0TG0' => $row['MB0TG0'], 'MB1TG1' => $row['MB1TG1'], 'MB2TG2' => $row['MB2TG2'], 'MB3TG3' => $row['MB3TG3'], 'MB4TG4' => $row['MB4TG4'], 'UP5' => $row['UP5'], 'MB0TG1' => $row['MB0TG1'], 'MB0TG2' => $row['MB0TG2'], 'MB1TG2' => $row['MB1TG2'], 'MB0TG3' => $row['MB0TG3'], 'MB1TG3' => $row['MB1TG3'], 'MB2TG3' => $row['MB2TG3'], 'MB0TG4' => $row['MB0TG4'], 'MB1TG4' => $row['MB1TG4'], 'MB2TG4' => $row['MB2TG4'], 'MB3TG4' => $row['MB3TG4'], 'S_Single_Rate' => $row['S_Single_Rate'], 'S_Double_Rate' => $row['S_Double_Rate'], 'S_0_1' => $row['S_0_1'], 'S_2_3' => $row['S_2_3'], 'S_4_6' => $row['S_4_6'], 'S_7UP' => $row['S_7UP'], 'MBMB' => $row['MBMB'], 'MBFT' => $row['MBFT'], 'MBTG' => $row['MBTG'], 'FTMB' => $row['FTMB'], 'FTFT' => $row['FTFT'], 'FTTG' => $row['FTTG'], 'TGMB' => $row['TGMB'], 'TGFT' => $row['TGFT'], 'TGTG' => $row['TGTG'], 'MB1TG0H' => $row['MB1TG0H'], 'MB2TG0H' => $row['MB2TG0H'], 'MB2TG1H' => $row['MB2TG1H'], 'MB3TG0H' => $row['MB3TG0H'], 'MB3TG1H' => $row['MB3TG1H'], 'MB3TG2H' => $row['MB3TG2H'], 'MB4TG0H' => $row['MB4TG0H'], 'MB4TG1H' => $row['MB4TG1H'], 'MB4TG2H' => $row['MB4TG2H'], 'MB4TG3H' => $row['MB4TG3H'], 'MB0TG0H' => $row['MB0TG0H'], 'MB1TG1H' => $row['MB1TG1H'], 'MB2TG2H' => $row['MB2TG2H'], 'MB3TG3H' => $row['MB3TG3H'], 'MB4TG4H' => $row['MB4TG4H'], 'UP5H' => $row['UP5H'], 'MB0TG1H' => $row['MB0TG1H'], 'MB0TG2H' => $row['MB0TG2H'], 'MB1TG2H' => $row['MB1TG2H'], 'MB0TG3H' => $row['MB0TG3H'], 'MB1TG3H' => $row['MB1TG3H'], 'MB2TG3H' => $row['MB2TG3H'], 'MB0TG4H' => $row['MB0TG4H'], 'MB1TG4H' => $row['MB1TG4H'], 'MB2TG4H' => $row['MB2TG4H'], 'MB3TG4H' => $row['MB3TG4H']
                );
            }
        }
        return $rate;
    }

    public function bk_rate($param = array()) {
        $this->_param = $param;
        $ids=explode(',',  $this->_param['id']);
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];
        if ($this->_param['more'] == 's') {
            $mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,Eventid,Hot,Play,ratio_ouho,ratio_ouhu,ior_OUHO,ior_OUHU,ratio_ouco,ratio_oucu,ior_OUCO,ior_OUCU,MB_Win_Rate,TG_Win_Rate from match_sports where MID in ({$this->_param['id']}) and Type='BK' and  M_Start>now() AND S_Show=1 and RQ_Show!=1 and MB_Team!=''";
            $result = $model->query($mysql);
            foreach ($result as $row) {
                $MB_Dime_Rate = change_rate('D', $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate('D', $row["TG_Dime_Rate"]);
                $MB_LetB_Rate = change_rate('D', $row['MB_LetB_Rate']);
                $TG_LetB_Rate = change_rate('D', $row['TG_LetB_Rate']);
                $S_Single_Rate = num_rate('D', $row['S_Single_Rate']);
                $S_Double_Rate = num_rate('D', $row['S_Double_Rate']);
                $ior_OUHO = $row['ior_OUHO'] < 0.02 ? "" : change_rate('D', $row['ior_OUHO']);
                $ior_OUHU = $row['ior_OUHU'] < 0.02 ? "" : change_rate('D', $row['ior_OUHU']);
                $ior_OUCO = $row['ior_OUCO'] < 0.02 ? "" : change_rate('D', $row['ior_OUCO']);
                $ior_OUCU = $row['ior_OUCU'] < 0.02 ? "" : change_rate('D', $row['ior_OUCU']);
                $MB_Win_Rate = num_rate('D', $row["MB_Win_Rate"]);
                $TG_Win_Rate = num_rate('D', $row["TG_Win_Rate"]);

                if ($S_Single_Rate == '') {
                    $Single = '';
                } else {
                    $Single = $o;
                }
                if ($S_Double_Rate == '') {
                    $Double = '';
                } else {
                    $Double = $e;
                }
                if ($row['M_Type'] == 1) {
                    $Running = '<br><font color=red>滚球</font>';
                } else {
                    $Running = '';
                }
                if ($row['ShowTypeR'] == 'H') {
                    $MG_LetB = $row['M_LetB'];
                    $TG_LetB = '';
                } else {
                    $MG_LetB = '';
                    $TG_LetB = $row['M_LetB'];
                }
                $LetB_Rate = array();
                $LetB_Rate = get_other_ioratio('H', $MB_LetB_Rate, $TG_LetB_Rate, 100);
                $MB_LetB_Rate = $LetB_Rate[0];
                $TG_LetB_Rate = $LetB_Rate[1];

                $Dime_Rate = array();
                $Dime_Rate = get_other_ioratio('H', $MB_Dime_Rate, $TG_Dime_Rate, 100);
                $MB_Dime_Rate = $Dime_Rate[0];
                $TG_Dime_Rate = $Dime_Rate[1];


                $rate[] = array(
                    'MID' => $row['MID'],
                    'M_Time' => $row['M_Date'] . ' ' . $row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'], 'MG_LetB' => $MG_LetB, 'TG_LetB' => $TG_LetB,
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate, 'MB_Win_Rate' => $MB_Win_Rate, 'TG_Win_Rate' => $TG_Win_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate, 'MB_Dime' => $row['MB_Dime'], 'TG_Dime' => $row['TG_Dime'],
                    'RB_Show' => 0,
                    'S_Show' => 1,
                    'Type' => 'BK', 'ratio_ouho' => $row['ratio_ouho'], 'ratio_ouhu' => $row['ratio_ouhu'], 'ior_OUHO' => $ior_OUHO, 'ior_OUHU' => $ior_OUHU, 'ratio_ouco' => $row['ratio_ouco'], 'ratio_oucu' => $row['ratio_oucu'], 'ior_OUCO' => $ior_OUCO, 'ior_OUCU' => $ior_OUCU
                );
            }
        } else if ($this->_param['more'] == 'b') {
            $mysql = "select MID,M_Date,M_Time,M_Type,MB_MID,TG_MID,MB_Team,TG_Team,M_League,ShowTypeHR,M_LetB,MB_LetB_Rate,TG_LetB_Rate,MB_Dime,TG_Dime,MB_Dime_Rate,TG_Dime_Rate,S_Single_Rate,S_Double_Rate,Eventid,Hot,Play from match_sports where MID in ({$this->_param['id']}) and Type='BK' and M_Start>now() AND RQ_Show=1 and MB_Team!=''";
            $result = $model->query($mysql);
            foreach ($result as $row) {
                $MB_Dime_Rate = change_rate('D', $row["MB_Dime_Rate"]);
                $TG_Dime_Rate = change_rate('D', $row["TG_Dime_Rate"]);
                $MB_LetB_Rate = change_rate('D', $row['MB_LetB_Rate']);
                $TG_LetB_Rate = change_rate('D', $row['TG_LetB_Rate']);
                $S_Single_Rate = num_rate('D', $row['S_Single_Rate']);
                $S_Double_Rate = num_rate('D', $row['S_Double_Rate']);
                if ($S_Single_Rate == '') {
                    $Single = '';
                } else {
                    $Single = $o;
                }
                if ($S_Double_Rate == '') {
                    $Double = '';
                } else {
                    $Double = $e;
                }
                if ($row['M_Type'] == 1) {
                    $Running = '<br><font color=red>滚球</font>';
                } else {
                    $Running = '';
                }
                $LetB_Rate = array();
                $LetB_Rate = get_other_ioratio('H', $MB_LetB_Rate, $TG_LetB_Rate, 100);
                $MB_LetB_Rate = $LetB_Rate[0];
                $TG_LetB_Rate = $LetB_Rate[1];

                $Dime_Rate = array();
                $Dime_Rate = get_other_ioratio('H', $MB_Dime_Rate, $TG_Dime_Rate, 100);
                $MB_Dime_Rate = $Dime_Rate[0];
                $TG_Dime_Rate = $Dime_Rate[1];

                $rate[] = array(
                    'MID' => $row['MID'],
                    'M_Time' => $row['M_Date'] . ' ' . $row['M_Time'],
                    'MB_Team' => $row['MB_Team'],
                    'TG_Team' => $row['TG_Team'], 'MG_LetB' => $MG_LetB, 'TG_LetB' => $TG_LetB,
                    'MB_LetB_Rate' => $MB_LetB_Rate,
                    'TG_LetB_Rate' => $TG_LetB_Rate,
                    'MB_Dime_Rate' => $MB_Dime_Rate,
                    'TG_Dime_Rate' => $TG_Dime_Rate,
                    'S_Single_Rate' => $S_Single_Rate,
                    'S_Double_Rate' => $S_Double_Rate, 'MB_Dime' => $row['MB_Dime'], 'TG_Dime' => $row['TG_Dime'],
                    'RB_Show' => 0,
                    'S_Show' => 1,
                    'Type' => 'BK'
                );
            }
        } else if ($this->_param['more'] == 'r') {
            $sql_ip = "select * from web_url_control where type='zh-cn' and canuse=1 and url='web1' order by rand() limit 1";
            $row = current($model->query($sql_ip));
            if (!empty($row['c_uid']) && !empty($row['c_url'])) {//检测是否有更新过uid。
                $suid = $row['c_uid'];
                $site = $row['c_url'];
            }
            //后台会员锁定篮球无法下注（第三、四节）
            $bk_r34_limit = $this->bk_r34_limit();
            import('@.Util.Team.Curlhttp');
            $curl = &new Curlhttp();
            $curl->set_user_agent("Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
            $curl->set_referrer("" . $site . "/app/member/BK_browse/index.php?rtype=re_all&uid=$suid&langx=$langx&mtype=4");
            $html_data = $curl->fetch_url("" . $site . "/app/member/BK_browse/body_var.php?rtype=re_all&uid=$suid&langx=zh-cn&mtype=4");

            $a = array("parent.GameFT=new ", "parent.GameHead = new ");
            $b = array("", "");
            unset($matches);
            unset($datainfo);
            $msg = str_replace($a, $b, $html_data);
            preg_match_all("/g\(\[(.+?)\]\);/is", $msg, $matches);
            $cou = sizeof($matches[1]);
            for ($i = 0; $i < $cou; $i++) {
                $messages = $matches[1][$i];
                $messages = str_replace("g([", "", $messages);
                $messages = str_replace("'", "", $messages);
                $messages = str_replace("]);", "", $messages);
                $datainfo = explode(",", $messages);
                if (!in_array($datainfo[0],$ids)) {
                    continue;
                }
                $opensql = "select Open,MB_Ball,TG_Ball from `match_sports` where Type='BK' and  MID='$datainfo[0]'";
                $openresult = $model->query($opensql);
                $openrow = current($openresult);
                if ($openrow['Open'] == 1) {
                    if ($datainfo[51] == 'Y') {
                        $master[$datainfo[50]] = array($datainfo[53], $datainfo[54]);
                        $nnn = $datainfo[52] . "^" . $datainfo[56];
                    } else {
                        $datainfo[53] = $master[$datainfo[50]][0];
                        $datainfo[54] = $master[$datainfo[50]][1];
                    }
                    $sql = "update match_sports set ShowTypeRB='$datainfo[7]',M_LetB_RB='$datainfo[8]',T_LetB_RB='$datainfo[8]',MB_LetB_Rate_RB='$datainfo[9]',TG_LetB_Rate_RB='$datainfo[10]',MB_Dime_RB='$datainfo[11]',TG_Dime_RB='$datainfo[12]',TG_Dime_Rate_RB='$datainfo[13]',MB_Dime_Rate_RB='$datainfo[14]',MB_Ball=if(MB_Ball>$datainfo[53],MB_Ball,$datainfo[53]),TG_Ball=if(TG_Ball>$datainfo[54],TG_Ball,$datainfo[54]),RB_Show=1,S_Show=0 where MID=$datainfo[0] and `Type`='BK'";
                    //echo $sql;
                    $model->execute($sql);
                    if ($datainfo[9] <> '') {
                        $datainfo[9] = change_rate('D', $datainfo[9]);
                        $datainfo[10] = change_rate('D', $datainfo[10]);
                    }
                    if ($datainfo[13] <> '') {
                        $datainfo[13] = change_rate('D', $datainfo[13]);
                        $datainfo[14] = change_rate('D', $datainfo[14]);
                    }
                    if ($datainfo[7] == 'H') {
                        $MG_LetB_RB = $datainfo[8];
                        $TG_LetB_RB = '';
                    } else {
                        $MG_LetB_RB = '';
                        $TG_LetB_RB = $datainfo[8];
                    }
                    $LetB_Rate_RB = array();
                    $LetB_Rate_RB = get_other_ioratio('H', $datainfo[9], $datainfo[10], 100);

                    $Dime_Rate_RB = array();
                    $Dime_Rate_RB = get_other_ioratio('H', $datainfo[13], $datainfo[14], 100);

                    if ($datainfo[51] == "Y") {
                        $jie = $datainfo[52];
                    }
                    if ( $bk_r34_limit && (!preg_match('/NBA/is', str_replace(' ', '', $openrow['M_League']))) && ($jie == "H2" or $jie == "Q3" or $jie == "Q4" or $jie == "OT")) {
                        $rate[] = array(
                            'MID' => $datainfo[0],
                            'M_Time' => $datainfo[1],
                            'MB_Team' => $datainfo[5],
                            'TG_Team' => $datainfo[6],
                            'ShowTypeRB' => $datainfo[7],
                            'MG_LetB_RB' => '',
                            'TG_LetB_RB' => '',
                            'MB_LetB_Rate_RB' => '',
                            'TG_LetB_Rate_RB' => '',
                            'MB_Dime_RB' => '',
                            'TG_Dime_RB' => '',
                            'TG_Dime_Rate_RB' => '',
                            'MB_Dime_Rate_RB' => '',
                            'RB_Show' => 1,
                            'Type' => 'BK', 'MB_Ball' => $openrow['MB_Ball'], 'TG_Ball' => $openrow['TG_Ball']
                        );
                    } else {
                        $rate[] = array(
                            'MID' => $datainfo[0],
                            'M_Time' => $datainfo[1],
                            'MB_Team' => $datainfo[5],
                            'TG_Team' => $datainfo[6],
                            'ShowTypeRB' => $datainfo[7],
                            'MG_LetB_RB' => $MG_LetB_RB,
                            'TG_LetB_RB' => $TG_LetB_RB,
                            'MB_LetB_Rate_RB' => $LetB_Rate_RB[0],
                            'TG_LetB_Rate_RB' => $LetB_Rate_RB[1],
                            'MB_Dime_RB' => $datainfo[11],
                            'TG_Dime_RB' => $datainfo[12],
                            'TG_Dime_Rate_RB' => $Dime_Rate_RB[0],
                            'MB_Dime_Rate_RB' => $Dime_Rate_RB[1],
                            'RB_Show' => 1,
                            'Type' => 'BK', 'MB_Ball' => $openrow['MB_Ball'], 'TG_Ball' => $openrow['TG_Ball']
                        );
                    }
                }
            }
        }
        return $rate;
    }

    public function bs_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function tn_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function vb_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    public function op_rate($param = array()) {
        $this->_param = $param;
        $rate = array();
        $model = new Model();
        $open = $_SESSION['OpenType'];

        return $rate;
    }

    //会员是否被限制篮球滚球3，4节显示bk_r34_limit()
    public function bk_r34_limit(){
        $username = $_SESSION['username'];
        $res = 0;
        $model = new Model();
        $sql = "select b.BK from web_member_data_lock b,web_member_data a where b.mem_id=a.ID and a.UserName='".$username."' ";
        $row = current($model->query($sql));
        if(!empty($row)){
            $res = $row['BK'];
        }
        return $res;
    }
}

?>