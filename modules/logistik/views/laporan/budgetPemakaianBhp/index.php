<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Budget Pemakaian Bahan Pembantu';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Budget Pemakaian Bahan Pembantu '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Kode') ?></th>
							<th><?= Yii::t('app', 'Tanggal') ?></th>
							<th></th>
							<th><?= Yii::t('app', 'Nama Item') ?></th>
                            <th><?= Yii::t('app', 'Qty') ?></th>
							<?php 
								$pegawai_id = Yii::$app->user->identity->pegawai_id;
								if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
								{
							?>
									<th><?= Yii::t('app', 'Harga Peritem') ?></th>
									<th><?= Yii::t('app', 'Sub Total') ?></th>
							<?php } ?>
							<th><?= Yii::t('app', 'Target Plan') ?></th>
							<th><?= Yii::t('app', 'Target Peruntukan') ?></th>
							<th><?= Yii::t('app', 'Departement') ?></th>
                            <th><?= Yii::t('app', 'Dept. Peruntukan') ?></th>
							<th><?= Yii::t('app', 'Asset Peruntukan') ?></th>
							<th><?= Yii::t('app', 'Keterangan') ?></th>
						</tr>
					</thead>
					<?php 
						$pegawai_id = Yii::$app->user->identity->pegawai_id;
						if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
						{
					?>
					<tfoot>
						<tr>
                            <th colspan="7" style="text-align:right">Total Per Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="7" style="text-align:right; ">Grand Total All Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
					</tfoot>
					<?php } ?>
				</table>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php 
	$pegawai_id = Yii::$app->user->identity->pegawai_id;
	if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
	{
?>
<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporanA();
		return false;
	});
	formconfig(); 
	dtLaporanA();
	changePertanggalLabel();
	getPeruntukan();
	getAssetPeruntukan();
", yii\web\View::POS_READY); ?>
<?php } else { ?>
	<?php $this->registerJs("
	$('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
	formconfig(); 
	dtLaporan();
	changePertanggalLabel();
	getPeruntukan();
	getAssetPeruntukan();
", yii\web\View::POS_READY); ?>
<?php } ?>
<script>
function dtLaporanA(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/budgetPemakaianBhp') ?>',
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
                targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, visible: false },
            {   
                targets: 4,
                render: function( data, type, full, meta) { 
                    return full[3];
                }
            },
			{ 	
                targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[4]+'</center>';
                }
            },
            {
                targets: 6,
                render: function ( data, type, full, meta ){
					return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[5]))+'</div>';
                }
            },
			{
				targets: 7,
				render: function ( data, type, full, meta ){
					return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[11]))+'</div>';
				}
			},
            {   
                targets: 8,
                render: function( data, type, full, meta ) {
                    return '<center>'+full[6]+'</center>';
                }
            },
			{ 	
                targets: 9, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[7]+'</center>';
                }
            },
			{
				targets: 10,
				render: function ( data, type, full, meta ) {
					return '<center>'+full[12]+'</center>';
				}
			},
			{ 	
                targets: 11, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[8]+'</center>';
                }
            },
            {   
                targets: 12,
                render: function(data, type, full, meta){
					if(full[9] != null){
						ret = full[13] + '<br>' +full[9];
					} else {
						ret = '<center> - </center>';
					}
                    return ret;
                }
            },
			{
				targets: 13,
				render: function(data, type, full, meta){
					return full[10];
				}
			}
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
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

            // Total over all pages
            total = api
                .column( 11 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(0).toLocaleString())}`);
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/logistik/laporan/budgetPemakaianBhpTotal') ?>?"+$('#form-search-laporan').serialize(),
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

function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/budgetPemakaianBhp') ?>',
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
                targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, visible: false },
            {   
                targets: 4,
                render: function( data, type, full, meta) {
                    return full[3];
                }
            },
			{ 	
                targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[4]+'</center>';
                }
            },
            {   
                targets: 6,
                render: function( data, type, full, meta ) {
                    return '<center>'+full[6]+'</center>';
                }
            },
			{ 	
                targets: 7, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[7]+'</center>';
                }
            },
			{
				targets: 8,
				render: function ( data, type, full, meta ) {
					return '<center>'+full[12]+'</center>';
				}
			},
			{ 	
                targets: 9, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[8]+'</center>';
                }
            },
            {   
                targets: 10,
                render: function(data, type, full, meta){
					if(full[9] != null){
						ret = full[9];
					} else {
						ret = '<center> - </center>';
					}
                    return ret;
                }
            },
			{
				targets: 11,
				render: function(data, type, full, meta){
					return full[10];
				}
			}
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
	window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/budgetPemakaianBhpPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function getPeruntukan(obj, departement_id = null) {
		var departement = document.getElementById("departement");
		var departement_id = departement.value;
		if(departement_id){
			$.ajax({
				url: '<?= \yii\helpers\Url::toRoute(['/logistik/laporan/getPeruntukan']); ?>',
				// type: 'POST',
				data: {
					departement_id: departement_id
				},
				success: function(data) {
					$('#target-peruntukan').html(data);
				},
			});
		} else {
			$('#target-peruntukan').html('<option value="">All</option>');
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
</script>