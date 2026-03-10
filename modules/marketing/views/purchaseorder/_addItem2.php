<?php

use app\models\TPoKo;

if($jns_produk=="Limbah"){
    $onclickmaster = "masterLimbah(this);";
}else if($jns_produk=="JasaKD" || $jns_produk=="JasaGesek" || $jns_produk=="JasaMoulding"){
    $onclickmaster = "masterJasa(this);";
} else if($jns_produk=="Log"){
    $onclickmaster = "masterLog(this);";
} else{
    $onclickmaster = "masterProduk(this);";
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut','style'=>'width:30px;']); ?>
        <?= yii\helpers\Html::activeHiddenInput($modDetail, "[ii]po_ko_detail_id") ?>
        <span class="no_urut"></span>
    </td>
    <td>
        <div style="display: flex; align-items: center; gap: 10px;">
            <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]produk_alias', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
            <?= \yii\bootstrap\Html::activeCheckbox($modDetail, "[ii]alias",['class'=>'form-control','label'=>'', 'onchange'=>'setFieldProduk(this)']) ?>
        </div>
    </td>
    <td>
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]diameter_alias', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
    </td>
    <td style="vertical-align: middle;" id="item-detail" class="td-kecil">
        <?php
            $itemproduk = [];
            if(!empty($edit)){
                if($jns_produk == "Log"){
                    if($modDetail->alias){
                        $produk_ids = explode(',', $modDetail->produk_id_alias);
                        foreach($produk_ids as $p => $log_id){
                            $modLog = app\models\MBrgLog::findOne($log_id);
                            $log_namas[] = $modLog->log_nama;
                        }
                    } else {
                        $modLog = \app\models\MBrgLog::findOne($modDetail->produk_id);
                        $kayu_id = $modLog->kayu_id;
                        $log_id = $modLog->log_id;
                    }
                    
                    $itemproduk = [$modLog->log_id=>$modLog->log_nama];
                    $produk_nama = $modLog->log_nama;
                } 
                    
                else{
                    $itemproduk = [$modDetail->produk_id=>$modDetail->produk->produk_kode];
                    $produk_nama = "xxx";
                }
            } else {
                $produk_nama = "yyy";
            }
        ?>
        <div id='block-produk_id'>
            <span class="input-group-btn" style="width: 100%">
                <?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id',$itemproduk,['class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']);?>
            </span>        
            <span class="input-group-btn" style="width: 10%">
                <a class="btn btn-icon-only btn-default tooltips" onclick="<?= $onclickmaster ?>" data-original-title="Cari Pilihan" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-list"></i></a>
            </span>
        </div>
		<div id='block-produk_id_alias' style="display: none;">
            <?php echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id_alias[]',$itemproduk,['multiple'=>'', 'class'=>'form-control select2','prompt'=>'','style'=>'width:100%;']); ?>
        </div>
    </td>
    <td>
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]komposisi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px; text-align: right;', 'onblur'=>'hitungTotal()']); ?>
    </td>
    <td>
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px; text-align: right;', 'onblur'=>'hitungTotal()']); ?>
    </td>
    <td>
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]harga', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'hitungTotal()\');"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>

