<?php
/* @var $this yii\web\View */
$this->title = 'Penerimaan Kayu Olahan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\Select2Asset::register($this);
$hiddenTab = ( (\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_STAFF)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_KADEP)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_TALLY) )?"hidden":"";
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Penerimaan Kayu Olahan'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
<!--					<li class="<?= $hiddenTab ?>">
						<a href="<?php // echo \yii\helpers\Url::toRoute("/gudang/penerimaanko/index") ?>"> <?php // echo Yii::t('app', 'Penerimaan Reguler'); ?> </a>
					</li>-->
					<li class="<?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/scanterima") ?>"> <?= Yii::t('app', 'Penerimaan Reguler'); ?> </a>
					</li>
					<li class="<?= $hiddenTab ?>">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/terimarepacking") ?>"> <?= Yii::t('app', 'Penerimaan Hasil Repacking'); ?> </a>
					</li>
                    <li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/gudang/penerimaanko/riwayatpenerimaan") ?>"> <?= Yii::t('app', 'Riwayat Penerimaan'); ?> </a>
					</li>
				</ul>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered form-search">
                            <div class="portlet-title">
                                <div class="tools panel-cari">
                                    <button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
                                    <span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian'); ?></span>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <?php $form = \yii\bootstrap\ActiveForm::begin([
                                    'id' => 'form-search-laporan',
                                    'fieldConfig' => [
                                        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
                                        'labelOptions'=>['class'=>'col-md-3 control-label'],
                                    ],
                                    'enableClientValidation'=>false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Tanggal','model' => $model,'form'=>$form]) ?>
                                            <?php echo $form->field($model, 'produk_id')->dropDownList(\app\models\THasilProduksi::getOptionProdukAvail(),['class'=>'form-control select2','prompt'=>'All'])->label("Produk");?>
                                        </div>
                                        <div class="col-md-5">
                                            <?php echo $form->field($model, 'gudang_id')->dropDownList(app\models\MGudang::getOptionList(),['prompt'=>'All'])->label("Lokasi Gudang"); ?>
                                            <?php echo $form->field($model, 'nomor_produksi')->textInput()->label("Kode Barang Jadi"); ?>
                                        </div>
                                    </div>
                                    <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <div class="row" style="margin-left: -40px; margin-right: -40px;">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered" >
                            <div class="portlet-body" style="margin-left: -5px; margin-right: -5px;">
                                <table class="table table-striped table-bordered table-hover table-laporan" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th style="width: 70px;"><?= Yii::t('app', 'Kode Terima'); ?></th>
                                            <th style="width: 80px;"><?= Yii::t('app', 'Jenis Terima'); ?></th>
                                            <th style="width: 130px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
                                            <th style="width: 120px;"><?= Yii::t('app', 'Kode Produk'); ?></th>
                                            <th style=""><?= Yii::t('app', 'Nama Produk'); ?></th>
                                            <th style="width: 75px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Terima'); ?></th>
                                            <th style="width: 75px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
                                            <th style="width: 50px; "><?= Yii::t('app', 'Gudang'); ?></th>
                                            <th style="width: 40px; "><?= Yii::t('app', 'Qty'); ?></th>
                                            <th style="width: 50px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                            <th style="width: 50px;"><?= Yii::t('app', 'Waktu Terima'); ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if( (\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_STAFF)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_KADEP)||(\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_PPIC_TALLY) ){
    $pagemode = "setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Riwayat Penerimaan Gudang'))."');";
}else{
    $pagemode = "setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Penerimaan Kayu Olahan'))."');";
}
?>
<?php $this->registerJs("
    $('#form-search-laporan').submit(function(){
		dtLaporan();
		return false;
	});
    dtLaporan();
    formconfig(); 
    $('select[name*=\"[produk_id]\"]').select2({
		allowClear: !0,
        placeholder :'All'
	});
    $pagemode
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 190,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/gudang/penerimaanko/Riwayatpenerimaan') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class : 'td-kecil', orderable: false,
                render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'</center>';
                }
            },
            {	targets: 1, class:'td-kecil', },
            {	targets: 2, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					var ret = "";
                    if(data){
                        ret = "Hasil Mutasi";
                    }else{
                        ret = "Reguler";
                    }
					return '<center>'+ret+'</center>';
                }
            },
            {	targets: 3, class:'td-kecil' },
            {	targets: 4, class:'td-kecil' },
            {	targets: 5, class:'td-kecil' },
			{ 	targets: 6, class:'td-kecil', 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 7, class:'td-kecil', 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 8, class:'td-kecil text-align-center', },
			{	targets: 9, class:'td-kecil text-align-center', 
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{	targets: 10, class:'text-align-right td-kecil',
				render: function ( data, type, full, meta ) {
					return formatNumberFixed4(data);
				}
			},
            {	targets: 11, class:'text-align-center td-kecil2',
				render: function ( data, type, full, meta ) {
                    var time = new Date(data);
					time = time.toString('H:m:s');
					return '<center>'+time+'<br>'+full[12]+'</center>';
				}
			},
			{	targets: 12, class:'td-kecil', 
				class:'td-kecil text-align-center',
				render: function ( data, type, full, meta ) {
                    if(full[2]){
                        var ret =  '<center>\n\
                                        <a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')" ><i class="fa fa-eye"></i></a>\n\
                                        <a style="margin-left: -8px;" class="btn btn-xs btn-outline blue-hoki tooltips <?= (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)?"":"hidden"; ?>" data-original-title="Print Label" onclick="printKartuBarang('+full[0]+')"><i class="fa fa-print"></i></a>\n\
                                        <a style="margin-left: -8px;" class="btn btn-xs btn-outline red-flamingo tooltips <?= (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)?"":"hidden"; ?>" data-original-title="Hapus" onclick="openModal(\'<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/deleteTerimaRepacking','id'=>'']) ?>'+full[3]+'&tableid=table-master\',\'modal-delete-record\')"><i class="fa fa-trash-o"></i></a>\n\
                                    </center>';
                    }else{
                        var ret =  '<center>\n\
                                        <a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')" ><i class="fa fa-eye"></i></a>\n\
                                        <a style="margin-left: -8px;" class="btn btn-xs btn-outline blue-hoki tooltips <?= (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)?"":"hidden"; ?>" data-original-title="Print Label" onclick="printKartuBarang('+full[0]+')"><i class="fa fa-print"></i></a>\n\
                                        <a style="margin-left: -8px;" class="btn btn-xs btn-outline red-flamingo tooltips <?= (Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER)?"":"hidden"; ?>" data-original-title="Hapus" onclick="openModal(\'<?php echo \yii\helpers\Url::toRoute(['/sysadmin/managetransaction/stockproduk','id'=>'']) ?>'+full[3]+'&tableid=table-master\',\'modal-delete-record\')"><i class="fa fa-trash-o"></i></a>\n\
                                    </center>';
                    }
					
					return ret;
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
        autoWidth:false,
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
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
function lihatDetail(id){
    window.open('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/index','tbko_id'=>'']); ?>'+id, '_blank');
}
function lihatDetailHasilRepacking(id){
    window.open('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/terimarepacking','tbko_id'=>'']); ?>'+id, '_blank');
}
function printKartuBarang(id){
	window.open("<?= yii\helpers\Url::toRoute('/gudang/penerimaanko/printKartuBarang') ?>?id="+id+"&caraprint=PRINT","",'location=_new, width=1200px, scrollbars=yes');
}
function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/gudang/penerimaanko/riwayatpenerimaanPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>