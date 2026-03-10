<?php
$itemnota = [];
$modSpm = app\models\TSpmKo::findOne($modDetail->spm_ko_id);
$modNota = app\models\TNotaPenjualan::findOne($modDetail->nota_penjualan_id);
$modJasa = app\models\MProdukJasa::findOne($notadetail->produk_id);
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]invoice_lokal_detail_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]invoice_lokal_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]spm_ko_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]nota_penjualan_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]nota_penjualan_detail_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]harga_nota") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]satuan_kecil") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]produk_id") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]qty_besar") ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]satuan_besar") ?>
        
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <b><?= $modNota->kode ?></b> - <?= \app\components\DeltaFormatter::formatDateTimeForUser2($modNota->tanggal) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil" id="place-detail-deskripsi">
		<?= $modJasa->nama ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center" id="place-detail-kirim-tanggal">
		<?= \app\components\DeltaFormatter::formatDateTimeForUser2($modSpm->tanggal) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center" id="place-detail-kirim-nopolsupir">
       <?= $modSpm->kendaraan_nopol." / ".$modSpm->kendaraan_supir ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-qty-pcs">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]qty_kecil",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-qty-m3">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]kubikasi",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]harga_invoice",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right" id="place-detail-total">
        <?= \yii\helpers\Html::activeTextInput($modDetail, "[ii]subtotal",['class'=>'form-control float','disabled'=>true,'style'=>'font-size:1.2rem; padding:5px;']) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>
<script>

</script>
