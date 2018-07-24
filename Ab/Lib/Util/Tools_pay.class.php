<?php
/**
 * 作用：支付方式工具类
 * 研发：小七
 * 修改：小叶
 * 时间：2016-12-23 15：52
 * 版本：V1.0
 */
class Tools_pay{

    private $pay_way;                                     //支付方式
    private $banking_business;                           //银行的各个支付方式

    public function __construct()
    {

        $this->banking_business = array(

        );
    }


    //在线存款，公司入款和支付宝入款验证是否可用
    //$uerinfo为用户信息,
    //$output['online']为在线存款是否启用，$output['company']为公司入款是否启用，$output['Ali']为支付宝入款是否启用，如果支付宝入款启用后但是返回依然为零时，$output['Ali_scr']为返回零的原因说明
    public function outline_pay($uerinfo){

        // $online_set_sql = "select * from `web_payment_data` where `status`=0 and `AutoPayType` = 99 limit 1";
        // $online_set_result = mysql_query($online_set_sql);
        // $online_set_row = mysql_fetch_assoc($online_set_result);

        $output['online'] = 1;
        $output['company'] = 1;
        $output['Ali'] = 1;
        $output['WX'] = 1;

        //查询在线银行的返回url
        //查找顺序：会员制定分层，会员匹配上的支付分层，支付未分层（支付未分层相当于无限制）
        if($output['online'])
        {
            $level_id = $uerinfo['level_id'];
            $AddDate = $uerinfo['AddDate'];
            $ctime = $uerinfo['ctime'];
            $cmoney = $uerinfo['cmoney'];

            //会员是否制定分层
            if($level_id)
            {
                $sqls  = "select * from `web_payment_data` where `Switch`=1 and `Status`=0 and (bank<8 or (bank=10 and PaymentMethod=3)) and `level_id`=".$level_id." limit 1";
                $results = mysql_query($sqls);
                $rows = mysql_fetch_assoc($results);
                if(mysql_num_rows($results)>0){
                    $output['online_bank_url'] = $rows['Address'];
                    $output['online_bank_ID'] = $rows['ID'];
                }
                else
                {
                    $output['online'] = 0;
                }
            }
            else
            {
                //会员未分层
                $online_bank_sql = "select a.* from `web_payment_data` a,`web_bank_level` b where a.level_id=b.id and b.type=1 and a.Switch=1 and a.status=0 and (a.bank<8 or (a.bank=10 and a.PaymentMethod=3))";
                $sql_limit = " and (b.save_time<=".$ctime." or b.save_time=0)";           //最低存款次数
                $sql_limit.= " and (b.save_time_max>".$ctime." or b.save_time_max=0)";            //最高存款次数
                $sql_limit.= " and (b.save_amount<=".$cmoney." or b.save_amount=0)";         //最低存款金额
                $sql_limit.= " and (b.max_amount>".$cmoney." or b.max_amount=0)";         //最高存款次数
                $sql_limit.= " and (b.regtime_start<='".$AddDate."' or b.regtime_start='0000-00-00')";     //注册起始时间
                $sql_limit.= " and (b.regtime_end>'".$AddDate."' or b.regtime_end='0000-00-00')";         //注册终止时间

                $online_bank_result = mysql_query($online_bank_sql.$sql_limit.' order by a.ID limit 1');
                $online_bank_row = mysql_fetch_assoc($online_bank_result);
                if($online_bank_row)
                {
                    $output['online_bank_url'] = $online_bank_row['Address'];
                    $output['online_bank_ID'] = $online_bank_row['ID'];
                }
                else
                {
                    //会员未匹配到封层，则查找未分层的支付方式
                    $sqlf = "select * from `web_payment_data` where `Switch`=1 and Status=0 and `AutoPayType`=0 and (bank<8 or (bank=10 and PaymentMethod=3)) and `level_id`=0 limit 1";
                    $resf = mysql_query($sqlf);
                    $rowf = mysql_fetch_assoc($resf);
                    if(mysql_num_rows($resf)>0)
                    {
                        $output['online_bank_url'] = $rowf['Address'];
                        $output['online_bank_ID'] = $rowf['ID'];
                    }
                    else
                    {
                        $output['online'] = 0;
                    }
                }
            }
        }

        //看是否有公司入款账号
        if($output['company'])
        {
            $bank_level_id = $uerinfo['bank_level_id'];
            $AddDate = $uerinfo['AddDate'];
            $ctime = $uerinfo['ctime'];
            $cmoney = $uerinfo['cmoney'];

            if($bank_level_id){ //已分层
                $mysqls = 'select max(`ID`) as compayid from `web_bank_date` where `is_alipay`=0 and `is_we_pay`=0 and `status`=1 and `bank_level_id` = \''.$bank_level_id.'\' group by `bank` order by `bank`';
            }else{
                $mysqls="select max(a.ID) as compayid from  web_bank_level c,web_bank_date a ";
                $mysqls .= "where a.bank_level_id = c.id and a.is_alipay = 0 and a.is_we_pay = 0 and a.status = 1 " ;
                $mysqls .= "and (c.regtime_start='0000-00-00'  or c.regtime_start <='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.regtime_end='0000-00-00' or c.regtime_end >='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.save_time=0 or c.save_time<='".$ctime."') ";
                $mysqls .= "and (c.save_time_max=0 or c.save_time_max>='".$ctime."') ";
                $mysqls .= "and (c.save_amount=0 or c.save_amount <='".$cmoney."') ";
                $mysqls .= "and (c.max_amount=0 or c.max_amount >='".$cmoney."') ";
                $mysqls .= "group by a.bank " ;
                $mysqls .= "order by a.bank " ;
            }

            $company_result = mysql_query($mysqls);
            $company_row = mysql_fetch_assoc($company_result);
            if($company_row)
            {
                $company_id = $company_row['compayid'];
                while ($company_row = mysql_fetch_assoc($company_result))
                {
                    $company_id .= ','.$company_row['compayid'];
                }
                $output['company_ID'] = $company_id;
            }
            else
            {
                $output['company'] = 0;
                $output['company_scr'] = '没有可用支付宝收款账号';
            }
        }

        //看是否有收款支付宝账号
        if($output['Ali'])
        {
            $bank_level_id = $uerinfo['bank_level_id'];
            $AddDate = $uerinfo['AddDate'];
            $ctime = $uerinfo['ctime'];
            $cmoney = $uerinfo['cmoney'];

            if($bank_level_id){ //已分层
                $mysqls = 'select * from `web_bank_date` where is_alipay=1  and `status`=1 and `bank_level_id` = \''.$bank_level_id.'\' order by `ID` desc limit 1';
            }else{
                $mysqls="select a.* from  web_bank_level c,web_bank_date a ";
                $mysqls .= "where a.bank_level_id = c.id and a.is_alipay = 1 and a.status = 1 " ;
                $mysqls .= "and (c.regtime_start='0000-00-00'  or c.regtime_start <='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.regtime_end='0000-00-00' or c.regtime_end >='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.save_time=0 or c.save_time<='".$ctime."') ";
                $mysqls .= "and (c.save_time_max=0 or c.save_time_max>='".$ctime."') ";
                $mysqls .= "and (c.save_amount=0 or c.save_amount <='".$cmoney."') ";
                $mysqls .= "and (c.max_amount=0 or c.max_amount >='".$cmoney."') ";
                $mysqls .= "group by a.id " ;
                $mysqls .= "order by a.id desc limit 1 " ;
            }

            $ali_result = mysql_query($mysqls);
            $ali_row = mysql_fetch_assoc($ali_result);
            if($ali_row)
            {

                $output['Ali_ID'] = $ali_row['ID'];
            }
            else
            {
                $output['Ali'] = 0;
                $output['Ali_scr'] = '没有可用支付宝收款账号';
            }
        }

        //看是否有收款微信账号
        if($output['WX'])
        {
            $bank_level_id = $uerinfo['bank_level_id'];
            $AddDate = $uerinfo['AddDate'];
            $ctime = $uerinfo['ctime'];
            $cmoney = $uerinfo['cmoney'];

            if($bank_level_id){ //已分层
                $mysqls = 'select * from `web_bank_date` where `is_we_pay`=1  and `status`=1 and `bank_level_id` = \''.$bank_level_id.'\' order by `ID` desc limit 1';
            }else{
                $mysqls="select a.* from  web_bank_level c,web_bank_date a ";
                $mysqls .= "where a.bank_level_id = c.id and a.is_we_pay = 1 and a.status = 1 " ;
                $mysqls .= "and (c.regtime_start='0000-00-00'  or c.regtime_start <='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.regtime_end='0000-00-00' or c.regtime_end >='".date('Y-m-d',strtotime($AddDate))."') ";
                $mysqls .= "and (c.save_time=0 or c.save_time<='".$ctime."') ";
                $mysqls .= "and (c.save_time_max=0 or c.save_time_max>='".$ctime."') ";
                $mysqls .= "and (c.save_amount=0 or c.save_amount <='".$cmoney."') ";
                $mysqls .= "and (c.max_amount=0 or c.max_amount >='".$cmoney."') ";
                $mysqls .= "group by a.id " ;
                $mysqls .= "order by a.id desc limit 1 " ;
            }

            $wx_result = mysql_query($mysqls);
            $wx_row = mysql_fetch_assoc($wx_result);
            if($wx_row)
            {
                $output['WX_ID'] = $wx_row['ID'];
            }
            else
            {
                $output['WX'] = 0;
                $output['WX_scr'] = '没有可用微信收款账号';
            }
        }

        return $output;
    }

