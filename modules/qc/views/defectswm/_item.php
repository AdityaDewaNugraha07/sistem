<?php
if(!empty($model->defect_swm_id) && empty($edit)){
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
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]defect_swm_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <div style="display:flex; align-items:center; gap:6px;">
            <?php 
            echo \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]produk_t', ['class'=>'form-control float', 'disabled'=>$disabled]);
            echo '<span>x</span>';
            echo \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]produk_l', ['class'=>'form-control float', 'disabled'=>$disabled]);  
            ?>
        </div>
    </td>
    <td>
        <?php 
        echo \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]produk_p', ['class'=>'form-control float', 'disabled'=>$disabled]);
        ?>
    </td>
    <td>
        <?= \yii\bootstrap\Html::activeDropDownList($modDetail, '[ii]kategori_defect', \app\models\MDefaultValue::getOptionList('defect-sawmill'),['class'=>'form-control','style'=>'font-size: 1.2rem;', 'prompt'=>'','disabled'=>$disabled]); ?>
    </td>
    <td><?= \yii\bootstrap\Html::activeTextInput($modDetail, '[ii]qty', ['class'=>'form-control numbers-only', 'disabled'=>$disabled]); ?></td>
    <td><?= \yii\bootstrap\Html::activeTextarea($modDetail, '[ii]keterangan', ['class'=>'form-control', 'rows'=>1, 'disabled'=>$disabled]); ?></td>
    <td>
        <?php echo $removebtn; ?>
    </td>
</tr>