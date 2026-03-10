<?php

use app\models\TOpKo;

if($jns_produk=="Limbah"){
    $onclickmaster = "masterLimbah(this);";
    $onblurqtykecil = "";
    $onblurkubikasi = "";
}else if($jns_produk=="JasaKD" || $jns_produk=="JasaGesek" || $jns_produk=="JasaMoulding"){
    $onclickmaster = "masterJasa(this);";
    $onblurqtykecil = "";
    $onblurkubikasi = "";
} else if($jns_produk=="Log"){
    $onclickmaster = "masterLog(this);";
    $onblurqtykecil = "";
    $onblurkubikasi = "";
} else{
    $onclickmaster = "masterProduk(this);";
    $onblurqtykecil = "setMeterKubik(this);";
    $onblurkubikasi = "setQtyByKubikasi(this);";
}


isset($harga_enduser) ? $harga_enduser = $harga_enduser : $harga_enduser = 0;
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
            $availablestock = ''; 
            $itemproduk = [];
			if(!empty($edit)){
                if($jns_produk == "Limbah"){
                    $modLimbah = \app\models\MBrgLimbah::findOne($modDetail->produk_id);
                    $itemproduk = [$modLimbah->limbah_id=>$modLimbah->limbah_kode." - (".$modLimbah->limbah_produk_jenis.") ".$modLimbah->limbah_nama];
                    $produk_nama = $modLimbah->limbah_kode." - ".$modLimbah->limbah_nama;
                }
                
                else if($jns_produk == "JasaKD" || $jns_produk == "JasaGesek" || $jns_produk == "JasaMoulding"){
                    $modJasa = \app\models\MProdukJasa::findOne($modDetail->produk_id);
                    $itemproduk = [$modJasa->produk_jasa_id=>$modJasa->kode." - ".$modJasa->nama];
                    $produk_nama = $modJasa->kode." - ".$modJasa->nama;
                } 
                
                else if($jns_produk == "Log"){
                    // $model = TOpKo::findOne($modDetail->op_ko_id);
                    $modLog = \app\models\MBrgLog::findOne($modDetail->produk_id);
                //     $kayu_id = $modLog->kayu_id;
                //     $log_id = $modLog->log_id;
                //     if($model->terima_logalam_id == null){
                //         $availablestock = \app\models\HPersediaanLog::getCurrentStockPerLog($po_ko_id, $log_id, );
                //         // var_dump($availablestock);exit;
                //         if(!empty($availablestock)){
                //             $availablestock = $availablestock['stock']." pcs";
                //         }
                //     } else {
                //         $availablestock = '';
                //     }
                    $itemproduk = [$modLog->log_id=>$modLog->log_nama];
                    $produk_nama = $modLog->log_nama;
                } 
                
                else{
                    $availablestock = \app\models\HPersediaanProduk::getCurrentStockPerProduk($modDetail->produk_id);
                    if(!empty($availablestock)){
                        $availablestock = $availablestock['qty_kecil']."(".$availablestock['in_qty_kecil_satuan'].")<br>".$availablestock['kubikasi']."M<sup>3</sup>";
                    }
                    $itemproduk = [$modDetail->produk_id=>$modDetail->produk->produk_kode];
                    $produk_nama = "xxx";
                }
			} else {
                $produk_nama = "yyy";
            }
			?>
            <?php if(!empty($edit)){ 
                    $model = TOpKo::findOne($modDetail->op_ko_id);
                    if($model->terima_logalam_id == null){
                        echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',$itemproduk,['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;']);
                    } else {
                        echo yii\bootstrap\Html::activeHiddenInput($modDetail, "[ii]produk_id",['class'=>'form-control float','style'=>'width:50%; font-size:1.2rem; padding:5px;']);
                        echo yii\bootstrap\Html::activeTextInput($modDetail, "[ii]produk_nama",['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;','value'=>$produk_nama, 'disabled'=>'disabled']);
                    }
                } else {
                    echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',$itemproduk,['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;']);
                }
            ?>
			<?php //echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',$itemproduk,['class'=>'form-control select2','onchange'=>'setItem(this)','prompt'=>'','style'=>'width:100%;']); ?>
		</span>
        <?php
        if(!empty($edit)){
            if($model->terima_logalam_id == null){?>
                <span class="input-group-btn" style="width: 10%">
                    <a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
                </span>
        <?php } else { ?>
                <span class="input-group-btn" style="width: 10%">
                    <a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px; pointer-events: none;"><i class="fa fa-list"></i></a>
                </span>
        <?php } ?>
        <?php } else { ?>
            <span class="input-group-btn" style="width: 10%">
                <a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
            </span>
        <?php } ?>
		<!-- <span class="input-group-btn" style="width: 10%">
			<a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
		</span> -->
		<span class="input-group-btn btn-random" style="width: 10%; display: none;">
			<a class="btn btn-default tooltips" onclick="listRandom(this);" data-original-title="Lihat Detail Produk Random" style="margin-left: 0px; border-radius: 4px; font-size: 1rem; line-height: 1">Produk<br>Random</a>
		</span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php
        if($jns_produk == "Limbah"){
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_besar',['value'=>1, 'title'=>'qty_besar '.$produk_nama]);
        } else if ($jns_produk == "Log"){
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_kecil', ['value'=>1, 'title'=>'qty_kecil '.$produk_nama]);
        } else{
            echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_besar', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'title'=>'qty_besar '.$produk_nama]);
        }
        ?>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <span class="input-group-btn" style="width: 50%">
            <?php
            if($jns_produk == "Limbah"){
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float ', 'style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>true]);
            } else if ($jns_produk == "Log"){
                echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]qty_besar', ['class'=>'form-control float qty_besar', 'onblur' => 'setVolume(this)', 'style'=>'width:100%; font-size:1.2rem; padding:5px;','disabled'=>'disabled']);
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_kecil');
            } else if($jns_produk == "JasaGesek"){
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_kecil');
                echo "<center>-</center>";
            } else{
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]qty_kecil', ['class'=>'form-control float','onblur'=>$onblurqtykecil,'style'=>'width:100%; font-size:1.2rem; padding:5px;', 'title'=>'qty_kecil '.$produk_nama]);
            }
            
            echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]qty_kecil_perpalet');
            ?>
        </span>
        <span class="input-group-btn" style="width: 50%">
            <?php
            if($jns_produk == "JasaGesek"){
                echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_kecil',['value'=>"-"]);
            }else if($jns_produk == "Log"){
                echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]satuan_besar',['class'=>'form-control','disabled'=>'disabled','style'=>'width:100%;  font-size:1.2rem; padding:5px;']);
            }else{
                echo \yii\helpers\Html::activeTextInput($modDetail, '[ii]satuan_kecil', ['class'=>'form-control','disabled'=>'disabled','style'=>'width:100%;  font-size:1.2rem; padding:5px;']); 
            }
            ?>
        </span>
    </td>
    <td style="vertical-align: middle;" class="td-kecil">
        <?php if($jns_produk == "Limbah"){ ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]kubikasi',['value'=>0]) ?>
            <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]satuan_besar', ['class'=>'form-control satuan_kecil','disabled'=>true,'style'=>'width:100%; font-size:1rem; padding:2px;']) ?>
        <?php }else if($jns_produk == "Log"){ ?>
            <?= yii\bootstrap\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float kubikasi','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'disabled'=>'disabled']) ?>
            <?php //echo yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_kecil') ?>
        <?php }else{ ?>
            <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float satuan_besar','onblur'=>$onblurkubikasi,'style'=>'width:100%; font-size:1.2rem; padding:5px;', 'title'=>'kubikasi '.$produk_nama]); ?>
            <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]satuan_besar') ?>
        <?php } ?>
        <?php if (!in_array($jns_produk, ["Limbah","JasaKD","JasaGesek","JasaMoulding", "Log"])) { ?>
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
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]harga_jual', ['class'=>'form-control float harga_jualx','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'setVerify(), total()','disabled'=>'disabled']); ?>
        <?= yii\bootstrap\Html::activeHiddenInput($modDetail, '[ii]harga_hpp',['class'=>'float','disabled'=>'disabled']) ?> 	
        <input type="hidden" name="TOpKoDetail[ii][harga_jual_lama]" class="form-control harga_jual_lama text-right" value="<?php echo number_format($harga_enduser);?>" style="height: 10px; font-size: 10px;">
        <input type="hidden" name="TOpKoDetail[ii][status_harga]" class="form-control status_harga text-right" value="" style="height: 10px; font-size: 10px;">
    </td>
	<td style="vertical-align: middle;" class="td-kecil">
        <?= \yii\helpers\Html::activeTextInput($modDetail, '[ii]subtotal', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;','onblur'=>'total()','disabled'=>'disabled']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php if(!empty($edit)){
            if($model->terima_logalam_id == null){
                echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>';
            } else {
                echo '<center>-</center>';
            }
        } else {
            echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>';
        }?>
        <?php //echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>

