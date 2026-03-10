<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = 'Approval Daftar Rencana Pembayaran (DRP)';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);

$status == 'Not Confirmed' ? $status1 = 'active' : $status1 = '';
$status == 'Confirmed' ? $status2 = 'active' : $status2 = '';

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
                    <li class="<?php echo $status1;?>">
						<a href="<?= yii\helpers\Url::toRoute("/topmanagement/approvaldrp/index"); ?>"> <?= Yii::t('app', 'Not Confirmed'); ?> </a>
                    </li>
					<li class="<?php echo $status2;?>">
						<a href="<?= \yii\helpers\Url::toRoute("/topmanagement/approvaldrp/indexConfirmed") ?>"> <?= Yii::t('app', 'Confirmed'); ?> </a>
					</li>
                </ul>                
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
									<?= Yii::t('app', 'Daftar Approval DRP'); ?>
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
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Kode') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Rencana Pembayaran') ?></th>
                                            <!-- <th style="line-height: 1;"><?= Yii::t('app', 'Kategori') ?></th> -->
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
<?php $this->registerJs(" formconfig(); dtMaster(); setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Approval DRP'))."');", yii\web\View::POS_READY); ?>
<?php $url = \yii\helpers\Url::toRoute('/topmanagement/approvaldrp/'.$this->context->action->id); ?>
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
			{	targets: 3, class: "td-kecil text-align-left",
                render: function(data, type, full, meta){
                    if( data == ''){
                        ket = '<center>-</center>';
                    }else{
                        ket = data;
                    }
                    return ket;
                }
            },
			{	targets: 4, class: "td-kecil text-align-left"},
			{	targets: 5, class: "td-kecil text-align-left"},
			{	targets: 6, class: "td-kecil text-align-center"},
            {	targets: 7, class: "td-kecil text-align-left"},
            {	targets: 8, class: "td-kecil text-align-left",
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a></center>';
                }
            }
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
        "bStateSave": true,
    });
}
function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/topmanagement/approvaldrp/info','id'=>'']) ?>'+id,'modal-master-info','95%'," $('#table-master').dataTable().fnClearTable(); ");
}
function gkk(id){
        var url = '<?= Url::toRoute(['/kasir/pengeluarankaskecil/detailGkk']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-gkk").modal('show');
            $("#modal-gkk").on('hidden.bs.modal', function () { });
            $("#modal-gkk .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }

    function ppk(id){
        var url = '<?= Url::toRoute(['/kasir/ppk/detailppk']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ppk").modal('show');
            $("#modal-ppk").on('hidden.bs.modal', function () { });
            $("#modal-ppk .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }

    function ajuanDinas(id){
        var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ajuandinas").modal('show');
            $("#modal-ajuandinas").on('hidden.bs.modal', function () { });
            $("#modal-ajuandinas .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }
    
    function ajuanMakan(id){
        var url = '<?= Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan']); ?>?id='+id;
        $(".modals-place-2").load(url, function() {
            $("#modal-ajuanmakan").modal('show');
            $("#modal-ajuanmakan").on('hidden.bs.modal', function () { });
            $("#modal-ajuanmakan .modal-dialog").css('width',"21cm");
            spinbtn();
            draggableModal();
        });
    }
</script>