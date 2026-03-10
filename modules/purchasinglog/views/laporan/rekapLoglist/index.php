<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Rekap Loglist'; 
app\assets\DatatableAsset::register($this); 
app\assets\DatepickerAsset::register($this); 
app\assets\InputMaskAsset::register($this); 
app\assets\Select2Asset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?> </h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Loglist '); ?><span id="periode-label" class="font-blue-soft"></span></span>
				</div>
				<div class="tools">
					<a href="javascript:;" class="reload"> </a>
					<a href="javascript:;" class="fullscreen"> </a>
				</div>
			</div>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
							<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Nama Tongkang'); ?></th>
                            <th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'No. Kontrak'); ?></th>
                            <th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Nama Perusahaan'); ?></th>
							<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Kayu'); ?></th>
							<th rowspan="2" style="width: 70px;"><?= Yii::t('app', 'Pcs'); ?></th>
							<th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
						</tr>
						<tr>
							<th style="width: 70px;"><?= Yii::t('app', 'Range'); ?></th>
							<th style="width: 50px;"><?= Yii::t('app', 'Value'); ?> m<sup>3</sup></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig(); 
	dtLaporan();
	changePertanggalLabel();
    $('#".yii\bootstrap\Html::getInputId($model, 'kayu_id')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
	$('#".yii\bootstrap\Html::getInputId($model, 'pihak1_perusahaan')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
	setNoKontrak();
	$('#".yii\bootstrap\Html::getInputId($model, 'log_kontrak_id')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
	$('#".yii\bootstrap\Html::getInputId($model, 'tongkang')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Loglist Rekap'))."')
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/laporan/loglist') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                class:"text-align-center td-kecil",
            },
			{ 	targets: 1, 
                class:"text-align-center td-kecil",
            },
			{ 	targets: 2, 
                class:"text-align-center td-kecil",
            },
            { 	targets: 3, 
                class:"text-align-center td-kecil",
            },
			{ 	targets: 4, 
                class:"text-align-center td-kecil",
            },
			{ 	targets: 5, 
                class:"text-align-center td-kecil",
            },
			{ 	targets: 6, 
                class:"text-align-right td-kecil",
				render: function ( data, type, full, meta ) {
					var vol = formatNumberForUser2Digit(data);
					return vol;
				},
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
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/rekapLoglistPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function setNoKontrak(){
	var perusahaan = $('#<?= yii\bootstrap\Html::getInputId($model, 'pihak1_perusahaan');?>').val();
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/setNoKontrak']); ?>',
		type   : 'POST',
		data   : {perusahaan: perusahaan},
		success: function (data) {
			if(data){
				$('#<?= \yii\helpers\Html::getInputId($model, "log_kontrak_id") ?>').html(data.html);
			}
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}
</script>