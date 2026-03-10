<?php
if((!empty($modDetail->stockopname_peserta_id))&&(empty($edit))){
    $disabled = true;
}else{
    $disabled = false;
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, '[ii]stockopname_peserta_id'); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail">
        <?php
        if((!empty($modDetail->stockopname_peserta_id))&&(empty($edit))){
            echo yii\helpers\Html::activeHiddenInput($modDetail, '[ii]pegawai_id');
            echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]pegawai_nama', ['class'=>'form-control','disabled'=>true]);
        }else{
            if(!empty($edit)){
                $selected = [$modDetail->pegawai_id=>$modDetail->pegawai->pegawai_nama." - ".(!empty($modDetail->pegawai->departement_id)?$modDetail->pegawai->departement->departement_nama:"")];
            }else{
                $selected = [];
            }
            echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]pegawai_id',$selected,['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:90%']);
        }
		?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]jabatan_nama', ['disabled'=>true,'class'=>'form-control']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center; font-size: 1.6rem; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]departement_nama', ['disabled'=>true,'class'=>'form-control']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]keterangan', ['class'=>'form-control','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?php
        if($disabled){
            echo '<a class="btn btn-xs grey"><i class="fa fa-remove"></i></a>';
        }else{
            echo '<a class="btn btn-xs red" onclick="cancelItem(this);"><i class="fa fa-remove"></i></a>';
        }
        ?>
        
    </td>
</tr>