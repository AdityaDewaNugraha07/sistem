<tr>
    <td style="padding-top: 10px;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px; ']); ?>
        <span class="no_urut"></span>
    </td>
	<td>
		<div class="input-group date date-picker">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]tanggal_datang',['class'=>'form-control','style'=>'width:100%','readonly'=>'readonly']); ?>
            <span class="input-group-btn">
                <button class="btn default" type="button" style="margin-left: -10px; padding: 6px;">
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div> 
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]asal_kayu', ['class'=>'form-control']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]nopol', ['class'=>'form-control']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]suplier_pcs', ['class'=>'form-control money-format','style'=>'width:40px;','value'=>0]); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]suplier_m3', ['class'=>'form-control float','style'=>'width:50px;','value'=>0]); ?>
	</td>
	<td>
		<div class="form-group">
			<div class="col-md-8">
				<div class="repeater">
					
				</div>
			</div>
		</div>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]cwm_pcs', ['class'=>'form-control money-format','style'=>'width:40px;','value'=>0,'onblur'=>'total(this)']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]cwm_m3', ['class'=>'form-control float','style'=>'width:50px;','value'=>0,'onblur'=>'total(this)']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]afkir_pcs', ['class'=>'form-control money-format','style'=>'width:40px;','value'=>0,'onblur'=>'total(this)']); ?>
	</td>
	<td>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]afkir_m3', ['class'=>'form-control float','style'=>'width:50px;','value'=>0,'onblur'=>'total(this)']); ?>
	</td>
	<td style="text-align:left;">
		Total : <span class="total"></span>
		<br>
		Selisih : <span class="selisih"></span>
		<br>
		Status Afkir : 
		<span class="status-afkir">
			<?php $modDetail->status_afkir = 0; ?>
			<?php echo yii\bootstrap\Html::activeRadioList($modDetail, '[ii]status_afkir',['<b>Belum Dikirim</b>','<b>Sudah Dikirim</b>'],['encode'=>false,'separator'=>' &nbsp; ']) ?>
		</span>
	</td>
    <td style="padding-top: 10px;">
        <a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>
    </td>
</tr>