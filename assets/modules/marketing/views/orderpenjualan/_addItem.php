<?php
if($jns_produk=="Limbah"){
    $onclickmaster = "masterLimbah(this);";
    $onblurqtykecil = "";
    $onblurkubikasi = "";
}else if($jns_produk=="JasaKD" || $jns_produk=="JasaGesek" || $jns_produk=="JasaMoulding"){
    $onclickmaster = "masterJasa(this);";
    $onblurqtykecil = "";
    $onblurkubikasi = "";
}else{
    $onclickmaster = "masterProduk(this);";
    $onblurqtykecil = "setMeterKubik(this);";
    $onblurkubikasi = "setQtyByKubikasi(this);";
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]op_ko_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
		<span class="input-group-btn" style="width: 100%">
			<?php
			$availablestock = ''; $itemproduk = [];
			if(!empty($edit)){
                if($jns_produk == "Limbah"){
                    $modLimbah = \app\models\MBrgLimbah::findOne($modDetail->produk_id);
                    $itemproduk = [$modLimbah->limbah_id=>$modLimbah->limbah_kode." - (".$modLimbah->limbah_produk_jenis.") ".$modLimbah->limbah_nama];
                }else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $modJasa = \app\models\MProdukJasa::findOne($modDetail->produk_id);
                    $itemproduk = [$modJasa->produk_jasa_id=>$modJasa->kode." - ".$modJasa->nama];
                }else{
                    $availablestock = \app\models\HPersediaanProduk::getCurrentStockPerProduk($modDetail->produk_id);
                    if(!empty($availablestock)){
                        $availablestock = $availablestock['qty_kecil']."(".$availablestock['in_qty_kecil_satuan'].")<br>".$availablestock['kubikasi']."M<sup>3</sup>";
                    }
                    $itemproduk = [$modDetail->produk_id=>$modDetail->produk->produk_kode];
                }
			}
			?>
			<?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',$itemproduk,['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;']); ?>
		</span>
		<span class="input-group-btn" style="width: 10%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span>
		<span class="input-group-btn btn-random" style="width: 10%; display: none;">
			<a class="btn btn-default tooltips" onclick="listRandom(this);" data-original-title="Lihat Detail Produk Random" style="margin-left: 0px; border-radius: 4px; font-size: 1rem; line-height: 1">Produk<br>Random</a>
		</span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php
        if($jns_produk == "Limbah"){
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_besar',['value'=>1]);
        }else{
            echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_besar', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;']);
        }
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <span class="input-group-btn" style="width: 50%">
            <?php
            if($jns_produk == "Limbah"){
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>true]);
            }else if($jns_produk == "JasaGesek"){
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_kecil');
                echo "<center>-</center>";
            }else{
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float','onblur'=>$onblurqtykecil,'style'=>'width:100%; font-size:1.2rem; padding:5px;']);
            }
            
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_kecil_perpalet');
            ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?php
            if($jns_produk == "JasaGesek"){
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_kecil',['value'=>"-"]);
            }else{
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]satuan_kecil', ['class'=>'form-control','disabled'=>'disabled','style'=>'width:100%;  font-size:1.2rem; padding:5px;']); 
            }
            ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php if($jns_produk == "Limbah"){ ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]kubikasi',['value'=>0]) ?>
            <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]satuan_besar', ['class'=>'form-control','disabled'=>true,'style'=>'width:100%; font-size:1rem; padding:2px;']) ?>
        <?php }else{ ?>
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float','onblur'=>$onblurkubikasi,'style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_besar') ?>
        <?php } ?>
        <?php if (!in_array($jns_produk, ["Limbah","JasaKD","JasaGesek","JasaMoulding"])) { ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]kubikasi_perpalet') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_p') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_l') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_t') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_p_satuan') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_l_satuan') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modProduk, '[ii]produk_t_satuan') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]is_random') ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]nomor_produksi_random') ?>
        <?php } ?>
    </td>
	<td style="vertical-align: middle; font-size: 1rem !important;" class="td-kecil text-align-center" id="place-availablestock"><?= $availablestock ?></td>
    <td style="vertical-align: middle;" class="td-kecil">
		<?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]harga_hpp',['class'=>'float','disabled'=>'disabled']) ?>
		<?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]harga_jual', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'total()','disabled'=>'disabled']); ?>
    </td>
	<td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]subtotal', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'total()','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
		<?php echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>
<script>

</script>
