<?php
if(!empty($model->mutasi_keluar_id)){
    if(!empty($edit)){
        $disabled = false;
    }else{
        $disabled = true;
    }
}else{
    $disabled = false;
}
?>
<tr>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <span class="no_urut"></span>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]mutasi_keluar_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]cara_keluar") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]gudang_asal") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]produk_id") ?>
    </td>
    <td style="text-align: center; background-color: #F7FFE2;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]gudang_asal_display',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; text-align:center;','disabled'=>true]) ?>
    </td>
    <td style="text-align: center; background-color: #F7FFE2;" class="td-kecil">
        <?= yii\helpers\Html::activeTextInput($model, '[ii]nomor_produksi',['class'=>'form-control','disabled'=>true,'style'=>'padding:2px; font-size:1.2rem; font-weight:600']); ?>
    </td>
    <td style="text-align: left; line-height: 1; background-color: #F7FFE2;" class="td-kecil">
        <b><?= $modProduk->produk_kode ?></b><br>
        <?= $modProduk->produk_nama ?>
    </td>
    <td style="text-align: left; line-height: 1; background-color: #F7FFE2;" class="td-kecil">
        <?= $modProduk->produk_dimensi ?>
    </td>
    <td style="text-align: center; background-color: #F7FFE2;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]qty_kecil',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; text-align:center; width:100%;','disabled'=>true]) ?>
    </td>
    <td style="text-align: center; background-color: #F7FFE2;" class="td-kecil">
		<?= \yii\helpers\Html::activeTextInput($model, '[ii]qty_m3',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem; text-align:right; width:100%;','disabled'=>true]) ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
        if($disabled==true){
            echo '<center class="font-green-seagreen">SUDAH DIMUTASI</center>';
        }else{
            echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total(); setButtonSave();\');"><i class="fa fa-remove"></i></a></center>';
        }
        ?>
    </td>
</tr>
<script>

</script>
