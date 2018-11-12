<?php

$sql="CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_brandpaylog') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '商家id',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未完成支付，1已经支付',
  `paytime` int(11) NOT NULL COMMENT '支付时间',
  `out_trade_no` varchar(50) NOT NULL COMMENT '外部订单号',
  `openid` varchar(200) NOT NULL COMMENT '支付的openid',
  `uniacid` int(11) NOT NULL COMMENT '应用id',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_cardcollect') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_str_id` text NOT NULL COMMENT '收集的卡片id列表',
  `card_img` varchar(200) NOT NULL COMMENT '卡片图',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `gid` int(11) NOT NULL COMMENT '活动id',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `allnum` int(11) NOT NULL DEFAULT '0' COMMENT '总次数',
  `usednum` int(11) NOT NULL DEFAULT '0' COMMENT '已使用次数',
  `endtime` int(11) NOT NULL COMMENT '活动结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_cardorder') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `gid` int(11) NOT NULL COMMENT '集卡活动id',
  `addtime` int(11) NOT NULL COMMENT '集成时间',
  `ordernum` varchar(50) NOT NULL COMMENT '编号',
  `detailinfo` varchar(100) NOT NULL COMMENT '地址',
  `telnumber` varchar(30) NOT NULL COMMENT '电话',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0未发货，1已发货，2已领取',
  `countyname` varchar(20) NOT NULL COMMENT '区域',
  `provincename` varchar(20) NOT NULL COMMENT '省份',
  `name` varchar(30) NOT NULL COMMENT '姓名',
  `cityname` varchar(20) NOT NULL COMMENT '城市',
  `shiptime` int(11) NOT NULL COMMENT '发货时间',
  `endtime` int(11) NOT NULL COMMENT '活动结束时间',
  `gname` varchar(200) NOT NULL COMMENT '商品名称',
  `sincetype` varchar(100) NOT NULL DEFAULT '0' COMMENT '发货类型',
  `time` varchar(100) NOT NULL,
  `uremark` varchar(100) NOT NULL COMMENT '备注',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款',
  `goodsimg` varchar(200) NOT NULL COMMENT '商品图',
  `bid` int(11) NOT NULL COMMENT '商家id',
  `bname` int(11) NOT NULL COMMENT '商家名称',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '数量',
  `deliveryfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费',
  `out_trade_no` varchar(100) NOT NULL COMMENT '外部订单号',
  `shipnum` varchar(50) NOT NULL COMMENT '快递单号',
  `shipname` varchar(50) NOT NULL COMMENT '快递名称',
  `finishtime` int(11) NOT NULL COMMENT '结束时间',
  `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号',
  `expirationtime` int(11) NOT NULL COMMENT '核销过期时间',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_cardshare') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `addtime` int(11) NOT NULL COMMENT '分享时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1成功，0失败',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `gid` int(11) NOT NULL COMMENT 'gid',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `num` int(11) NOT NULL COMMENT '当天分享次数',
  `click_user_str` text NOT NULL COMMENT '点击人，用英文逗号隔开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_cuthelp') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL COMMENT '帮砍人openid',
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `username` varchar(100) NOT NULL COMMENT '帮砍人名称',
  `cs_id` int(11) NOT NULL DEFAULT '0' COMMENT '砍主id',
  `isself` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0帮砍，1自砍',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `nowprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前价格',
  `cutprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '砍掉价格',
  `addtime` int(11) NOT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_cutself') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL COMMENT '砍价openid',
  `username` varchar(50) NOT NULL COMMENT '砍价用户名',
  `uniacid` int(11) NOT NULL DEFAULT '0' COMMENT 'uniacid',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `shopprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
  `nowprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前价格',
  `lavenum` int(11) NOT NULL COMMENT '剩余人数',
  `allnum` int(11) NOT NULL DEFAULT '0' COMMENT '总次数',
  `lowprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最低价格',
  `addtime` int(11) NOT NULL COMMENT '时间',
  `is_buy` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否已经下单，0未下单，1已经下单',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_mercapdetails') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL COMMENT '商家id',
  `bname` varchar(100) NOT NULL COMMENT '商家名称',
  `mcd_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '类型，1订单收入，2提现，3线下收款（线下收入直接打进商家账号，这里只是一个记录）',
  `mcd_memo` text NOT NULL COMMENT '订单收入等具体信息',
  `addtime` int(11) NOT NULL COMMENT '时间',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `paycommission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '提现的时候，支付的佣金',
  `uniacid` int(11) NOT NULL COMMENT '11',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态，1成功，2不成功',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `wd_id` int(11) NOT NULL DEFAULT '0' COMMENT '提现id',
  `ratesmoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_sms') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appkey` varchar(100) NOT NULL,
  `tpl_id` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `is_open` int(11) NOT NULL DEFAULT '2',
  `tid1` varchar(50) NOT NULL,
  `tid2` varchar(50) NOT NULL,
  `tid3` varchar(50) NOT NULL,
  `order_tplid` int(11) NOT NULL COMMENT '聚合-订单提醒id',
  `order_refund_tplid` int(11) NOT NULL COMMENT '聚合-订单退款提醒id',
  `smstype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '短信类型，1为253，2为聚合',
  `ytx_apiaccount` varchar(50) NOT NULL COMMENT '253短信账号',
  `ytx_apipass` varchar(50) NOT NULL COMMENT '253短信密码',
  `ytx_apiurl` varchar(50) NOT NULL COMMENT '253短信地址',
  `ytx_order` varchar(255) NOT NULL COMMENT '云通信订单消息提醒',
  `ytx_orderrefund` varchar(255) NOT NULL COMMENT '云通信退款订单消息提醒',
  `tid4` varchar(50) DEFAULT NULL COMMENT '开奖模板',
  `aly_accesskeyid` varchar(255) NOT NULL COMMENT '阿里大鱼 accessKeyId',
  `aly_accesskeysecret` varchar(255) NOT NULL COMMENT '阿里大鱼 AccessKeySecret',
  `aly_order` varchar(255) NOT NULL COMMENT '阿里大鱼 订单模板',
  `aly_orderrefund` varchar(255) NOT NULL COMMENT '阿里大鱼 退款模板',
  `aly_sign` varchar(100) NOT NULL COMMENT '签名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_storefacility') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facilityname` varchar(50) NOT NULL COMMENT '设施名称',
  `selectedimg` varchar(200) NOT NULL COMMENT '选中图',
  `unselectedimg` varchar(200) NOT NULL COMMENT '未选中图',
  `sort` int(11) NOT NULL COMMENT '排序',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_storelimit') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻期限类别id',
  `lt_name` varchar(30) NOT NULL COMMENT '入驻期限类别名称',
  `lt_day` int(5) NOT NULL COMMENT '入驻期限类别天数',
  `uniacid` int(11) NOT NULL COMMENT '应用id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '入驻价格',
  `sort` int(5) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_storelimittype') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lt_name` varchar(50) NOT NULL COMMENT '期限名',
  `lt_day` int(5) NOT NULL COMMENT '期限天数',
  `uniacid` int(11) NOT NULL COMMENT '应用id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_storecate') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(255) NOT NULL COMMENT '店铺分类名称',
  `store_img` varchar(200) NOT NULL COMMENT '店铺分类图',
  `sort` int(5) NOT NULL COMMENT '排序',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_userformid') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `form_id` varchar(50) NOT NULL COMMENT 'form_id',
  `time` datetime NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL COMMENT 'openid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='formid表' AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_vipcode') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipid` int(11) NOT NULL COMMENT 'vip种类id',
  `vc_code` varchar(100) NOT NULL COMMENT 'vip激活码',
  `vc_isuse` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0未使用，1已使用',
  `vc_starttime` datetime NOT NULL COMMENT '使用开始时间',
  `vc_endtime` datetime NOT NULL COMMENT '过期时间',
  `uid` int(11) NOT NULL COMMENT '激活的用户id',
  `uniacid` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员卡价格',
  `viptitle` varchar(100) NOT NULL COMMENT 'vip名称',
  `vipday` int(11) NOT NULL COMMENT '激活天数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_vippaylog') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vipid` int(11) NOT NULL COMMENT '激活码类别id',
  `viptitle` varchar(50) NOT NULL COMMENT 'vip类别名称',
  `uniacid` int(11) NOT NULL COMMENT '标识id',
  `activetype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '激活类别，0激活码激活，1线上购买激活',
  `vc_code` varchar(100) NOT NULL COMMENT '激活码',
  `addtime` int(11) NOT NULL DEFAULT '0' COMMENT '激活时间',
  `openid` varchar(200) NOT NULL COMMENT 'openid',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员卡价格',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_withdraw') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL COMMENT '提现用户oppenid',
  `money` decimal(10,2) NOT NULL COMMENT '提现金额',
  `wd_type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '提现方式，1微信，2支付宝，3银行账号',
  `wd_account` varchar(100) NOT NULL COMMENT '提现账号',
  `wd_name` varchar(50) NOT NULL COMMENT '提现名字',
  `wd_phone` varchar(50) NOT NULL COMMENT '提现联系方式',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0审核中，1通过审核，2拒绝提现，3自动打款',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际金额',
  `paycommission` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '需要支付佣金',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `ratesmoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '手续费',
  `bid` int(11) NOT NULL DEFAULT '0' COMMENT '商家id',
  `bname` varchar(100) NOT NULL COMMENT '提现商家名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_withdrawset') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wd_type` varchar(50) NOT NULL DEFAULT '1' COMMENT '（1,2,3）提现方式，1微信支付，2支付宝，3银行打款',
  `min_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '最低提现金额',
  `avoidmoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '免审金额，可直接提现金额',
  `wd_content` text NOT NULL COMMENT '提现须知',
  `cms_rates` float NOT NULL DEFAULT '0' COMMENT '平台佣金比率',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `is_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '提现开关，2关，1开',
  `wd_wxrates` float NOT NULL DEFAULT '0' COMMENT '微信提现手续费',
  `wd_alipayrates` float NOT NULL DEFAULT '0' COMMENT '支付宝提现手续费',
  `wd_bankrates` float NOT NULL DEFAULT '0' COMMENT '银行卡提现手续费',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_wxappjump') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '跳转的小程序名',
  `pic` varchar(255) NOT NULL COMMENT '小程序图标',
  `appid` varchar(100) NOT NULL COMMENT '小程序appid',
  `path` varchar(255) NOT NULL COMMENT '跳转到的小程序页面',
  `position` tinyint(3) NOT NULL COMMENT '当前小程序点击位置',
  `sort` int(11) NOT NULL DEFAULT '255' COMMENT '排序',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `addtime` int(11) NOT NULL COMMENT '增加时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_hyorder') ." (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(50) NOT NULL COMMENT '订单号',
  `detailInfo` varchar(200) DEFAULT NULL COMMENT '地址',
  `telNumber` varchar(100) DEFAULT NULL COMMENT '电话',
  `money` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `status` varchar(255) DEFAULT '0' COMMENT '1 取消订单，2待支付，3已支付，4待收货，5已完成',
  `openid` varchar(150) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL,
  `countyName` varchar(150) DEFAULT NULL COMMENT '区域',
  `provinceName` varchar(150) DEFAULT NULL COMMENT '省份',
  `name` varchar(100) DEFAULT NULL COMMENT '名字',
  `addtime` varchar(100) DEFAULT NULL COMMENT '加入的时间',
  `cityName` varchar(100) DEFAULT NULL COMMENT '城市',
  `uremark` varchar(100) DEFAULT NULL,
  `sincetype` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `paytime` int(11) NOT NULL COMMENT '付款时间',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `gname` varchar(100) NOT NULL COMMENT '商品名称',
  `num` int(11) NOT NULL COMMENT '件数',
  `out_trade_no` varchar(100) NOT NULL COMMENT '外部订单号',
  `ordertype` tinyint(1) NOT NULL COMMENT '类型，待用',
  `deliveryfee` decimal(10,2) NOT NULL COMMENT '运费',
  `goodsimg` varchar(200) NOT NULL COMMENT '商品图',
  `bid` int(11) NOT NULL COMMENT '商家id',
  `bname` varchar(100) NOT NULL COMMENT '商家名称',
  `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款',
  `shipnum` varchar(50) NOT NULL COMMENT '快递单号',
  `shipname` varchar(50) NOT NULL COMMENT '快递名称',
  `shiptime` int(11) NOT NULL COMMENT '发货时间',
  `finishtime` int(11) NOT NULL COMMENT '结束时间',
  `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号',
  `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间',
  `islottery` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0未开奖，1中奖，2未中奖',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`oid`),
  KEY `oid` (`oid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_specialtopic') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `content` text CHARACTER SET utf8 NOT NULL COMMENT '内容',
  `seenum` int(11) NOT NULL COMMENT '查看数量',
  `commentnum` int(11) NOT NULL COMMENT '评论数量',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `introduction` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '简介',
  `likenum` int(11) NOT NULL COMMENT '点赞数',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `sort` int(5) NOT NULL DEFAULT '255' COMMENT '排序',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否置顶，0不置顶，1置顶',
  `img` varchar(255) CHARACTER SET utf8 NOT NULL COMMENT '缩略图片',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `bid` int(11) NOT NULL COMMENT '门店id',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示，0不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_stlike') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `addtime` int(11) NOT NULL COMMENT '点赞时间',
  `stid` int(11) NOT NULL COMMENT '专题id',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_circle') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL,
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `content` text NOT NULL COMMENT '圈子内容',
  `img` text NOT NULL COMMENT '图片',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `commentnum` int(11) NOT NULL COMMENT '评论数',
  `likenum` int(11) NOT NULL COMMENT '点赞数',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `uid` int(11) NOT NULL COMMENT '用户id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_circlelike') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'uid',
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `cid` int(11) NOT NULL COMMENT '圈子id',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `uimg` varchar(255) NOT NULL COMMENT '用户头像',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_circlecomment') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `cid` int(11) NOT NULL COMMENT '圈子id',
  `content` text NOT NULL COMMENT '评论内容',
  `uname` varchar(255) NOT NULL COMMENT '评论人姓名',
  `uid` int(11) NOT NULL COMMENT '评论人id',
  `uimg` varchar(255) NOT NULL COMMENT '评论人头像',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_rechargecard') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '给个标题好查看',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `lessmoney` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0不启用，1启用',
  `sort` int(11) NOT NULL COMMENT '排序',
  `uniacid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_rechargelogo') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) NOT NULL COMMENT 'openid',
  `rc_id` int(11) NOT NULL COMMENT '充值卡id',
  `uniacid` int(11) NOT NULL COMMENT '应用id',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
  `addmoney` decimal(10,2) NOT NULL COMMENT '充值卡赠送的金额',
  `addtime` int(11) NOT NULL COMMENT '添加时间',
  `rtype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0普通充值，1充值卡，2购买会员，3订单付款，4订单退款',
  `out_trade_no` varchar(200) NOT NULL COMMENT '外部订单号',
  `memo` text NOT NULL COMMENT '备注',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

";
pdo_run($sql);


if(!pdo_fieldexists('mzhk_sun_goods',  'bname')) {
	pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `bname` varchar(50) NOT NULL COMMENT '门店名称';");
}
if(!pdo_fieldexists('mzhk_sun_gift',  'gname')) {
	pdo_query("ALTER TABLE ".tablename('mzhk_sun_gift')." ADD `gname` varchar(200) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('mzhk_sun_gift',  'gid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_gift')." ADD `gid` int(11) DEFAULT 0 COMMENT '商品id';");
}

if(!pdo_fieldexists('mzhk_sun_brand',  'deliveryfee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `deliveryfee` decimal(10,2) DEFAULT '0.00' COMMENT '配送费';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'deliverytime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `deliverytime` varchar(20) NOT NULL COMMENT '配送时间';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'deliveryaway')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `deliveryaway` float(11) DEFAULT '0' COMMENT '配送距离';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'paytime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `paytime` int(11) DEFAULT '0' COMMENT '付款时间';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'gid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `gid` int(11) DEFAULT '0' COMMENT '商品id';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'gname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `gname` varchar(200) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'num')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `num` int(11) DEFAULT '0' COMMENT '数量';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'out_trade_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `out_trade_no` varchar(200) DEFAULT NULL COMMENT '外部订单id';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'ordertype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `ordertype` tinyint(1) DEFAULT '1' COMMENT '订单类型，0普通，1抢购，2拼团，3砍价';");
}


if(!pdo_fieldexists('mzhk_sun_qgorder',  'deliveryfee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `deliveryfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'store_open')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `store_open` tinyint(11) DEFAULT '1' COMMENT '商家入驻开关，默认1，1开';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'store_in_notice')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `store_in_notice` text DEFAULT NULL COMMENT '商家入驻须知';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'in_openid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `in_openid` varchar(255) NOT NULL COMMENT '入驻时提交信息的微信openid';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'bind_openid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `bind_openid` varchar(255) DEFAULT NULL COMMENT '绑定的openid';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'loginname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `loginname` varchar(50) DEFAULT NULL COMMENT '登陆名';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'loginpassword')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `loginpassword` varchar(50) DEFAULT NULL COMMENT '登陆密码';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'uname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `uname` varchar(50) NOT NULL COMMENT '联系人';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'starttime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `starttime` varchar(30) DEFAULT NULL COMMENT '营业时间，开始';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'endtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `endtime` varchar(30) DEFAULT NULL COMMENT '营业时间，结束';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'coordinates')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `coordinates` varchar(50) DEFAULT NULL COMMENT '经纬度';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'longitude')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `longitude` varchar(50) DEFAULT NULL COMMENT '经度';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'latitude')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `latitude` varchar(50) DEFAULT NULL COMMENT '纬度';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'lt_id')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `lt_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻周期id';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'lt_day')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `lt_day` int(11) NOT NULL DEFAULT '0' COMMENT '入驻周期时间';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'settleintime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `settleintime` int(11) NOT NULL DEFAULT '0' COMMENT '入驻开始时间，用于缴费';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'paytime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '入驻缴费完成时间';");
}


if(!pdo_fieldexists('mzhk_sun_brand',  'facility')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `facility` varchar(50) NOT NULL DEFAULT '0' COMMENT '设施id，用，号分隔';");
}
if(!pdo_fieldexists('mzhk_sun_vip',  'prefix')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vip')." ADD `prefix` varchar(50) NOT NULL COMMENT '前缀';");
}
if(!pdo_fieldexists('mzhk_sun_coupon',  'isvip')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_coupon')." ADD `isvip` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否会员领取，1会员，0非会员';");
}
if(!pdo_fieldexists('mzhk_sun_coupon',  'content')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_coupon')." ADD `content` text NOT NULL COMMENT '优惠券详情';");
}
if(!pdo_fieldexists('mzhk_sun_coupon',  'img')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_coupon')." ADD `img` varchar(200) NOT NULL COMMENT '图片';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'tech_title')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `tech_title` varchar(50) NOT NULL COMMENT '技术支持名称';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'tech_img')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `tech_img` varchar(100) NOT NULL COMMENT '技术支持logo';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'tech_phone')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `tech_phone` varchar(50) NOT NULL COMMENT '技术支持电话';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'is_show_tech')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `is_show_tech` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不显示，1显示';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'is_open_pop')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `is_open_pop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭首页弹窗，1开启首页弹窗';");
}


