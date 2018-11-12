<?php



defined('IN_IA') or exit('Access Denied');

require 'inc/func/core.php';

class mzhk_sunModuleSite extends Core {

    public function doWebNotify($data){
        global $_W;
        $attach = $data['attach'];
        if($attach){
            $attach_arr = explode("@@@",$attach);
            if(sizeof($attach_arr)>0){
                $_W['uniacid'] = $uniacid = intval($attach_arr[0]);
                $ordertype = $attach_arr[1];
            }else{
                die('FAIL');
            }
        }else{
            die('FAIL');
        }
        $out_trade_no = $data['out_trade_no'];
        
        if($ordertype==1){
            $orderinfo = pdo_get('mzhk_sun_ptgroups', array('out_trade_no' => $out_trade_no,'uniacid' => $uniacid,'status' => 2));
            if($orderinfo){
                $order = pdo_get('mzhk_sun_ptorders',array('id'=>$orderinfo["order_id"],'uniacid'=>$uniacid),array("gid","gname","buynum","neednum","is_ok","id"));
                
                $data = $datas = array();
                $data["buynum"] = $order["buynum"]+1;
                if($data["buynum"] == $order["neednum"]){
                    $data["is_ok"] = 1;
                    
                    $datas['status'] = 4;
                    $datas['paytime'] = time();
                    $set_status = pdo_update('mzhk_sun_ptgroups',$datas, array('id' => $orderinfo["id"], 'uniacid' => $uniacid));
                    $set_status_all = pdo_update('mzhk_sun_ptgroups',array("status"=>4), array('order_id' => $orderinfo["order_id"],'status' => 3, 'uniacid' => $uniacid));
                }else{
                    
                    $datas['status'] = 3;
                    $datas['paytime'] = time();
                    $set_status = pdo_update('mzhk_sun_ptgroups',$datas, array('id' => $orderinfo["id"], 'uniacid' => $uniacid));
                }
                $set_order = pdo_update('mzhk_sun_ptorders',$data, array('id' => $orderinfo["order_id"], 'uniacid' => $uniacid));
                if($set_status){
                    $goods=pdo_get('mzhk_sun_goods',array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                    if($goods["num"]>0){
                        if($goods["stocktype"]==1){
                            pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                        }
                    }
                }
            }
        }elseif($ordertype==3){

        }elseif($ordertype==4){
            $orderinfo = pdo_get('mzhk_sun_order', array('out_trade_no' => $out_trade_no,'uniacid' => $uniacid,'status' => 2));
            if($orderinfo){
                $order = pdo_update('mzhk_sun_order', array('status' => 3,'paytime' => time()), array('oid' => $orderinfo['oid'], 'uniacid' => $uniacid));
                if($order){
                    $goods=pdo_get('mzhk_sun_goods',array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                    if($goods["num"]>0){
                        if($goods["stocktype"]==1){
                            if($goods["num"]>=$orderinfo["num"]){
                                pdo_update('mzhk_sun_goods',array("num -="=>intval($orderinfo["num"])),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                            }else{
                                pdo_update('mzhk_sun_goods',array("num"=>0),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                            }
                        }
                    }
                }
            }
        }elseif($ordertype==5){
            $orderinfo = pdo_get('mzhk_sun_kjorder', array('out_trade_no' => $out_trade_no,'uniacid' => $uniacid,'status' => 2));
            if($orderinfo){
                $order = pdo_update('mzhk_sun_kjorder', array('status' => 3,'paytime' => time()), array('oid' => $orderinfo['oid'], 'uniacid' => $uniacid));
                if($order){
                    $goods=pdo_get('mzhk_sun_goods',array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                    if($goods["num"]>0){
                        if($goods["stocktype"]==1){
                            pdo_update('mzhk_sun_goods',array("num -="=>1),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                        }
                    }
                }
            }
        }elseif($ordertype==6){

        }elseif($ordertype==0){
            $orderinfo = pdo_get('mzhk_sun_qgorder', array('out_trade_no' => $out_trade_no,'uniacid' => $uniacid,'status' => 2));
            if($orderinfo){
                $order = pdo_update('mzhk_sun_qgorder',array('status' => 3,'paytime' => time()), array('oid' => $orderinfo['oid'], 'uniacid' => $uniacid));
                if($order){
                    $goods=pdo_get('mzhk_sun_goods',array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid),array("num","stocktype"));
                    if($goods["num"]>0){
                        if($goods["stocktype"]==1){
                            if($goods["num"]>=$orderinfo["num"]){
                                pdo_update('mzhk_sun_goods',array("num -="=>intval($orderinfo["num"])),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                            }else{
                                pdo_update('mzhk_sun_goods',array("num"=>0),array('gid'=>$orderinfo['gid'],'uniacid'=>$uniacid));
                            }
                        }
                    }
                }
            }
        }elseif($ordertype==10){
            $mercapdetails = pdo_get('mzhk_sun_mercapdetails', array('out_trade_no' => $out_trade_no,'uniacid' => $uniacid,'status' => 0));

            

            if($mercapdetails){
                $bid = $mercapdetails["bid"];
                $price = $mercapdetails["money"];
                $mer_id = $mercapdetails["id"];

                
                $brand = pdo_get('mzhk_sun_brand',array('uniacid'=>$uniacid,'bid'=>$bid),array("memdiscount","bname","bind_openid"));

                include IA_ROOT . '/addons/mzhk_sun/wxfirmpay.php';
                $appData = pdo_get('mzhk_sun_system', array('uniacid' => $uniacid));
                $mch_appid = $appData['appid'];
                $mchid = $appData['mchid'];
                $key = $appData['wxkey'];
                $openid = $brand["bind_openid"];
                $partner_trade_no = $mchid.time().rand(100000,999999);
                $re_user_name = $brand["bname"];
                $desc = "线下付款-自动打款";
                $offlinefee = 0;
                
                if($appData["offlinefee"]>0){
                    $offlinefee = $price*$appData["offlinefee"]/100;
                    $amount = sprintf("%.2f", ($price - $offlinefee));
                    $amount = $amount*100;
                }else{
                    $amount = $price*100;
                }

                $apiclient_cert = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_cert'];
                $apiclient_key = IA_ROOT . "/addons/mzhk_sun/cert/".$appData['apiclient_key'];

                $weixinfirmpay = new WeixinfirmPay($mch_appid, $mchid, $key, $openid,$partner_trade_no,$re_user_name,$desc,$amount,$apiclient_cert,$apiclient_key);
                $return = $weixinfirmpay->pay();

                if($return['result_code']=='SUCCESS'){
                    
                    $data = array();
                    $data["status"] = 1;
                    $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,商家实收".($amount/100)."元，收取手续费".$offlinefee."元";
                    pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
                }else{
                    file_put_contents('notify4.txt', print_r($return, true));
                    $data = array();
                    $data["status"] = 2;
                    $data["mcd_memo"] = "线下付款-直接打款给商家-支付金额:".$price."元,用户付款到平台微信商户号成功，由于绑定微信商户号问题导致无法付款给商家；错误代码".$return['result_code']."-错误信息:".$return['return_msg'].";（".$return['err_code_des']."）";
                    pdo_update('mzhk_sun_mercapdetails', $data, array('id' => $mer_id));
                }
            } 
        }

        die('SUCCESS');
    }

}