<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Retur';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\DatatableAsset::register($this)
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-pengeluaran-kas',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-7">{input} {error}</div>',
        'labelOptions'=>['class'=>'col-md-3 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
.table td, .table th {
    font-size: 13px;
}
</style>

<div class="row" >
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4><?= Yii::t('app', 'Daftar Retur Penjualan Kayu Olahan Lokal'); ?></h4></span>
                                </div>
                                <div class="tools">
									
                                </div>
                            </div>
                            <div class="portlet-body">
								<div class="row">
									<div class="col-md-12">
										<div class="table-scrollable">
											<table class="table table-striped table-bordered table-advance table-hover" id="table-laporan">
												<thead>
													<tr>
														<th style="width: 30px; vertical-align: middle; text-align: center;" >No.</th>
														<th style="width: 80px; text-align: center;"><?= Yii::t('app', 'Kode'); ?></th>
														<th style="width: 70px; text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
														<th style="width: 120px; text-align: center;"><?= Yii::t('app', 'Customer'); ?></th>
														<th style="text-align: center;"><?= Yii::t('app', 'Alasan Retur'); ?></th>
														<th style="width: 100px; line-height: 0.9;"><?= Yii::t('app', 'Petugas<br>Penerima'); ?></th>
														<th style="width: 100px; line-height: 0.9;"><?= Yii::t('app', 'Waktu<br>Terima'); ?></th>
														<th style="width: 100px; line-height: 0.9;"><?= Yii::t('app', 'Diperiksa<br>Security'); ?></th>
														<th style="width: 100px;"><?= Yii::t('app', ''); ?></th>
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
</div>
<div id="pick-panel"></div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
if(isset($_GET['kas_kecil_id'])){
    $pagemode = "";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
	dtTable();
	$(\"#".yii\bootstrap\Html::getInputId($model, 'tanggal')."\").datepicker({
        rtl: App.isRTL(),
        orientation: \"left\",
        autoclose: !0,
        format: \"dd/mm/yyyy\",
        clearBtn:false,
        todayHighlight:true
    });
    $pagemode;
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-laporan').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/gudang/penerimaanretur/index') ?>',data:{dt: 'table-laporan'} },
        order: [
            [0, 'desc']
        ],
		"pageLength": 100,
        columnDefs: [
            { 	targets: 0, 
                orderable: false,
                width: '5%',
				class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
			{ 	targets:1, class:'text-align-center td-kecil', },
			{ 	targets: 2, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 3, class:"td-kecil",},
			{ 	targets: 4, class:"td-kecil",},
			{ 	targets: 5, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					if(data){
						return data;
					}else{
						return '<center>-</center>';
					}
                }
			},
			{ 	targets: 6, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					if(data){
						var date = new Date(data);
						var time = new Date(data);
						date = date.toString('dd/MM/yyyy');
						time = time.toString('H:m:s');
						return '<center>'+date+'<br>'+time+'</center>';
					}else{
						return '<center>-</center>';
					}
                }
			},
			{ 	targets: 7, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					if(data){
						return data;
					}else{
						return '<center>-</center>';
					}
                }
			},
			{ 	targets: 8, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					if(data){
						return '<a class="btn btn-xs green-seagreen" style="font-size: 1.2rem;" onclick="infoRetur('+full[0]+');">'+data+'</a>';
					}else{
						return '<a class="btn btn-xs btn-outline blue" onclick="terimaRetur('+full[0]+')"><i class="fa fa-download"></i> Terima Barang</a>';
					}
                }
			},
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function terimaRetur(retur_produk_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanretur/terimaRetur'])?>?retur_produk_id='+retur_produk_id,'modal-transaksi');
}
function infoRetur(retur_produk_id){
	openModal('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanretur/infoRetur'])?>?retur_produk_id='+retur_produk_id,'modal-info');
}

</script>