if(!pdo_fieldexists('mzhk_sun_ptgroups',  'isrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `isrefund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款';");
}
if(!pdo_fieldexists('mzhk_sun_vipcode',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vipcode')." ADD `openid` varchar(255) NOT NULL COMMENT 'openid';");
}


if(!pdo_fieldexists('mzhk_sun_gift',  'probability')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_gift')." ADD `probability` float NOT NULL DEFAULT '0' COMMENT '概率';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'initialtimes')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `initialtimes` int(11) NOT NULL COMMENT '初始抽奖次数';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'hk_bgimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hk_bgimg` varchar(100) DEFAULT NULL COMMENT '黑卡背景图';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'hk_namecolor')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hk_namecolor` varchar(20) DEFAULT NULL COMMENT '黑卡名称颜色';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'store_id')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `store_id` int(11) NOT NULL COMMENT '分类id';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'store_name')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `store_name` varchar(100) NOT NULL COMMENT '分类名称';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'is_vip')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `is_vip` tinyint(1) NOT NULL COMMENT '是否会员，0非会员，1会员';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'buynum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `buynum` int(11) NOT NULL COMMENT '查看人数';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'viewnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `viewnum` int(11) NOT NULL COMMENT '查看人数';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'sharenum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `sharenum` int(11) NOT NULL COMMENT '分享次数';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'paytime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `paytime` int(11) NOT NULL COMMENT '付款时间';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'gid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `gid` int(11) NOT NULL COMMENT '商品id';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'gname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `gname` varchar(100) NOT NULL COMMENT '商品名称';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'num')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `num` int(11) NOT NULL COMMENT '件数';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'out_trade_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `out_trade_no` varchar(100) NOT NULL COMMENT '外部订单号';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'ordertype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `ordertype` tinyint(1) NOT NULL COMMENT '类型，待用';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'deliveryfee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `deliveryfee` decimal(10,2) NOT NULL COMMENT '运费';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'cutnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `cutnum` int(11) NOT NULL COMMENT '可参与砍价人数';");
}


