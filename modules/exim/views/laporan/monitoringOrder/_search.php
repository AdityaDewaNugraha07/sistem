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
							<?= $form->field($model, 'cust_id')->dropDownList(\app\models\MCustomer::getOptionListExport(),['prompt'=>'All'])->label("Buyer"); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'year')->dropDownList(['2018'=>'2018','2019'=>'2019','2020'=>'2020','2021'=>'2021'],['prompt'=>'All'])->label("Years"); ?>
						</div>
					</div>
					<div class="row" style="margin-top: -45px; margin-right: -30px;">
						<div class="col-md-1 pull-right" style="position: relative;">
							<?php echo \yii\helpers\Html::button( Yii::t('app', 'Search'),[
								'class'=>'btn hijau btn-outline ciptana-spin-btn pull-right',
								'type'=>'button', 'onclick'=>'search()',
								'name'=>'search-laporan',
								]);
							?>
						</div>
					</div>
				</div>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[col]'); ?>
				<?php echo yii\bootstrap\Html::hiddenInput('sort[dir]'); ?>
				<?php \yii\bootstrap\ActiveForm::end(); ?>
			</div>
		</div>
		<!-- END EXAMPLE TABLE PORTLET-->
	</div>
</div>