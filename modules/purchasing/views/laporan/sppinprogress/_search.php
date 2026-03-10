<?php
/** @var TSppDetail $model */

use app\models\MDefaultValue;
use app\models\MSuplier;
use app\models\TSppDetail;
use yii\bootstrap\ActiveForm;

?>
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered form-search">
			<div class="portlet-title">
				<div class="tools panel-cari">
					<button href="javascript:;" class="collapse btn btn-icon-only btn-default fa fa-search tooltips pull-left"></button>
					<span style=""> <?= Yii::t('app', '&nbsp;Filter Pencarian') ?></span>
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
                            <?= $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode Penerimaan','model' => $model,'form'=>$form]) ?>
							<?= $form->field($model, 'bhp_nm')->textInput()->label(Yii::t('app', 'Nama BHP')) ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'bhp_group')->dropDownList(MDefaultValue::getOptionList('group-bahan-pembantu'),['prompt'=>'All'])->label(Yii::t('app', 'Kel. BHP')) ?>
                            <?= $form->field($model, 'suplier_id')->dropDownList(MSuplier::getOptionListPo(), ['prompt' => '-- Cari Supplier --', 'class' => 'select2'])?>
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