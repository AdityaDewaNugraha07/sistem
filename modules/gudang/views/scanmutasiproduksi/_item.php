<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil text-align-center">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]mutasi_keluar_id"); ?>
		<b class="" style="font-size: 1.3rem;"><a onclick="infoPalet('<?= $model->nomor_produksi ?>')"><?= $model->nomor_produksi ?></a></b>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-left">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_id"); ?>
		<?= "<b>".$modProduksi->produk->produk_nama."</b><br>" ?> 
		<?= $modProduksi->produk->produk_dimensi ?> 
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]qty_kecil"); ?>
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_kecil) ?> 
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]qty_m3"); ?>
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_m3) ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php
		
		if(!empty(\app\models\TTerimaMutasi::findOne(['nomor_produksi'=>$model->nomor_produksi]))){
			echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>';
		}else{
			echo '<a class="btn btn-xs red" onclick="hapusItem(this);"><i class="fa fa-trash-o"></i></a>';
		}
		?>
    </td>
</tr>