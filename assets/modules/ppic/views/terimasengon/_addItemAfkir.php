<tr>
    <td style="vertical-align: middle; text-align: center; padding: 3px;">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]panjang', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem; height:30px;','onblur'=>'setTotalAfkir(this);','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]diameter', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem; height:30px;','onblur'=>'setTotalAfkir(this);','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_pcs', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem; height:30px;','onblur'=>'setTotalAfkir(this);','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; padding: 3px;">
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_m3', ['class'=>'form-control float','style'=>'padding: 3px; font-size:1.2rem; height:30px;','onblur'=>'setTotalAfkir(this);','disabled'=>$disabled]); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;  padding: 3px;">
		<?php if(!$disabled){
			echo '<a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a>';
		} ?>
    </td>
</tr>
<script>
</script>