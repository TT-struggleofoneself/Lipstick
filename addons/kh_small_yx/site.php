<?php
/**
 * 会创拼团模块微站定义
 *
 * @author huichuang
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT."/addons/kh_small_yx/inc/model.class.php"; 
class kh_small_yxModuleSite extends WeModuleSite {

    public function doWebTest()
    {
        global $_GPC, $_W;
        $png = "/headpic/hexiao".$_GPC['i'].time().".png";
        $filename = dirname(__FILE__).$png;
        var_dump($filename);
    }

    public function download_remote_pic($url){
        global $_W,$_GPC;
        $header = [
            'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0',      
            'Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3',      
            'Accept-Encoding: gzip, deflate',
        ];      
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING,'');  
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);  
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！      
           $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);  
        }  
        $img_content=$imgBase64Code;//图片内容  
        //echo $img_content;exit;  
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {   
            $type = $result[2];//得到图片类型png?jpg?gif?   
            $new_file = time().rand(1,10000).".{$type}";   
            if (file_put_contents(dirname(__FILE__)."/headpic/".$new_file, base64_decode(str_replace($result[1], ‘‘, $img_content)))) {  
                return $_W['siteroot']."addons/kh_small_yx/headpic/".$new_file; 
            }
        } 
    }
	

    /*public function doWebShenhe()
    {
        //这个操作被定义用来呈现 管理中心导航菜单
        global $_W, $_GPC;
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 20;
        $shenhe = pdo_getslice('hczhongzhuan_shenhe', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', array('sort desc'));
        for ($i = 0; $i < count($shenhe); $i++) {
            if ($shenhe[$i]['stact'] == 0) {
                $shenhe[$i]['stact'] = '不显示';
            } else {
                $shenhe[$i]['stact'] = '显示';
            }
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template("shenhe");
    }*/


    public function doWebAddshenhe()
    {
        global $_W, $_GPC;
        //这个操作被定义用来呈现 管理中心导航菜单
        if (!empty($_GPC['name'])) {
            $data['name'] = $_GPC['name'];
            $data['uniacid'] = $_W['uniacid'];
            $data['sort'] = $_GPC['sort'];
            $data['stact'] = $_GPC['stact'];
            $data['img'] = $_GPC['img'];
            $data['content'] = $_GPC['content'];
            $data['time'] = date("Y-m-d");
            pdo_insert('hczhongzhuan_shenhe', $data);
        }
        include $this->template("addshenhe");
    }





    
    public function doWebEditshenhe()
    {
        global $_W, $_GPC;
        $id = $_GPC['id'];
        if (!empty($_GPC['name'])) {
            $data['name'] = $_GPC['name'];
            $data['uniacid'] = $_W['uniacid'];
            $data['sort'] = $_GPC['sort'];
            $data['stact'] = $_GPC['stact'];
            $data['img'] = $_GPC['img'];
            $data['content'] = $_GPC['content'];
            pdo_update('hczhongzhuan_shenhe', $data, array('id' => $id));
        }
        $shenhe = pdo_get('hczhongzhuan_shenhe', array('id' => $id));
        include $this->template("editshenhe");
    }

    public function doWebDeleteshenhe()
    {
        global $_W, $_GPC;
        pdo_delete('hczhongzhuan_shenhe', array('id' => $_GPC['id']));
        $pindex = max(intval($_GPC['page']), 1);
        $psize = 20;
        $shenhe = pdo_getslice('hczhongzhuan_shenhe', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', array('sort desc'));
        for ($i = 0; $i < count($shenhe); $i++) {
            if ($shenhe[$i]['stact'] == 0) {
                $shenhe[$i]['stact'] = '不显示';
            } else {
                $shenhe[$i]['stact'] = '显示';
            }
        }
        $pager = pagination($total, $pindex, $psize);
        include $this->template("shenhe");
    }

    //商品管理
    public function doWebGoods() {
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_goods',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('goods');
    }

    //模板消息
    public function doWebMessage()
    {
        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/           
            //$data['hongbao_msgid'] = $_GPC['hongbao_msgid'];
            $data['msgid'] = $_GPC['msgid'];
            $data['keyword1'] = $_GPC['keyword1'];
            $data['keyword2'] = $_GPC['keyword2'];
            $data['keyword3'] = $_GPC['keyword3'];
            $data['fahuomsgid'] = $_GPC['fahuomsgid'];
            
            $ishave = pdo_get('kh_small_yx_message', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_message', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_message', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('message'));
            }
        }
        $setup = pdo_get('kh_small_yx_message', array('uniacid' => $_W['uniacid']));
        include $this->template('message');
    }




    //添加商品
    public function doWebAddgoods() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['goodsinfo'] = $_GPC['goodsinfo'];
            $data['price2'] = $_GPC['price2'];
            $data['rmb'] = $_GPC['rmb'];
            $data['paytype'] = $_GPC['paytype'];
            $data['maxrmb'] = $_GPC['maxrmb'];
            $data['selltype'] = $_GPC['selltype'];
            $data['shop_id'] = $_GPC['shop_id'];
            $data['minpeople'] = $_GPC['minpeople'];
            $data['maxbuy'] = $_GPC['maxbuy'];
            $data['brand'] = $_GPC['brand'];
            

            $result = pdo_insert('kh_small_yx_goods', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['goodsinfo'] = $_GPC['goodsinfo'];
            $data['price2'] = $_GPC['price2'];
            $data['rmb'] = $_GPC['rmb'];
            $data['paytype'] = $_GPC['paytype'];
            $data['maxrmb'] = $_GPC['maxrmb'];
            $data['selltype'] = $_GPC['selltype'];
            $data['shop_id'] = $_GPC['shop_id'];
            $data['minpeople'] = $_GPC['minpeople'];
            $data['maxbuy'] = $_GPC['maxbuy'];
            $data['brand'] = $_GPC['brand'];

            $result = pdo_update('kh_small_yx_goods', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_goods', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_goods',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('goods'));
            }
        }
        $shop = pdo_getall('kh_small_yx_shop',array('uniacid'=>$_W['uniacid']),array(),'','id asc');
        $info = pdo_get('kh_small_yx_goods',array('id'=>$id));
        $info['goods_img'] = json_decode($info['goods_img']);

        include $this->template('addgoods');
    }

    //奖品管理
    public function doWebAwards() {
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_awards',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);


        include $this->template('awards');
    }


    //添加奖品
    public function doWebAddawards() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];

            $result = pdo_insert('kh_small_yx_awards', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['goods_name'] = $_GPC['goods_name'];
            $data['main_img'] = $_GPC['main_img'];
            $data['goods_img'] = json_encode($_GPC['goods_img']);
            $data['price'] = $_GPC['price'];
            $data['inventory'] = $_GPC['inventory'];
            $data['express'] = $_GPC['express'];
            $data['status'] = $_GPC['status'];

            $result = pdo_update('kh_small_yx_awards', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_awards', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('awards'));
            }
        }
        $info = pdo_get('kh_small_yx_awards',array('id'=>$id));
        $info['goods_img'] = json_decode($info['goods_img']);

        include $this->template('addawards');
    }

    //用户管理
    public function doWebUsers(){
        global $_W,$_GPC;
        $op = $_GPC['op'];
        if ($op == 'del') {
            $id = $_GPC['id'];
            $item = pdo_get('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('users'),'error');
            }
            if(pdo_delete('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('users'),'success');
        }
        if ($op == 'black') {
            $id = $_GPC['id'];
            $item = pdo_get('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('users'),'error');
            }
            if(pdo_update('kh_small_yx_users',array('black'=>1), array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('users'),'success');
        }
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['nick_name LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['user_id'] = $keyword;
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['black'] = 0;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_users',$where,array($pageindex, $pagesize),$total,array(),'','user_id desc');
        $page = pagination($total, $pageindex, $pagesize);
       
        include $this->template('users');      
    }

    //黑名单
    public function doWebBlacklist(){
        global $_W,$_GPC;
        $op = $_GPC['op'];
        if ($op == 'white') {
            $id = $_GPC['id'];
            $item = pdo_get('kh_small_yx_users',array('user_id'=>$id,'uniacid'=>$_W['uniacid']));
            if(empty($item)){
                message('操作失败',$this->createWebUrl('blacklist'),'error');
            }
            if(pdo_update('kh_small_yx_users',array('black'=>0), array('user_id'=>$id,'uniacid'=>$_W['uniacid'])) === false) message('操作失败',referer(),'error');
            else message('操作成功',$this->createWebUrl('blacklist'),'success');
        }
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['nick_name LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['black'] = 1;
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_users',$where,array($pageindex, $pagesize),$total,array(),'','user_id desc');
        $page = pagination($total, $pageindex, $pagesize);
       
        include $this->template('blacklist');      
    }

    



    //充值面额设置
    public function doWebRechange(){

        global $_W,$_GPC;
        $list = pdo_getall('kh_small_yx_rechange',array(), array(),'','id desc');
        include $this->template('rechange');
    }



    //添加充值面值
    public function doWebAddRechange() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['price'] = $_GPC['price'];
            $data['addtime'] = date('y-m-d h:i:s',time());
            $data['state'] = 1;
            $result = pdo_insert('kh_small_yx_rechange', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }
        //上架
        if($_GPC['act']=='up'){
            $data['state'] = 1;
            $result = pdo_update('kh_small_yx_rechange', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }
        //下架
        if($_GPC['act']=='down'){
            $data['state'] =0;
            $result = pdo_update('kh_small_yx_rechange', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }



        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['price'] = $_GPC['price'];
            $data['addtime'] = date('y-m-d h:i:s',time());
            $data['state'] = 1;

            $result = pdo_update('kh_small_yx_rechange', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_rechange', array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_rechange',array(),array());
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('rechange'));
            }
        }

        $info = pdo_get('kh_small_yx_rechange',array('id'=>$id));
        include $this->template('addrechange');
    }






    //基础设置
    public function doWebSetting(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['sharetitle'] = $_GPC['sharetitle'];
            $data['sharepic'] = $_GPC['sharepic'];
            $data['coinname'] = $_GPC['coinname'];
            $data['rate'] = $_GPC['rate'];
            $data['sharestep'] = $_GPC['sharestep'];
            $data['boxprice'] = $_GPC['boxprice'];
            $data['rulepic'] = $_GPC['rulepic'];
            $data['headcolor'] = $_GPC['headcolor'];
            $data['xcx'] = $_GPC['xcx'];
            $data['up'] = $_GPC['up'];
            $data['notice'] = $_GPC['notice'];
            $data['shenhe'] = $_GPC['shenhe'];
            $data['loginpic'] = $_GPC['loginpic'];
            $data['indexbg'] = $_GPC['indexbg'];
            $data['indexbutton'] = $_GPC['indexbutton'];
            $data['inviteball'] = $_GPC['inviteball'];
            $data['upball'] = $_GPC['upball'];
            $data['zerotip'] = $_GPC['zerotip'];
            $data['poortip'] = $_GPC['poortip'];
            $data['is_follow'] = $_GPC['is_follow'];
            $data['followpic'] = $_GPC['followpic'];
            $data['kefupic'] = $_GPC['kefupic'];
            $data['maxstep'] = $_GPC['maxstep'];
            $data['followlogo'] = $_GPC['followlogo'];
            $data['sharetext'] = $_GPC['sharetext'];
            $data['shareinfo'] = $_GPC['shareinfo'];
            $data['upinfo'] = $_GPC['upinfo'];
            $data['boxpic'] = $_GPC['boxpic'];
            $data['smalltip'] = $_GPC['smalltip'];
            $data['frame'] = $_GPC['frame'];
            $data['smalltipcolor'] = $_GPC['smalltipcolor'];
            $data['sharetextcolor'] = $_GPC['sharetextcolor'];
            $data['shareinfocolor'] = $_GPC['shareinfocolor'];
            $data['buttonbg'] = $_GPC['buttonbg'];
            $data['balltextcolor'] = $_GPC['balltextcolor'];
            $data['centercolor'] = $_GPC['centercolor'];
            $data['cointextcolor'] = $_GPC['cointextcolor'];
            $data['coinpic'] = $_GPC['coinpic'];
            $data['invitetype'] = $_GPC['invitetype'];
            $data['longbg'] = $_GPC['longbg'];
            $data['user_level_1'] = $_GPC['user_level_1'];
            $data['user_level_2'] = $_GPC['user_level_2'];
            $data['user_level_3'] = $_GPC['user_level_3'];
            $data['shard_detail'] = $_GPC['shard_detail'];
            
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('setting'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('setting');
    }

    //问题设置
    public function doWebQuestion_set(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['questionpic'] = $_GPC['questionpic'];
            
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question_set'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('question_set');
    }

    //关注设置
    public function doWebGuanzhu(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['kefu_title'] = $_GPC['kefu_title'];
            $data['kefu_img'] = $_GPC['kefu_img'];
            $data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            $data['kefu_url'] = $_GPC['kefu_url'];
            $data['guanzhu_step'] = $_GPC['guanzhu_step'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('guanzhu'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('guanzhu');
    }

    //关注设置
    public function doWebShenhe(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['version'] = $_GPC['version'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shenhe'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('shenhe');
    }

    //流量主设置
    public function doWebAd(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['adunit'] = $_GPC['adunit'];
            $data['adunit2'] = $_GPC['adunit2'];
            $data['adunit3'] = $_GPC['adunit3'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('ad'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('ad');
    }

    //流量主设置
    public function doWebActivityset(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['activitypic'] = $_GPC['activitypic'];
            $data['applypic'] = $_GPC['applypic'];
            $data['rule'] = $_GPC['rule'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activityset'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('activityset');
    }

    //海报设置
    public function doWebPoster(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['sweattext'] = $_GPC['sweattext'];
            $data['icon'] = $_GPC['icon'];
            $data['posterpic'] = $_GPC['posterpic'];
            $data['comeon'] = $_GPC['comeon'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('poster'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('poster');
    }

    //海报设置
    public function doWebSignin(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['signsharemoney'] = $_GPC['signsharemoney'];
            $data['signpic'] = $_GPC['signpic'];
            $data['signsharetext'] = $_GPC['signsharetext'];
            $data['signicon'] = $_GPC['signicon'];
            $data['signtext'] = $_GPC['signtext'];
            $data['signtextcolor'] = $_GPC['signtextcolor'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('signin'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('signin');
    }

    //红包设置
    public function doWebHongbaoset(){

        global $_W,$_GPC;
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            /*empty($_GPC['client_id'])?'':$data['client_id'] = $_GPC['client_id'];*/
            $data['hongbaobg'] = $_GPC['hongbaobg'];
            $data['hongbaotext'] = $_GPC['hongbaotext'];
                      
            $ishave = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
            if(!empty($ishave)){
                $result = pdo_update('kh_small_yx_set', $data ,array('uniacid'=>$_W['uniacid']));
            }else{
                $result = pdo_insert('kh_small_yx_set', $data);
            }
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbaoset'));
            }
        }
        $info = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        include $this->template('hongbaoset');
    }

    //问题管理
    public function doWebQuestion() {
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_question',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        foreach($list as $key=>$val){
            $list[$key]['createtime'] = date('Y-m-d H:i',$val['createtime']);
        }
        $page = pagination($total, $pageindex, $pagesize);

        include $this->template('question');
    }


   //添加问题
    public function doWebQuestion_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            $data['createtime'] = time();
            empty($_GPC['title'])?'':$data['title'] = $_GPC['title'];
            empty($_GPC['content'])?'':$data['content'] = $_GPC['content'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];

            $result = pdo_insert('kh_small_yx_question', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['createtime'] = time();
            empty($_GPC['title'])?'':$data['title'] = $_GPC['title'];
            empty($_GPC['content'])?'':$data['content'] = $_GPC['content'];
            $data['enabled'] = $_GPC['enabled'];

            $result = pdo_update('kh_small_yx_question', $data, array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_question', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('question'));
            }
        }
        $info = pdo_get('kh_small_yx_question',array('id'=>$id));

        include $this->template('question_post');
    }

    //商品兑换记录
    public function doWebExchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['userName'] = $keyword;
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==3){
            $where['telNumber'] = $keyword;
        }
        $user_id = $_GPC['user_id'];  
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }
        $where['uniacid'] = $_W['uniacid'];
        // $where['status !='] = 2;
        // $where['type <'] = 10;
         $where['type'] = 0;
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_orders',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_orders',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('kh_small_yx_goods',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = $v['time'];
        }        

        include $this->template('exchange');
    }


    //充值记录
    public function doWebRecharge_exchange(){
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['userName'] = $keyword;
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==3){
            $where['telNumber'] = $keyword;
        }
        $user_id = $_GPC['user_id'];  
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }
        $where['uniacid'] = $_W['uniacid'];
        // $where['status !='] = 2;
        // $where['type <'] = 10;
        $where['type'] = 2;
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_orders',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_orders',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('kh_small_yx_goods',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = $v['time'];
        }        

        include $this->template('recharge_exchange');
    }



    //玩游戏记录
    public function doWebGame_exchange(){
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['game_level']) and $_GPC['order_status'] ==2){
            $where['game_level'] = $keyword;
        }
        $user_id = $_GPC['user_id'];  
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }

        $where['type'] = 1;//1  玩游戏  
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_moneylog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);
        //$list = pdo_getall('kh_small_yx_orders',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('kh_small_yx_goods',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] =$v['add_time'];
        }   
        include $this->template('game_exchange');
    }



    //奖品兑换记录
    public function doWebWin_exchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==1){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==2){
            $where['userName'] = $keyword;
        }
        if(!empty($_GPC['keyword']) and $_GPC['order_status'] ==3){
            $where['telNumber'] = $keyword;
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_winlog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_winlog',array('uniacid'=>$_W['uniacid']), array(),'','id desc');
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('kh_small_yx_awards',array('id'=>$v['goods_id'],'uniacid'=>$_W['uniacid']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goods_name'] = $goods['goods_name'];
           $list[$k]['time'] = date('Y-m-d H:i:s',$v['time']);
        }        

        include $this->template('win_exchange');
    }

    //步数兑换记录
    public function doWebCoin_exchange() {
        global $_W,$_GPC;
        
        $keyword = $_GPC['keyword'];        
        if(!empty($_GPC['keyword'])){
            $where['user_id LIKE'] = '%'.$keyword.'%';
        }
        $where['uniacid'] = $_W['uniacid'];
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_bushulog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
        }        

        include $this->template('coin_exchange');
    }

    //步数兑换记录
    public function doWebBushulog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_bushulog',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['timestamp'] = date('Y-m-d H:i:s',$v['timestamp']);
        }        

        include $this->template('bushulog');
    }

    //核销记录
    public function doWebHexiaolog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['type >'] = 9;
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_orders',$where,array($pageindex, $pagesize),$total,array(),'','time desc');
        $page = pagination($total, $pageindex, $pagesize);

        //$list = pdo_getall('kh_small_yx_bushulog',array('uniacid'=>$_W['uniacid']));
        
        foreach ($list as $k => $v) {
           $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
           $goods = pdo_get('kh_small_yx_goods',array('uniacid'=>$_W['uniacid'],'id'=>$v['goods_id']));
           $shop = pdo_get('kh_small_yx_shop',array('uniacid'=>$_W['uniacid'],'id'=>$goods['shop_id']));
           $list[$k]['head_pic'] = $user['head_pic'];
           $list[$k]['nick_name'] = $user['nick_name'];
           $list[$k]['goodsname'] = $goods['goods_name'];
           $list[$k]['shopname'] = $shop['shopname'];
           $list[$k]['shop_userid'] = $shop['user_id'];
           $list[$k]['hexiaotime'] = date('Y-m-d:H:i:s',$v['hexiaotime']);
           $list[$k]['time'] = date('Y-m-d:H:i:s',$v['time']);
        }        

        include $this->template('hexiaolog');
    }

    //挑战记录
    public function doWebTiaozhanlog(){
        global $_W,$_GPC;
        $tomorrow = date('Y-m-d',strtotime("+1 day"));      
        $today=date('Y-m-d',time());      
        $yesterday = date('Y-m-d',strtotime("-1 day"));

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $list = pdo_getslice('kh_small_yx_activitylog',$where,array($pageindex, $pagesize),$total,array(),'','timestamp desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v){
              $activity = pdo_get('kh_small_yx_activity',array('uniacid' => $_W['uniacid'],'id'=>$v['aid']));
              $list[$k]['name'] = $activity['step'];
              $list[$k]['entryfee'] = $activity['entryfee'];
              if($v['time'] == $tomorrow){
                $list[$k]['status'] = '未开赛';
              }elseif($v['time'] == $today){
                $list[$k]['status'] = '进行中';
              }else{
                if($v['status'] == 0){
                    $list[$k]['status'] = '挑战失败';
                }else{
                    $list[$k]['status'] = '挑战成功';
                }
              }           
          }       

        include $this->template('tiaozhanlog');
    }

    //邀请记录
    public function doWebInvitelog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];
        $user_id = $_GPC['user_id'];

        $set = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;
        $list = pdo_getslice('kh_small_yx_invitelog',$where,array($pageindex, $pagesize),$total,array(),'','invite_time desc');

        $page = pagination($total, $pageindex, $pagesize);
        
        foreach ($list as $k => $v) {
            $user = pdo_get('kh_small_yx_users',array('user_id'=>$v['sonid'],'uniacid'=>$_W['uniacid']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];  
            $list[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $count = count($list);         

        include $this->template('invitelog');
    }

    //邀请记录
    public function doWebHongbaolog(){
        global $_W,$_GPC;

        $where['uniacid'] = $_W['uniacid'];
        $where['user_id'] = $_GPC['user_id'];

        $user_id = $_GPC['user_id'];

        $set = pdo_get('kh_small_yx_set', array('uniacid' => $_W['uniacid']));
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_hongbaolog',$where,array($pageindex, $pagesize),$total,array(),'','invite_time desc');

        $page = pagination($total, $pageindex, $pagesize);
        
        foreach ($list as $k => $v) {
            $user = pdo_get('kh_small_yx_users',array('user_id'=>$v['sonid'],'uniacid'=>$_W['uniacid']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];  
            $list[$k]['invite_time'] = date('Y-m-d H:i',$v['invite_time']);
        }

        $count = count($list);         

        include $this->template('hongbaolog');
    }



    //发货
    public function doWebFahuo() {
        global $_W,$_GPC;
        $id = $_GPC['id']; 
        $user_id = $_GPC['user_id']; 
        $goods_id = $_GPC['goods_id']; 
        // if($_GPC['op'] == 'shangpin'){
        //     $info = pdo_get('kh_small_yx_orders',array('id'=>$id));
        //     $type = 'shangpin';
        // }
        // if($_GPC['act'] == 'shangpin'){
        //     $info = pdo_get('kh_small_yx_orders',array('id'=>$id));
        //     $data['status'] = 2;
        //     $data['express'] = $_GPC['express'];
        //     $data['expressname'] = $_GPC['expressname'];
        //     $data['fahuotime'] = time();
        //     $res = pdo_update('kh_small_yx_orders',$data, array('id' => $_GPC['id']));
        //     //模板消息
        //     $formid=pdo_getall('kh_small_yx_formid', array('user_id' => $info['user_id'],'status'=>0), array() , '',array('id DESC') , array());
        //     if(!empty($formid[0])){
        //         $formid[0]['orderid'] = $info['id'];
        //         $aa=$this->fahuotpl($formid[0]);
        //     }

        //     if($res){
        //         message('发货成功',$this->createWebUrl('exchange'),'success');
        //     }else{
        //         message('已发货',$this->createWebUrl('exchange'),'error');
        //     }
        // }
        if($_GPC['op'] == 'jiangpin'){
            $info = pdo_get('kh_small_yx_winlog',array('id'=>$id,'user_id'=>$user_id,'goods_id'=>$goods_id));
            if(empty($info)){
                $data['id'] = $id;
                $data['user_id'] = $user_id;
                $data['goods_id'] = $goods_id;
                $data['status'] = 0;
                $data['time'] = date('y-m-d h:i:s',time());
                $res = pdo_insert('kh_small_yx_winlog',$data);
                $info=$data;
            }

            $type = 'jiangpin';
        }
        if($_GPC['act'] == 'jiangpin'){
            $data['status'] = 1;
            $data['express'] = $_GPC['express'];
            $data['expressname'] = $_GPC['expressname'];
            $data['time'] = date('y-m-d h:i:s',time());
            $data['fahuotime'] = date('y-m-d h:i:s',time());

             // var_dump($data);exit;
             //如果是奖品的话，那么就insert
            $res = pdo_update('kh_small_yx_winlog',$data, array('id' => $_GPC['id']));
            if($res){
               $where['status']=2;
               //修改订单表中的订单发货状态
                pdo_update('kh_small_yx_orders',$where,array('id' => $_GPC['id']));

                message('发货成功',$this->createWebUrl('exchange'),'success');
            }else{
                message('已发货',$this->createWebUrl('exchange'),'error');
            }
        }
        include $this->template('fahuo');   
    }








    // //发货
    // public function doWebFahuo() {
    //     global $_W,$_GPC;
    //     $id = $_GPC['id'];      
    //     if($_GPC['op'] == 'shangpin'){
    //         $info = pdo_get('kh_small_yx_orders',array('id'=>$id));
    //         $type = 'shangpin';
    //     }
    //     if($_GPC['act'] == 'shangpin'){
    //         $info = pdo_get('kh_small_yx_orders',array('id'=>$id));
    //         $data['status'] = 2;
    //         $data['express'] = $_GPC['express'];
    //         $data['expressname'] = $_GPC['expressname'];
    //         $data['fahuotime'] = time();
    //         $res = pdo_update('kh_small_yx_orders',$data, array('id' => $_GPC['id']));
    //         //模板消息
    //         $formid=pdo_getall('kh_small_yx_formid', array('user_id' => $info['user_id'],'status'=>0), array() , '',array('id DESC') , array());
    //         if(!empty($formid[0])){
    //             $formid[0]['orderid'] = $info['id'];
    //             $aa=$this->fahuotpl($formid[0]);
    //         }

    //         if($res){
    //             message('发货成功',$this->createWebUrl('exchange'),'success');
    //         }else{
    //             message('已发货',$this->createWebUrl('exchange'),'error');
    //         }
    //     }
    //     if($_GPC['op'] == 'jiangpin'){
    //         $info = pdo_get('kh_small_yx_winlog',array('id'=>$id));
    //         $type = 'jiangpin';
    //     }
    //     if($_GPC['act'] == 'jiangpin'){
    //         $data['status'] = 2;
    //         $data['express'] = $_GPC['express'];
    //         $data['expressname'] = $_GPC['expressname'];
    //         $data['fahuotime'] = time();
    //         $res = pdo_update('kh_small_yx_winlog',$data, array('id' => $_GPC['id']));
    //         if($res){
    //             message('发货成功',$this->createWebUrl('win_exchange'),'success');
    //         }else{
    //             message('已发货',$this->createWebUrl('win_exchange'),'error');
    //         }
    //     }
    
    //     include $this->template('fahuo');	
    // }

    public function doWebAdv() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_adv',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('adv');
    }
    //幻灯片
    public function doWebAdv_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            //empty($_GPC['link'])?'':$data['link'] = $_GPC['link'];
            empty($_GPC['thumb'])?'':$data['thumb'] = $_GPC['thumb'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['jump'])?'':$data['jump'] = $_GPC['jump'];
            empty($_GPC['xcxpath'])?'':$data['xcxpath'] = $_GPC['xcxpath'];
            empty($_GPC['xcxappid'])?'':$data['xcxappid'] = $_GPC['xcxappid'];
            empty($_GPC['h5'])?'':$data['h5'] = $_GPC['h5'];
            empty($_GPC['tippic'])?'':$data['tippic'] = $_GPC['tippic'];
            empty($_GPC['type'])?'':$data['type'] = $_GPC['type'];

            $result = pdo_insert('kh_small_yx_adv', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            //$data['link'] = $_GPC['link'];
            $data['thumb'] = $_GPC['thumb'];
            $data['enabled'] = $_GPC['enabled'];
            $data['jump'] = $_GPC['jump'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['h5'] = $_GPC['h5'];
            $data['tippic'] = $_GPC['tippic'];
            $data['type'] = $_GPC['type'];

            $result = pdo_update('kh_small_yx_adv', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_adv', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_adv',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('adv'));
            }
        }
        $info = pdo_get('kh_small_yx_adv',array('id'=>$id));

        include $this->template('adv_post');
    }

    public function doWebIcon() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_icon',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('icon');
    }
    //幻灯片
    public function doWebIcon_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['advname'])?'':$data['advname'] = $_GPC['advname'];
            empty($_GPC['thumb'])?'':$data['thumb'] = $_GPC['thumb'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['jump'])?'':$data['jump'] = $_GPC['jump'];
            empty($_GPC['xcxpath'])?'':$data['xcxpath'] = $_GPC['xcxpath'];
            empty($_GPC['xcxappid'])?'':$data['xcxappid'] = $_GPC['xcxappid'];
            empty($_GPC['runpic'])?'':$data['runpic'] = $_GPC['runpic'];
            empty($_GPC['advnamecolor'])?'':$data['advnamecolor'] = $_GPC['advnamecolor'];
            empty($_GPC['h5'])?'':$data['h5'] = $_GPC['h5'];
            empty($_GPC['tippic'])?'':$data['tippic'] = $_GPC['tippic'];

            $result = pdo_insert('kh_small_yx_icon', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['advname'] = $_GPC['advname'];
            //$data['link'] = $_GPC['link'];
            $data['thumb'] = $_GPC['thumb'];
            $data['enabled'] = $_GPC['enabled'];
            $data['jump'] = $_GPC['jump'];
            $data['xcxpath'] = $_GPC['xcxpath'];
            $data['xcxappid'] = $_GPC['xcxappid'];
            $data['runpic'] = $_GPC['runpic'];
            $data['advnamecolor'] = $_GPC['advnamecolor'];
            $data['h5'] = $_GPC['h5'];
            $data['tippic'] = $_GPC['tippic'];

            $result = pdo_update('kh_small_yx_icon', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_icon', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_icon',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('icon'));
            }
        }
        $info = pdo_get('kh_small_yx_icon',array('id'=>$id));

        include $this->template('icon_post');
    }

    public function doWebHongbao() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_hongbao',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('hongbao');
    }
    //幻灯片
    public function doWebHongbao_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['hongbaoname'])?'':$data['hongbaoname'] = $_GPC['hongbaoname'];
            empty($_GPC['hongbaomoney'])?'':$data['hongbaomoney'] = $_GPC['hongbaomoney'];
            empty($_GPC['enabled'])?'':$data['enabled'] = $_GPC['enabled'];
            empty($_GPC['hongbaopic'])?'':$data['hongbaopic'] = $_GPC['hongbaopic'];
            empty($_GPC['hongbaonamecolor'])?'':$data['hongbaonamecolor'] = $_GPC['hongbaonamecolor'];

            $result = pdo_insert('kh_small_yx_hongbao', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['hongbaoname'] = $_GPC['hongbaoname'];
            $data['hongbaomoney'] = $_GPC['hongbaomoney'];
            $data['enabled'] = $_GPC['enabled'];
            $data['hongbaopic'] = $_GPC['hongbaopic'];
            $data['hongbaonamecolor'] = $_GPC['hongbaonamecolor'];

            $result = pdo_update('kh_small_yx_hongbao', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_hongbao', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_hongbao',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('hongbao'));
            }
        }
        $info = pdo_get('kh_small_yx_hongbao',array('id'=>$id));

        include $this->template('hongbao_post');
    }

    public function doWebXuni() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_xuni',$where,array($pageindex, $pagesize),$total,array(),'','time desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
            $goods = pdo_get('kh_small_yx_goods',array('id'=>$v['goods_id']));
            $list[$k]['goods_name'] = $goods['goods_name'];
        }

        //var_dump($list);

        include $this->template('xuni');
    }
    //虚拟订单
    public function doWebXuni_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['nick_name'])?'':$data['nick_name'] = $_GPC['nick_name'];
            empty($_GPC['head_pic'])?'':$data['head_pic'] = $_GPC['head_pic'];
            empty($_GPC['time'])?'':$data['time'] = $_GPC['time'];
            empty($_GPC['goods_id'])?'':$data['goods_id'] = $_GPC['goods_id'];

            $result = pdo_insert('kh_small_yx_xuni', $data);            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['nick_name'] = $_GPC['nick_name'];
            $data['head_pic'] = $_GPC['head_pic'];
            $data['time'] = $_GPC['time'];
            $data['goods_id'] = $_GPC['goods_id'];

            $result = pdo_update('kh_small_yx_xuni', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_xuni', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('xuni'));
            }
        }
        $goods = pdo_getall('kh_small_yx_goods',array('uniacid'=>$_W['uniacid']),array(),'','id asc');
        $info = pdo_get('kh_small_yx_xuni',array('id'=>$id));

        //var_dump($goods);

        include $this->template('xuni_post');
    }
    //门店管理
    public function doWebShop() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_shop',$where,array($pageindex, $pagesize),$total,array(),'','id desc');
        $page = pagination($total, $pageindex, $pagesize);

        foreach ($list as $k => $v) {
            $user = pdo_get('kh_small_yx_users',array('user_id'=>$v['user_id']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['head_pic'] = $user['head_pic'];
        }

        //var_dump($list);

        include $this->template('shop');
    }
    //虚拟订单
    public function doWebShop_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['shopname'])?'':$data['shopname'] = $_GPC['shopname'];
            empty($_GPC['logo'])?'':$data['logo'] = $_GPC['logo'];
            empty($_GPC['topbg'])?'':$data['topbg'] = $_GPC['topbg'];
            $data['sheng'] = $_GPC['dizhi']['province'];
            $data['shi'] = $_GPC['dizhi']['city'];
            $data['qu'] = $_GPC['dizhi']['district'];
            empty($_GPC['tel'])?'':$data['tel'] = $_GPC['tel'];
            empty($_GPC['address'])?'':$data['address'] = $_GPC['address'];
            empty($_GPC['starttime'])?'':$data['starttime'] = $_GPC['starttime'];
            empty($_GPC['endtime'])?'':$data['endtime'] = $_GPC['endtime'];
            empty($_GPC['user_id'])?'':$data['user_id'] = $_GPC['user_id'];

            //var_dump($data);

            $info = pdo_get('kh_small_yx_users',array('user_id'=>$_GPC['user_id']));
            if(!empty($info)){
                $result = pdo_insert('kh_small_yx_shop', $data);            
                if (!empty($result)) {
                    message('操作成功',$this->createWebUrl('shop'));
                }
            }else{
                message('用户不存在',$this->createWebUrl('shop'));
            }
         
        }
        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['shopname'] = $_GPC['shopname'];
            $data['logo'] = $_GPC['logo'];
            $data['topbg'] = $_GPC['topbg'];
            $data['sheng'] = $_GPC['dizhi']['province'];
            $data['shi'] = $_GPC['dizhi']['city'];
            $data['qu'] = $_GPC['dizhi']['district'];
            $data['tel'] = $_GPC['tel'];
            $data['address'] = $_GPC['address'];
            $data['starttime'] = $_GPC['starttime'];
            $data['endtime'] = $_GPC['endtime'];
            $data['user_id'] = $_GPC['user_id'];

            $result = pdo_update('kh_small_yx_shop', $data, array('id'=>$_GPC['id']));            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shop'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_shop', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('shop'));
            }
        }

        $info = pdo_get('kh_small_yx_shop',array('id'=>$id));


        include $this->template('shop_post');
    }

    public function doWebKefu(){
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_kefu',$where,array($pageindex, $pagesize),$total,array(),'','id asc');
        $page = pagination($total, $pageindex, $pagesize);

        //var_dump($list);

        include $this->template('kefu');
    }
    //幻灯片
    public function doWebKefu_post(){
        global $_W,$_GPC;
        $id = $_GPC['id'];
        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['kefu_keyword'])?'':$data['kefu_keyword'] = $_GPC['kefu_keyword'];
            empty($_GPC['kefu_title'])?'':$data['kefu_title'] = $_GPC['kefu_title'];
            empty($_GPC['kefu_img'])?'':$data['kefu_img'] = $_GPC['kefu_img'];
            empty($_GPC['kefu_gaishu'])?'':$data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            empty($_GPC['kefu_url'])?'':$data['kefu_url'] = $_GPC['kefu_url'];
            empty($_GPC['beizhu'])?'':$data['beizhu'] = $_GPC['beizhu'];

            $result = pdo_insert('kh_small_yx_kefu', $data);
            //客服消息
            if(!empty($_GPC['kefu_keyword'])){
                $isrule = pdo_get('rule',array('module' => $_W['current_module']['name']));
                if(empty($isrule)){
                    pdo_insert('rule',array(
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'name'   => $_W['current_module']['title'],
                        'module' => $_W['current_module']['name'],
                        'status' => 1
                        )
                    );
                    $rule_id = pdo_insertid();
                }else{
                    $rule_id = $isrule['id'];
                }
                
                $isrule_keyword = pdo_get('rule_keyword',array(
                    'rid'    => $rule_id,
                    'uniacid'=> $_W['uniaccount']['uniacid'],
                    'module' => $_W['current_module']['name'],
                    'content'=> $_GPC['kefu_keyword'],
                    )
                );

                if(empty($isrule_keyword)){
                    pdo_insert('rule_keyword',array(
                        'rid'    => $rule_id,
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'module' => $_W['current_module']['name'],
                        'content'=> $_GPC['kefu_keyword'],
                        'type'   => 1,
                        'status' => 1
                        )
                    );
                }
            }

            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }
        if($_GPC['act']=='edit'){
            $data['kefu_keyword'] = $_GPC['kefu_keyword'];
            $data['kefu_title'] = $_GPC['kefu_title'];
            $data['kefu_img'] = $_GPC['kefu_img'];
            $data['kefu_gaishu'] = $_GPC['kefu_gaishu'];
            $data['kefu_url'] = $_GPC['kefu_url'];
            $data['beizhu'] = $_GPC['beizhu'];

            $result = pdo_update('kh_small_yx_kefu', $data, array('id'=>$_GPC['id']));

            if(!empty($_GPC['kefu_keyword'])){
                $isrule = pdo_get('rule',array('module' => $_W['current_module']['name']));
                if(empty($isrule)){
                    pdo_insert('rule',array(
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'name'   => $_W['current_module']['title'],
                        'module' => $_W['current_module']['name'],
                        'status' => 1
                        )
                    );
                    $rule_id = pdo_insertid();
                }else{
                    $rule_id = $isrule['id'];
                }
                
                $isrule_keyword = pdo_get('rule_keyword',array(
                    'rid'    => $rule_id,
                    'uniacid'=> $_W['uniaccount']['uniacid'],
                    'module' => $_W['current_module']['name'],
                    'content'=> $_GPC['kefu_keyword'],
                    )
                );

                if(empty($isrule_keyword)){
                    pdo_insert('rule_keyword',array(
                        'rid'    => $rule_id,
                        'uniacid'=> $_W['uniaccount']['uniacid'],
                        'module' => $_W['current_module']['name'],
                        'content'=> $_GPC['kefu_keyword'],
                        'type'   => 1,
                        'status' => 1
                        )
                    );
                }
            }

            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }
        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_kefu', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('kefu'));
            }
        }

        $info = pdo_get('kh_small_yx_kefu',array('id'=>$id));

        include $this->template('kefu_post');
    }

    public function doWebActivity() {
        global $_W,$_GPC;
        $where['uniacid'] = $_W['uniacid'];
        
        $pageindex = max(1, intval($_GPC['page']));
        $pagesize = 10;

        $list = pdo_getslice('kh_small_yx_activity',$where,array($pageindex, $pagesize),$total,array(),'','displayorder asc');
        $page = pagination($total, $pageindex, $pagesize);
        //var_dump($list);

        include $this->template('activity');
    }
    //幻灯片
    public function doWebActivity_post() {
        global $_W,$_GPC;
        $id = $_GPC['id'];

        if($_GPC['act']=='add'){
            $data['uniacid'] = $_W['uniacid'];
            empty($_GPC['displayorder'])?'':$data['displayorder'] = $_GPC['displayorder'];
            empty($_GPC['step'])?'':$data['step'] = $_GPC['step'];
            empty($_GPC['entryfee'])?'':$data['entryfee'] = $_GPC['entryfee'];
            /*empty($_GPC['starttime'])?'':$data['starttime'] = $_GPC['starttime'];
            empty($_GPC['endtime'])?'':$data['endtime'] = $_GPC['endtime'];
            empty($_GPC['rule'])?'':$data['rule'] = $_GPC['rule'];*/

            $result = pdo_insert('kh_small_yx_activity', $data);
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }

        if($_GPC['act']=='edit'){
            $data['uniacid'] = $_W['uniacid'];
            $data['displayorder'] = $_GPC['displayorder'];
            $data['step'] = $_GPC['step'];
            $data['entryfee'] = $_GPC['entryfee'];
            /*$data['starttime'] = $_GPC['starttime'];
            $data['endtime'] = $_GPC['endtime'];
            $data['rule'] = $_GPC['rule'];*/

            $result = pdo_update('kh_small_yx_activity', $data, array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }

        if($_GPC['act']=='del'){
            $result = pdo_delete('kh_small_yx_activity', array('id'=>$_GPC['id']));
            
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }
        if($_GPC['act']=='display'){
            $result = pdo_update('kh_small_yx_activity',array('displayorder'=>$_GPC['displayorder']),array('id'=>$_GPC['id']));
            if (!empty($result)) {
                message('操作成功',$this->createWebUrl('activity'));
            }
        }
        $info = pdo_get('kh_small_yx_activity',array('id'=>$id));

        include $this->template('activity_post');
    }

    public function doWebActivitylog(){
        global $_W,$_GPC;
        $aid = $_GPC['id'];
        $set = pdo_get('kh_small_yx_activity', array('uniacid'=>$_W['uniacid'],'id'=>$aid));
        $step = $set['step'];

        $lastday = date('Y-m-d',strtotime("-1 day"));
        $today = date('Y-m-d',time());
        $zuotian = date('Y年m月d日',strtotime("-1 day"));

        $data['yesterday']['success'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status !='=>0,'aid'=>$aid));
        $success = count($data['yesterday']['success']);
        $data['yesterday']['fail'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>0,'aid'=>$aid));
        $fail = count($data['yesterday']['fail']);
        $data['yesterday']['zong'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        $zong = count($data['yesterday']['zong']);

        if ($success == 0){
            $jiangjin = 0;
        }else{
            $jiangjin = $zong * $set['entryfee'] / $success;
        }
        
        $list = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        foreach ($list as $k => $v) {
            $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
            $list[$k]['nick_name'] = $user['nick_name'];
            $list[$k]['time'] = date("Y-m-d H:i:s",$v['timestamp']);
            if($v['status'] == 1){
               $list[$k]['status'] = '已达标，未发奖';
               $list[$k]['jiangjin'] = $jiangjin;
            }elseif($v['status'] == 2){
               $list[$k]['status'] = '已达标，已发奖';
               $list[$k]['jiangjin'] = $jiangjin;
            }else{
               $list[$k]['status'] = '未达标';
               $list[$k]['jiangjin'] = 0;
            } 
        }
        
        include $this->template('activitylog');
    }

    public function doWebsendmoney(){
        
        global $_W, $_GPC;
        $aid = $_GPC['id'];
        $set = pdo_get('kh_small_yx_activity', array('uniacid'=>$_W['uniacid'],'id'=>$aid));

        $lastday = date('Y-m-d',strtotime("-1 day"));
        $data['yesterday']['success'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>1,'aid'=>$aid));
        $success = count($data['yesterday']['success']);
        $data['yesterday']['fail'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'status'=>0,'aid'=>$aid));
        $fail = count($data['yesterday']['fail']);
        $data['yesterday']['zong'] = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));
        $zong = count($data['yesterday']['zong']);

        $jiangjin = $zong * $set['entryfee'] / $success;
        
        $list = pdo_getall('kh_small_yx_activitylog',array('uniacid'=>$_W['uniacid'],'time'=>$lastday,'aid'=>$aid));

        foreach ($list as $k => $v) {
            if($v['status'] == 1){
                $user = pdo_get('kh_small_yx_users',array('uniacid'=>$_W['uniacid'],'user_id'=>$v['user_id']));
                if(!empty($user)){
                    $nowmoney = $user['money'] + $jiangjin;
                    $faqian[] = pdo_update('kh_small_yx_users',array('money' => $nowmoney), array('user_id'=>$v['user_id'],'uniacid' =>$_W['uniacid']));
                    $zhuangtai = pdo_update('kh_small_yx_activitylog',array('status' => 2 ,'jiangjin' => $jiangjin), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                }else{
                    $zhuangtai = pdo_update('kh_small_yx_activitylog',array('status' =>-1), array('id'=>$v['id'],'uniacid' =>$_W['uniacid']));
                }
            }
        }

        if ($faqian){
            message('发放成功',$this->createWebUrl('activity'),'success');
        }else{
            message('没有可发放的记录',$this->createWebUrl('activity'),'success');
        }

    }
    //模板消息
    public function doWebMsg(){
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_getall('kh_small_yx_users', array('uniacid' => $_W['uniacid']));
        for($i=0;$i<count($users);$i++){
            $formid=pdo_getall('kh_small_yx_formid', array('user_id' => $users[$i]['user_id'],'status'=>0), array() , '',array('id DESC') , array());
            if(!empty($formid[0])){
                $aa=$this->getMessage($formid[0]);
                echo "<pre>";
                //var_dump($aa);
                echo"</br>";
                var_dump($formid[0]);
                echo "</pre>";
            }
        }
        echo "发送成功，请关闭";
    }

    //发奖模板消息
    public function doWebFajiang(){
        ob_end_clean();
        global $_GPC, $_W;
        $users=pdo_getall('kh_small_yx_users', array('uniacid' => $_W['uniacid']));
        for($i=0;$i<count($users);$i++){
            $formid=pdo_getall('kh_small_yx_formid', array('user_id' => $users[$i]['user_id'],'status'=>0), array() , '',array('id DESC') , array());
            if(!empty($formid[0])){
                $aa=$this->getMessage($formid[0]);
                echo "<pre>";
                //var_dump($aa);
                echo "</pre>";
            }
        }
        echo "发送成功，请关闭";
    }

    public function getMessage($formid) {
        global $_GPC, $_W;
        $user=pdo_get('kh_small_yx_users', array('user_id' => $formid['user_id']));
        $setup = pdo_get('kh_small_yx_message', array('uniacid' => $_W['uniacid']));      
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->wx_get_token();
        $data['touser']=$user['open_id'];
        $data['template_id']=$setup['msgid'];
        //$setup=json_decode($setup,true);
        $data['form_id']=$formid['formid'];
        $data['page']='kh_small_yx/pages/index/index';
        $data['data']['keyword1']['value']=$setup['keyword1'];
        $data['data']['keyword1']['color']='#173177';
        $data['data']['keyword2']['value']=$setup['keyword2'];
        $data['data']['keyword2']['color']='#173177';
        $data['data']['keyword3']['value']=$setup['keyword3'];
        $data['data']['keyword3']['color']='#000000';
        $json = json_encode($data);
        $dete=$this->api_notice_increment($url,$json);
        pdo_update('kh_small_yx_formid', array('status' => 1), array('id' => $formid['id']));
        return $dete;
    }

    public function fahuotpl($formid){
        global $_GPC, $_W;
        $user=pdo_get('kh_small_yx_users', array('user_id' => $formid['user_id']));
        $info = pdo_get('kh_small_yx_orders',array('id'=>$formid['orderid']));
        $goods = pdo_get('kh_small_yx_goods',array('id'=>$info['goods_id']));
        $setup = pdo_get('kh_small_yx_message', array('uniacid' => $_W['uniacid']));      
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$this->wx_get_token();
        $data['touser']=$user['open_id'];
        $data['template_id']=$setup['fahuomsgid'];
        //$setup=json_decode($setup,true);
        $data['form_id']=$formid['formid'];
        $data['page']='kh_small_yx/pages/index/index';
        $data['data']['keyword1']['value']=$info['expressname'];
        $data['data']['keyword1']['color']='#173177';
        $data['data']['keyword2']['value']=date("Y-m-d",$info['fahuotime']);
        $data['data']['keyword2']['color']='#173177';
        $data['data']['keyword3']['value']=$goods['goods_name'];
        $data['data']['keyword3']['color']='#000000';
        $data['data']['keyword4']['value']=$info['express'];
        $data['data']['keyword4']['color']='#000000';
        $json = json_encode($data);
        $dete=$this->api_notice_increment($url,$json);
        pdo_update('kh_small_yx_formid', array('status' => 1), array('id' => $formid['id']));
        return $dete;
    }

    function api_notice_increment($url, $data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    function wx_get_token() {
        global $_GPC, $_W;
        $appid=$_W['account']['key'];
        $AppSecret=$_W['account']['secret'];
        $res = file_get_contents('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$AppSecret);
        $res = json_decode($res, true);
        $token = $res['access_token'];
        return $token;
    }



}