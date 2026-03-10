<?php
/* @var $this yii\web\View */
$this->title = 'Hasil Repacking Belum Diterima Gudang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Hasil Repacking & Penanganan Barang Retur <small>( Pengiriman Hasil Repacking Ke Gudang )</small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">			
                    <li class="active">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/laporan/HasilRepackingblmTerimaGudang") ?>"> <?= Yii::t('app', 'Belum Diterima Gudang'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/laporan/RekapHasilRepackingblmTerimaGudang") ?>"> <?= Yii::t('app', 'Rekap Belum Diterima Gudang'); ?> </a>
                    </li>
                </ul>
                <?= $this->render('_search', ['model' => $model]) ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>Status Peneriman Gudang</h4></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-informasi">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Kode /<br>Tanggal'); ?></th>
                                                    <th><?= Yii::t('app', 'Jenis Produk'); ?></th>
                                                    <th><?= Yii::t('app', 'KBJ / Nama Produk'); ?></th>
                                                    <th></th>
                                                    <th style="width: 50px;"><?= Yii::t('app', 'Qty'); ?></th>
                                                    <th style="width: 60px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                                    <th style="width: 50px; line-height: 1"><?= Yii::t('app', 'Diserahkan<br>Oleh'); ?></th>
                                                    <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Penerimaan<br>Gudang'); ?></th>
                                                    <th></th>
                                                    <th style="width: 60px; line-height: 1"><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
                                                    <th style="width: 50px; line-height: 1"><?= Yii::t('app', 'Diterima<br>Oleh'); ?></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Hasil Repacking Belum Diterima Gudang'))."');
    
    formconfig();
    changePertanggalLabel();
    let dt = $('#table-informasi').DataTable(params());
    $('#form-search-laporan').submit(function(){
        if(dt != null) {
            dt.clear();
            dt.destroy();
        }
        dt = $('#table-informasi').DataTable(params());
        return false;
    });
    
", yii\web\View::POS_READY); ?>
<script>

function params() {
    return {
        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/HasilRepackingblmTerimaGudang') ?>',
            data: {
                dt : 'table-informasi',
                laporan_params : $("#form-search-laporan").serialize()                
            },
//             success: function (res) {console.log(res)},
//             error: function(err) {console.log(err)}, 
        },
        order: [
        // [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
                                        
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return full[12]+'<br><b>'+data+'</b><br>'+date;
                }
            },
            { 	targets: 2, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                                        
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return full[12];
                }    
            },
            { 	targets: 3, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					return '<b>'+data+'</b><br>'+full[4];
                }
            },
            { 	targets: 4, visible:false },
            { 	targets: 5, class:"text-align-center td-kecil" },
            { 	targets: 6, class:"text-align-right td-kecil" },
            { 	targets: 7, class:"text-align-left td-kecil2" },
            { 	targets: 8, class:"text-align-left td-kecil" },
            { 	targets: 9, visible:false },
            { 	targets: 10, class:"text-align-center td-kecil" },
            { 	targets: 11, class:"text-align-left td-kecil2" },
            { 	targets: 12, visible: false },
        ],
        "autoWidth": false,
        "drawCallback": function( oSettings ) {
            formattingDatatableReport(oSettings.sTableId);
            changePertanggalLabel()
            mergeSameValue();
        },
    }
}

function mergeSameValue(){
	var arr = [];
	var coll = [0];
	$("#table-informasi").find('tr').each(function (r, tr) {
		$(this).find('td').each(function (d, td) {
            if ( coll.indexOf(d) !== -1) {
                var $td = $(td);
				var v_dato = $td.html();
				if(typeof arr[d] != 'undefined' && 'dato' in arr[d] && arr[d].dato == v_dato) {
					var rs = arr[d].elem.data('rowspan');
					if(rs == 'undefined' || isNaN(rs)) rs = 1;
					arr[d].elem.data('rowspan', parseInt(rs) + 1).addClass('rowspan-combine');
					$td.addClass('rowspan-remove');
				} else {
					arr[d] = {dato: v_dato, elem: $td};
				};
			}
		});
	});
	$('.rowspan-combine').each(function (r, tr) {
	  var $this = $(this);
	  $this.attr('rowspan', $this.data('rowspan')).css({'vertical-align': 'middle'});
	});
	$('.rowspan-remove').remove();
}

function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/index','pengajuan_repacking_id'=>'']); ?>'+id);
}

function infoKembaligudang(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanrepacking/infoKembaligudang','id'=>'']) ?>'+id,'modal-info','85%');
}
function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/HasilRepackingblmTerimaGudangPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>