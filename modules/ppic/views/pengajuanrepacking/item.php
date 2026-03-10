<?php
//if(!empty($model->pengajuan_repacking_detail_id)){
// $model->qty_stock = $modStock['palet'];
/*if (isset($disabledX)) {
    if ($disabledX == "disabled") {
        if(empty($edit)){
            $disabled = false;
        }else{
            $disabled = true;
        }
    }else{
        $disabled = false;
    }
} else {
    $disabled = false;
    $disabledX = false;
}*/
if (isset($pengajuan_repacking_id)) {
    if (isset($edit) && $edit == 1) {
        if ($cek->approval_status == "Not Confirmed") {
            $disabledX = "";
            $disabled = false;
        } else {
            $disabledX = "disabled";
            $disabled = 1;
        }
    } else {
        $disabledX = 'disabled';
        $disabled = 1;
    }
    $keperluan = $cek->keperluan;
    if($keperluan == 'Penanganan Barang Retur'){
        $modReturDet = \app\models\TReturProdukDetail::findOne($model->retur_produk_detail_id);
    }
} else {
    $disabledX = '';
        $disabled = false;
}
?>
<tr class="rows">
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?= yii\bootstrap\Html::hiddenInput('no_urut',null,['id'=>'no_urut']); ?>
        <span class="no_urut"></span>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]pengajuan_repacking_detail_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]pengajuan_repacking_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]produk_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]qty_stock") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]retur_produk_detail_id") ?>
        <?= \yii\helpers\Html::activeHiddenInput($model, "[ii]kubikasi") ?>
    </td>
    <td style="text-align: left;" class="td-kecil">
        <?php 
        $no_produksi = '';
        if($keperluan == 'Penanganan Barang Retur'){
            $no_produksi = $modReturDet->nomor_produksi . ' - ';
        }
        ?>
        <b><?= $no_produksi . $model->produk_nama ?></b>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?php
        if($keperluan == 'Penanganan Barang Retur'){
            echo \yii\helpers\Html::activeTextInput($model, '[ii]qty_besar',['class'=>'form-control','style'=>'padding:2px; text-align:right; width:100%;','onblur'=>'total();', 'disabled'=>'']);
        } else {
            if ($disabled == 1) {
                echo \yii\helpers\Html::activeTextInput($model, '[ii]qty_besar',['class'=>'form-control','style'=>'padding:2px; text-align:right; width:100%;','onblur'=>'total();','disabled'=>$disabledX]);
            } else {
                echo \yii\helpers\Html::activeTextInput($model, '[ii]qty_besar',['class'=>'form-control','style'=>'padding:2px; text-align:right; width:100%;','onblur'=>'total();']);
            }
        }
        ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?php
        if($keperluan == 'Penanganan Barang Retur'){
            echo "<b>". $modReturDet->qty_kecil ." Pcs</b><br>(". number_format($modReturDet->kubikasi,4)." m<sup>3</sup>)";
        } else {
            echo "<b>".$modStock['palet']." Palet</b><br>(". number_format($modStock['kubikasi'],4)." m<sup>3</sup>)";
        }
        ?>
    </td>
    <td style="text-align: center;" class="td-kecil">
        <?php
        if ($disabled == 1) {
            echo \yii\helpers\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem;','disabled'=>$disabledX]);
        } else {
            echo \yii\helpers\Html::activeTextInput($model, '[ii]keterangan',['class'=>'form-control','style'=>'padding:2px; font-size:1.1rem;']);
        }
        ?>
    </td>
    <td style="vertical-align: middle; text-align: center;" class="td-kecil">
        <?php
        if($disabled == 1){
            echo '<center><a class="btn btn-xs grey"><i class="fa fa-remove"></i></a></center>';
        }else{
            echo '<center><a class="btn btn-xs red" onclick="cancelItem(this,\'total()\');"><i class="fa fa-remove"></i></a></center>';
        }
        ?>
    </td>
</tr>
<script>

</script>
