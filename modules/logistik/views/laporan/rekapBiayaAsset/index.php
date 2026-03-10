<?php
/* @var $this yii\web\View */
$this->title = 'Rekap Biaya Asset';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php 
	$pegawai_id = Yii::$app->user->identity->pegawai_id;
	if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
	{
?>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-cogs"></i>
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Biaya Asset '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Nama Asset') ?></th>
							<th><?= Yii::t('app', 'Periode') ?></th>
							<th><?= Yii::t('app', 'Target Plan') ?></th>
							<th><?= Yii::t('app', 'Total Biaya') ?></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
                            <th colspan="4" style="text-align:right">Total Per Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="4" style="text-align:right; ">Grand Total All Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
					</tfoot>
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
	getAssetPeruntukan();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/rekapBiayaAsset') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	
                targets: 0, 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	
                targets: 1, 
                render: function ( data, type, full, meta ) {
					return data + '<br>' + full[2];
                }
            },
			{ 	
                targets: 2, class:'text-align-center',
                render: function ( data, type, full, meta ) {
					if(full[3] == 1){
						bulan = 'Jan';
					} else if(full[3] == 2){
						bulan = 'Feb';
					} else if(full[3] == 3){
						bulan = 'Mar';
					} else if(full[3] == 4){
						bulan = 'Apr';
					} else if(full[3] == 5){
						bulan = 'Mei';
					} else if(full[3] == 6){
						bulan = 'Jun';
					} else if(full[3] == 7){
						bulan = 'Jul';
					} else if(full[3] == 8){
						bulan = 'Agu';
					} else if(full[3] == 9){
						bulan = 'Sep';
					} else if(full[3] == 10){
						bulan = 'Okt';
					} else if(full[3] == 11){
						bulan = 'Nov';
					} else if(full[3] == 12){
						bulan = 'Des';
					}
					return bulan + '-' + full[4];
                }
            },
			{	targets: 3, class:'text-align-center',
				render: function( data, type, full, meta) {
                    return full[5];
                }
			 },
            {   
                targets: 4, class:'text-align-right',
                render: function( data, type, full, meta) {
                    return formatNumberForUser(Math.round(full[6]));
                }
            },
			{	targets: 5, visible: false },
			{	targets: 6, visible: false },
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		footerCallback: function(row, data, start, end, display) {
			var api = this.api();
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

            // Total over all pages
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(0).toLocaleString())}`);
			$.ajax({
                url: "<?= yii\helpers\Url::toRoute('/logistik/laporan/rekapBiayaAssetTotal') ?>?"+$('#form-search-laporan').serialize(),
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).total.toFixed(0).toLocaleString())}`);
                }                                                                                                                                                                                                                                                                                                                                                                                                             
            })
			
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function getAssetPeruntukan(obj, departement_id = null) {
	var departement = document.getElementById("departement");
	var departement_id = departement.value;
	$.ajax({
		url: '<?= \yii\helpers\Url::toRoute(['/logistik/laporan/getAssetPeruntukan']); ?>',
		data: {
			departement_id: departement_id
		},
		success: function(data) {
			$('#asset-peruntukan').html(data);
		},
	});
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/rekapBiayaAssetPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>
<?php } ?>