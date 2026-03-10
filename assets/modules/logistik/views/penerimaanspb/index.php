<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan SPB';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan SPB (Surat Permintaan Barang)'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
				<ul class="nav nav-tabs">
                    <li class="">
                        <a href="<?= yii\helpers\Url::toRoute("/logistik/spb/index"); ?>"> <?= Yii::t('app', 'SPB Baru'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= yii\helpers\Url::toRoute("/logistik/penerimaanspb/index"); ?>"> <?= Yii::t('app', 'SPB Masuk'); ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
						<?= $this->render('_search', ['model' => $model]) ?>
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-list"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'List Semua SPB Yang Masuk'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="table-scrollable">
									<table class="table table-striped table-bordered table-hover" id="table-penerimaan">
										<thead>
											<tr>
												<th></th>
												<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
												<th style="width: 150px;"><?= Yii::t('app', 'No. SPB'); ?></th>
												<th><?= Yii::t('app', 'Tanggal'); ?></th>
												<th><?= Yii::t('app', 'Sifat Permintaan'); ?></th>
												<th><?= Yii::t('app', 'Dept. Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Pegawai Pemesan'); ?></th>
												<th><?= Yii::t('app', 'Status SPB'); ?></th>
												<!--<th><?php // echo Yii::t('app', 'Status Approval'); ?></th>-->
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
</div>
<?php $this->registerJs(" 
setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Surat Permintaan Barang'))."');
$('#form-search-laporan').submit(function(){
	dtBhp(); 
	return false;
});
formconfig(); 
dtBhp(); 
changePertanggalLabel();"
, yii\web\View::POS_READY); ?>
<script>
function dtBhp(){
    var dt_table =  $('#table-penerimaan').dataTable({
		ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/penerimaanspb/index') ?>',
			data:{
				dt: 'table-penerimaan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '15%' },
            {	targets: 2, width: '10%' },
            { 	targets: 3, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 4, width: '10%' },
            {	targets: 7,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data == "BELUM DIPROSES"){
                        ret = '<span class="label label-sm label-info"> '+data+' </span>';
                    }else if(data == "SEDANG DIPROSES"){
                        ret = '<span class="label label-sm label-warning"> '+data+' </span>';
                    }else if(data == "TERPENUHI"){
                        ret = '<span class="label label-sm label-success"> '+data+' </span>';
                    }else if(data == "DITOLAK"){
                        ret = '<span class="label label-sm label-danger"> '+data+' </span>';
                    }
                    return ret;
                }
            },
            {	targets: 8, 
                orderable: false,
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class=\"btn btn-xs blue-hoki btn-outline\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+')\"><i class="fa fa-info-circle"></i></a>';
                    return ret;
                }
            },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		order:[],
		"bDestroy": true,
    });
}

function info(id){
	openModal('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanspb/info','id'=>'']) ?>'+id,'modal-penerimaanspb-info');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function setDropdownPegawai(){
    $('#<?= \yii\bootstrap\Html::getInputId($model, 'spb_diminta') ?>').addClass('animation-loading');
    var dept_id = $('#<?= \yii\bootstrap\Html::getInputId($model, 'departement_id') ?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanspb/setDropdownPegawai']); ?>',
		type   : 'POST',
		data   : {dept_id:dept_id},
		success: function (data) {
			$("#<?= \yii\bootstrap\Html::getInputId($model, 'spb_diminta') ?>").html(data.html);
            $('#<?= \yii\bootstrap\Html::getInputId($model, 'spb_diminta') ?>').removeClass('animation-loading');
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>