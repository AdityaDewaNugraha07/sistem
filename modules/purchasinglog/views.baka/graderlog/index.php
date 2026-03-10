<?php
/* @var $this yii\web\View */
$this->title = 'Grader Log';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasinglog/graderlog/index"); ?>"> <?= Yii::t('app', 'Master Grader'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasinglog/wilayahdinasgrader/index"); ?>"> <?= Yii::t('app', 'Wilayah Dinas'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Grader Log'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-master">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Nama Grader') ?></th>
                                            <th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Grader') ?></th>
                                            <th><?= Yii::t('app', 'Phone') ?></th>
                                            <th><?= Yii::t('app', 'Alamat') ?></th>
                                            <th><?= Yii::t('app', 'Email') ?></th>
                                            <th><?= Yii::t('app', 'Tipe') ?></th>
                                            <th><?= Yii::t('app', 'Active') ?></th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Grader Log'))."')", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/graderlog/index') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 2, class:"text-align-center" },
			{	targets: 6, 
                render: function ( data, type, full, meta ) {
					if(data == 'GLA'){
						return "Grader Log Alam";
					}else{
						return "Grader Log Sengon";
					}
                }
            },
            {	targets: 7,
                orderable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data){
                        ret = 'Active'
                    }else{
                        ret = '<span style="color:#B40404">Non-Active</span>'
                    }
                    return ret;
                }
            },
            {	targets: 8, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
    });
}

function create(){
	openModal('<?= \yii\helpers\Url::toRoute('/purchasinglog/graderlog/create') ?>','modal-master-create');
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/graderlog/info','id'=>'']) ?>'+id,'modal-master-info');
}
function printout(caraPrint){
    var search = $('.form-control.input-sm.input-small.input-inline').val();
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/graderlog/GraderLogPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint+"&search="+search,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>