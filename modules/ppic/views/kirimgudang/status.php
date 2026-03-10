<?php
/* @var $this yii\web\View */
$this->title = 'Kirim Gudang';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> Kirim Gudang <small>( Pengiriman Barang Hasil Produksi Ke Gudang )</small></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-5 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
					<li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/kirimgudang/index") ?>"> <?= Yii::t('app', 'Scan Barang Pengiriman'); ?> </a>
                    </li>
                    <li class="active">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/kirimgudang/statusPengiriman") ?>"> <?= Yii::t('app', 'Status Pengiriman'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/kirimgudang/BlmditerimaGudang") ?>"> <?= Yii::t('app', 'Pengiriman Belum Diterima Gudang'); ?> </a>
                    </li>
                    <li class="">
                        <a href="<?= \yii\helpers\Url::toRoute("/ppic/kirimgudang/Rekap") ?>"> <?= Yii::t('app', 'Rekap Belum Diterima Gudang'); ?> </a>
                    </li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										Status Pengiriman ke Gudang
									</h4></span>
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
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php $this->registerJs(" 
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Kirim Gudang'))."');
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-informasi').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/kirimgudang/statusPengiriman') ?>',data:{dt: 'table-informasi'} },
        order: [
//            [0, 'desc']
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
            { 	targets: 4, visible:false, },
            { 	targets: 5, class:"text-align-center td-kecil", },
            { 	targets: 6, class:"text-align-right td-kecil", },
            { 	targets: 7, class:"text-align-left td-kecil2", },
            { 	targets: 8, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
					var ret = '';
                    if (full[13] == "REJECTED") {
                        var xxx = JSON.parse(full[14]);
                        var by = xxx[0].by;
                        var kirim_gudang_detail_id = full[0];
                        var emangPekok = function () {
                            var yoKoweKuwi = null;
                            $.ajax({
                                async  : false,
                                url    : '<?= \yii\helpers\Url::toRoute(['/ppic/kirimgudang/golekiJenengSingNgrejekSu']); ?>',
                                type   : 'POST',
                                data   : {kirim_gudang_detail_id:kirim_gudang_detail_id},
                                success: function (data) {
                                    yoKoweKuwi = data;
                                },
                                error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
                            });
                            return yoKoweKuwi;
                        }();
                        var koweEmangPekok = emangPekok;
                        var at = xxx[0].at;
                        var reason = xxx[0].reason;
                        ret = "<div class='text-center'><span class='font-red-flamingo' style='font-size:1rem;'>REJECTED</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>by : "+koweEmangPekok+"</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>at : "+formatDateTimeForUser(at)+"</span>"
                                    +"<br><span class='font-red-flamingo' style='font-size:0.8rem;'>reason : "+reason+"+</span></div>";
                    } else {
                        if(data){
                            var date = new Date(full[9]);
                            date = date.toString('dd/MM/yyyy H:i:s');
                            ret = '<center><span class="font-green-soft" style="font-size:1rem;">DITERIMA</span>\n\
                                    <br><span class="font-green-soft" style="font-size:0.8rem;">'+date+'</span></center>';
                        }else {
                            ret = '<center><label class="label label-default" style="font-size:1rem;">WAITING</label></center>';
                        }

                    }
                    return ret;
                }
            },
            { 	targets: 9, visible:false, },
            { 	targets: 10, class:"text-align-center td-kecil", },
            { 	targets: 11, class:"text-align-left td-kecil2", },
            { 	targets: 12, visible: false },
        ],
		"autoWidth": false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        "fnDrawCallback": function( oSettings ) {
            mergeSameValue();
        },
    });
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
</script>