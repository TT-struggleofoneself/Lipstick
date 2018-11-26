

<?php
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';

header('Content-type:text/html;charset=UTF-8');

$xml = (string)file_get_contents("php://input"); // 这里拿到微信返回的数据结果
//禁止引用外部xml实体


libxml_disable_entity_loader(true);
$payreturn = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);

if ($payreturn['result_code'] == 'SUCCESS' && $payreturn['result_code'] == 'SUCCESS') {

            $ordersn = trim($payreturn['out_trade_no']);
            //查询支付表中的支付记录
            $order = pdo_get('kh_small_yx_payment',array('ordersn'=>$ordersn));

            //如果
            if($order['paystatus']==1){
                 echo 'success';
                return;
            }

           //更改订单的状态
            $goods_order = pdo_get('kh_small_yx_orders',array('goods_id'=>$order['goods_id'],'oid'=>$order['id']));
            if($goods_order){
                pdo_update('kh_small_yx_orders', array('status'=>1), array('goods_id'=>$order['goods_id'],'oid'=>$order['id']));
            }

            //更改
            $user = pdo_get('kh_small_yx_users',array('user_id'=>$order['uid']));
            if ($order){
                var_dump($order);
                //更新订单状态
                $order_data = array(
                    'paystatus'=>1,
                    'paytime'=>date('y-m-d h:i:s',time()),
                    'transid'=>$payreturn['transaction_id']
                );
                pdo_update('kh_small_yx_payment', $order_data, array('ordersn'=>$ordersn));

            }
            //查询支付类型
            $type = pdo_getcolumn('kh_small_yx_payment',array('ordersn'=>$ordersn),array('paytype'));
            if($type == 0){//微信支付

                 //查询充值类型是多少金额
                $user = pdo_get('kh_small_yx_users',array('user_id'=>$order['uid']));
                $rechange = pdo_get('kh_small_yx_rechange', array('id'=>$order["goods_id"]));
                $userMoneyData["money"]=$user["money"]+$rechange["price"];


                //记录金额变动的记录
                $moneylogData["user_id"]=$user["user_id"];
                $moneylogData["goods_id"]=$order["goods_id"];
                $moneylogData["type"]="0";
                $moneylogData["last_money"]=$user["money"];
                $moneylogData["current_money"]=$userMoneyData["money"];
                $moneylogData["add_time"]=date('y-m-d h:i:s',time());
                $moneylogData["change_money"]=$rechange["price"];
                //增加金额变动记录
                pdo_insert('kh_small_yx_moneylog',$moneylogData);

                //更新用户钱包
                $result=pdo_update('kh_small_yx_users',$userMoneyData, array('user_id'=>$user["user_id"]));
            }
            


            // if($type == 0){
            //     $user_data = array('status'=>0);
            //     pdo_update('kh_small_yx_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
            //     $nowinventory = $goods['inventory'] - 1;//库存减少
            //     pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
            // }
            // if($type == 1)
            // {
            //     $user_data = array('status'=>0);
            //     pdo_update('kh_small_yx_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
            //     $nowinventory = $goods['inventory'] - 1;
            //     pdo_update('kh_small_yx_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
            //     $nowmoney = $user['money'] - $goods['price2'];
            //     pdo_update('kh_small_yx_users',array('money'=>$nowmoney), array('user_id' =>$order['uid']));
            // }
            echo 'success';
            return;
        }
