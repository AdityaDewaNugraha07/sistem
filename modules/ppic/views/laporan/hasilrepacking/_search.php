<?php

use app\models\MDefaultValue;
use app\models\MGlue;
use app\models\MGrade;
use app\models\MJenisKayu;
use app\models\MKondisiKayu;
use app\models\MProfilKayu;
use app\models\THasilRepacking;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet light bordered form-search">
			<div class="portlet-title">
				<div class="tools panel-cari">
					<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
					<span style=""> &nbsp;<?= Yii::t('app', 'Filter Pencarian') ?></span>
				</div>
                <div class="tools">
                    <form action="<?= Url::toRoute('/apps/toggleLanguage')?>" class="form-inline">
                        <div class="form-group">
                            <label for="language"><?= Yii::t('app', 'Bahasa') ?></label>
                            <select name="language" id="language" class="form-control" onchange="console.log(this.form.submit())">
                                <option value="id-ID" <?= Yii::$app->session->get('language') === 'id-ID' ? 'selected' : '' ?>>Indonesia</option>
                                <option value="en-US" <?= Yii::$app->session->get('language') === 'en-US' ? 'selected' : '' ?>>English</option>
                            </select>
                        </div>
                    </form>
				</div>
			</div>
			<div class="portlet-body">
				<?php $form = ActiveForm::begin([
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
                            <?php /** @var THasilRepacking $model */
                            echo $this->render('@views/apps/form/periodeTanggal', ['label' => Yii::t('app', 'Periode'), 'model' => $model, 'form'=>$form ]) ?>
							<?= $form->field($model, 'hasil_dari')->dropDownList(["Repacking"=>"Repacking","Regrade"=>"Regrade","Restamp"=>"Restamp","Penanganan Barang Retur"=>"Penanganan Barang Retur"],['class'=>'form-control','prompt'=>'All','onchange'=>'setHasildari();'])->label("Hasil Dari"); ?>
                        	<?php echo $form->field($model, 'hasil_dari_retur')->dropDownList(["Regrade"=>"Regrade","Repair"=>"Repair"],['class'=>'form-control','prompt'=>'All', 'style'=>'display: none;'])->label("Keperluan Retur Barang", ['id' => 'label-hasil-dari-retur', 'style' => 'display: none;']); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'jenis_produk')->dropDownList(MDefaultValue::getOptionListProduk('jenis-produk'),['prompt'=>'All', 'onchange'=>'setFilterByProdukGroup()']) ?>
                            <?= $form->field($model, 'jenis_kayu')->dropDownList(MJenisKayu::getOptionList(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'grade')->dropDownList(MGrade::getOptionList(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'glue')->dropDownList(MGlue::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'profil_kayu')->dropDownList(MProfilKayu::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'kondisi_kayu')->dropDownList(MKondisiKayu::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
						</div>
					</div>
					<?php echo $this->render('@views/apps/form/tombolSearch') ?>
				</div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>
<script>
	function setHasildari(){
		$("#<?= yii\bootstrap\Html::getInputId($model, "hasil_dari_retur") ?>").val('');
		var hasil_dari = $("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari") ?>").val();
		$('#table-detail-paletasal tbody').empty();
		if(hasil_dari == 'Penanganan Barang Retur'){
			$("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', '');
			$("#label-hasil-dari-retur").css('display', '');
		} else {
			$("#<?= \yii\helpers\Html::getInputId($model, "hasil_dari_retur") ?>").css('display', 'none');
			$("#label-hasil-dari-retur").css('display', 'none');
		}
	}
</script>