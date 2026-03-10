<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Loglist Detail'; 
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Loglist '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th colspan="3"><?= Yii::t('app', 'Nomor'); ?></th>
							<th rowspan="2" style="width: 100px;"><?= Yii::t('app', 'Kayu'); ?></th>
							<th rowspan="2" style="width: 70px;"><?= Yii::t('app', 'Panjang'); ?></th>
							<th colspan="3"><?= Yii::t('app', 'Diameter'); ?></th>
							<th colspan="3"><?= Yii::t('app', 'Unsur Cacat'); ?></th>
							<th colspan="2"><?= Yii::t('app', 'Volume'); ?></th>
							<th rowspan="2" style="width: 50px;"><?= Yii::t('app', 'Fresh'); ?></th>
						</tr>
						<tr>
							<th style="width: 50px;"><?= Yii::t('app', 'Grd'); ?></th>
							<th style="width: 60px;"><?= Yii::t('app', 'Prod'); ?></th>
							<th style="width: 75px;"><?= Yii::t('app', 'Pcs'); ?></th>
							<th style="width: 50px;"><?= Yii::t('app', 'P'); ?></th>
							<th style="width: 50px;"><?= Yii::t('app', 'U'); ?></th>
							<th style="width: 55px;"><?= Yii::t('app', 'Rata'); ?><sup>2</sup></th>
							<th style="width: 70px;"><?= Yii::t('app', 'Pjg'); ?></th>
							<th style="width: 70px;"><?= Yii::t('app', 'GB'); ?></th>
							<th style="width: 70px;"><?= Yii::t('app', 'GR'); ?></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/laporan/loglistDetail') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                class:"text-align-center",
            },
			{ 	targets: 1, 
                class:"text-align-center",
            },
			{ 	targets: 2, 
                class:"text-align-center",
            },
			{ 	targets: 4, 
                class:"text-align-right",
            },
			{ 	targets: 5, 
                class:"text-align-right",
            },
			{ 	targets: 6, 
                class:"text-align-right",
            },
			{ 	targets: 7, 
                class:"text-align-right",
            },
			{ 	targets: 8, 
                class:"text-align-right",
            },
			{ 	targets: 9, 
                class:"text-align-right",
            },
			{ 	targets: 10, 
                class:"text-align-right",
            },
			{ 	targets: 11, 
                class:"text-align-center",
            },
			{ 	targets: 12, 
                class:"text-align-right",
            },
			{ 	targets: 13, 
                class:"text-align-center",
				render: function ( data, type, full, meta ) {
					if(data){
						return "Ya";
					}else{
						return "Tidak";
					}
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/loglistDetailPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>