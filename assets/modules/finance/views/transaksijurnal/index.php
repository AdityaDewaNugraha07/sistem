<?php
/* @var $this yii\web\View */
$this->title = 'Transaksi Jurnal';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Transaksi Jurnal Akuntansi '); ?><span id="pertanggal-label" class="font-blue-soft"></span></span>
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
							<th style="width: 100px;"><?= Yii::t('app', 'Kode Jurnal') ?></th>
							<th style="width: 80px;"><?= Yii::t('app', 'Acct Number') ?></th>
							<th><?= Yii::t('app', 'Tanggal') ?></th>
							<th><?= Yii::t('app', 'Account Name') ?></th>
							<th style="width: 120px;"><?= Yii::t('app', 'Memo') ?></th>
							<th style="width: 120px;"><?= Yii::t('app', 'Debt') ?></th>
							<th><?= Yii::t('app', 'Credit') ?></th>
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
			url: '<?= \yii\helpers\Url::toRoute('/finance/transaksijurnal/index') ?>',
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
			{ 	targets: 3, 
				visible:false,
            },
			{ 	targets: 5, 
				visible:false,
            },
			{ 	targets: 6, 
				class:"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatInteger(data);
                }
            },
			{ 	targets: 7, 
				class:"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatInteger(data);
                }
            },
			
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReportThis(oSettings.sTableId);
			changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"bDestroy": true,
		"rowGroup": {
			dataSrc: 1,
			startRender: function ( rows, group ) {
				var tanggal = rows.data().pluck(3)[0];
				var date = new Date(tanggal);
				tanggal = date.toString('dd/MM/yyyy');
				var memo = rows.data().pluck(4)[0];
                return $('<tr/>')
                    .append( '<td style="background-color:#e7eafe"></td>' )
                    .append( '<td colspan="6" style="font-weight:600; padding:10px; font-size:1.5rem; background-color:#e7eafe">'+tanggal+' - '+memo+'</td>' );
            },
			endRender: null,
		}
    });
}
	
function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/finance/transaksijurnal/printout') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
function changePertanggalLabel(){
	$('#pertanggal-label').html(""+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" to "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
}
function formattingDatatableReportThis(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md');
    $(".tooltips").tooltip({ delay: 50 });
}
</script>