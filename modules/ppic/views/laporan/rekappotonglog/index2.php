<?php
/* @var $this yii\web\View */
$this->title = 'Rekap Pemotongan Log';
app\assets\DatatableAsset::register($this);
app\assets\DatepickerAsset::register($this);
\app\assets\InputMaskAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
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
                                        'labelOptions'=>['class'=>'col-md-4 control-label'],
                                    ],
                                    'enableClientValidation'=>false
                                ]); ?>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-5">
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
                                    </div>
                                </div>
                                <?php echo $this->render('@views/apps/form/tombolSearch') ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
                                <?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
                                <?php \yii\bootstrap\ActiveForm::end(); ?>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <div class="row" style="padding-left: 15px; padding-right: 15px;">
                    <div class="col-md-12 table-scrollable" style="padding-left: 0px; padding-right: 0px;">
                        <table class="table table-striped table-bordered table-hover" id="table-rekap">
                            <thead>
                                <tr>
                                    <th><?= Yii::t('app', 'Alokasi'); ?></th>
                                    <th><?= Yii::t('app', 'Grading Rule'); ?></th>
                                    <th><?= Yii::t('app', 'Kayu'); ?></th>
                                    <th><?= Yii::t('app', 'Panjang (cm)'); ?></th>
                                    <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $sql = "SELECT alokasi, grading_rule, m_kayu.kayu_nama, SUM(panjang_baru) AS panjang, SUM(volume_baru) AS vol
                                        FROM t_pemotongan_log_detail_potong
                                        JOIN t_pemotongan_log_detail ON t_pemotongan_log_detail.pemotongan_log_detail_id = t_pemotongan_log_detail_potong.pemotongan_log_detail_id
                                        JOIN m_kayu ON m_kayu.kayu_id = t_pemotongan_log_detail.kayu_id";
                                $where = [];
                                if (isset($_POST['TPemotonganLogDetailPotong']['kayu_id']) && $_POST['TPemotonganLogDetailPotong']['kayu_id'] != "") {
                                    $kayu_id = $_POST['TPemotonganLogDetailPotong']['kayu_id'];
                                    $model->kayu_id = $kayu_id;
                                    $where[] = "t_pemotongan_log_detail.kayu_id = ".$kayu_id;
                                }
                                if (isset($_POST['TPemotonganLogDetailPotong']['alokasi']) && $_POST['TPemotonganLogDetailPotong']['alokasi'] != "") {
                                    $alokasi = $_POST['TPemotonganLogDetailPotong']['alokasi'];
                                    $model->alokasi = $alokasi;
                                    $where[] = "alokasi = '".$alokasi."'";
                                }
                                if (isset($_POST['TPemotonganLogDetailPotong']['grading_rule']) && $_POST['TPemotonganLogDetailPotong']['grading_rule'] != "") {
                                    $grading_rule = $_POST['TPemotonganLogDetailPotong']['grading_rule'];
                                    $model->grading_rule = $grading_rule;
                                    $where[] = "grading_rule = '".$grading_rule."'";
                                }
                                if (isset($_POST['TPemotonganLogDetailPotong']['panjang']) && $_POST['TPemotonganLogDetailPotong']['panjang'] != "") {
                                    $panjang = $_POST['TPemotonganLogDetailPotong']['panjang'];
                                    $model->panjang_baru = $panjang;
                                    $where[] = "panjang_baru = ".$panjang;
                                }
                                if (count($where) > 0) {
                                    $sql .= " WHERE " . implode(' AND ', $where);
                                }
                                $sql .= " GROUP BY alokasi, grading_rule, m_kayu.kayu_nama ORDER BY alokasi, grading_rule, kayu_nama, panjang";
                                $datas = Yii::$app->db->createCommand($sql)->queryAll();
                                if(count($datas) > 0){
                                    $spanalokasi = []; $spangrading = [];
                                    foreach($datas as $i => $data){
                                        $spanalokasi[$data['alokasi']] = isset($spanalokasi[$data['alokasi']]) ? $spanalokasi[$data['alokasi']] + 1 : 1;
                                        $spangrading[$data['grading_rule']] = isset($spangrading[$data['grading_rule']]) ? $spangrading[$data['grading_rule']] + 1 : 1;
                                    }
                                    $printedAlokasi = []; $printedGrading = [];
                                    foreach ($datas as $data) { ?>
                                        <tr>
                                            <td class="td-kecil"><?= $data['alokasi']; ?></td>
                                            <td class="td-kecil"><?= $data['grading_rule']?$data['grading_rule']:'-'; ?></td>
                                            <td class="td-kecil"><?= $data['kayu_nama']; ?></td>
                                            <td class="td-kecil text-align-right"><?= $data['panjang']; ?></td>
                                            <td class="td-kecil text-align-right"><?= $data['vol']; ?></td>
                                        </tr>
                                    <?php }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<?php $this->registerJs("
	$('#form-search-rekap').submit(function(){
		return false;
	});
    tampilGradingrule();
", yii\web\View::POS_READY); ?>
<script>
function tampilGradingrule(){
    var gradingField = document.getElementById('grading-rule-dropdown').parentElement;
    var gradingLabel = document.getElementById('grading-rule-label');
    if($('#<?= yii\bootstrap\Html::getInputId($model, 'alokasi');?>').val() == 'Plymill'){
        gradingField.style.display = 'block';
        gradingLabel.style.display = 'block';
    } else {
        gradingField.style.display = 'none';
        gradingLabel.style.display = 'none';
    }
}

function printout(caraPrint){
	window.open("<?= yii\helpers\Url::toRoute('/ppic/rekap/rekappotonglogPrint') ?>?"+$('#form-search-rekap').serialize()+"&caraprint="+caraPrint,"",'location=_new, width=1200px, scrollbars=yes');
}
</script>