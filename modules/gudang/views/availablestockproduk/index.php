<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Available Stock';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Available Stock'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered">
			<?= $this->render('_search', ['model' => $model]) ?>
			<div class="portlet-body">
				<table class="table table-striped table-bordered table-hover" id="table-laporan">
					<thead>
						<tr>
                            <th>No.</th>
                            <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                            <th><?= Yii::t('app', 'Kode Produk') ?></th>
                            <th><?= Yii::t('app', 'Nama Produk') ?></th>
                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>Palet') ?></th>
                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>Qty<br>(Pcs)') ?></th>
                            <th></th>
                            <th style="line-height: 1"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
                            <th style="width: 50px;"></th>
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
    setFilterByProdukGroup();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
        pageLength: 100,
        ajax: { 
			url: '<?= Url::toRoute('/gudang/availablestockproduk/index') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			/*{ 	targets: 0, orderable: false, width: '5%', class: 'td-kecil',
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            {	targets: 1, class:'td-kecil', },
            {	targets: 2, class:'td-kecil', },
            {	targets: 3, class:'td-kecil',},
            {	targets: 4, class:'td-kecil text-align-right', searchable: false,
				render: function ( data, type, full, meta ) {
                    return full[16];
                }
            },
            {	targets: 5, class:'td-kecil text-align-right', searchable: false,
				render: function ( data, type, full, meta ) {
                    return full[7];
                }
			},
			{	targets: 6, class:'td-kecil', visible: false, searchable: false},
            {	targets: 7, class:'td-kecil text-align-right', searchable: false,
				render: function ( data, type, full, meta ) {
                    return formatNumberFixed4(full[9]);
                }            
            },
            {	targets: 8, class:'td-kecil', 
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"produklistAS('+full[0]+',\''+full[8]+'\')\"><i class="fa fa-info-circle"></i> Detail</a></center>';
                }
            },*/
            { 	targets: 0, orderable: false, width: '5%', class: 'td-kecil',
                render: function ( data, type, full, meta ) {
				return '<center>'+(meta.row+1)+'</center>';
                }
            },
            {	targets: 1, class:'td-kecil', },
            {	targets: 2, class:'td-kecil', visible: false},
            {	targets: 3, class:'td-kecil',},
            {	targets: 4, class:'td-kecil text-right',
                render: function ( data, type, full, meta ) {
                    return formatNumber(full[5]);
                }
            },
            {	targets: 5, class:'td-kecil text-right',
                render: function ( data, type, full, meta ) {
                    return formatNumber(full[6]);
                }
            },
            {	targets: 6, class:'td-kecil', visible: false,},
            {	targets: 7, class:'td-kecil text-right',
                render: function ( data, type, full, meta ) {
                    return formatNumberFixed4(full[8]);
                }
            },
            {	targets: 8, class:'td-kecil',
                orderable: false,
                width: '5%',
                render: function ( data, type, full, meta ) {
                    return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"produklistAS('+full[0]+',\''+full[7]+'\',\''+full[5]+'\')\"><i class="fa fa-info-circle"></i> Detail</a></center>';
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
        order: [
            [0, 'desc']
        ],
        "dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
		"autoWidth":false,

    });
}

function info(id){
    openModal('<?= Url::toRoute(['/gudang/laporan/stockPaletDetail','id'=>'']) ?>'+id,'modal-madul','80%');
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/gudang/availablestockproduk/stockPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function setFilterByProdukGroup(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:none");
	$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:none");
	setDropdownJenisKayu(function(){
		setDropdownGrade(function(){
			setDropdownGlue(function(){
				setDropdownProfilKayu(function(){
					setDropdownKondisiKayu(function(){
						if(callback){ callback(); }
					});
				});
			});
		});
	});
	if(jenis_produk == "Plywood" || jenis_produk == "Lamineboard" || jenis_produk == "Platform"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Sawntimber"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Moulding"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
		$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").parents(".form-group").attr("style","display:");
	}
	if(jenis_produk == "Veneer"){
		$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").parents(".form-group").attr("style","display:");
	}
}

function setDropdownJenisKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html("");
	$.ajax({
		url    : '<?= Url::toRoute(['/ppic/produk/setDropdownJenisKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'jenis_kayu') ?>").html(data.html);
				//$("#<?= yii\bootstrap\Html::getInputId($model, "jenis_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownGrade(callback=null){
    var jenis_produk = $('#<?= \yii\bootstrap\Html::getInputId($model, 'produk_group') ?>').val();
	$.ajax({
		url    : '<?= Url::toRoute(['/ppic/produk/setDropdownGrade']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'grade') ?>").html(data.html);
				//$("#<?= yii\bootstrap\Html::getInputId($model, "grade") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownGlue(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html("");
	$.ajax({
		url    : '<?= Url::toRoute(['/ppic/produk/setDropdownGlue']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'glue') ?>").html(data.html);
				//$("#<?= yii\bootstrap\Html::getInputId($model, "glue") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownProfilKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html("");
	$.ajax({
		url    : '<?= Url::toRoute(['/ppic/produk/setDropdownProfilKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'profil_kayu') ?>").html(data.html);
				//$("#<?= yii\bootstrap\Html::getInputId($model, "profil_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function setDropdownKondisiKayu(callback=null){
	var jenis_produk = $("#<?= yii\bootstrap\Html::getInputId($model, "produk_group") ?>").val();
	$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html("");
	$.ajax({
		url    : '<?= Url::toRoute(['/ppic/produk/setDropdownKondisiKayu']); ?>',
		type   : 'POST',
		data   : {jenis_produk:jenis_produk},
		success: function (data) {
			if(data.html){
				$("#<?= \yii\bootstrap\Html::getInputId($model, 'kondisi_kayu') ?>").html(data.html);
				//$("#<?= yii\bootstrap\Html::getInputId($model, "kondisi_kayu") ?>").prepend('<option value="" selected="selected">All</option>');
			}
			if(callback){ callback(); }
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
	});
}

function produklistAS(produk_id,satuan_kecil,gudang_nm){
	openModal('<?= Url::toRoute(['/gudang/availablestockproduk/produklist','produk_id'=>'']) ?>'+produk_id+'&satuan_kecil='+satuan_kecil+'&gudang_nm='+gudang_nm,'modal-produklist','90%');
}

</script>