<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Pengeluaran Bahan Pembantu';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);

$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => app\models\MUser::find(),
    'pagination' => [
        'pageSize' => 20,
    ],
]);

$model2 = new app\models\MUser();
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Pengeluaran Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'No.'); ?></th>
							<th><?= Yii::t('app', 'Kode BPB') ?></th>
							<th><?= Yii::t('app', 'Tanggal Keluar') ?></th>
							<th><?= Yii::t('app', 'Kode Item') ?></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
							<th><?= Yii::t('app', 'Qty') ?></th>
							<th><?= Yii::t('app', 'Satuan') ?></th>
							<th><?= Yii::t('app', 'Dept Tujuan') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
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
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasing/laporan/bhpKeluar') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, 
                render: function ( data, type, full, meta ) {
					var parse1 = $.trim(data.split('/')[1]);
					var parse2 = '';
					var parse3 = '';
					if($.trim(data.split('/')[2])){
						parse2 = '/'+$.trim(data.split('/')[2]);
					}
					if($.trim(data.split('/')[3])){
						parse3 = '/'+$.trim(data.split('/')[3]);
					}
					var ret = parse1+parse2+parse3;
					return ret;
                }
            },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 7, 
                render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
                }
            },
			{ 	targets: 8, 
                render: function ( data, type, full, meta ) {
					return '<span style="font-size:1.1rem;">'+data+'</span>';
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
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/purchasing/laporan/bhpKeluarPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>