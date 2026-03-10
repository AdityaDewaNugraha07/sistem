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
						<div class="input-group date date-picker">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]periode_awal',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
							<span class="input-group-btn">
								<button class="btn default" type="button" style="margin-left: -40px;">
									<i class="fa fa-calendar"></i>
								</button>
							</span>
						</div> 
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]periode_akhir', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<div class="input-group date date-picker">
							<?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]periode_akhir',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly','placeholder'=>'Pilih Tanggal']); ?>
							<span class="input-group-btn">
								<button class="btn default" type="button" style="margin-left: -40px;">
									<i class="fa fa-calendar"></i>
								</button>
							</span>
						</div> 
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]graderlog_id', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]graderlog_id',[],['class'=>'form-control select2','onchange'=>'setMasterGrader(this)','prompt'=>'','style'=>'width:90%']); ?>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]tipe_dinas', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<?php echo yii\helpers\Html::activeRadioList($modDetail, '[ii]tipe_dinas', \app\models\MDefaultValue::getOptionList('tipe-dinas-grader'),['separator' => ' &nbsp; &nbsp;', 'tabindex' => 3,'style'=>'margin-top:7px;']); ?>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]wilayah_dinas_id', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]wilayah_dinas_id', \app\models\MWilayahDinas::getOptionList(),['class'=>'form-control','prompt'=>'','onchange'=>'setWilayahDinas(this)']); ?>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]tujuan_dinas', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-8">
						<?php echo yii\helpers\Html::activeTextarea($modDetail, '[ii]tujuan_dinas',['class'=>'form-control','placeholder'=>'Ketik Nama PT - Kota']); ?>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]grader_norek', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<?php echo yii\helpers\Html::activeTextInput($modDetail, '[ii]grader_norek',['class'=>'form-control']); ?>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]grader_bank', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<?php echo yii\helpers\Html::activeTextInput($modDetail, '[ii]grader_bank',['class'=>'form-control']); ?>
					</div>
				</div>
				<div class="form-group">
					<?= \yii\bootstrap\Html::activeLabel($modDetail, '[ii]biaya_grader_detail_jml', ['class'=>'col-md-4 control-label']); ?>
					<div class="col-md-7">
						<?php echo yii\helpers\Html::activeTextInput($modDetail, '[ii]biaya_grader_detail_jml',['class'=>'form-control money-format','onchange'=>'setTotalBiaya()']); ?>
					</div>
				</div>
			</div>
		</div>
	</td>
	<td style="vertical-align: middle; text-align: center; border: 1px solid #bdbdbd;">
		<a class="btn btn-xs red" onclick="cancelItem(this,'setTotalBiaya();');"><i class="fa fa-remove"></i></a>
	</td>
</tr>
<?php $this->registerJs(" 
	
", yii\web\View::POS_READY); ?>