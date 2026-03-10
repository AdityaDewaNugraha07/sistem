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
							<?= $form->field($model, 'produk_group')->dropDownList(\app\models\MDefaultValue::getOptionList("jenis-produk"),['prompt'=>'All','onchange'=>'setFilterByProdukGroup()'])->label(Yii::t('app', 'Jenis Produk')); ?>
							<?php echo $form->field($model, 'jenis_kayu')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Jenis Kayu')); ?>
							<?php echo $form->field($model, 'grade')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Grade')); ?>
							<?php echo $form->field($model, 'glue')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Glue')); ?>
							<?php echo $form->field($model, 'profil_kayu')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Profil Kayu')); ?>
							<?php echo $form->field($model, 'kondisi_kayu')->dropDownList([],['prompt'=>'All'])->label(Yii::t('app', 'Kondisi Kayu')); ?>
						</div>
						<div class="col-md-5">
							<?php echo $form->field($model, 'nomor_produksi')->textInput()->label(Yii::t('app', 'KBJ')); ?>
							<?php echo $form->field($model, 'produk_nama')->textInput()->label(Yii::t('app', 'Nama Produk')); ?>
							<?= $form->field($model, 'gudang_id')->dropDownList(\app\models\MGudang::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Gudang')); ?>
							<?= $form->field($model, 'per_tanggal',[
											'template'=>'{label}<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime"  data-date-end-date="+0d">{input} <span class="input-group-addon">
														 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
														 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
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