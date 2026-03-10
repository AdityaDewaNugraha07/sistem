<?php
$modInvoiceDetail = \app\models\TInvoiceDetail::find()
					->join("JOIN", "m_brg_produk", "t_invoice_detail.produk_id = m_brg_produk.produk_id")
					->where("invoice_id = {$model->invoice_id} AND keterangan = '{$modDetail->keterangan}' AND grade='{$modDetail->grade}' AND harga_jual = {$modDetail->harga_jual} ")
					->one();
$modInvoiceDetail->qty_besar = $modDetail->qty_besar;
$modInvoiceDetail->qty_kecil = $modDetail->qty_kecil;
$modInvoiceDetail->kubikasi_display = $modDetail->kubikasi_grouping;
$modInvoiceDetail->harga_jual = $modDetail->harga_jual;
$modInvoiceDetail->subtotal = $modDetail->subtotal;
$modInvoiceDetail->subtotal_display = $modDetail->subtotal_display;
$modDetail->attributes = $modInvoiceDetail->attributes;
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <span class="no_urut"><?= $i+1; ?></span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]produk_id",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_besar",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_besar"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]qty_kecil",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]satuan_kecil"); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]kubikasi",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]harga_hpp",['class'=>'float']); ?>
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, "[".$i."]ppn",['class'=>'float']); ?>
		<?php
		$modDetail->keterangan = !empty($modDetail->keterangan)?$modDetail->keterangan: $modInvoiceDetail->produk->produk_nama;
		echo \yii\helpers\Html::activeTextInput($modDetail, "[".$i."]keterangan",['class'=>'form-control','style'=>"padding:2px;"]); 
		?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?php echo $modInvoiceDetail->produk->grade; ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-center">
		<?php 
		$tebal = ""; $lebar = ""; $panjang = "";
		$sql_t = "SELECT produk_t FROM t_invoice_detail
					JOIN m_brg_produk ON t_invoice_detail.produk_id = m_brg_produk.produk_id 
					WHERE invoice_id = {$model->invoice_id} AND keterangan = '{$modDetail->keterangan}' 
						AND grade='{$modDetail->grade}' AND harga_jual = {$modDetail->harga_jual} 
					GROUP BY 1";
		$modtebal = Yii::$app->db->createCommand($sql_t)->queryAll();
		if(count($modtebal)>0){
			if(count($modtebal)>1){
				foreach($modtebal as $i => $asdt){
					$tebal .= $asdt['produk_t'];
					if(count($modtebal)!=($i+1)){
						$tebal .= "/";
					}
				}
				$tebal .= " ".$modInvoiceDetail->produk->produk_t_satuan;
			}else{
				$tebal = $modtebal[0]['produk_t']." ".$modInvoiceDetail->produk->produk_t_satuan;
			}
		}
		$sql_l = "SELECT produk_l FROM t_invoice_detail
					JOIN m_brg_produk ON t_invoice_detail.produk_id = m_brg_produk.produk_id 
					WHERE invoice_id = {$model->invoice_id} AND keterangan = '{$modDetail->keterangan}' 
						AND grade='{$modDetail->grade}' AND harga_jual = {$modDetail->harga_jual} 
					GROUP BY 1";
		$modlebar = Yii::$app->db->createCommand($sql_l)->queryAll();
		if(count($modlebar)>0){
			if(count($modlebar)>1){
				foreach($modlebar as $i => $asdl){
					$lebar .= $asdl['produk_l'];
					if(count($modlebar)!=($i+1)){
						$lebar .= "/";
					}
				}
				$lebar .= " ".$modInvoiceDetail->produk->produk_l_satuan;
			}else{
				$lebar = $modlebar[0]['produk_l']." ".$modInvoiceDetail->produk->produk_l_satuan;
			}
		}
		$sql_p = "SELECT produk_p FROM t_invoice_detail
					JOIN m_brg_produk ON t_invoice_detail.produk_id = m_brg_produk.produk_id 
					WHERE invoice_id = {$model->invoice_id} AND keterangan = '{$modDetail->keterangan}' 
						AND grade='{$modDetail->grade}' AND harga_jual = {$modDetail->harga_jual} 
					GROUP BY 1";
		$modpanjang = Yii::$app->db->createCommand($sql_p)->queryAll();
        $norandom = true;
		if(count($modpanjang)>0){
            foreach($modpanjang as $i => $ppp){
                if($ppp['produk_p'] == 0){
                    $norandom &= false;
                }
            }
            // query ulang jika ada palet random yang panjang nya 0 (ex. 00226)
            if(!$norandom){
                $sql_p = "SELECT t_packinglist_container.length AS produk_p FROM t_invoice_detail 
                            JOIN t_invoice ON t_invoice.invoice_id = t_invoice_detail.invoice_id
                            JOIN t_packinglist ON t_packinglist.packinglist_id = t_invoice.packinglist_id
                            JOIN t_packinglist_container ON t_packinglist_container.packinglist_id = t_packinglist.packinglist_id
                            WHERE t_invoice.invoice_id = {$model->invoice_id} AND t_packinglist_container.packinglist_id = {$modPackinglist->packinglist_id} 
                                    AND t_invoice_detail.keterangan = '{$modDetail->keterangan}' 
                            AND grade='{$modDetail->grade}' AND harga_jual = {$modDetail->harga_jual} 
                            GROUP BY 1";
                $modpanjang = Yii::$app->db->createCommand($sql_p)->queryAll();
            }
            
            $p_awal = ($modpanjang[0]['produk_p']!=0)?$modpanjang[0]['produk_p']:(isset($modpanjang[1]['produk_p'])?$modpanjang[1]['produk_p']:"0");
			if(count($modpanjang)>1){
                $panjang = $p_awal."~".$modpanjang[(count($modpanjang)-1)]['produk_p']." ".$modInvoiceDetail->produk->produk_p_satuan;
			}else{
				$panjang = $p_awal." ".$modInvoiceDetail->produk->produk_p_satuan;
			}
		}
        
        // special case (00272 th.2019)
        if($model->invoice_id == '303' && $modDetail->harga_jual == '950'){ 
            $panjang = "2400~5700 mm";
        }
        
		echo $tebal." x ".$lebar." x ".$panjang;
//            echo "<pre>";
//            print_r($panjang);
//            echo "<pre>";
//            print_r($sql_p);
		?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modInvoiceDetail->qty_besar) ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= app\components\DeltaFormatter::formatNumberForUserFloat($modInvoiceDetail->qty_kecil) ?> <i>(<?= $modInvoiceDetail->satuan_kecil ?>)</i>
    </td>
    <td style="vertical-align: middle;" class="td-kecil text-align-right">
		<?= yii\bootstrap\Html::activeHiddenInput($modInvoiceDetail, "[".$i."]kubikasi_display",['class'=>'']); ?>
		<?= number_format($modInvoiceDetail->kubikasi_display,4); ?>
    </td>
	<td class="text-align-right td-kecil">
		<?php
		echo \yii\helpers\Html::activeTextInput($modInvoiceDetail, "[".$i."]harga_jual",['class'=>'form-control float','style'=>"padding:2px;",'onblur'=>'subTotal();']);
		?>
	</td>
	<td class="text-align-right td-kecil">
		<?php
		echo yii\bootstrap\Html::activeHiddenInput($modInvoiceDetail, "[".$i."]subtotal",['class'=>'float']);
		echo \yii\helpers\Html::activeTextInput($modInvoiceDetail, "[".$i."]subtotal_display",['class'=>'form-control float','style'=>"padding:2px;",'disabled'=>'disabled']);
		?>
	</td>
</tr>
<script>

</script>
