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
							<?= $form->field($model, 'hasil_dari')->dropDownList(["Repacking"=>"Repacking","Regrade"=>"Regrade","Restamp"=>"Restamp","Penanganan Barang Retur"=>"Penanganan Barang Retur"],['class'=>'form-control','prompt'=>'All','onchange'=>'setHasildari();'])->label("Hasil Dari"); ?>
                        	<?php echo $form->field($model, 'hasil_dari_retur')->dropDownList(["Regrade"=>"Regrade","Repair"=>"Repair"],['class'=>'form-control','prompt'=>'All', 'style'=>'display: none;'])->label("Keperluan Retur Barang", ['id' => 'label-hasil-dari-retur', 'style' => 'display: none;']); ?>
						</div>
						<div class="col-md-5">
							<?php 
                            echo $form->field($model, 'jenis_produk')->dropDownList(\app\models\MDefaultValue::getOptionListProduk('jenis-produk'),['prompt'=>'All']); 
                            ?>
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