<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<?php
        if($modDetail->opKo->jenis_produk == "Limbah"){
            echo $modDetail->limbah->limbah_kode." - (".$modDetail->limbah->limbah_produk_jenis.") ".$modDetail->limbah->limbah_nama;
        }else if($modDetail->opKo->jenis_produk == "JasaKD" || $modDetail->opKo->jenis_produk == "JasaGesek" || $modDetail->opKo->jenis_produk == "JasaMoulding"){
            echo $modDetail->produkJasa->kode." - ".$modDetail->produkJasa->nama;
        }else if($modDetail->opKo->jenis_produk == "Log"){
            echo $modDetail->log->log_nama;
        }else{
            echo $modDetail->produk->produk_kode;
        }
        ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]op_ko_detail_id") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi") ?>
		<?= \yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_jual") ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?= ($modDetail->opKo->jenis_produk == "Limbah" || $modDetail->opKo->jenis_produk == "Log")?"": app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?php
        if($modDetail->opKo->jenis_produk == "JasaGesek"){
            echo "-";
        }else if($modDetail->opKo->jenis_produk == "Log"){
            // echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_besar);
            echo '<center>'.$modDetail->satuan_besar.'</center>';
        }else{
            echo app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->qty_kecil). "(". $modDetail->satuan_kecil .")";
        }
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
        <?= ($modDetail->opKo->jenis_produk == "Limbah" )? ( ($modDetail->satuan_kecil=="Rit")?$modDetail->satuan_besar:"" ) : app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->kubikasi) ?>
    </td>
	<td></td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modDetail->harga_jual) ?>
        <?php
        if($modDetail->opKo->jenis_produk == "Log"){
            $sql_harga_enduser = "select harga from t_po_ko_detail where po_ko_id = {$modDetail->opKo->po_ko_id} 
                                    and produk_id = {$modDetail->produk_id}";
        } else if($modDetail->opKo->jenis_produk == "Limbah"){
            $sql_harga_enduser = "select harga_enduser from m_harga_limbah ".
                                "	where limbah_id = ".$modDetail->produk_id."".
                                "	and harga_tanggal_penetapan <= '".$modDetail->opKo->tanggal."' ".
                                "	and status_approval = 'APPROVED' ".
                                "	order by harga_tanggal_penetapan desc ".
                                "	limit 1".
                                "	";
        } else {
            $sql_harga_enduser = "select harga_enduser ".
                                "   from m_harga_produk ".
                                "   where produk_id = '".$modDetail->produk_id."' ".
                                "   and harga_tanggal_penetapan <= '".$modDetail->opKo->tanggal."' ".
                                "   and status_approval = 'APPROVED' ".
                                "   order by harga_tanggal_penetapan desc ".
                                "   limit 1 ".
                                "   ";
        }
        $harga_enduser = Yii::$app->db->createCommand($sql_harga_enduser)->queryScalar();
        ?>        
        <input type="hidden" name="TOpKoDetail[ii][harga_jual_lama]" class="form-control harga_jual_lama text-right" value="<?php echo $harga_enduser;?>" readonly="readonly" style="height: 10px; font-size: 10px;">
        <?php 
        if($modDetail->harga_jual <  $harga_enduser) {
            $status_harga = 'low3';
        } else {
            $status_harga = 'ok';
        }
        ?>
        <input type="hidden" name="TOpKoDetail[ii][status_harga]" class="form-control status_harga text-right" value="<?php echo $status_harga ?>" style="height: 10px; font-size: 10px;">
    </td>
	<td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?php
		if($modDetail->opKo->jenis_produk == "Plywood" || $modDetail->opKo->jenis_produk == "Lamineboard" || $modDetail->opKo->jenis_produk == "Platform" || $modDetail->opKo->jenis_produk == "Limbah" || $modDetail->opKo->jenis_produk == "FingerJointLamineBoard" || $modDetail->opKo->jenis_produk == "FingerJointStick" || $modDetail->opKo->jenis_produk == "Flooring" ){
			$subtotal = $modDetail->qty_kecil * $modDetail->harga_jual;
		}else{
			$subtotal = $modDetail->kubikasi * $modDetail->harga_jual;
		}
		?>
		<?= app\components\DeltaFormatter::formatNumberForUserFloat( $subtotal ) ?>
    </td>
	<td></td>
</tr>
<script>

</script>