<?php 
foreach ($models as $i => $produk) {
    $produk_id = $produk['produk_id'];
    $produk_kode = $produk['produk_kode'];
    $produk_dimensi = $produk['produk_dimensi'];
    $harga_enduser = $produk['harga_enduser'];
?>
<tr>
    <td style="text-align:center">
        <?= $i+1 ?>
        <?= \yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']produk_id',['value'=>$produk['produk_id']]) ?>
    </td>
    <td><?= $produk['produk_nama']; ?></td>
    <td><?= $produk['produk_kode']; ?></td>
    <td><?php echo $produk_dimensi;?></td>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_hpp', ['value'=>'0']); ?>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_distributor', ['value'=>'0']); ?>
    <?= yii\bootstrap\Html::activeHiddenInput($modHarga, '['.$i.']harga_agent', ['value'=>'0']); ?>

    <td style="text-align: right; padding-right: 15px;">
        <?= yii\bootstrap\Html::activeTextInput($modHarga, '['.$i.']harga_enduser', ['class'=>'form-control money-format','value'=>'']); ?>
    </td>
</tr>
<?php 
} 
?> 
