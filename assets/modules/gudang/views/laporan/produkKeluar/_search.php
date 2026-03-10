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
						<div class="col-md-6">
							<?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form, 'sizelabel'=>"4",'sizeelement'=>"8"]) ?>
							<?php echo $form->field($model, 'nomor_produksi')->textInput()->label(Yii::t('app', 'Kode Barang Jadi')); ?>
							<?php echo $form->field($model, 'produk_nama')->textInput()->label(Yii::t('app', 'Nama Produk')); ?>
						</div>
						<div class="col-md-5">
							<?php echo $form->field($model, 'reff_no')->textInput()->label(Yii::t('app', 'Reff No.')); ?>
							<?= $form->field($model, 'cara_keluar')->dropDownList(["SPM Lokal"=>"SPM Lokal",
																					"Export"=>"Export",
																					"Mutasi Ke Produksi"=>"Mutasi Ke Produksi",
																					"Mutasi Kebutuhan Internal"=>"Mutasi Kebutuhan Internal",
																					"Lainnya"=>"Lainnya"],['prompt'=>'All'])
									->label(Yii::t('app', 'Departement')); ?>
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