    //是否有可用的支付方式，有则返回支付方式数组，没有则返回数组，status为状态
    //$uerinfo为用户信息，$bank为需要第三方代码，$PaymentMethod为详细支付方式（0：全部；1：为支付宝；2：为微信，3：为全部银行；4为部分银行），$bankname为部分银行代码多家银行以逗号分隔,$precise是否精确匹配（默认只支持精确匹配）
    public function available($uerinfo,$bank,$PaymentMethod=0,$bankname=''){

        $result = array( 'status'=>false,'ouput'=>'','input'=>'','sql'=>'','url'=>'.','bankname'=>$bankname);

        //输入验证
        if(!count($uerinfo))
        {
            return array( 'status'=>false,'ouput'=>'用户信息不为空','sql'=>'','url'=>'.','bankname'=>$bankname);
        }
        if(!(isset($uerinfo['ctime']) && isset($uerinfo['cmoney'])))
        {
            return array( 'status'=>false,'ouput'=>'用户信息必须包含存款次数和存款金额','sql'=>'','url'=>'.','bankname'=>$bankname);
        }
        if($PaymentMethod<0||$PaymentMethod>15)
        {
            return array( 'status'=>false,'ouput'=>'详细支付方式代码为：0~5','sql'=>'','url'=>'.','bankname'=>$bankname);
        }

/*        if ($PaymentMethod==4 and $bankname=='')
        {
            return array('status'=>false,'ouput'=>'查询部分银行信息时，需要提交第四个参数（部分银行代码）','url'=>'.','bankname'=>$bankname);
        }*/

        $level_id = $uerinfo['level_id'];
        $AddDate = $uerinfo['AddDate'];
        $ctime = $uerinfo['ctime'];
        $cmoney = $uerinfo['cmoney'];
        if($level_id){//会员有分层
            $sql  = "select * from `web_payment_data` where `Switch`=1 and Status=0 and `bank`=".$bank." and PaymentMethod=".$PaymentMethod." and `level_id`=".$level_id." limit 1";
            $result_s = mysql_query($sql);
            if(mysql_num_rows($result_s)>0){
                $result['status'] = true;
                $result['ouput'] = mysql_fetch_assoc($result_s);
                $result['sql']  = $sql;
                $result['url']  = $result['ouput']['is_same_url']?$result['ouput']['Address']:'.';
                return $result;
            }
        }


        $sql  = "select a.* from `web_payment_data` a,`web_bank_level` b where a.level_id=b.id and b.type=1 and a.Switch=1 and a.Status=0 and a.bank=".$bank;

        //部分银行逻辑
        // if($PaymentMethod==4 and $bankname){
        //     $bankname_t = explode(',',$bankname);
        //     $sql_bank = '';
        //     foreach($bankname_t as $v)
        //     {
        //         $sql_bank .= " and find_in_set({$v},`paybank`)";
        //     }
        // }else{
        //     $sql_bank = '';
        // }

        $sql_limit = " and (b.save_time<=".$ctime." or b.save_time=0)";           //最低存款次数
        $sql_limit.= " and (b.save_time_max>".$ctime." or b.save_time_max=0)";            //最高存款次数
        $sql_limit.= " and (b.save_amount<=".$cmoney." or b.save_amount=0)";         //最低存款金额
        $sql_limit.= " and (b.max_amount>".$cmoney." or b.max_amount=0)";         //最高存款次数
        $sql_limit.= " and (b.regtime_start<='".$AddDate."' or b.regtime_start='0000-00-00')";     //注册起始时间
        $sql_limit.= " and (b.regtime_end>'".$AddDate."' or b.regtime_end='0000-00-00')";         //注册终止时间
        $selectsql1 = " and a.PaymentMethod = ".$PaymentMethod;
        $sql_time = " order by a.ID limit 1";
        $result_sql = mysql_query($sql.$selectsql1.$sql_bank.$sql_limit.$sql_time);
        $result_cou=mysql_num_rows($result_sql);
        if($result_cou == 0)
        {
            $sql = "select * from `web_payment_data` a where `Switch`=1 and `Status`=0 and `bank`=".$bank." and `level_id`=0 ";
            $sql_limit = '';
            $result_sql = mysql_query($sql.$selectsql1.$sql_bank.$sql_limit.$sql_time);
            $result_cou=mysql_num_rows($result_sql);
            if($result_cou == 0)
            {
                return array('status'=>false,'ouput'=>'没有匹配到支持的支付方式0！','sql'=>$sql.$selectsql1.$sql_bank.$sql_limit.$sql_time,'url'=>'.','bankname'=>$bankname);
            }
        }

        if($result_cou)
        {
            $result['status'] = true;
            $result['ouput'] = mysql_fetch_assoc($result_sql);
            $result['sql']  = $sql.$selectsql1.$sql_bank.$sql_limit.$sql_time;
            $result['url']  = $result['ouput']['is_same_url']?$result['ouput']['Address']:'.';
        }
        else
        {
            return array('status'=>false,'ouput'=>'没有匹配到支持的支付方式1！','sql'=>$sql.$selectsql1.$sql_bank.$sql_limit.$sql_time,'url'=>'.','bankname'=>$bankname);
        }
        return $result;
    }
}