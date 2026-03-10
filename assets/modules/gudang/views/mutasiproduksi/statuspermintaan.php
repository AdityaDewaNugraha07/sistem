<?php
/* @var $this yii\web\View */
$this->title = 'Mutasi Produk';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
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
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasigudang/index") ?>"> <?= Yii::t('app', 'Mutasi Gudang'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasikeluar/index") ?>"> <?= Yii::t('app', 'Mutasi Keluar'); ?> </a>
					</li>
					<li class="">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasiproduksi/index") ?>"> <?= Yii::t('app', 'Mutasi Ke Produksi'); ?> </a>
					</li>
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/mutasiproduksi/statuspermintaanbarangjadi") ?>"> <?= Yii::t('app', 'Permintaan Barang Jadi'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4>
										Status Permintaan Barang Jadi Dari PPIC
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
                                                    <th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Kode /<br>Tanggal'); ?></th>
                                                    <th></th>
                                                    <th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Keperluan /<br>Approval'); ?></th>
                                                    <th></th>
                                                    <th><?= Yii::t('app', 'Produk / Dimensi'); ?></th>
                                                    <th style="width: 50px;"><?= Yii::t('app', 'Permintaan<br>Mutasi Palet'); ?></th>
                                                    <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Palet Dimutasi<br>Keluar Gudang'); ?></th>
                                                    <th style="width: 110px; line-height: 1"><?= Yii::t('app', 'Palet Diterima<br>Oleh PPIC'); ?></th>
                                                    <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Palet Kembali<br>Ke Gudang'); ?></th>
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
	setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Mutasi'))."');
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-informasi').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pengajuanrepacking/status') ?>',data:{dt: 'table-informasi'} },
        order: [
//            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"text-align-left td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return '<b>'+data+'</b><br>'+date;
                }
            },
			{ 	targets: 2, visible:false, },
            { 	targets: 3, class:"text-align-center td-kecil", 
                render: function ( data, type, full, meta ) {
					var status = full[4];
					if(status=="APPROVED"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="REJECTED"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
                    var ret = "<u>"+data+"</u><br><span style='font-size:1rem;'><b>"+status+"</b>";
					return ret;
                }
            },
            { 	targets: 4, visible:false, },
            { 	targets: 5, class:"text-align-left td-kecil", },
            { 	targets: 6, class:"text-align-center td-kecil", },
            { 	targets: 7, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    data = $.parseJSON(data);
					var ret = '';
                    if(data){
                        $(data).each(function(key,val){
                            ret += "<a class='fontsize-0-9' onclick='infoPalet(\""+val.nomor_produksi+"\")'>"+(key+1)+".&nbsp; "+val.nomor_produksi+"</a><br>";
						});
                    }else {
                        ret = '<center><label class="label label-default" style="font-size:1rem;">WAITING</label></center>';
                    }
                    return ret;
                }
            },
            { 	targets: 8, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                    data = $.parseJSON(data);
					var ret = '';
                    if(data){
                        $(data).each(function(key,val){
							ret += "<a class='fontsize-0-9' onclick='infoPalet(\""+val.nomor_produksi+"\")'>"+(key+1)+".&nbsp; "+val.nomor_produksi+"</a><br>";
						});
                    }else {
                        ret = '<center><label class="label label-default" style="font-size:1rem;">WAITING</label></center>';
                    }
                    return ret;
                }
            },
            {	targets: 9, class: 'text-align-left td-kecil',
				render: function ( data, type, full, meta ) {
					data = $.parseJSON(data);
					var ret = "";
					if(data){
						$(data).each(function(key,val){
							ret += "<a class='fontsize-0-9 font-green-dark' onclick='infoKembaligudang("+val.hasil_repacking_id+")'>"+(key+1)+".&nbsp; "+val.nomor_produksi+"</a><br>";
						});
					}else{
                        ret = '-';
                    }
					return ret;
                }
			},
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
	var coll = [0,1,6];
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