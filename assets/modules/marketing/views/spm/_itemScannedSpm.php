<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil text-align-center">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]produk_keluar_id"); ?>
		<b class="" style="font-size: 1.5rem;"><a onclick="infoPalet('<?= $model->nomor_produksi ?>')"><?= $model->nomor_produksi ?></a></b>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center" style="font-size: 1.5rem;">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]qty_besar"); ?>
		<?= $model->qty_besar ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]qty_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]satuan_kecil"); ?>
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->qty_kecil) ?> 
		<i>(<?= $model->satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($model, "[ii]kubikasi"); ?>
		<?= \app\components\DeltaFormatter::formatNumberForUserFloat($model->kubikasi) ?>
    </td>
	<td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php
		$modSpm = \app\models\TSpmKo::findOne(['kode'=>$model->reff_no]);
		if(!empty($modSpm) && $modSpm->status == \app\models\TSpmKo::REALISASI){
			echo '<a class="btn btn-xs grey"><i class="fa fa-trash-o"></i></a>';
		}else{
			echo '<a class="btn btn-xs red" onclick="hapusItem(this);"><i class="fa fa-trash-o"></i></a>';
		}
		?>
    </td>
</tr>
<script>
function infoPalet(nomor_produksi){
	openModal('<?= \yii\helpers\Url::toRoute(['/marketing/spm/infoPalet','nomor_produksi'=>'']) ?>'+nomor_produksi,'modal-info-palet','90%');
}
</script>
