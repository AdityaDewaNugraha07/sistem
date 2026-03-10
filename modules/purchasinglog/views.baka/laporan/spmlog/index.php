<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Spm Log'; 
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
		<div class="portlet light bordered" id="main">
			<div id="filter-search"><?= $this->render('_search', ['model' => $model]) ?></div>
			<div id="daftar-spmlog">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar SPM Log '); ?> :: <span id="periode-label"></span></span>
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
                                <th><?= Yii::t('app', 'NamaTongkang') ?></th>
                                <th><?= Yii::t('app', 'ETD') ?></th>
                                <th><?= Yii::t('app', 'ETA Logpond') ?></th>
                                <th><?= Yii::t('app', 'ETA') ?></th>
                                <th><?= Yii::t('app', 'Lokasi Muat') ?></th>
                                <th><?= Yii::t('app', 'Estimasi<br>Total<br>Batang') ?></th>
                                <th><?= Yii::t('app', 'Estimasi<br>Total<br>Volume') ?></th>
                                <th><?= Yii::t('app', 'Asuransi') ?></th>
                                <th><?= Yii::t('app', 'PIC Shipping') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th>Detail</th>
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
	$('#".yii\bootstrap\Html::getInputId($model, 'spk_shipping_id')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
    $('#".yii\bootstrap\Html::getInputId($model, 'pic_shipping')."').select2({ 
		allowClear: !0, 
		placeholder: 'Pilih PIC', 
		width: null, 
	});
    $('#".yii\bootstrap\Html::getInputId($model, 'status')."').select2({ 
		allowClear: !0, 
		placeholder: 'Pilih Status', 
		width: null, 
	});
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/laporan/spmlog') ?>',
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
            {   targets: 3, // nama_tongkang
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+full[3]+'</center>';
                }
            },
            {   targets: 4, // etd
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(full[4]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 5, // eta_logpond
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(full[5]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 6, // eta
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(full[6]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 7, // lokasi_muat
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[7];
                }
            },
            {   targets: 8, // estimasi_total_batang
                className: 'td-kecil text-right',
                render: function (data, type, full, meta) {
                    return full[8];
                }
            },
            {   targets: 9, // estimasi_total_m3
                className: 'td-kecil text-right',
                render: function (data, type, full, meta) {
                    return formatNumberForUser(full[9]);
                }
            },
            {   targets: 10, // asuransi
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[10];
                }
            },
            {   targets: 11, // pic_shipping
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[11];
                }
            },
            {   targets: 12, // keterangan
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[13];
                }
            },
            {   targets: 13, // status
                className: 'td-kecil text-center',
                render: function (data, type, full, meta) {
                    return '<a class="btn btn-xs white-gallery btn-outline" onclick="openKeputusanPembelianlog('+full[0]+')"><i class="fa fa-eye"></i></a>';
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/spmlogsPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function lihatDetail(spk_shipping_id) {
    $('#filter-search').hide();
    $('#daftar-spmlog').hide();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/lihatDetail']); ?>',
        type   : 'POST',
        data   : {spk_shipping_id:spk_shipping_id},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#main').fadeIn(200,function(){

                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function closeDetail() {
    window.location.href = "spmlog";
}

function openKeputusanPembelianlog(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/openKeputusanPembelianlog','id'=>'']) ?>'+id,'modal-KeputusanPembelianlog','95%');
}

function openDetailKeputusanPembelianlog(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/openDetailKeputusanPembelianlog','id'=>'']) ?>'+id,'modal-madul','80%');
}

function openDetailTracking(id) {
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/openDetailTracking1','id'=>'']) ?>'+id,'modal-madul','80%');
}
</script>