if(!pdo_fieldexists('mzhk_sun_kjorder',  'goodsimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `goodsimg` varchar(200) NOT NULL COMMENT '商品图';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'bid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `bid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'bname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `bname` varchar(100) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'isrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'goodsimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `goodsimg` varchar(200) NOT NULL COMMENT '商品图';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'bid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `bid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'bname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `bname` varchar(100) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'num')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `num` int(11) NOT NULL DEFAULT '1' COMMENT '数量';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'goodsimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `goodsimg` varchar(200) NOT NULL COMMENT '商品图';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'bid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `bid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'bname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `bname` varchar(100) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'goodsimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `goodsimg` varchar(200) NOT NULL COMMENT '商品图';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'bid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `bid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'bname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `bname` varchar(100) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'isrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'goodsimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `goodsimg` varchar(200) NOT NULL COMMENT '商品图';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'bid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `bid` int(11) NOT NULL COMMENT '商家id';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'bname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `bname` varchar(100) NOT NULL COMMENT '商家名称';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'isrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'showcheck')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `showcheck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1过审页面，0正常页面';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wxappletscode')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wxappletscode` varchar(200) NOT NULL COMMENT '小程序码';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'tab_navdata')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `tab_navdata` text NOT NULL COMMENT '底部菜单数据';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'hk_userrules')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hk_userrules` text NOT NULL COMMENT '黑卡会员规则';");
}
if(!pdo_fieldexists('mzhk_sun_user',  'telphone')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_user')." ADD `telphone` varchar(20) NOT NULL COMMENT '手机号码';");
}

