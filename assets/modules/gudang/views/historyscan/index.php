<?php
/* @var $this yii\web\View */
$this->title = 'History Scan';
app\assets\DatatableAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'History Scan'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<style>
#table-master tbody tr td{
	font-size: 1.3rem;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'History Scan Pemuatan'); ?></span>
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
                                            <th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Barang Jadi') ?></th>
                                            <th><?= Yii::t('app', 'Qty') ?></th>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Kubikasi') ?></th>
                                            <th><?= Yii::t('app', 'Kode SPM') ?></th>
                                            <th><?= Yii::t('app', 'Customer') ?></th>
                                            <th><?= Yii::t('app', 'Waktu Scan') ?></th>
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
<?php $this->registerJs("
	dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/historyscan/index') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, 
				render: function ( data, type, full, meta ) {
                    return '<center><a onclick="infoPalet(\''+data+'\')">'+data+'</a></center>';
                }
			},
			{	targets: 2, visible: false },
            {	targets: 3, visible: false },
        ],
		"autoWidth":false,
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
		},
    });
}

function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}
</script>