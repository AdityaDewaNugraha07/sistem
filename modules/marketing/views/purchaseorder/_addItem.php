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
    <td style="vertical-align: middle;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]produk_alias', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
            <?= \yii\bootstrap\Html::activeCheckbox($modDetail, "[ii]alias",['class'=>'form-control','label'=>'']) ?>
        </div>
    </td>
    <td class="td-kecil">
        <?php 
        if($edit){
            if(!$modDetail->range_diameter){
                $produk_ids = explode(',', $modDetail->produk_id_alias);
                $mod = app\models\MBrgLog::findOne($produk_ids[0]);
                $r_diameter = $mod->range_awal.'-'.$mod->range_akhir;
                $range_diameter = $modDetail->range_diameter?$modDetail->range_diameter:$r_diameter; 
                $modDetail->range_diameter =  $range_diameter;
            }
        }
        echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]range_diameter', \app\models\MDefaultValue::getOptionList('range-diameter'), ['class'=>'form-control','prompt'=>'','style'=>'width:100%; font-size:1.2rem;', 'onchange'=>'setDiameterAlias(this)']); 
        ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]diameter_alias', ['class'=>'form-control','style'=>'width:100%; font-size:1.2rem; padding:5px; text-align:center;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;">
        <?php echo \yii\bootstrap\Html::activeCheckbox($modDetail, "[ii]fsc",['class'=>'form-control','label'=>'', 'onchange'=>'setDDProdukIdAlias(this)']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php 
        if($edit){
            echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id_alias',[], ['multiple'=>'multiple', 'class'=>'form-control select2-multiple','prompt'=>'','style'=>'width:100%; font-size:1.2rem;']); 
        } else {
            echo yii\helpers\Html::activeDropDownList($modDetail, '[ii]produk_id_alias',[], ['multiple'=>'multiple', 'class'=>'form-control select2-multiple','prompt'=>'','style'=>'width:100%; font-size:1.2rem;', 'disabled'=>'']); 
        }?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]komposisi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px; text-align: right;', 'onblur'=>'hitungTotal()']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]kubikasi', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px; text-align: right;', 'onblur'=>'hitungTotal()']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]harga', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;', 'onblur'=>'hitungTotal()']); ?>
    </td>
    <td style="vertical-align: middle;">
        <?php echo yii\bootstrap\Html::activeTextInput($modDetail, '[ii]subtotal', ['class'=>'form-control float','style'=>'width:100%; font-size:1.2rem; padding:5px;']); ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= '<center><a class="btn btn-xs red" onclick="cancelItems(this);"><i class="fa fa-remove"></i></a></center>'; ?>
    </td>
</tr>