if(!pdo_fieldexists('mzhk_sun_brand',  'totalamount')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `totalamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总金额';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'frozenamount')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `frozenamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'commission')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `commission` float NOT NULL DEFAULT '0' COMMENT '佣金比例';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'memdiscount')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `memdiscount` float NOT NULL DEFAULT '0' COMMENT '会员折扣，线下付款';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'ship_type')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `ship_type` varchar(10) NOT NULL DEFAULT '1' COMMENT '配送方式，1到店消费，2送货上门，3快递';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'ship_delivery_fee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `ship_delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '送货上门-配送费';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'ship_delivery_time')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `ship_delivery_time` varchar(30) NOT NULL COMMENT '送货上门-配送时间';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'ship_delivery_way')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `ship_delivery_way` float NOT NULL DEFAULT '1' COMMENT '送货上门-配送距离';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'ship_express_fee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `ship_express_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '快递-快递费';");
}


if(!pdo_fieldexists('mzhk_sun_cardorder',  'deliveryfee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `deliveryfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'out_trade_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `out_trade_no` varchar(100) NOT NULL COMMENT '外部订单号';");
}


if(!pdo_fieldexists('mzhk_sun_cardorder',  'shipnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `shipnum` varchar(50) NOT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'shipname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `shipname` varchar(50) NOT NULL COMMENT '快递名称';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'finishtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `finishtime` int(11) NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'version')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `version` varchar(30) NOT NULL COMMENT '小程序版本号';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'shipnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `shipnum` varchar(50) NOT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'shipname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `shipname` varchar(50) NOT NULL COMMENT '快递名称';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'shiptime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `shiptime` int(11) NOT NULL COMMENT '发货时间';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'finishtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `finishtime` int(11) NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'shipnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `shipnum` varchar(50) NOT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'shipname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `shipname` varchar(50) NOT NULL COMMENT '快递名称';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'shiptime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `shiptime` int(11) NOT NULL COMMENT '发货时间';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'finishtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `finishtime` int(11) NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'shipnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `shipnum` varchar(50) NOT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'shipname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `shipname` varchar(50) NOT NULL COMMENT '快递名称';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'finishtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `finishtime` int(11) NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'shipnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `shipnum` varchar(50) NOT NULL COMMENT '快递单号';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'shipname')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `shipname` varchar(50) NOT NULL COMMENT '快递名称';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'shiptime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `shiptime` int(11) NOT NULL COMMENT '发货时间';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'finishtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `finishtime` int(11) NOT NULL COMMENT '结束时间';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'limitnum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `limitnum` int(11) NOT NULL DEFAULT '0' COMMENT '限购数量';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'isshelf')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `isshelf` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架，0下架，1上架';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'limittime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `limittime` float NOT NULL DEFAULT '0' COMMENT '参团时限';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'out_refund_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'isrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'out_refund_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'out_refund_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'out_refund_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'out_refund_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号';");
}

