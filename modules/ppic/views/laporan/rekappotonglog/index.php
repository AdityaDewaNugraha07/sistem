<?php 
/* @var $this yii\web\View */
$this->title = 'Rekap Pemotongan Log';
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
                                            <?= $form->field($model, 'alokasi')->dropDownList([
                                                                                                    '' => 'All',
                                                                                                    'Sawmill' => 'Sawmill',
                                                                                                    'Plymill' => 'Plymill',
                                                                                                    'Afkir' => 'Afkir',
                                                                                                    'Gudang' => 'Gudang'
                                                                                                ], ['class' => 'form-control', 'onchange'=>'tampilGradingrule()'])?>
                                            <?= $form->field($model, 'grading_rule')->dropDownList(['' => 'All', 'Q1' => 'Q1', 'Q2' => 'Q2','Q3' => 'Q3'], ['class' => 'form-control','id' => 'grading-rule-dropdown'])->label('Grade', ['id' => 'grading-rule-label'])?>
                                        </div>
                                        <div class="col-md-5">
                                            <?php echo $form->field($model, 'kayu_id')->dropDownList(\app\models\MKayu::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Jenis Kayu')); ?>
                                            <?= $form->field($model, 'panjang_baru')->dropDownList(app\models\TPemotonganLogDetailPotong::getOptionListPanjang(), ['prompt' => 'All'])->label('Panjang');?>
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
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Rekap Pemotongan Log '); ?><span id="periode-label" class="font-blue-soft"></span></span>
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
                                    <th><?= Yii::t('app', 'Alokasi'); ?></th>
                                    <th><?= Yii::t('app', 'Grade'); ?></th>
                                    <th><?= Yii::t('app', 'Kayu'); ?></th>
                                    <th><?= Yii::t('app', 'Panjang (m)'); ?></th>
                                    <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" style="text-align:right">Total Per Page:</th>
                                    <th colspan="1" style="text-align:right"></th>
                                </tr>
                                <tr>
                                    <th colspan="4" style="text-align:right;" class="td-kecil">Total All Page:</th>
                                    <th colspan="1" style="text-align:right;" class="td-kecil"></th>
                                </tr>
                            </tfoot>
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
    tampilGradingrule();
    changePertanggalLabel();
", yii\web\View::POS_READY); ?>
<script>
function dtLaporan(){
    var dt_table =  $('#table-laporan').dataTable({
		pageLength: 100,
        ajax: { 
			url: '<?= \yii\helpers\Url::toRoute('/ppic/laporan/rekappotonglog') ?>',
			data:{
				dt: 'table-laporan',
				laporan_params : $("#form-search-laporan").serialize(),
			} 
		},
        order: [
            [0, 'asc'], [1, 'asc'], [2, 'asc']
        ],
        columnDefs: [
			{ 	targets: 0, class:'td-kecil' },
            { 	targets: 1, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
					return data?data:'-';
                }
            },
            { 	targets: 2, class:'td-kecil' },
            { 	targets: 3, class:'td-kecil text-align-right' },
            { 	targets: 4, class:'td-kecil text-align-right' },
            
        ],
		"fnDrawCallback": function( oSettings ) {
			formattingDatatableReport(oSettings.sTableId);
            changePertanggalLabel();
			if(oSettings.aLastSort[0]){
				$('form').find('input[name*="[col]"]').val(oSettings.aLastSort[0].col);
				$('form').find('input[name*="[dir]"]').val(oSettings.aLastSort[0].dir);
			}
		},
        footerCallback: function(row, data, start, end, display) {
			var api = this.api();
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                         i : 0;
            };

			// console.log(api.column(22).data().toArray());

            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $('tr:eq(0) th:eq(1)', api.table().footer() ).html(`${(pageTotal.toFixed(2).toLocaleString())}`); //formatNumberForUser
            $.ajax({
                url: "<?= yii\helpers\Url::toRoute('/ppic/laporan/rekappotonglogTotal') ?>?"+$('#form-search-laporan').serialize(),
                success: res => {
                    $('tr:eq(1) th:eq(1)', api.table().footer() ).html(`${(JSON.parse(res).total.toFixed(2).toLocaleString())}`)  //formatNumberForUser
                }                                                                                                                                                                                                                                                                                                                                                                                                             
            })
		},
		"dom": "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12 dataTables_moreaction'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>", // default data master cis
		"bDestroy": true,
    });
}

function printout(caraprint) {
    window.open("<?= yii\helpers\Url::toRoute('/ppic/laporan/rekappotonglogPrint') ?>?"+$('#form-search-laporan').serialize()+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
}

function changePertanggalLabel(){
	if($('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()){
		$('#periode-label').html("Periode "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_awal');?>').val()+" sd "+$('#<?= yii\bootstrap\Html::getInputId($model, 'tgl_akhir');?>').val());
	}else{
		$('#periode-label').html("");
	}
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
