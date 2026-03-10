<?php 
/* @var $this yii\web\View */
$this->title = 'Penerimaan Log Alam Pelabuhan';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\RepeaterAsset::register($this);
app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE -->
<!-- END PAGE HEADER -->
<!-- BEGIN EXAMPLE TABLE PORTLET -->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <?php /*<ul class="nav nav-tabs">
					<li class="active">
						<a href="<?= \yii\helpers\Url::toRoute("/ppic/laporan/pengeluaranLogAlam/index") ?>"> <?= Yii::t('app', $this->title); ?> </a>
					</li>
				</ul> */?>
                <!-- contoh perubahan yang dilakukan -->
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
                                        <div class="col-md-5">
                                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
                                            <?= $form->field($modDetail, 'fsc')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'true' => 'FSC 100%',
                                                                                                    'false' => 'Non FSC'
                                                                                                ], ['class' => 'form-control'])->label('Status FSC')?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'peruntukan')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'Industri' => 'Industri',
                                                                                                    'Trading' => 'Trading'
                                                                                                ], ['class' => 'form-control', 'onclick'=>'setCustomer();'])?>
                                            <?= $form->field($model, 'lokasi_tujuan')->dropDownList(\app\models\TTerimaLogalam::getOptionLokTujuan(), ['prompt'=>'', 'class' => 'form-control select2','id' => 'customer-dropdown'])->label('Customer', ['id' => 'customer-label'])?>
                                            <?php echo $form->field($model, 'no_dokumen')->textInput()->label(Yii::t('app', 'Nomor Dokumen')); ?>
                                        </div>
                                        <?php echo $this->render('@views/apps/form/tombolSearch') ?>                                        
                                    </div>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-laporan">
                            <thead>
                                <tr>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Kode'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Tanggal'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'No. Truk'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'No. Dokumen'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'PIC UKUR'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Peruntukan'); ?></th>
                                    <th class='td-kecil' colspan='5'><?= Yii::t('app', 'Nomor'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Panjang<br>(cm)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Kode<br>Potong'); ?></th>
                                    <th class='td-kecil' colspan='5'><?= Yii::t('app', 'Diameter (cm)'); ?></th>
                                    <th class='td-kecil' colspan='3'><?= Yii::t('app', 'Cacat (cm)'); ?></th>
                                    <th class='td-kecil' rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)'); ?></th>
                                    <th class='td-kecil' rowspan="2"><?= Yii::t('app', 'Status FSC'); ?></th> <!-- tambah fsc -->
                                </tr>
                                <tr>
                                    <th class='td-kecil'><?= Yii::t('app', 'QRCode'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Lapangan'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Grade'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Batang'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Produksi'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Ujung 1'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Ujung 2'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Pangkal 1'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Pangkal 2'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Rata'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Panjang'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Gubal'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Growong'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->registerJs("
    $('#form-search-laporan').submit(function(){
		dtPenerimaanLogAlam();
		return false;
	});
    formconfig();
    dtPenerimaanLogAlam();
    changePertanggalLabel();
    setCustomer();
    $('select[name*=\"[lokasi_tujuan]\"]').select2({
		allowClear: !0,
		placeholder: 'Pilih Customer',
        width: '100%',
	});
", yii\web\View::POS_READY); ?>
<script>
function dtPenerimaanLogAlam(){
    var dt_table =  $('#table-laporan').dataTable({
        pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/penerimaanLogAlam') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        order: [
            [0, 'desc']
        ],
        autoWidth:false,
        columnDefs: [
            {	targets: 0, class:"text-align-center td-kecil", },
            {	targets: 1, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(full[1]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 2, class:"text-align-center td-kecil", },
            {	targets: 3, class:"text-align-center td-kecil",},
            {	targets: 4, class:"text-align-center td-kecil",},
            {	targets: 5, class:"text-align-center td-kecil",},
            {	targets: 6, class:"text-align-center td-kecil",},
            {	targets: 7, class:"text-align-center td-kecil",},
            {	targets: 8, class:"text-align-center td-kecil",},
            {	targets: 9, class:"text-align-center td-kecil",},
            {	targets: 10, class:"text-align-center td-kecil",},
            {	targets: 11, class:"text-align-center td-kecil",},
            {	targets: 12, class:"text-align-center td-kecil",},
            {	targets: 13, class:"text-align-center td-kecil",},
            {	targets: 14, class:"text-align-center td-kecil",},
            {	targets: 15, class:"text-align-center td-kecil",},
            {	targets: 16, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[19];
                }
            },
            {	targets: 17, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[16];
                }
            },
            {	targets: 18, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[17];
                }
            },
            {	targets: 19, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[18];
                }
            },
            {	targets: 20, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    return full[21];
                }
            },
            {	targets: 21, class:"text-align-center td-kecil",
            },
            {	targets: 22, class:"text-right td-kecil",
            },
            {	targets: 23, class:"text-center td-kecil",
                render: function ( data, type, full, meta ) {
                    if(data){
                        ret = 'FSC 100%';
                    } else {
                        ret = 'Non FSC';
                    }
                    return ret;
                }
            },

        ],
        "bDestroy": true,
        drawCallback: function({sTableId}) {
            formattingDatatableReport(sTableId);
        }
    });
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
}

function printout(caraprint) {
    window.open(`<?= yii\helpers\Url::toRoute('/ppic/laporan/penerimaanLogAlamPrint')?>?caraprint=${caraprint}&${$('#form-search-laporan').serialize()}`, '_blank');
}

function setCustomer(){
    var peruntukan = $('#<?= yii\bootstrap\Html::getInputId($model, 'peruntukan');?>').val();
    var field = document.getElementById('customer-dropdown').parentElement;
    var label = document.getElementById('customer-label');
    if(peruntukan == 'Trading'){
        field.style.display = 'block';
        label.style.display = 'block';
    } else {
        field.style.display = 'none';
        label.style.display = 'none';
    }
}

</script>
