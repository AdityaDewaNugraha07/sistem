<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Spm Log Tracking'; 
app\assets\DatatableAsset::register($this); 
app\assets\DatepickerAsset::register($this); 
app\assets\InputMaskAsset::register($this); 
app\assets\Select2Asset::register($this);
echo $model->kode;
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?> </h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered" id="main">
			<div id="filter-search"><?= $this->render('_search', ['model' => $model]) ?></div>
			<div id="daftar-spmlogtracking">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar SPM Log Tracking'); ?> :: <span id="periode-label"></span></span>
                    </div>
                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover" id="table-laporan">
                        <thead>
                            <tr>
                                <th style="width: 50px;"><?= Yii::t('app', 'No.'); ?></th>
                                <th style="width: 100px;"><?= Yii::t('app', 'Kode') ?></th>
                                <th style="width: 100px;"><?= Yii::t('app', 'Tanggal') ?></th>
                                <th style="width: 100px;"><?= Yii::t('app', 'Jam') ?></th>
                                <th style="width: 150px;"><?= Yii::t('app', 'NamaTongkang') ?></th>
                                <th style="width: 150px;"><?= Yii::t('app', 'Jenis') ?></th>
                                <th><?= Yii::t('app', 'Lokasi') ?></th>
                            </tr>
                        </thead>
                    </table>
                </div>
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
	$('#".yii\bootstrap\Html::getInputId($model, 'jenis')."').select2({ 
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
			url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/laporan/spmlogtracking') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			}
		},
        columnDefs: [
			{ 	targets: 0, 
                orderable: false,
                width: '5%',
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets: 1, // kode
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+full[1]+'</center>';
                }
            },
            {   targets: 2, // tanggal
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 3, // tanggal
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(full[2]);
					date = date.toString('HH:mm:ss');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 4, // nama_tongkang
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+full[3]+'</center>';
                }
            },
            {   targets: 5, // jenis
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[4];
                }
            },
            {   targets: 6, // lokasi
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[5];
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

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function openDetailTracking(id) {
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/openDetailTracking2','id'=>'']) ?>'+id,'modal-spkShippingTracking','95%');
}

function closeDetail() {
    window.location.href = "spmlogtracking";
}
</script>