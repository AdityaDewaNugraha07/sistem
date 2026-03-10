<?php
/* @var $this yii\web\View */
$this->title = 'Approval Agenda Verifikasi Data';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
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
									<?= Yii::t('app', 'Daftar Approval Agenda'); ?>
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
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Kode<br>Agenda') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Penanggungjawab') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Menyetujui<br>Kadiv FAT') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Mengetahui<br>Kanit Gudang') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Mengetahui<br>Kadiv MKT') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Keterangan') ?></th>
                                            <th><?= Yii::t('app', 'Assign To') ?></th>
                                            <th><?= Yii::t('app', 'Approved By') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Level<br>Agreement') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
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
<?php $this->registerJs(" dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approve Agenda Verifikasi Data'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvalagendaso/'.$this->context->action->id); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= $url ?>',data:{dt: 'table-master'} },
		order: [],
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{	targets: 1, class: "td-kecil text-align-center"},
			{ 	targets: 2, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class: "td-kecil text-align-center"},
			{	targets: 4, class: "td-kecil text-align-center"},
			{	targets: 5, class: "td-kecil text-align-center"},
			{	targets: 6, class: "td-kecil text-align-center"},
			{	targets: 7, class: "td-kecil text-align-left"},
			{	targets: 8, class: "td-kecil text-align-center"},
            {	targets: 9, class: "td-kecil text-align-center",},
            {	targets: 10, class: "td-kecil text-align-center",},
            {	targets: 11, class: "td-kecil text-align-center",
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data=='APPROVED'){
                        ret = '<span class="label label-success">'+data+'</span>';
                    }else if(data=='REJECTED'){
                        ret = '<span class="label label-danger">'+data+'</span>';
                    }else if(data=='Not Confirmed'){
                        ret = '<span class="label label-default">'+data+'</span>';
                    }
                    return ret;
                }
            },
            {	targets: 12, 
                orderable: false,
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvalagendaso/info','id'=>'']) ?>'+id,'modal-master-info','95%'," $('#table-master').dataTable().fnClearTable(); ");
}
</script>