<?php
/* @var $this yii\web\View */
$this->title = 'Laporan Loglist'; 
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
			<div id="daftar-loglist">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>
                        <span class="caption-subject hijau bold"><?= Yii::t('app', 'Daftar Loglist '); ?> <span id="periode-label"></span></span>
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
                                <th><?= Yii::t('app', 'Tanggal') ?></th>
                                <th><?= Yii::t('app', 'Kode Loglist') ?></th>
                                <th><?= Yii::t('app', 'No. Kontrak') ?></th>
                                <th><?= Yii::t('app', 'Tongkang') ?></th>
                                <th><?= Yii::t('app', 'Lokasi Muat') ?></th>
                                <th><?= Yii::t('app', 'Suplier') ?></th>
                                <th><?= Yii::t('app', 'Area') ?></th>
                                <th></th>
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
	$('#".yii\bootstrap\Html::getInputId($model, 'loglist_id')."').select2({ 
		allowClear: !0, 
		placeholder: '', 
		width: null, 
	});
    $('#".yii\bootstrap\Html::getInputId($model, 'suplier_id')."').select2({ 
		allowClear: !0, 
		placeholder: 'Pilih Suplier Log Alam', 
		width: null, 
	});
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Laporan Loglist'))."')
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 20,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/laporan/loglist') ?>',
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
			{ 	targets: 1,
                className: 'td-kecil',
                render: function ( data, type, full, meta ) {
					var date1 = new Date(full[0]);
					date1 = date1.toString('dd/MM/yyyy');
					return '<center>'+date1+'</center>';
                }
            },
            {   targets: 2,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[1];
                }
            },
            {   targets: 3,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[2];
                }
            },
            {   targets: 4,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[3];
                }
            },
            {   targets: 5,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[4];
                }
            },
            {   targets: 6,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[5];
                }
            },
            {   targets: 7,
                className: 'td-kecil',
                render: function (data, type, full, meta) {
                    return full[6];
                }
            },
            {   targets: 8,
                className: 'td-kecil text-center',
                render: function (data, type, full, meta) {
                    return '<a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[7]+')"><i class="fa fa-eye"></i></a>';
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
	window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/laporan/biayaGraderPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}
function lihatDetail(loglist_id) {
    $('#filter-search').hide();
    $('#daftar-loglist').hide();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/laporan/lihatDetail']); ?>',
        type   : 'POST',
        data   : {loglist_id:loglist_id},
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
    window.location.href = "loglist";
}

</script>