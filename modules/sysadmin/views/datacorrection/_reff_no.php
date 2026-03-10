<?php if($tipe == "KOREKSI HARGA JUAL" || $tipe == "KOREKSI NOPOL MOBIL" || $tipe == "KOREKSI ALAMAT BONGKAR" || $tipe == "POTONGAN PIUTANG"){ ?>

<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Kode Nota</label>
    <div class="col-md-7">
        <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','disabled'=>true,'placeholder'=>'Pilih Kode Nota']); ?>
        </span>
        <span class="input-group-btn" style="width: 25%">
            <a id="btn-open-nota" class="btn btn-icon-only btn-default tooltips" onclick="openNota();" data-original-title="Cari Nota Penjualan Lokal" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span>
    </div>
</div>

<?php }else if($tipe == "KOREKSI PIUTANG LOG & JASA"){ ?>

<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Nomor </label>
    <div class="col-md-7">
        <span class="input-group-btn" style="width: 100%">
            <?= \yii\bootstrap\Html::activeTextInput($model, 'reff_no',['class'=>'form-control','prompt'=>'','disabled'=>true,'placeholder'=>'Pilih Nomor Log & Jasa']); ?>
        </span>
        <span class="input-group-btn" style="width: 25%">
            <a id="btn-open-nota" class="btn btn-icon-only btn-default tooltips" onclick="openLogjasa();" data-original-title="Cari Nomor Log & Jasa" style="margin-left: 3px; border-radius: 4px;"><i class="fa fa-search"></i></a>
        </span>
    </div>
</div>

<?php } ?>