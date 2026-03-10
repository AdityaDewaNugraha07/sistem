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
							<?= $form->field($model, 'departement_id')->dropDownList(\app\models\MDepartement::getOptionList(),['prompt'=>'All'])->label(Yii::t('app', 'Departement')); ?>
							<?= $form->field($model, 'tipe')->dropDownList(['REGULER'=>'REGULER', 'PEMBAYARAN LOG ALAM'=>'PEMBAYARAN LOG ALAM', 'DP LOG SENGON'=>'DP LOG SENGON', 'PELUNASAN LOG SENGON'=>'PELUNASAN LOG SENGON', 'PEMBAYARAN ASURANSI LOG SHIPPING'=>'PEMBAYARAN ASURANSI LOG SHIPPING'],['prompt'=>'All'])->label(Yii::t('app', 'Tipe')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'cara_bayar')->dropDownList(['Transfer Bank'=>'Transfer Bank', 'Tunai'=>'Tunai / Cash'],['prompt'=>'All'])->label(Yii::t('app', 'Cara Bayar')); ?>
							<?= $form->field($model, 'status_bayar')->dropDownList(["WAITING"=>"WAITING","PAID"=>"PAID", "UNPAID"=>"UNPAID"],['prompt'=>'All'])->label(Yii::t('app', 'Status Bayar')); ?>
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