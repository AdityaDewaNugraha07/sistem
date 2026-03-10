<?php if($tipe == "PEMBAYARAN LOG ALAM"){ ?>

<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Nomor Keputusan Pembelian Log</label>
    <div class="col-md-8">
        <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','onchange'=>'setReff()','disabled'=>true]); ?>
        </span>
        <span class="input-group-btn" style="width: 25%">
            <a class="btn btn-icon-only btn-default tooltips" onclick="openKeputusan();" data-original-title="Cari Keputusan Pembelian Log Alam" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span>
    </div>
</div>

<?php }else if($tipe == "DP LOG SENGON"){ ?>

<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Kode PO Sengon</label>
    <div class="col-md-8">
        <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','onchange'=>'setReff()','disabled'=>true]); ?>
        </span>
        <span class="input-group-btn" style="width: 25%">
            <a class="btn btn-icon-only btn-default tooltips" onclick="openPOSengon();" data-original-title="Cari PO Log Sengon" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span>
    </div>
</div>

<?php }else if($tipe == "PELUNASAN LOG SENGON"){ ?>

<div class="form-group" style="margin-bottom: 10px;">
    <label class="col-md-4 control-label">Kode PO Sengon</label>
    <div class="col-md-8">
        <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','oninput'=>'alert()','disabled'=>true]); ?>
        </span>
        <span class="input-group-btn" style="width: 25%">
            <a class="btn btn-icon-only btn-default tooltips" onclick="openPOSengon();" data-original-title="Cari PO Log Sengon" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span>
    </div>
</div>
<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Tagihan Sengon</label>
    <div class="col-md-8">
        <?= \yii\bootstrap\Html::activeDropDownList($model, 'reff_no2[]', [], ['class'=>'form-control','prompt'=>'','multiple'=>'','onchange'=>'setReff2()']); ?>
    </div>
</div>

<?php } ?>