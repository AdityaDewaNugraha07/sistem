<?php
\app\assets\Select2Asset::register($this);
?>
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
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
							<?php
								$pegawai_id = Yii::$app->user->identity->pegawai_id;
								if(in_array($pegawai_id, app\components\Params::DEFAULT_PEGAWAI_ID_BUDGETING))
								{
									$res = \app\models\MDepartement::find()->where(['active'=>true])->orderBy('created_at ASC')->all(); 
									$data = \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
									echo $form->field($model, 'departement_id')->dropDownList($data,['id'=>'departement', 'prompt'=>'All','onchange'=>'getPeruntukan(this); getAssetPeruntukan(this);'])->label('Departement'); 
								}else{
									$res = \app\models\MDepartement::find()->where(['departement_id'=>Yii::$app->user->identity->pegawai->departement_id])->all();
									$data = \yii\helpers\ArrayHelper::map($res, 'departement_id', 'departement_nama');
									echo $form->field($model, 'departement_id')->dropDownList($data,['id'=>'departement','onchange'=>'getPeruntukan(this); getAssetPeruntukan(this);'])->label('Departement');
								}
							?>
							<?php echo $form->field($model, 'bhp_nm')->textInput()->label(Yii::t('app', 'Nama BHP')); ?>
						</div>
						<div class="col-md-5">
							<?php
                            $target_plan = \app\models\MDefaultValue::find()->where(['type'=>'plan-part-bhp'])->all();
                            $data = \yii\helpers\ArrayHelper::map($target_plan, 'name', 'name'); 
                            echo $form->field($model, 'target_plan')->dropDownList($data,['prompt'=>'All'])->label('Target Plan'); 
                            ?>
							<?php
                            echo $form->field($model, 'target_peruntukan')->dropDownList([],['prompt'=>'All', 'id'=>'target-peruntukan'])->label('Target Peruntukan'); 
                            ?>
							<?= $form->field($model, 'asset_peruntukan')->dropDownList([],['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple','id'=>'asset-peruntukan'])->label(Yii::t('app', 'Asset Peruntukan'));?>
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
<?php 
$this->registerJs("
    $('.select2').select2({
        placeholder: 'Masukkan Nama Asset Peruntukan',
        width: null,
		minimumInputLength: 1,
	});
", yii\web\View::POS_READY); ?>