if(!pdo_fieldexists('mzhk_sun_brand',  'istop')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶';");
}
if(!pdo_fieldexists('mzhk_sun_brand',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_brand')." ADD `sort` int(11) NOT NULL DEFAULT '255' COMMENT '排序';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'sort')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `sort` int(5) NOT NULL DEFAULT '255' COMMENT '排序';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'order_tplid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `order_tplid` int(11) NOT NULL COMMENT '聚合-订单提醒id';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'order_refund_tplid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `order_refund_tplid` int(11) NOT NULL COMMENT '聚合-订单退款提醒id';");
}

if(!pdo_fieldexists('mzhk_sun_sms',  'smstype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `smstype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '短信类型，1为253，2为聚合';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'ytx_apiaccount')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `ytx_apiaccount` varchar(50) NOT NULL COMMENT '253短信账号';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'ytx_apipass')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `ytx_apipass` varchar(50) NOT NULL COMMENT '253短信密码';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'ytx_apiurl')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `ytx_apiurl` varchar(50) NOT NULL COMMENT '253短信地址';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'ytx_order')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `ytx_order` varchar(255) NOT NULL COMMENT '云通信订单消息提醒';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'ytx_orderrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `ytx_orderrefund` varchar(255) NOT NULL COMMENT '云通信退款订单消息提醒';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}
if(!pdo_fieldexists('mzhk_sun_hyorder',  'expirationtime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_hyorder')." ADD `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'stocktype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `stocktype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0下单减库存，1付款减库存';");
}
if(!pdo_fieldexists('mzhk_sun_cardshare',  'click_user_str')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardshare')." ADD `click_user_str` text NOT NULL COMMENT '点击人，用英文逗号隔开';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'lotterytime')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `lotterytime` int(11) NOT NULL COMMENT '开奖时间';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'winway')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `winway` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开奖方式，0自动，1手动';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'islottery')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `islottery` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否已经开奖，0否，1是';");
}


