<?php 
$onclick = '';
if($kategori == "DRP Operational"){
    $onclick = "openDrpOperational()";
}else if($kategori == "DRP Log Sengon"){
    $onclick = "openDrpLogSengon()";
}else if($kategori == "DRP Log Alam"){
    $onclick = "openDrpLogAlam()";
}else {
    $onclick;
}
?>
<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Kode Voucher Pengeluaran</label>
    <div class="col-md-7">
         <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','onchange'=>$onclick,'disabled'=>true]); ?>
            <?= \yii\bootstrap\Html::activeHiddenInput($modDrpDetail, 'voucher_pengeluaran_id',['class'=>'form-control','prompt'=>'']); ?>
        </span>
        <!-- <span class="input-group-btn" style="width: 25%">
            <a class="btn btn-icon-only btn-default tooltips" onclick= <?php //echo $onclick; ?> data-original-title="Cari Kode Open Voucher" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span> -->
    </div>
</div>