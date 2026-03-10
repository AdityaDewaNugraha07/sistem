<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Retur Penjualan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Retur Penjualan'); ?> <span id="periode-label" class="font-blue-soft"></span></span>
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
							<th></th>
							<th style="width:150px;"><?= Yii::t('app', 'Kode Retur') ?></th>
							<th style="width:150px;"><?= Yii::t('app', 'Tanggal Retur') ?></th>
							<th style="width:150px;"><?= Yii::t('app', 'Nomor Nota') ?></th>
							<th style="width:300px;"><?= Yii::t('app', 'Customer'); ?></th>
							<th style="width:80px;"><?= Yii::t('app', 'Jml Pcs'); ?></th>
							<th style="width:80px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
							<th><?= Yii::t('app', 'Total Harga Retur'); ?></th>
						</tr>
					</thead>
				</table>
				<div class="place-total">
					<table class="table table-striped table-bordered table-hover" style="width: 100%;">
						<tr style="background-color: #D7E1EC">
							<td style="width: 784px; text-align: right"><b>Total &nbsp; </b></td>
							<td style="width:90px; font-weight: 600;" id='place-totalpcs' class='text-align-center'></td>
							<td style="width:90px; font-weight: 600;" id='place-totalm3' class='text-align-right'></td>
							<td style="font-weight: 600;" id='place-totalharga' class='text-align-right'></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Laporan Retur Penjualan'))."')
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
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/marketing/laporan/returPenjualan') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{	targets: 0, visible: false },
			{ 	targets: 2, render: data => formatDateForUser(data) },
			{ 	targets: 4 , class:"text-left" },
			{ 	targets: 6 , class:"text-right",
                render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
                }
            },
			{ 	targets: 7 , class:"text-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{
				targets: '_all',
				class: 'text-center'
			}
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
			var api = this.api(), data;
			// Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            // Total over all pages
            let total_pcs = api.column( 5 ).data().reduce( (a, b) => intVal(a) + intVal(b), 0 );
            var total_m3  = api.column( 6 ).data().reduce( (a, b) => intVal(a) + intVal(b), 0 );
            var total_harga = api.column( 7 ).data().reduce( (a, b) => intVal(a) + intVal(b), 0 );
			$("#place-totalpcs").html( formatNumberForUser(total_pcs) );
			$("#place-totalm3").html( formatNumberFixed4(total_m3) );
			$("#place-totalharga").html( formatNumberForUser(total_harga) );
			
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth" : false,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/marketing/laporan/ReturPenjualanPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
</script>