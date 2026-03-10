<?php foreach($models as $i => $produk){ ?>
<tr>
    <td style="text-align:center">
        <?= $i+1 ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']produk_id',['value'=>$produk['produk_id']]) ?>
    </td>
    <td>
        <?= $produk['produk_nama']; ?>
    </td>
    <td>
        <?= $produk['produk_kode']; ?>
    </td>
    <td>
        <?php /*<?= $produk['produk_kode']; ?> */?>
        <?php
        //$sql_produk_dimensi = "select produk_dimensi from m_brg_produk where produk_id = '".$produk['produk_id']."' ";
        //$produk_dimensi = Yii::$app->$db->createCommand($sql_produk_dimensi)->queryScalar(); 
        //echo $sql_produk_dimensi;
        $produk_id = $produk['produk_id'];
        $sql_produk_dimensi = "select produk_dimensi from m_brg_produk where produk_id = '".$produk_id."' ";
        $produk_dimensi = Yii::$app->db->createCommand($sql_produk_dimensi)->queryScalar();
        echo $produk_dimensi;
        ?>
    </td>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_hpp', ['value'=>'0']); ?>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_distributor', ['value'=>'0']); ?>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_agent', ['value'=>'0']); ?>
    <?php /*<td style="text-align: right; padding-right: 15px;">
        <label id="label-<?= $i; ?>-harga_hpp"></label>
        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_hpp', ['class'=>'form-control money-format','value'=>'','style'=>'display:none;']); ?>
    </td>
    <td style="text-align: right; padding-right: 15px;">
        <label id="label-<?= $i; ?>-harga_distributor"></label>
        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_distributor', ['class'=>'form-control money-format','value'=>'','style'=>'display:none;','disabled'=>'disabled']); ?>
    </td>
    <td style="text-align: right; padding-right: 15px;">
        <label id="label-<?= $i; ?>-harga_agent"></label>
        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_agent', ['class'=>'form-control money-format','value'=>'','style'=>'display:none;','disabled'=>'disabled']); ?>
    </td> */?>
    <td style="text-align: right; padding-right: 15px;">
        <label id="label-<?= $i; ?>-harga_enduser"></label>
        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_enduser', ['class'=>'form-control money-format','value'=>'','style'=>'display:none;']); ?>
    </td>
</tr>
<?php } ?>
 