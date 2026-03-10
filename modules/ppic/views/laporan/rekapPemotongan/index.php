<?php 
/* @var $this yii\web\View */
$this->title = 'Laporan Pemotongan Log';
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
                                            <?= $form->field($model, 'kayu_id')->dropDownList(app\models\MKayu::getOptionList(), ['prompt' => 'All'])->label('Jenis Kayu');?>
                                            <?php echo $form->field($model, 'nomor')->textInput()->label(Yii::t('app', 'Nomor')); ?>
                                        </div>
                                        <div class="col-md-5">
                                            <?= $form->field($model, 'peruntukan')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'Industri' => 'Industri',
                                                                                                    'Trading' => 'Trading'
                                                                                                ], ['class' => 'form-control'])?>
                                            <?= $form->field($model, 'alokasi')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'Sawmill' => 'Sawmill',
                                                                                                    'Plymill' => 'Plymill',
                                                                                                    'Afkir' => 'Afkir',
                                                                                                    'Gudang' => 'Gudang'
                                                                                                ], ['class' => 'form-control', 'onchange'=>'tampilGradingrule()'])?>
                                            <?= $form->field($model, 'grading_rule')->dropDownList(['' => 'All', 'Q1' => 'Q1', 'Q2' => 'Q2','Q3' => 'Q3'], ['class' => 'form-control','id' => 'grading-rule-dropdown'])->label('Grade', ['id' => 'grading-rule-label'])?>
                                            <?= $form->field($model, 'panjang')->dropDownList(app\models\TPemotonganLogDetailPotong::getOptionListPanjang(), ['prompt' => 'All'])->label('Panjang');?>
                                        </div>
                                        <?php echo $this->render('@views/apps/form/tombolSearch') ?>                                        
                                    </div>
                                </div>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Laporan Pemotongan Log '); ?><span id="periode-label" class="font-blue-soft"></span></span>
                                </div>
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
                                    <th class='td-kecil' rowspan='3'><?= Yii::t('app', 'Kode'); ?></th>
                                    <th class='td-kecil' rowspan='3'><?= Yii::t('app', 'Peruntukan'); ?></th>
                                    <th class='td-kecil' rowspan='3'><?= Yii::t('app', 'Nomor'); ?></th>
                                    <th class='td-kecil' rowspan='3'><?= Yii::t('app', 'Tanggal'); ?></th>
                                    <th class='td-kecil' rowspan='3'><?= Yii::t('app', 'Petugas'); ?></th>
                                    <th class='td-kecil' colspan='6'><?= Yii::t('app', 'Asal Kayu Semula'); ?></th>
                                    <th class='td-kecil' colspan='15'><?= Yii::t('app', 'Dipotong Menjadi'); ?></th>
                                </tr>
                                <tr>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Jenis<br>Kayu'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'QRCode <br> No. Lapangan'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'P <br>(m)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', '&#8709; <br>(cm)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'V <br>(m<sup>3</sup>)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Reduksi'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Jml<br>Potong'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'QRCode Baru<br> No. Lapangan'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'P <br>(m)'); ?></th>
                                    <th class='td-kecil' colspan="4"><?= Yii::t('app', 'Diameter (cm)'); ?></th>
                                    <th class='td-kecil' colspan="3"><?= Yii::t('app', 'Cacat (cm)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Reduksi'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'V <br>(m<sup>3</sup>)'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Alokasi'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Gading<br>Rule'); ?></th>
                                    <th class='td-kecil' rowspan='2'><?= Yii::t('app', 'Cut'); ?></th>
                                </tr>
                                <tr>
                                    <th class='td-kecil'><?= Yii::t('app', 'U1'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'U2'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'P1'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'P2'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'P'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Gb'); ?></th>
                                    <th class='td-kecil'><?= Yii::t('app', 'Gr'); ?></th>
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
		dtLaporan();
		return false;
	});
    formconfig();
    dtLaporan();
    changePertanggalLabel();
    tampilGradingrule();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/rekapPemotongan') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        columnDefs: [
			{ 	targets: 0, class:'td-kecil' },
            { 	targets: 1, class:'text-align-center td-kecil' },
            { 	targets: 2, class:'text-align-center td-kecil' },
			{ 	targets: 3, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 4, class:'text-align-center td-kecil' },
            { 	targets: 5, class:'text-align-center td-kecil' },
            { 	targets: 6, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
					return data + '<br>' + full[26];
                }
            },
            { 	targets: 7, class:'text-align-right td-kecil' },
            { 	targets: 8, class:'text-align-right td-kecil' },
            { 	targets: 9, class:'text-align-right td-kecil' },
            { 	targets: 10, class:'text-align-center td-kecil', visible: false },
            { 	targets: 11, class:'text-align-right td-kecil' },
            { 	targets: 12, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					return data + '<br>' + full[25];
                }
            },
            { 	targets: 13, class:'text-align-right td-kecil' },
            { 	targets: 14, class:'text-align-right td-kecil' },
            { 	targets: 15, class:'text-align-right td-kecil' },
            { 	targets: 16, class:'text-align-right td-kecil' },
            { 	targets: 17, class:'text-align-right td-kecil' },
            { 	targets: 18, class:'text-align-right td-kecil' },
            { 	targets: 19, class:'text-align-right td-kecil' },
            { 	targets: 20, class:'text-align-right td-kecil' },
            { 	targets: 21, class:'text-align-center td-kecil', visible: false  },
            { 	targets: 22, class:'text-align-right td-kecil' },
            { 	targets: 22, class:'text-align-right td-kecil' },
            { 	targets: 23, class:'text-align-center td-kecil' },
            { 	targets: 24, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					return data?data:'-';
                }
            },
            { 	targets: 25, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[27]){
                        var ret = '<input type="checkbox" disabled checked/>';
                    } else {
                        var ret = '<input type="checkbox" disabled/>';
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
		order:[[1, 'desc'],[6, 'asc'], [12, 'asc']],
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

function printout(caraprint) {
    window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/rekapPemotonganPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}

function tampilGradingrule(){
    var gradingField = document.getElementById('grading-rule-dropdown').parentElement;
    var gradingLabel = document.getElementById('grading-rule-label');
    $('#grading-rule-dropdown').empty();
    if($('#<?= yii\bootstrap\Html::getInputId($model, 'alokasi');?>').val() == 'Plymill'){
        $('#grading-rule-dropdown').append(new Option('All', ''));
        $('#grading-rule-dropdown').append(new Option('Q1', 'Q1'));
        $('#grading-rule-dropdown').append(new Option('Q2', 'Q2'));
        $('#grading-rule-dropdown').append(new Option('Q3', 'Q3'));
        gradingField.style.display = 'block';
        gradingLabel.style.display = 'block';
    } else if($('#<?= yii\bootstrap\Html::getInputId($model, 'alokasi');?>').val() == 'Sawmill'){
        $('#grading-rule-dropdown').append(new Option('All', ''));
        $('#grading-rule-dropdown').append(new Option('Standard', 'Standard'));
        $('#grading-rule-dropdown').append(new Option('Tanduk', 'Tanduk'));
        gradingField.style.display = 'block';
        gradingLabel.style.display = 'block';
    } else {
        $('#grading-rule-dropdown').append(new Option('All', ''));
        gradingField.style.display = 'none';
        gradingLabel.style.display = 'none';
    }
}

/**function tampilGradingrule(){
    var gradingField = document.getElementById('grading-rule-dropdown').parentElement;
    var gradingLabel = document.getElementById('grading-rule-label');
    if($('#<?= yii\bootstrap\Html::getInputId($model, 'alokasi');?>').val() == 'Plymill'){
        gradingField.style.display = 'block';
        gradingLabel.style.display = 'block';
    } else {
        gradingField.style.display = 'none';
        gradingLabel.style.display = 'none';
    }
}*/
</script>
