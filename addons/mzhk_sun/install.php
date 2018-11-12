<?php
pdo_query("CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_acbanner') ." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lb_imgs` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs1` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs2` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs3` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `bname` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname1` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname2` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname3` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs4` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '免单banner图',
  `bname4` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '免单banner名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_active') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `subtitle` varchar(45) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `createtime` int(13) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `content` text NOT NULL COMMENT '文章内容',
  `sort` int(10) DEFAULT '0',
  `antime` timestamp NULL DEFAULT NULL,
  `hits` int(10) DEFAULT '0',
  `status` tinyint(10) DEFAULT '0' COMMENT '0审核中1审核通过',
  `astime` timestamp NULL DEFAULT NULL,
  `thumb` varchar(200) DEFAULT NULL,
  `num` int(10) DEFAULT '0',
  `sharenum` int(11) DEFAULT NULL COMMENT '每天可分享次数',
  `thumb_url` text,
  `part_num` varchar(15) DEFAULT '0' COMMENT '参与人数',
  `share_plus` varchar(15) DEFAULT '1' COMMENT '分享之后可得的次数',
  `new_partnum` varchar(15) DEFAULT NULL COMMENT '初始虚拟参与人数',
  `user_id` varchar(100) DEFAULT NULL COMMENT '用户ID',
  `storeinfo` varchar(200) DEFAULT NULL COMMENT '店铺信息',
  `showindex` int(11) DEFAULT NULL COMMENT '0不显示1显示',
  `active_num` int(11) DEFAULT NULL COMMENT '活动商品数量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=120 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_banner') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bname` varchar(200) CHARACTER SET utf8 NOT NULL,
  `url` varchar(300) CHARACTER SET utf8 NOT NULL COMMENT 'banner名字',
  `lb_imgs` varchar(500) CHARACTER SET utf8 NOT NULL COMMENT 'banner图片',
  `uniacid` int(11) NOT NULL,
  `bname1` varchar(200) CHARACTER SET utf8 NOT NULL,
  `bname2` varchar(200) CHARACTER SET utf8 NOT NULL,
  `bname3` varchar(200) CHARACTER SET utf8 NOT NULL,
  `lb_imgs1` varchar(500) CHARACTER SET utf8 NOT NULL,
  `lb_imgs2` varchar(500) CHARACTER SET utf8 NOT NULL,
  `lb_imgs3` varchar(500) CHARACTER SET utf8 NOT NULL,
  `url1` varchar(300) CHARACTER SET utf8 NOT NULL,
  `url2` varchar(300) CHARACTER SET utf8 NOT NULL,
  `url3` varchar(300) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=14 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_brand') ." (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `bname` varchar(120) NOT NULL COMMENT '品牌名称',
  `logo` text NOT NULL,
  `content` text COMMENT '品牌描述',
  `uniacid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL COMMENT '电话',
  `address` varchar(255) DEFAULT NULL COMMENT '地址',
  `img` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `feature` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `deliveryfee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '配送费',
  `deliverytime` varchar(20) NOT NULL COMMENT '配送时间',
  `deliveryaway` float NOT NULL DEFAULT '0' COMMENT '配送距离',
  `in_openid` varchar(255) NOT NULL COMMENT '入驻时提交信息的微信openid',
  `bind_openid` varchar(255) DEFAULT NULL COMMENT '绑定的openid',
  `loginname` varchar(50) DEFAULT NULL COMMENT '登陆名',
  `loginpassword` varchar(50) DEFAULT NULL COMMENT '登陆密码',
  `uname` varchar(50) NOT NULL COMMENT '联系人',
  `starttime` varchar(30) DEFAULT NULL COMMENT '营业时间，开始',
  `endtime` varchar(30) DEFAULT NULL COMMENT '营业时间，结束',
  `coordinates` varchar(50) DEFAULT NULL COMMENT '经纬度',
  `longitude` varchar(50) DEFAULT NULL COMMENT '经度',
  `latitude` varchar(50) DEFAULT NULL COMMENT '纬度',
  `lt_id` int(11) NOT NULL DEFAULT '0' COMMENT '入驻周期id',
  `lt_day` int(11) NOT NULL DEFAULT '0' COMMENT '入驻周期时间',
  `settleintime` int(11) NOT NULL DEFAULT '0' COMMENT '入驻开始时间，用于缴费',
  `paytime` int(11) NOT NULL DEFAULT '0' COMMENT '入驻缴费完成时间',
  `facility` varchar(50) NOT NULL DEFAULT '0' COMMENT '设施id，用，号分隔',
  `store_id` int(11) NOT NULL COMMENT '分类id',
  `store_name` varchar(100) NOT NULL COMMENT '分类名称',
  `totalamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总金额',
  `frozenamount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结金额',
  `commission` float NOT NULL DEFAULT '0' COMMENT '佣金比例',
  `memdiscount` float NOT NULL DEFAULT '0' COMMENT '会员折扣，线下付款',
  `istop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '置顶',
  `sort` int(11) NOT NULL DEFAULT '255' COMMENT '排序',
  PRIMARY KEY (`bid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_brandpaylog') ." (
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

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_coupon') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `title` varchar(255) NOT NULL COMMENT '优惠券名称，展示用',
  `type` tinyint(3) unsigned DEFAULT NULL COMMENT '优惠券类型（1:折扣 2:满减 3;赠送）',
  `astime` timestamp NULL DEFAULT NULL COMMENT '活动开始时间',
  `antime` timestamp NULL DEFAULT NULL COMMENT '活动结束时间',
  `expiryDate` int(10) unsigned DEFAULT NULL COMMENT '领取后，使用有效期',
  `allowance` int(10) unsigned DEFAULT NULL COMMENT '余量',
  `total` int(10) unsigned DEFAULT NULL COMMENT '总量',
  `val` int(25) DEFAULT NULL COMMENT '功能',
  `showIndex` tinyint(4) DEFAULT NULL COMMENT '是否首页显示（0:不显示 1:显示）',
  `uniacid` int(11) DEFAULT NULL,
  `vab` int(11) DEFAULT NULL COMMENT '满减',
  `state` int(11) DEFAULT '1',
  `bid` varchar(50) DEFAULT NULL COMMENT '商店id',
  `remarks` varchar(50) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `mj` int(11) DEFAULT NULL,
  `md` int(11) DEFAULT NULL,
  `is_counp` int(11) DEFAULT '1',
  `isvip` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否会员领取，1会员，0非会员',
  `content` text NOT NULL COMMENT '优惠券详情',
  `img` varchar(200) NOT NULL COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

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

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_evaluate') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL,
  `time` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `xingxing` varchar(7) DEFAULT NULL,
  `content` text,
  `gid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_gift') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号id',
  `title` varchar(200) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `content` text NOT NULL COMMENT '文章内容',
  `sort` int(10) DEFAULT '0',
  `pic` varchar(200) DEFAULT NULL,
  `gid` int(11) DEFAULT '0' COMMENT '商品id',
  `gname` varchar(200) DEFAULT NULL COMMENT '商品名称',
  `probability` float NOT NULL DEFAULT '0' COMMENT '概率',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_goods') ." (
  `gid` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品id',
  `gname` text NOT NULL COMMENT '商品名称',
  `kjprice` decimal(10,2) DEFAULT NULL COMMENT '砍价的价格',
  `shopprice` decimal(10,2) DEFAULT NULL COMMENT '原价',
  `selftime` varchar(200) DEFAULT NULL COMMENT '加入时间',
  `pic` varchar(200) DEFAULT NULL COMMENT '封面图',
  `probably` text COMMENT '备注',
  `tid` int(11) DEFAULT NULL COMMENT '商品是否推荐，1推荐，2不推荐',
  `status` int(11) DEFAULT NULL COMMENT '商品状态，1为审核，2审核通过，3删除',
  `uniacid` int(11) DEFAULT NULL,
  `content` text COMMENT '商品详情',
  `lid` int(11) DEFAULT NULL COMMENT '商品类别id 1.为普通，2位砍价，3位拼团，4为集卡，5是抢购',
  `bid` int(11) DEFAULT NULL COMMENT '店铺id',
  `num` int(11) DEFAULT NULL,
  `ptprice` decimal(10,2) DEFAULT NULL COMMENT '拼团价格',
  `astime` varchar(30) DEFAULT NULL COMMENT '活动开始时间',
  `antime` varchar(30) DEFAULT NULL COMMENT '活动结束时间',
  `ptnum` int(11) DEFAULT NULL COMMENT '拼团活动人数量',
  `lb_imgs` varchar(400) DEFAULT NULL COMMENT '轮播图',
  `qgprice` decimal(10,2) DEFAULT NULL COMMENT '抢购',
  `charnum` int(11) DEFAULT NULL,
  `charaddnum` int(11) NOT NULL,
  `biaoti` varchar(300) NOT NULL,
  `kjbfb` float NOT NULL DEFAULT '20' COMMENT '砍价百分比',
  `is_ptopen` int(11) NOT NULL DEFAULT '1',
  `is_kjopen` int(11) NOT NULL DEFAULT '1',
  `is_jkopen` int(11) NOT NULL DEFAULT '1',
  `is_qgopen` int(11) NOT NULL DEFAULT '1',
  `is_hyopen` int(11) NOT NULL DEFAULT '1',
  `bname` varchar(50) NOT NULL COMMENT '店铺名称',
  `initialtimes` int(11) NOT NULL COMMENT '初始抽奖次数',
  `is_vip` tinyint(1) NOT NULL COMMENT '是否会员，0非会员，1会员',
  `buynum` int(11) NOT NULL COMMENT '已购买数量',
  `viewnum` int(11) NOT NULL COMMENT '查看人数',
  `sharenum` int(11) NOT NULL COMMENT '分享次数',
  `cutnum` int(11) NOT NULL COMMENT '可参与砍价人数',
  `ship_type` varchar(10) NOT NULL DEFAULT '1' COMMENT '配送方式，1到店消费，2送货上门，3快递',
  `ship_delivery_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '送货上门-配送费',
  `ship_delivery_time` varchar(30) NOT NULL COMMENT '送货上门-配送时间',
  `ship_delivery_way` float NOT NULL DEFAULT '1' COMMENT '送货上门-配送距离',
  `ship_express_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '快递-快递费',
  `limitnum` int(11) NOT NULL DEFAULT '0' COMMENT '限购数量',
  `isshelf` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否上架，0下架，1上架',
  `limittime` float NOT NULL DEFAULT '0' COMMENT '参团时限',
  `sort` int(5) NOT NULL DEFAULT '255' COMMENT '排序',
  `expirationtime` int(11) NOT NULL DEFAULT '0' COMMENT '核销过期时间',
  `stocktype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0下单减库存，1付款减库存',
  `lotterytime` int(11) NOT NULL COMMENT '开奖时间',
  `winway` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开奖方式，0自动，1手动',
  `islottery` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否已经开奖，0否，1是',
  `lotterytype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '开奖条件，0按时间，1按人数',
  `lotterynum` int(11) NOT NULL COMMENT '开奖人数',
  `index_img` varchar(255) DEFAULT NULL COMMENT '首页展示图',
  `code_img` mediumblob NOT NULL COMMENT '小程序码',
  `vipprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '会员价格',
  `mustlowprice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '砍价，0不用砍到底价，1必须砍到低价才能购买',
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='商品 ' AUTO_INCREMENT=58 ;

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

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_kanjia') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(200) NOT NULL COMMENT '用户id',
  `gid` int(11) DEFAULT NULL COMMENT '砍价商品id',
  `mch_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `price` decimal(10,0) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `add_time` int(11) DEFAULT NULL,
  `kanjia` decimal(11,0) NOT NULL,
  `price1` decimal(10,2) NOT NULL,
  `kanjia1` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_kjorder') ." (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(50) NOT NULL COMMENT '订单号',
  `detailInfo` varchar(200) DEFAULT NULL COMMENT '地址',
  `telNumber` varchar(100) DEFAULT NULL COMMENT '电话',
  `money` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `status` tinyint(2) DEFAULT '2' COMMENT '1 取消订单，2待支付，3待发货，4已支付，5已完成',
  `openid` varchar(150) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL,
  `countyName` varchar(150) DEFAULT NULL COMMENT '区域',
  `provinceName` varchar(150) DEFAULT NULL COMMENT '省份',
  `name` varchar(100) DEFAULT NULL COMMENT '名字',
  `addtime` int(11) DEFAULT '0' COMMENT '加入的时间',
  `cityName` varchar(100) DEFAULT NULL COMMENT '城市',
  `uremark` varchar(100) DEFAULT NULL,
  `sincetype` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `paytime` int(11) NOT NULL COMMENT '付款时间',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `gname` varchar(200) NOT NULL COMMENT '商品名称',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '购买数量',
  `out_trade_no` varchar(200) DEFAULT NULL COMMENT '外部订单id',
  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型，0普通，1抢购，2拼团，3砍价',
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
  `expirationtime` int(11) NOT NULL COMMENT '核销过期时间',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
  `out_trade_no` varchar(255) NOT NULL COMMENT '外部订单号',
  `openid` varchar(255) NOT NULL COMMENT '线下付款人openid',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_order') ." (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(50) NOT NULL COMMENT '订单号',
  `detailInfo` varchar(200) DEFAULT NULL COMMENT '地址',
  `telNumber` varchar(100) DEFAULT NULL COMMENT '电话',
  `money` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `status` varchar(255) DEFAULT '2' COMMENT '1 取消订单，2待支付，3待发货，4已支付，5已完成',
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
  `expirationtime` int(11) NOT NULL COMMENT '核销过期时间',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_popbanner') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `pop_title` varchar(50) NOT NULL COMMENT '弹窗名称',
  `pop_urltype` tinyint(2) NOT NULL DEFAULT '1' COMMENT '弹窗链接类别',
  `pop_urltxt` int(11) NOT NULL COMMENT '相关 id',
  `pop_img` varchar(200) NOT NULL COMMENT '弹窗图',
  `uniacid` int(11) NOT NULL COMMENT 'uniacid',
  `sort` int(11) NOT NULL COMMENT '排序',
  `position` int(5) NOT NULL DEFAULT '1' COMMENT '1弹窗，2首页轮播（默认主题），3砍价列表，4集卡列表，5抢购列表，6拼团列表，7免单列表，8营销图标，9底部导航，10广告1（主题2），10广告2（主题2）',
  `unselectimg` varchar(255) DEFAULT NULL COMMENT '未选中图标',
  `isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1显示，0不显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_ptgroups') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupordernum` varchar(50) NOT NULL COMMENT '团单号，非订单号',
  `detailinfo` varchar(200) DEFAULT NULL COMMENT '地址',
  `telnumber` varchar(100) DEFAULT NULL COMMENT '电话',
  `money` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `status` tinyint(2) DEFAULT '2' COMMENT '1 取消订单，2待支付，3已支付，4待发货，5已完成',
  `openid` varchar(150) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL,
  `countyname` varchar(150) DEFAULT NULL COMMENT '区域',
  `provincename` varchar(150) DEFAULT NULL COMMENT '省份',
  `name` varchar(100) DEFAULT NULL COMMENT '名字',
  `addtime` int(11) DEFAULT NULL COMMENT '加入的时间',
  `cityname` varchar(100) DEFAULT NULL COMMENT '城市',
  `uremark` varchar(100) DEFAULT NULL,
  `sincetype` varchar(100) DEFAULT NULL,
  `paytime` int(11) DEFAULT '0' COMMENT '付款时间',
  `gid` int(11) NOT NULL COMMENT '商品id',
  `gname` varchar(200) NOT NULL COMMENT '商品名称',
  `is_lead` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否团长，1是，0不是',
  `order_id` int(11) NOT NULL COMMENT '订单id',
  `shiptime` int(11) NOT NULL COMMENT '发货时间',
  `out_trade_no` varchar(100) DEFAULT NULL COMMENT '外部订单号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型，0拼团，1单独购买',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '数量为1',
  `deliveryfee` decimal(10,2) NOT NULL COMMENT '运费',
  `isrefund` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款',
  `goodsimg` varchar(200) NOT NULL COMMENT '商品图',
  `bid` int(11) NOT NULL COMMENT '商家id',
  `bname` varchar(100) NOT NULL COMMENT '商家名称',
  `shipnum` varchar(50) NOT NULL COMMENT '快递单号',
  `shipname` varchar(50) NOT NULL COMMENT '快递名称',
  `finishtime` int(11) NOT NULL COMMENT '结束时间',
  `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号',
  `expirationtime` int(11) NOT NULL COMMENT '核销过期时间',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=51 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_ptorders') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '拼团id',
  `gid` int(11) DEFAULT NULL COMMENT '商品的id',
  `openid` varchar(100) DEFAULT NULL COMMENT '团长用户的id',
  `addtime` int(11) DEFAULT NULL COMMENT '生成时间',
  `uniacid` int(11) DEFAULT NULL,
  `is_ok` tinyint(2) DEFAULT '0' COMMENT '是否成功拼团，1成功，0未成功，2取消关闭',
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '拼团价格',
  `buynum` int(11) NOT NULL DEFAULT '0' COMMENT '拼团人数，实际购买人数',
  `ordernum` varchar(100) NOT NULL COMMENT '拼团总单号',
  `gname` varchar(200) NOT NULL COMMENT '商品名称',
  `peoplenum` int(11) NOT NULL DEFAULT '0' COMMENT '参与该团人数，包括未付款',
  `groupuser_id` varchar(100) DEFAULT NULL COMMENT '成功参与拼团会员id',
  `groupuser_img` text COMMENT '成功参与拼团会员头像',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `neednum` int(11) NOT NULL DEFAULT '0' COMMENT '需要人数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_qgorder') ." (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `orderNum` varchar(50) NOT NULL COMMENT '订单号',
  `detailInfo` varchar(200) DEFAULT NULL COMMENT '地址',
  `telNumber` varchar(100) DEFAULT NULL COMMENT '电话',
  `money` decimal(10,2) DEFAULT NULL COMMENT '总价',
  `status` tinyint(2) DEFAULT '2' COMMENT '1 取消订单，2待支付，3待发货，4已支付，5已完成',
  `openid` varchar(150) DEFAULT NULL COMMENT '用户id',
  `uniacid` int(11) DEFAULT NULL,
  `countyName` varchar(150) DEFAULT NULL COMMENT '区域',
  `provinceName` varchar(150) DEFAULT NULL COMMENT '省份',
  `name` varchar(100) DEFAULT NULL COMMENT '名字',
  `addtime` int(11) DEFAULT '0' COMMENT '加入的时间',
  `cityName` varchar(100) DEFAULT NULL COMMENT '城市',
  `uremark` varchar(100) DEFAULT NULL,
  `sincetype` varchar(100) DEFAULT NULL,
  `time` varchar(100) DEFAULT NULL,
  `paytime` int(11) NOT NULL COMMENT '付款时间',
  `gid` int(11) NOT NULL DEFAULT '0' COMMENT '商品id',
  `gname` varchar(200) NOT NULL COMMENT '商品名称',
  `num` int(11) NOT NULL DEFAULT '1' COMMENT '购买数量',
  `out_trade_no` varchar(200) DEFAULT NULL COMMENT '外部订单id',
  `ordertype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '订单类型，0普通，1抢购，2拼团，3砍价',
  `deliveryfee` decimal(10,2) NOT NULL COMMENT '运费',
  `goodsimg` varchar(200) NOT NULL COMMENT '商品图',
  `bid` int(11) NOT NULL COMMENT '商家id',
  `bname` varchar(200) NOT NULL COMMENT '商家名称',
  `isrefund` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0正常，1申请退款，2已退款',
  `shipname` varchar(50) NOT NULL COMMENT '快递名称',
  `shipnum` varchar(50) NOT NULL COMMENT '快递单号',
  `shiptime` int(11) NOT NULL COMMENT '发货时间',
  `finishtime` int(11) NOT NULL COMMENT '结束时间',
  `out_refund_no` varchar(100) NOT NULL COMMENT '退款单号',
  `expirationtime` int(11) NOT NULL COMMENT '核销过期时间',
  `paytype` tinyint(5) NOT NULL DEFAULT '1' COMMENT '1微信支付，2余额支付',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_qgorderlist') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `openid` varbinary(100) DEFAULT NULL,
  `num` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `createTime` varchar(100) DEFAULT NULL,
  `gid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_system') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `appid` varchar(100) DEFAULT NULL COMMENT 'appid',
  `appsecret` varchar(200) DEFAULT NULL COMMENT 'appsecret',
  `mchid` varchar(100) DEFAULT NULL COMMENT '商户号',
  `wxkey` varchar(250) DEFAULT NULL COMMENT '商户秘钥',
  `uniacid` int(11) DEFAULT NULL,
  `url_name` varchar(20) DEFAULT NULL COMMENT '网址名称',
  `details` text COMMENT '关于我们',
  `url_logo` varchar(100) DEFAULT NULL COMMENT '网址logo',
  `bq_name` varchar(50) DEFAULT NULL COMMENT '版权名称',
  `link_name` varchar(30) DEFAULT NULL COMMENT '网站名称',
  `link_logo` varchar(100) DEFAULT NULL COMMENT '网站logo',
  `support` varchar(20) DEFAULT NULL COMMENT '技术支持',
  `bq_logo` varchar(100) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL COMMENT '颜色',
  `tz_appid` varchar(50) DEFAULT NULL,
  `tz_name` varchar(50) DEFAULT NULL,
  `pt_name` varchar(100) DEFAULT NULL COMMENT '平台名称',
  `tz_audit` int(11) DEFAULT NULL COMMENT '帖子审核1.是 2否',
  `sj_audit` int(11) DEFAULT NULL COMMENT '商家审核1.是 2否',
  `cityname` varchar(20) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `pic` varchar(200) DEFAULT NULL,
  `client_ip` varchar(100) DEFAULT NULL,
  `apiclient_key` varchar(100) DEFAULT NULL,
  `apiclient_cert` varchar(100) DEFAULT NULL,
  `fontcolor` varchar(100) DEFAULT NULL,
  `ptnum` int(11) DEFAULT NULL,
  `hk_logo` varchar(150) DEFAULT NULL,
  `hk_tubiao` varchar(150) DEFAULT NULL,
  `store_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1开启，0关闭，默认开启',
  `store_in_notice` text NOT NULL COMMENT '商家入驻须知',
  `tech_title` varchar(50) NOT NULL COMMENT '技术支持名称',
  `tech_img` varchar(100) NOT NULL COMMENT '技术支持logo',
  `tech_phone` varchar(50) NOT NULL COMMENT '技术支持电话',
  `is_show_tech` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0不显示，1显示',
  `is_open_pop` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭首页弹窗，1开启首页弹窗',
  `hk_bgimg` varchar(100) DEFAULT NULL COMMENT '黑卡背景图',
  `hk_namecolor` varchar(20) DEFAULT NULL COMMENT '黑卡名称颜色',
  `showcheck` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1过审页面，0正常页面',
  `wxappletscode` varchar(200) NOT NULL COMMENT '小程序码',
  `tab_navdata` text NOT NULL COMMENT '底部菜单数据',
  `hk_userrules` text NOT NULL COMMENT '黑卡会员规则',
  `version` varchar(30) NOT NULL COMMENT '小程序版本号',
  `wg_title` varchar(255) DEFAULT NULL COMMENT '福利群标题',
  `wg_directions` varchar(255) DEFAULT NULL COMMENT '福利群说明',
  `wg_img` varchar(255) DEFAULT NULL COMMENT '福利群图标',
  `wg_keyword` varchar(255) DEFAULT NULL COMMENT '福利群加群关键字',
  `showgw` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否显示，0不显示，1显示',
  `wg_addicon` varchar(255) DEFAULT NULL COMMENT '福利群加群关键字',
  `is_open_circle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '圈子0不审核，1审核',
  `hometheme` tinyint(5) NOT NULL DEFAULT '0' COMMENT '首页主题',
  `is_homeshow_circle` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否在首页显示，1显示，0不显示',
  `offlinefee` float NOT NULL DEFAULT '0' COMMENT '线下付款手续费',
  `home_circle_name` varchar(255) NOT NULL DEFAULT '晒单啦' COMMENT '风格2首页显示晒单内容',
  `store_in_name` varchar(255) NOT NULL DEFAULT '商家入驻' COMMENT '商家入驻名',
  `hk_mytitle` varchar(255) NOT NULL COMMENT '我的页面黑卡营销标题（我的页面风格2）',
  `hk_mybgimg` varchar(255) NOT NULL COMMENT '我的页面黑卡背景图（我的页面风格2）',
  `mytheme` tinyint(5) NOT NULL DEFAULT '0' COMMENT '我的页面主题设置',
  `loginimg` varchar(255) NOT NULL COMMENT '商家后台登陆logo',
  `isopen_recharge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0关闭，1开启',
  `isany_money_recharge` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0关闭，1开启',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_tbbanner') ." (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lb_imgs` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs1` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs2` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs3` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `bname` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname1` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname2` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `bname3` varchar(110) CHARACTER SET utf8 DEFAULT NULL,
  `lb_imgs4` varchar(200) CHARACTER SET utf8 NOT NULL,
  `bname4` varchar(110) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_user') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'iq',
  `openid` varchar(100) DEFAULT NULL COMMENT 'openid',
  `img` varchar(200) DEFAULT NULL COMMENT '头像',
  `time` varchar(50) DEFAULT NULL COMMENT '登录时间',
  `uniacid` int(11) DEFAULT NULL,
  `money` decimal(11,2) DEFAULT '0.00',
  `name` varchar(100) DEFAULT NULL,
  `addtime` varchar(50) DEFAULT NULL COMMENT '会员的添加的时间',
  `viptype` int(11) DEFAULT '0' COMMENT '会员登记',
  `endtime` varchar(50) DEFAULT NULL COMMENT '结束时间',
  `telphone` varchar(20) NOT NULL COMMENT '手机号码',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_user_coupon') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `vab` int(11) DEFAULT NULL COMMENT '优惠券名称，展示用',
  `type` tinyint(3) unsigned DEFAULT NULL COMMENT '优惠券类型（1:折扣 2:代金券）',
  `val` int(11) DEFAULT NULL COMMENT '功能',
  `createTime` varchar(50) DEFAULT NULL COMMENT '领取时间',
  `limitTime` varchar(50) DEFAULT NULL COMMENT '使用截止时间',
  `isUsed` tinyint(3) DEFAULT '0' COMMENT '是否使用',
  `useTime` varchar(50) DEFAULT '0' COMMENT '使用时间',
  `from` int(11) DEFAULT NULL COMMENT '优惠券来源（0:用户领取 1:充值赠送 2:支付赠送）',
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_userformid') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `form_id` varchar(50) NOT NULL COMMENT 'form_id',
  `time` datetime NOT NULL,
  `uniacid` varchar(50) NOT NULL,
  `openid` varchar(50) NOT NULL COMMENT 'openid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='formid表' AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_vip') ." (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '标题，展示用',
  `day` int(10) unsigned DEFAULT NULL COMMENT '时间',
  `price` decimal(10,2) unsigned DEFAULT NULL COMMENT '价格',
  `jihuoma` varchar(30) DEFAULT '0' COMMENT '激活码',
  `time` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `prefix` varchar(50) NOT NULL COMMENT '前缀',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

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
  `vipday` int(11) NOT NULL COMMENT 'vip天数',
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

CREATE TABLE IF NOT EXISTS ". tablename('mzhk_sun_youhui') ." (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bid` int(11) NOT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

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


");
file_put_contents(IA_ROOT."/addons/mzhk_sun/inc/web/sqcode.php","");