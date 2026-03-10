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
						<div class="col-md-4">
                            <?php echo $this->render('@views/apps/form/periodeTanggal', ['label'=>'Periode','model' => $model,'form'=>$form]) ?>
						</div>
                        <label class="col-md-2 control-label"></label>
                        <div class="col-md-4">
                        <?= $form->field($model, 'suplier_id')->dropDownList(\app\models\MSuplier::getOptionListBHP(),['prompt'=>'All','class'=>'form-control select2'])->label(Yii::t('app', 'Supplier')); ?>
                        </div>
						<div class="col-md-2">
						    <button type="submit" class="btn hijau btn-outline ciptana-spin-btn pull-right ladda-button" name="search-laporan" data-style="zoom-in"><span class="ladda-label">Search</span><span class="ladda-spinner"></span><span class="ladda-spinner"></span></button>
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