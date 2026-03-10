<?php
if(!empty($model->log_rubahjenis_id) && empty($edit)){
    $disabled = true;
    $removebtn = '<center><a class="btn btn-xs grey" disabled="disabled"><i class="fa fa-remove"></i></a></center>';
}else{
    $disabled = false;
    $removebtn = '<center><a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a></center>';
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?php
        $modPersediaan = \app\models\HPersediaanLog::findOne(['no_barcode'=>$no_barcode]);
        $barcode_lap = $no_barcode . ' / ' . $modPersediaan->no_lap;
        echo yii\bootstrap\Html::activeTextInput($model, '[ii]barcode_lap', ['class' => 'form-control', 'style' => 'font-size:1.2rem;', 'value'=>$barcode_lap, 'disabled' => 'disabled']);
        echo yii\bootstrap\Html::activeHiddenInput($model, '[ii]no_barcode', ['class' => 'form-control', 'style' => 'font-size:1.2rem;', 'value'=>$no_barcode, 'disabled' => 'disabled']); 
        echo yii\bootstrap\Html::activeHiddenInput($model, '[ii]no_lap', ['class' => 'form-control', 'style' => 'font-size:1.2rem;', 'value'=>$modPersediaan->no_lap, 'disabled' => 'disabled']);
        ?>
    </td>
    <td>
        <?php
        $modKayu = \app\models\MKayu::findOne($kayu_id);
        echo yii\bootstrap\Html::activeTextInput($model, '[ii]kayu_nama', ['class' => 'form-control', 'style' => 'font-size:1.2rem;', 'value'=>$modKayu->kayu_nama, 'disabled' => 'disabled']); 
        echo yii\bootstrap\Html::activeHiddenInput($model, "[ii]kayu_id_old", ['class'=>'form-control', 'value'=>$kayu_id]);
    ?></td>
    <td>
        <?php echo \yii\bootstrap\Html::activeDropDownList($model, '[ii]kayu_id_new', \app\models\MKayu::getOptionListNamaKayu(),['class'=>'form-control select2','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td>
        <?php echo $removebtn; ?>
    </td>
</tr>