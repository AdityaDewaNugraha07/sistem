<?php
if ($numrows > 0) {
    $i=1;
    $xxx = 0;
    $yyy = 0;
    foreach ($models as $kolom) {
    ?>
    <tr>
        <td class='td-kecil' style="text-align:center"><?php echo $i;?></td>
        <td class='td-kecil'><?php echo $kolom['log_kode'];?></td>
        <td class='td-kecil'><?php echo $kolom['log_nama'];?></td>
        <td class="td-kecil text-center"><?php echo $kolom['range_awal'].'cm - '.$kolom['range_akhir'].'cm';?></td>
        <?php
        $sql_harga_lama = "select a.harga_enduser ".
                        "	from m_harga_log a ".
                        "	where a.log_id = ".$kolom['log_id']." ".
                        "	and a.status_approval = 'APPROVED' ".
                        "	and a.harga_tanggal_penetapan < '".$harga_tanggal_penetapan."' ".
                        "	order by a.harga_id desc ".
                        "	limit 1 ".
                        "	";
        $harga_lama = Yii::$app->db->createCommand($sql_harga_lama)->queryScalar();
        $harga_lama > 0 || $harga_lama != NULL ? $harga_lama = $harga_lama : $harga_lama = 0;
        ?>
        <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser(number_format($harga_lama));?></td>
        <td class="td-kecil text-right">
			<?php
				$harga_enduser = $kolom['harga_enduser'];
				if ($harga_enduser > $harga_lama) {
					$color = "#ff0000";
                    $sign = "<a onclick='graf(".$kolom['log_id'].")'><i class='fa fa-arrow-circle-up' aria-hidden='true' style='color: #ff0000;'></i></a>";
				} else if ($harga_enduser < $harga_lama) {
					$color = "#33cc33";
					$sign = "<a onclick='graf(".$kolom['log_id'].")'><i class='fa fa-arrow-circle-down' aria-hidden='true' style='color: #33cc33;'></i></a>";
				} else {
					$color = "#000";
					$sign = "<a onclick='graf(".$kolom['log_id'].")'><i class='fa fa-arrow-circle-right' aria-hidden='true' style='color: #dedede;'></i></a>";
				}
			?>
            <font style="color: <?php echo $color;?>"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser(number_format($kolom['harga_enduser']))." ".$sign;?></font>
        </td>        
    </tr>
    <?php
        $i++;
        $xxx += $harga_lama;
        $yyy += $kolom['harga_enduser'];
    }
    ?>
    <tr>
        <td colspan="4" class="text-right"><b>Total</b></td>
        <td class="td-kecil text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForUser($xxx);?></b></td>
        <td class="td-kecil text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForUser($yyy);?></b></td>
    </tr>    
<?php } else {
	?>
    <tr>
        <td colspan="6"></td>
    </tr>
<?php
}
?>

<script>
    function graf(id,kode) {
        openModal('<?php echo \yii\helpers\Url::toRoute(['/marketing/pricelistlog/graf','id'=>'']) ?>'+id+'&kode='+kode,'modal-madul','85%');
    }
</script> 