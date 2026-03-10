<?php
use yii\helpers\ArrayHelper;
//$sql_tipe_openvoucher = "select distinct(tipe) from t_open_voucher union  select upper(name) from m_default_value where type = 'tipe-voucher' and default_value_id in (174,198) order by tipe";
//$query_tipe_openvoucher = Yii::$app->db->createCommand($sql_tipe_openvoucher)->queryAll();
//$sql_tipe_openvoucher = "select distinct(tipe) from t_open_voucher";

$query_tipe_openvoucher = [
    ['tipe' => 'Pembelian BHP', 'tipe' => 'Pembelian BHP', 'class'],
    ['tipe' => 'Pembayaran DP BHP', 'tipe' => 'Pembayaran DP BHP'],
    ['tipe' => 'DP LOG SENGON', 'tipe' => 'DP LOG SENGON'],
    ['tipe' => 'PEMBAYARAN LOG ALAM', 'tipe' => 'PEMBAYARAN LOG ALAM'],
    ['tipe' => 'PELUNASAN LOG SENGON', 'tipe' => 'PELUNASAN LOG SENGON'],
];

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
							<?= $form->field($model, 'kode')->textInput()->label(Yii::t('app', 'Kode BBK')); ?>
						</div>
						<div class="col-md-5">
							<?= $form->field($model, 'tipe')->dropDownList(ArrayHelper::map($query_tipe_openvoucher,'tipe','tipe'), ['prompt'=>'Pilih Tipe']); ?>
							<?= $form->field($model, 'status_bayar')->dropDownList(["PAID"=>"PAID","UNPAID"=>"UNPAID"],['prompt'=>'All'])->label(Yii::t('app', 'Status Bayar')); ?>
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