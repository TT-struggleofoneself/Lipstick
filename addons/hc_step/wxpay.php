<?php
define('IN_MOBILE', true);
require '../../framework/bootstrap.inc.php';
$postStr = file_get_contents("php://input"); // 这里拿到微信返回的数据结果
libxml_disable_entity_loader(true);
$xmlstring = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
$payreturn = json_decode(json_encode($xmlstring),true);
if ($payreturn['result_code'] == 'SUCCESS' && $payreturn['result_code'] == 'SUCCESS') {

    $ordersn = trim($payreturn['out_trade_no']);
    $order = pdo_get('hcstep_payment',array('ordersn'=>$ordersn));
    $goods = pdo_get('hcstep_goods',array('id'=>$order['goods_id']));
    $user = pdo_get('hcstep_users',array('user_id'=>$order['uid']));
    if ($order){
        //更新订单状态
        $order_data = array(
            'paystatus'=>1,
            'paytime'=>time(),
            'transid'=>$payreturn['transaction_id']
        );
        pdo_update('hcstep_payment', $order_data, array('ordersn'=>$ordersn));

    }
    $type = pdo_getcolumn('hcstep_payment',array('ordersn'=>$ordersn),array('paytype'));
    if($type == 0){
        $user_data = array('status'=>0);
        pdo_update('hcstep_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
        $nowinventory = $goods['inventory'] - 1;
        pdo_update('hcstep_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
    }
    if($type == 1)
    {
        $user_data = array('status'=>0);
        pdo_update('hcstep_orders', $user_data, array('oid'=>$order['id']));       //用户状态为代理
        $nowinventory = $goods['inventory'] - 1;
        pdo_update('hcstep_goods',array('inventory'=>$nowinventory), array('id' => $order['goods_id']));
        $nowmoney = $user['money'] - $goods['price2'];
        pdo_update('hcstep_users',array('money'=>$nowmoney), array('user_id' =>$order['uid']));
    }
    echo 'success';
    return ;
}