if(!pdo_fieldexists('mzhk_sun_acbanner',  'lb_imgs4')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_acbanner')." ADD `lb_imgs4` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '免单banner图';");
}
if(!pdo_fieldexists('mzhk_sun_acbanner',  'bname4')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_acbanner')." ADD `bname4` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '免单banner名';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'lotterytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `lotterytype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开奖条件，0按时间，1按人数';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'lotterynum')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `lotterynum` int(11) NOT NULL COMMENT '开奖人数';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wg_title')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wg_title` varchar(255) DEFAULT NULL COMMENT '福利群标题';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wg_directions')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wg_directions` varchar(255) DEFAULT NULL COMMENT '福利群说明';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wg_img')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wg_img` varchar(255) DEFAULT NULL COMMENT '福利群图标';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wg_keyword')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wg_keyword` varchar(255) DEFAULT NULL COMMENT '福利群加群关键字';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'showgw')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `showgw` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否显示，0不显示，1显示';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'wg_addicon')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `wg_addicon` varchar(255) DEFAULT NULL COMMENT '福利群加群关键字';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'index_img')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `index_img` varchar(255) DEFAULT NULL COMMENT '首页展示图';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'tid4')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `tid4` varchar(50) DEFAULT NULL COMMENT '开奖模板';");
}


