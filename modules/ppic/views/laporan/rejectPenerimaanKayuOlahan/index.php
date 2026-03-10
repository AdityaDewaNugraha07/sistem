<?php
/* @var $this yii\web\View */
$this->title = 'Approval Reject Penerimaan Kayu Olahan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
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
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions'=>['class'=>'col-md-3 control-label'],
                                    ],
                                    'enableClientValidation'=>false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Tanggal Berkas','model' => $model,'form'=>$form]) ?>
                                        </div>
                                        <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                    </div>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'ID') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Reff No') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Berkas') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Approve') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Produk Nama') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Produk Kode') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Status Kirim Gudang') ?></th>
                                            <th style="line-height: 1;"><?= Yii::t('app', 'Status Approval') ?></th>
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
<?php 
$this->registerJs(" 
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Reject Penerimaan Kayu Olahan'))."');
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
    formconfig();
    dtLaporan();
    changePertanggalLabel();
", yii\web\View::POS_READY);
?>
<script>
function dtLaporan(){
    var tgl_awal = $("#tapproval-tgl_awal").val();
    var tgl_akhir = $("#tapproval-tgl_akhir").val();
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?php echo \yii\helpers\Url::toRoute('/ppic/laporan/'.$this->context->action->id);?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
                tgl_awal : tgl_awal,
                tgl_akhir : tgl_akhir,
			} 
		},
        columnDefs: [
            {	targets: 0, class: "td-kecil text-align-left" },
            {	targets: 1, class: "td-kecil text-align-left"},
            { 	targets: 2, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
            { 	targets: 3, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(full[3]);
					date = date.toString('dd/MM/yyyy');
					return date;
                }
            },
            {	targets: 4, class: "td-kecil text-align-left"},
            {	targets: 5, class: "td-kecil text-align-left"},
            {	targets: 6, class: "td-kecil text-align-left col-md-2",
                render: function ( data, type, full, meta ) {
					var ret = '';
                        var xxx = JSON.parse(full[6]);
                        var by = xxx[0].by;
                        var kirim_gudang_detail_id = full[8];
                        var emangPekok = function () {
                            var yoKoweKuwi = null;
                            $.ajax({
                                async  : false,
                                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/kirimgudang/golekiJenengSingNgrejekSu']); ?>',
                                type   : 'POST',
                                data   : {kirim_gudang_detail_id:kirim_gudang_detail_id},
                                success: function (data) {
                                    yoKoweKuwi = data;
                                },
                                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
                            });
                            return yoKoweKuwi;
                        }();
                        var koweEmangPekok = emangPekok;
                        var at = xxx[0].at;
                        var reason = xxx[0].reason;
                        ret = "<div class='text-center'><span class='font-red-flamingo' style='font-size:1rem;'>REJECTED</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>by : "+koweEmangPekok+"</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>at : "+formatDateTimeForUser(at)+"</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>reason : "+reason+"</span></div>";
                    return ret;
                }
            },
            {	targets: 7, class: "td-kecil text-align-left col-md-2",
                render: function ( data, type, full, meta ) {
					var ret = '';
                        var xxx = JSON.parse(full[7]);
                        var by = xxx[0].by;
                        var kirim_gudang_detail_id = full[8];
                        var emangPekok = function () {
                            var yoKoweKuwi = null;
                            $.ajax({
                                async  : false,
                                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/kirimgudang/golekiJenengSingApproveSu']); ?>',
                                type   : 'POST',
                                data   : {kirim_gudang_detail_id:kirim_gudang_detail_id},
                                success: function (data) {
                                    yoKoweKuwi = data;
                                },
                                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
                            });
                            return yoKoweKuwi;
                        }();
                        var koweEmangPekok = emangPekok;
                        var at = xxx[0].at;
                        var reason = xxx[0].reason;
                        ret = "<div class='text-center'><span class='font-green-jungle' style='font-size:1rem;'>APPROVED</span>"
                                    +"<br><span class='font-green-jungle' style='font-size:0.8rem;'>by : "+koweEmangPekok+"</span>"
                                    +"<br><span class='font-green-jungle' style='font-size:0.8rem;'>at : "+formatDateTimeForUser(at)+"</span>"
                                    +"<br><span class='font-green-jungle' style='font-size:0.8rem;'>reason : "+reason+"</span></div>";
                    return ret;
                }
            },
        ],
		order:[],
        "fnDrawCallback": function( oSettings ) {
			formattingDatatableMasterThis(oSettings.sTableId);
		},
		"bDestroy": true,
		"autoWidth" : false,
    });
}

function formattingDatatableMasterThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/rejectPenerimaanKayuOlahanPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>