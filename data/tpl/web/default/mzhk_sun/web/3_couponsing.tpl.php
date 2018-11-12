<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/header', TEMPLATE_INCLUDEPATH)) : (include template('public/header', TEMPLATE_INCLUDEPATH));?>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('public/comhead', TEMPLATE_INCLUDEPATH)) : (include template('public/comhead', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/mzhk_sun/template/public/ygcss.css">
<style type="text/css">
    input[type="radio"] + label::before {
        content: "\a0"; /*不换行空格*/
        display: inline-block;
        vertical-align: middle;
        font-size: 16px;
        width: 1em;
        height: 1em;
        margin-right: .4em;
        border-radius: 50%;
        border: 2px solid #ddd;
        text-indent: .15em;
        line-height: 1;
    }
    input[type="radio"]:checked + label::before {
        background-color: #444444;
        background-clip: content-box;
        padding: .1em;
        border: 2px solid #444444;
    }
    input[type="radio"] {
        position: absolute;
        clip: rect(0, 0, 0, 0);
    }
</style>

<ul class="nav nav-tabs">
    <span class="ygxian"></span>
    <div class="ygdangq">当前位置:</div>
    <li class="active"><a href="javascript:void(0);">优惠券设置</a></li>
    <li  class="activ"><a href="<?php  echo $this->createWebUrl('mjcounp')?>">满减优惠券列表</a></li>
    <li  class="activ"><a href="<?php  echo $this->createWebUrl('dzcounp')?>">打折优惠券列表</a></li>
    <li  class="activ"><a href="<?php  echo $this->createWebUrl('mscounp')?>">满送优惠券列表</a></li>
</ul>
<div class="main">
    <form action="" method="post" class="form-horizontal form" enctype="multipart/form-data">
        <!--<input type="hidden" name="parentid" value="<?php  echo $parent['id'];?>" />-->
        <div class="panel panel-default ygdefault">
            <div class="panel-heading wyheader">
                优惠券设置
            </div>

            <div class="panel-body">

                <div class="form-group">
                    <label for="lastname" class="col-sm-2 control-label">是否开启优惠券</label>
                    <div class="col-sm-10">
                        <label class="radio-inline">
                            <input type="radio" id="emailwy1" name="is_counp" value="1" <?php  if($item['is_counp']==1 || empty($item['is_counp'])) { ?>checked<?php  } ?> />
                            <label for="emailwy1">开启</label>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" id="emailwy2" name="is_counp" value="2" <?php  if($item['is_counp']==2) { ?>checked<?php  } ?> />
                            <label for="emailwy2">关闭</label>
                        </label>
                    </div>
                </div>

            </div>

        </div>

        <div class="form-group">
            <input type="submit" name="submit" value="提交" class="btn col-lg-3" style="color: white;background-color: #444444;"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
        </div>
    </form>
</div>
<script type="text/javascript">
    $(function(){
        $("#frame-9").show();
        $("#yframe-9").addClass("wyactive");
    })
</script>