if(!pdo_fieldexists('mzhk_sun_tbbanner',  'lb_imgs4')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_tbbanner')." ADD `lb_imgs4` varchar(200) CHARACTER SET utf8 NOT NULL;");
}
if(!pdo_fieldexists('mzhk_sun_tbbanner',  'bname4')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_tbbanner')." ADD `bname4` varchar(110) CHARACTER SET utf8 NOT NULL;");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'code_img')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `code_img` mediumblob NOT NULL COMMENT '小程序码';");
}
if(!pdo_fieldexists('mzhk_sun_popbanner',  'position')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_popbanner')." ADD `position` int(5) NOT NULL DEFAULT '1' COMMENT '1弹窗，2首页轮播（默认主题），3砍价列表，4集卡列表，5抢购列表，6拼团列表，7免单列表，8营销图标，9底部导航，10广告1（主题2），10广告2（主题2）';");
}
if(!pdo_fieldexists('mzhk_sun_popbanner',  'isshow')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_popbanner')." ADD `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示，0不显示';");
}
if(!pdo_fieldexists('mzhk_sun_popbanner',  'unselectimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_popbanner')." ADD `unselectimg` varchar(255) DEFAULT NULL COMMENT '未选中图标';");
}


if(!pdo_fieldexists('mzhk_sun_sms',  'aly_accesskeyid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `aly_accesskeyid` varchar(255) NOT NULL COMMENT '阿里大鱼 accessKeyId';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'aly_accesskeysecret')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `aly_accesskeysecret` varchar(255) NOT NULL COMMENT '阿里大鱼 AccessKeySecret';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'aly_order')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `aly_order` varchar(255) NOT NULL COMMENT '阿里大鱼 订单模板';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'aly_orderrefund')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `aly_orderrefund` varchar(255) NOT NULL COMMENT '阿里大鱼 退款模板';");
}
if(!pdo_fieldexists('mzhk_sun_sms',  'aly_sign')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_sms')." ADD `aly_sign` varchar(100) NOT NULL COMMENT '签名';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'is_open_circle')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `is_open_circle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '圈子0不审核，1审核';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'hometheme')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hometheme` tinyint(5) NOT NULL DEFAULT '0' COMMENT '首页主题';");
}
if(!pdo_fieldexists('mzhk_sun_goods',  'vipprice')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `vipprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员价格';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'is_homeshow_circle')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `is_homeshow_circle` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否在首页显示，1显示，0不显示';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'offlinefee')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `offlinefee` float NOT NULL DEFAULT '0' COMMENT '线下付款手续费';");
}


if(!pdo_fieldexists('mzhk_sun_goods',  'mustlowprice')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." ADD `mustlowprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '砍价，0不用砍到底价，1必须砍到低价才能购买';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'home_circle_name')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `home_circle_name` varchar(255) NOT NULL DEFAULT '晒单啦' COMMENT '风格2首页显示晒单内容';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'store_in_name')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `store_in_name` varchar(255) NOT NULL DEFAULT '商家入驻' COMMENT '商家入驻名';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'hk_mytitle')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hk_mytitle` varchar(255) NOT NULL COMMENT '我的页面黑卡营销标题（我的页面风格2）';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'hk_mybgimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `hk_mybgimg` varchar(255) NOT NULL COMMENT '我的页面黑卡背景图（我的页面风格2）';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'mytheme')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `mytheme` tinyint(5) NOT NULL DEFAULT '0' COMMENT '我的页面主题设置';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'loginimg')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `loginimg` varchar(255) NOT NULL COMMENT '商家后台登陆logo';");
}


if(!pdo_fieldexists('mzhk_sun_system',  'isopen_recharge')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `isopen_recharge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭，1开启';");
}
if(!pdo_fieldexists('mzhk_sun_system',  'isany_money_recharge')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_system')." ADD `isany_money_recharge` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0关闭，1开启';");
}
if(!pdo_fieldexists('mzhk_sun_cardorder',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardorder')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}
if(!pdo_fieldexists('mzhk_sun_kjorder',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_kjorder')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}
if(!pdo_fieldexists('mzhk_sun_order',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_order')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}
if(!pdo_fieldexists('mzhk_sun_ptgroups',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_ptgroups')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}
if(!pdo_fieldexists('mzhk_sun_qgorder',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}
if(!pdo_fieldexists('mzhk_sun_hyorder',  'paytype')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_hyorder')." ADD `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付';");
}


if(!pdo_fieldexists('mzhk_sun_vipcode',  'money')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vipcode')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员卡价格';");
}
if(!pdo_fieldexists('mzhk_sun_vipcode',  'viptitle')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vipcode')." ADD `viptitle` varchar(100) NOT NULL COMMENT 'vip名称';");
}
if(!pdo_fieldexists('mzhk_sun_vipcode',  'vipday')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vipcode')." ADD `vipday` int(11) NOT NULL COMMENT '激活天数';");
}
if(!pdo_fieldexists('mzhk_sun_vippaylog',  'money')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vippaylog')." ADD `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员卡价格';");
}
if(!pdo_fieldexists('mzhk_sun_vippaylog',  'vipday')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_vippaylog')." ADD `vipday` int(11) NOT NULL COMMENT 'vip天数';");
}
if(!pdo_fieldexists('mzhk_sun_mercapdetails',  'openid')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_mercapdetails')." ADD `openid` varchar(255) NOT NULL COMMENT '线下付款人openid';");
}
if(!pdo_fieldexists('mzhk_sun_mercapdetails',  'out_trade_no')) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_mercapdetails')." ADD `out_trade_no` varchar(255) NOT NULL COMMENT '外部订单号';");
}




if(pdo_fieldexists('mzhk_sun_gift',  'bid')) {
	pdo_query("ALTER TABLE ".tablename('mzhk_sun_gift')." DROP COLUMN `bid` ;");
}


if(pdo_fieldmatch('mzhk_sun_goods', 'bid', 'int', '11')===-1) {
	pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." MODIFY `bid` int(11) DEFAULT 0 COMMENT '门店id';");
}
if(pdo_fieldmatch('mzhk_sun_goods', 'kjbfb', 'float', '5')===-1) {
	pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." MODIFY `kjbfb` float(5) DEFAULT 20 COMMENT '砍价百分比';");
}

if(pdo_fieldmatch('mzhk_sun_qgorder', 'status', 'tinyint', '2')===-1) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." MODIFY `status` tinyint(2) DEFAULT '2' COMMENT '1 取消订单，2待支付，3待发货，4已支付，5已完成';");
}
if(pdo_fieldmatch('mzhk_sun_qgorder', 'addtime', 'int', '11')===-1) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_qgorder')." MODIFY `addtime` int(11) DEFAULT '0' COMMENT '加入的时间';");
}

if(pdo_fieldmatch('mzhk_sun_goods', 'code_img', 'mediumblob')===-1) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_goods')." MODIFY `code_img` mediumblob NOT NULL COMMENT '小程序码';");
}

if(pdo_fieldmatch('mzhk_sun_cardcollect', 'card_str_id', 'text')===-1) {
  pdo_query("ALTER TABLE ".tablename('mzhk_sun_cardcollect')." MODIFY `card_str_id` text NOT NULL COMMENT '收集的卡片id列表';");
}
file_put_contents(IA_ROOT."/addons/mzhk_sun/inc/web/sqcode.php","");
?>