<?php
if(!empty($model->spk_sawmill_id) && empty($edit)){
    $disabled = true;
    $pointer = 'pointer-events: none;';
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
}else{
    $disabled = false;
    $pointer ='';
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]brakedown_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <span class="input-group-btn" style="width: 100%;">
			<?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]no_lap_baru', $modDetail->no_lap_baru ? [$modDetail->no_lap_baru => $modDetail->no_lap_baru] : [],['class'=>'form-control select2','prompt'=>'','onchange'=>'setItem(this);', 'disabled'=>$disabled]); ?>
		</span>
		<span class="input-group-btn" style="width: 20%;">
			<a class="btn btn-icon-only btn-default tooltips" onclick="openModalNoLap(this);" data-original-title="Daftar No. Lap" style="margin-left: 3px; border-radius: 4px; disabled:<?= $disabled; ?>; <?= $pointer; ?>"><i class="fa fa-list"></i></a>
		</span>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]no_barcode_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center;']) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]grading_rule", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 80px;']) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]panjang_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;']) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]diameter_ujung1_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;']) ?>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]diameter_ujung2_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;']) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]diameter_pangkal1_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;']) ?>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]diameter_pangkal2_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;']) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]cacat_pjg_baru", ['class'=>'form-control float', 'onblur'=>'hitungvol(this);', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;', 'disabled'=>$disabled]) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]cacat_gb_baru", ['class'=>'form-control float', 'onblur'=>'hitungvol(this);', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;', 'disabled'=>$disabled]) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]cacat_gr_baru", ['class'=>'form-control float', 'onblur'=>'hitungvol(this);', 'style'=>'font-size: 1.2rem; text-align: center; width: 55px;', 'disabled'=>$disabled]) ?>
    </td>
    <td>
        <?= yii\helpers\Html::activeTextInput($modDetail, "[ii]volume_baru", ['class'=>'form-control', 'disabled'=>'disabled', 'style'=>'font-size: 1.2rem; text-align: right; width: 60px;']) ?>
    </td>
    <td>
        <?php echo $removebtn; ?>
    </td>
</tr>