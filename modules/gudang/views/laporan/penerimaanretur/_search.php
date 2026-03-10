<?php
\app\assets\Select2Asset::register($this);
app\assets\DatepickerAsset::register($this);
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
                    'method' => 'get',
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
                            <?= $form->field($model, 'jenis_kayu')->dropDownList(\app\models\MJenisKayu::getOptionList(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'grade')->dropDownList(\app\models\MGrade::getOptionList(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'glue')->dropDownList(\app\models\MGlue::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'profil_kayu')->dropDownList(\app\models\MProfilKayu::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
                            <?= $form->field($model, 'kondisi_kayu')->dropDownList(\app\models\MKondisiKayu::getOptionListNama(),['class'=>'form-control select2','prompt'=>'','multiple'=>'multiple'])?>
						</div>
						<div class="col-md-5">
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form, 'sizelabel'=>"4",'sizeelement'=>"8"]) ?>
                            <?php echo $form->field($model, 'produk_nama')->textInput()->label(Yii::t('app', 'Nama Produk')); ?>
							<?= $form->field($model, 'gudang_id')->dropDownList(\app\models\MGudang::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Gudang')); ?>
							<?= $form->field($model, 'status')->dropDownList(['BELUM DITERIMA'=>'BELUM DITERIMA', 'SUDAH DITERIMA'=>'SUDAH DITERIMA'],['prompt'=>'All'])->label(Yii::t('app', 'Status')); ?>
							<?php //echo $form->field($model, 'per_tanggal',[
											// 'template'=>'{label}<div class="col-md-6"><div class="input-group input-small date date-picker bs-datetime" data-date-end-date="+0d">{input} <span class="input-group-addon">
											// 			 <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
											// 			 {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
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
<?php 
$this->registerJs("
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    $('.select2').select2({
        allowClear: !0,
        placeholder: 'Pilih Data',
        width: null 
	});
	formconfig();
", yii\web\View::POS_READY); ?>