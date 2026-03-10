<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Penyerapan Budget';
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
					<span class="caption-subject hijau bold"><?= Yii::t('app', 'Details Penyerapan Budget'); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
							<th><?= Yii::t('app', 'Departement') ?></th>							
							<th><?= Yii::t('app', 'Peruntukan') ?></th>
							<th><?= Yii::t('app', 'Target') ?></th>
							<th><?= Yii::t('app', 'Periode') ?></th>
							<th><?= Yii::t('app', 'Penerimaan') ?></th>
							<th><?= Yii::t('app', 'Pemakaian') ?></th>
							<th><?= Yii::t('app', 'Prosentase<br>Penyerapan') ?></th>
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
	getPeruntukan();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/logistik/laporan/penyerapanBudgeting') ?>',
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
					return '<center>'+full[1]+'</center>';
                }
            },
			{ 	
                targets: 2, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[2]+'</center>';
                }
            },
			{ 	
                targets: 3, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[3]+'</center>';
                }
            },
			{ 	
                targets: 4, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[4]+'</center>';
                }
            },
			{ 	
                targets: 5, 
                render: function ( data, type, full, meta ) {
					return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[5]))+'</div>';
                }
            },
			{ 	
                targets: 6, 
                render: function ( data, type, full, meta ) {
					return '<div style="text-align: right">'+formatNumberForUser(Math.round(full[6]))+'</div>';
                }
            },
			{ 	
                targets: 7, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[7]+' % </center>';
                }
            },
			
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/logistik/laporan/penyerapanBudgetingPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
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
</script>