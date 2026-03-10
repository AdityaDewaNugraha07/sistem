<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan SPP';
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan SPP (Surat Permintaan Pembelian)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/index"); ?>"> <?= Yii::t('app', 'PO Baru'); ?> </a>
                    </li>
					<li class="">
						<a href="<?= yii\helpers\Url::toRoute("/purchasing/pobhp/podibuat"); ?>"> <?= Yii::t('app', 'PO Dibuat'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/spl/index"); ?>"> <?= Yii::t('app', 'SPL Baru'); ?> </a>
                    </li>
                    <li class="active">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/index"); ?>"> <?= Yii::t('app', 'SPP Masuk'); ?> </a>
                    </li>
					<li class="">	
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/penerimaanspp/sppmasuk"); ?>"> <?= Yii::t('app', 'SPP Masuk Detail'); ?> </a>
                    </li>
					<li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/purchasing/dpbhp/index"); ?>"> <?= Yii::t('app', 'Downpayment'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'List Semua SPP Yang Masuk'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-list">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
                                            <th style="width: 150px;"><?= Yii::t('app', 'No. SPP'); ?></th>
                                            <th><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th><?= Yii::t('app', 'Dept. Origin'); ?></th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
dtDetail();
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Bahan Pembantu'))."');
", yii\web\View::POS_READY); ?>
<script>
function dtDetail(){
    var dt_table =  $('#table-list').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/penerimaanspp/index') ?>',data:{dt: 'table-list'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '15%' },
            {	targets: 2, width: '15%' },
//            {	targets: 5,
//                orderable: false,
//                width: '10%',
//                render: function ( data, type, full, meta ) {
//                    var ret = ' - ';
//                    if(data == "TO-DO"){
//                        ret = '<span class="label label-sm label-info"> '+data+' </span>';
//                    }else if(data == "INPROGRESS"){
//                        ret = '<span class="label label-sm label-warning"> '+data+' </span>';
//                    }else if(data == "COMPLETE"){
//                        ret = '<span class="label label-sm label-success"> '+data+' </span>';
//                    }else if(data == "CANCEL"){
//                        ret = '<span class="label label-sm label-danger"> '+data+' </span>';
//                    }else if(data == "PENDING"){
//                        ret = '<span class="label label-sm label-default"> '+data+' </span>';
//                    }
//                    return ret;
//                }
//            },
            {	targets: 5, 
                orderable: false,
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class=\"btn btn-xs blue-hoki btn-outline\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/info','id'=>'']) ?>'+id,'modal-info',null,'$(\'#table-list\').dataTable().fnClearTable();');
}
</script>