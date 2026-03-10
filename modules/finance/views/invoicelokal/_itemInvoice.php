<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <?= \yii\helpers\Html::activeTextarea($modDetail, "[ii]uraian", ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','rows'=>'2']); ?>
    </td>
    <td>
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]kubikasi_inv", ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'onblur'=>'totalInvoice();']) ?>
    </td>
    <td>
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]harga_inv", ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'onblur'=>'totalInvoice();']) ?>
    </td>
    <td>
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]total_inv", ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'onblur'=>'totalInvoice();']) ?>
    </td>
    <td style="vertical-align: top; text-align: center; padding-top: 5px;" >
		<?php echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'totalInvoice()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>