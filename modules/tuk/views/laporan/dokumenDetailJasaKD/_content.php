<tr>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-center" style="vertical-align: middle !important; font-size: 1.1rem !important;">
		<?php echo $j+1;?>
	</td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-left" style="font-size: 1.1rem !important; line-break: 1;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal);?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-left" style="font-size: 1.1rem !important;"><?php echo $nomor_dokumen;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-left" style="font-size: 1.1rem !important;"><?php echo $jenis_produk;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-left" style="font-size: 1.1rem !important;"><?php echo $customer;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-center" style="font-size: 1.1rem !important;"><?php echo $produk_nama;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-right" style="font-size: 1.1rem !important;"><?php echo $t;?> <?php echo $t_satuan;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-right" style="font-size: 1.1rem !important;"><?php echo $l;?> <?php echo $l_satuan;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-right" style="font-size: 1.1rem !important;"><?php echo $p;?> <?php echo $p_satuan;?></td>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-right" style="font-size: 1.1rem !important;"><?php echo $qty_kecil;?>
	<td style="background-color: <?php echo $color;?>;" class="td-kecil text-align-right" style="font-size: 1.1rem !important;"><?php echo $kubikasi;?></td>
</tr>
<?php
$baris_akhir = $numrows_y - $j;
if ($baris_akhir == 1) {
?>
<tr>
	<td colspan="11" style="height: 5px; background-color: #EEF1F5;"></td>
</tr>
<?php
}
?>
