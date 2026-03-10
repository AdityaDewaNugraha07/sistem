<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Stock Bahan Pembantu Budgeting';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Stock Bahan Pembantu Budgeting '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Trace<br>Barang') ?></th>
						</tr>
					</thead>
					<?php 
						$pegawai_id = Yii::$app->user->identity->pegawai_id;
						if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
						{
					?>
					<tfoot>
						<tr>
                            <th colspan="5" style="text-align:right">Total Per Page:</th>
                            <th colspan="1" style="text-align:right"></th>
                        </tr>
                        <tr>
                            <th colspan="5" style="text-align:right; ">Grand Total All Page:</th>
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
		getPeruntukan();
	", yii\web\View::POS_READY); ?>
<?php } else {?>
	<?php $this->registerJs("
		$('#form-search-laporan').submit(function(){
			dtLaporan();
			return false;
		});
		formconfig(); 
		dtLaporan();
		getPeruntukan();
	", yii\web\View::POS_READY); ?>
<?php } ?>
<script>
function dtLaporanA(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/stockBudgeting') ?>',
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
				targets: 3, 
				render: function ( data, type, full, meta ){
					return '<center>' + data + '</center>';
				}
			},
			{
				targets: 4,
				render: function ( data, type, full, meta ){
					// var	number_string = full[8].toString();
                    // var sisa 	= number_string.length % 3;
                    // var harga 	= number_string.substr(0, sisa);
                    // var ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                    // if (ribuan) {
                    //     separator = sisa ? ',' : '';
                    //     harga += separator + ribuan.join(',');
                    // }
                    return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[8]))+'</div>';
				}
			},
			{
				targets: 5,
				render: function ( data, type, full, meta ){
					// var subtotal = full[3] * full[8];
					// var	number_string = subtotal.toString();
                    // var sisa 	= number_string.length % 3;
                    // var subt 	= number_string.substr(0, sisa);
                    // var ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
                    // if (ribuan) {
                    //     separator = sisa ? ',' : '';
                    //     subt += separator + ribuan.join(',');
                    // }
					return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[9]))+'</div>';
				}
			},
            {   
                targets: 6,
                render: function( data, type, full, meta) {
                    return '<center>' + full[4] + '</center>';
                }
            },
			{ 	
                targets: 7, 
                render: function ( data, type, full, meta ) {
					return '<center>' + full[5] + '</center>';
                }
            },
			{
				targets: 8,
				render: function ( data, type, full, meta ){
					return '<center>' + full[7] + '</center>';
				}
			},
            {
                targets: 9,
                render: function ( data, type, full, meta ){
					if(full[6] != null) {
						ret = '<center>' + full[6] + '</center>';
					} else {
						ret = '<center> - </center>';
					}
                    return ret;
                }
            },
			{
				targets: 10,
				render: function ( data, type, full, meta ){
					return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+', '+full[10]+')\"><i class="fa fa-info-circle"></i></a></center>';
				}
			}
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
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
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(pageTotal.toFixed(0).toLocaleString())}`);
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/logistik/laporan/stockBudgetingTotal') ?>?"+$('#form-search-laporan').serialize(),
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${formatNumberForUser(JSON.parse(res).total.toFixed(0).toLocaleString())}`)  
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
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/stockBudgeting') ?>',
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
				targets: 3, 
				render: function ( data, type, full, meta ){
					return '<center>' + data + '</center>';
				}
			},
            {   
                targets: 4,
                render: function( data, type, full, meta) {
                    return '<center>' + full[4] + '</center>';
                }
            },
			{ 	
                targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<center>' + full[5] + '</center>';
                }
            },
			{
				targets: 6,
				render: function ( data, type, full, meta ){
					return '<center>' + full[7] + '</center>';
				}
			},
            {
                targets: 7,
                render: function ( data, type, full, meta ){
					if(full[6] != null) {
						ret = '<center>' + full[6] + '</center>';
					} else {
						ret = '<center> - </center>';
					}
                    return ret;
                }
            },
			{
				targets: 8,
				render: function ( data, type, full, meta ){
					return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info('+full[0]+', '+full[10]+')\"><i class="fa fa-info-circle"></i></a></center>';
				}
			}
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
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
	window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/stockBudgetingPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
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

function info(reff_detail_id, dept_id){
	if(dept_id != null){
		openModal('<?= \yii\helpers\Url::toRoute('/logistik/laporan/infoTraceStockDep') ?>?reff_detail_id='+reff_detail_id+'&dept_id='+dept_id,'modal-tracestock','50%');
	} else {
		openModal('<?= \yii\helpers\Url::toRoute('/logistik/laporan/infoTraceStock') ?>?reff_detail_id='+reff_detail_id,'modal-tracestock','50%');
	}
}
</script>