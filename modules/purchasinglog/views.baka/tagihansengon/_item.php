<?php

?>
<tr style="">
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
		<?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, '[ii]tagihan_sengon_id',[]); ?>
		<span class="no_urut"></span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <span class="input-group-btn" style="width: 50%">
            <?php echo yii\helpers\Html::activeDropDownList($model, '[ii]panjang', ['100'=>'100 Cm','130'=>'130 Cm','200'=>'200 Cm','260'=>'260 Cm'],['class'=>'form-control','prompt'=>'','style'=>'width:100%; padding: 1px; height:25px;']); ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?php echo yii\helpers\Html::activeDropDownList($model, '[ii]wilayah', ['jateng'=>'Jateng','luar_jateng'=>'Luar Jateng'],['class'=>'form-control','style'=>'width:100%; padding: 1px; height:25px;']); ?>
        </span>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <span class="input-group-btn" style="width: 45%">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]diameter_awal',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;']); ?>
        </span>
        <span class="input-group-addon textarea-addon" style="width: 5%; background-color: #fff; border: 0;">
            sd
        </span>
        <span class="input-group-btn" style="width: 45%">
            <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]diameter_akhir',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;']); ?>
        </span>
	</td>
	<td class="td-kecil" style="vertical-align: top; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]pcs',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'setTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: top; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]m3',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'setTotal()']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]harga',['class'=>'form-control float','style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;','onblur'=>'setTotal()']); ?>
	</td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]subtotal',['class'=>'form-control float','disabled'=>true,'style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;']); ?>
	</td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]pph',['class'=>'form-control float','disabled'=>true,'style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;']); ?>
	</td>
    <td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?= \yii\bootstrap\Html::activeTextInput($model, '[ii]bayar',['class'=>'form-control float','disabled'=>true,'style'=>'width:100%; padding: 1px; height:25px; font-size:1.2rem;']); ?>
	</td>
	<td class="td-kecil" style="vertical-align: middle; text-align: center;">
        <?php if(!empty($model->tagihan_sengon_id) && empty($edit)){ ?>
            <a class="btn btn-xs grey" style="padding: 2px;"><i class="fa fa-trash-o"></i></a>
        <?php }else{ ?>
            <a class="btn btn-xs red tooltips" data-original-title="Delete" style="padding: 2px;" onclick="cancelItem(this);"><i class="fa fa-trash-o"></i></a>
        <?php } ?>
	</td>
</tr>
