<?php
/* @var $this yii\web\View */
$this->title = 'User Management';
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'User Management'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/user/index"); ?>"> <?= Yii::t('app', 'User Account'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/usergroup/index"); ?>"> <?= Yii::t('app', 'User Group'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/sysadmin/useraccess/index"); ?>"> <?= Yii::t('app', 'User Access'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered bg-inverse">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'User Access Settings'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body text-center">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group select2-bootstrap-append">
                                                <select id="choose-user-group" class="form-control" onchange="chooseUserGroup(this)">
                                                    <option></option>
                                                    <?php foreach($modUserGroup as $key => $usergroup){
                                                        echo '<option value="'.$usergroup['user_group_id'].'">'.$usergroup['name'].'</option>';
                                                    } ?>
                                                </select>
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button" data-select2-open="choose-user-group">
                                                        <span class="fa fa-search"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="alert alert-block alert-info fade in text-justify">
                                            <button class="close" type="button" data-dismiss="alert"></button>
                                            <h4 class="alert-heading">Info</h4>
                                            <p style="font-weight: 300"> Akses User Di Breakdown berdasarkan menu-menu pada sistem.
                                                Pengelompokan akses user berdasarkan group user.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div id="config-panel" class=""></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$this->registerJs("
    $('#choose-user-group').select2({
        allowClear: !0,
        placeholder: 'Choose an user group',
        width: null
    });
    chooseUserGroup('0');
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('User'))."');
", yii\web\View::POS_READY); ?>
<script>
function chooseUserGroup(ele){
    var user_group_id = $(ele).val();
    setUserGroup(user_group_id);
}
function setUserGroup(user_group_id){
    $("#config-panel").addClass("animation-loading");
    $("#config-content").addClass("animation-loading");
    $.ajax({
		url    : '<?= \yii\helpers\Url::toRoute('/sysadmin/useraccess/loadUserAccessContent'); ?>',
		type   : 'POST',
		data   : {user_group_id:user_group_id},
		success: function (data) {
            if(data){
                $('#config-panel').html(data);
            }else{
                $('#config-panel').html("<h5>Choose User Group Option List.</h5>");
            }
            $("#config-panel").removeClass("animation-loading");
            $("#config-content").removeClass("animation-loading");
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>