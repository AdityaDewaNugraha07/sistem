<?php 
if(!empty($model->losstime_swm_id) && empty($edit)){
    $disabled = true;
    $btn_dis = 'disabled';
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
}else{
    $disabled = false;
    $btn_dis = '';
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]losstime_swm_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]nomor_bandsaw', \app\models\MDefaultValue::getOptionList('nomor-bandsaw'),['class'=>'form-control','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kategori_losstime', \app\models\MDefaultValue::getOptionList('losstime-sawmill'),['class'=>'form-control','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td>
        <div class="input-group date form_datetime bs-datetime">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]losstime_start',['class'=>'form-control','readonly'=>'readonly','placeholder'=>'Pilih Tanggal', 'disabled'=>$disabled]); ?>
            <span class="input-group-addon">
                <button class="btn default" type="button" style="margin-left: 0px;" <?= $btn_dis; ?>>
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td>
        <div class="input-group date form_datetime bs-datetime">
            <?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]losstime_end',['class'=>'form-control','readonly'=>'readonly','placeholder'=>'Pilih Tanggal', 'disabled'=>$disabled]); ?>
            <span class="input-group-addon">
                <button class="btn default" type="button" style="margin-left: 0px;" <?= $btn_dis; ?>>
                    <i class="fa fa-calendar"></i>
                </button>
            </span>
        </div>
    </td>
    <td><?= \yii\bootstrap\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control', 'rows'=>1, 'disabled'=>$disabled]); ?></td>
    <td>
        <?php echo $removebtn; ?>
    </td>
</tr>