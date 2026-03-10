<?php 
if(count($model) > 0){
	foreach ($model as $m => $mod){ 
        $modBrg = \app\models\MBrgProduk::findOne($mod->produk_id);
        // $modStock = \app\models\HPersediaanProduk::getCurrentStockPerProduk($mod->produk_id);
        $modRetur = \app\models\TReturProdukDetail::findOne($mod->retur_produk_detail_id);
        if($modRepacking->keperluan == 'Penanganan Barang Retur'){
            $nama_produk = $modRetur->nomor_produksi . ' - ' . $modBrg->produk_nama;
            $qty_kecil = $mod->qty_kecil;
            $kubikasi = \app\components\DeltaFormatter::formatNumberForUser($mod->kubikasi, 4);
        } else {
            $nama_produk = $modBrg->produk_nama;
            $qty_kecil = $mod->qty_besar; //$modStock['palet'];
            $kubikasi = '-';//$modStock['kubikasi'];
        }
    ?>
	<tr>
		<td class="text-align-center"><?= $m+1; ?></td>
		<td class="td-kecil"><?= $nama_produk ?></td>
		<td class="td-kecil text-align-right"><?= $qty_kecil; ?></td>
        <td class="td-kecil text-align-right"><?= $kubikasi; ?></td>
	</tr>
	<?php }
}
?>