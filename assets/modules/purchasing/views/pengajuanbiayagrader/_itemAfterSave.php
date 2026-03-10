<tr style="">
	<td style="vertical-align: middle; text-align: center; border: 1px solid #bdbdbd;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
		<span class="no_urut"></span>
	</td>
	<td style="vertical-align: middle; padding-top: 20px; padding-bottom: 20px; border: 1px solid #bdbdbd;">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]periode_awal', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= app\components\DeltaFormatter::formatDateTimeForUser2($modDetail->periode_awal); ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]periode_akhir', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= app\components\DeltaFormatter::formatDateTimeForUser2($modDetail->periode_akhir); ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]graderlog_id', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= $modDetail->graderlog->graderlog_nm; ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]tipe_dinas', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= $modDetail->tipe_dinas; ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]wilayah_dinas_id', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= $modDetail->wilayahDinas->wilayah_dinas_nama; ?></b>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]tujuan_dinas', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<b><?= $modDetail->tujuan_dinas; ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]grader_norek', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<b><?= $modDetail->grader_norek; ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]grader_bank', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<b><?= $modDetail->grader_bank; ?></b>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]biaya_grader_detail_jml', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<b><?= app\components\DeltaFormatter::formatUang($modDetail->biaya_grader_detail_jml); ?></b>
					</div>
				</div>
			</div>
		</div>
	</td>
	<td style="vertical-align: middle; text-align: center; border: 1px solid #bdbdbd;">
		-
	</td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>