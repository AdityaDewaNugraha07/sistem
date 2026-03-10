<?php if($tipe == "DP LOG SENGON" || $tipe == "PELUNASAN LOG SENGON"){ ?>
<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Referensi : </label>
    <div class="col-md-8">
        <a id="btn-reff-1" class="btn btn-outline btn-xs grey" onclick="detailPoByKode()"><i class="icon-tag"></i> Lihat PO</a>
        <a id="btn-reff-2" class="btn btn-outline btn-xs grey"><i class="icon-wallet"></i> Riwayat Saldo</a>
        <a id="btn-reff-3" class="btn btn-outline btn-xs grey"><i class="fa fa-download"></i> Penerimaan</a>
    </div>
</div>
<?php } else if($tipe == "PEMBAYARAN LOG ALAM"){ ?>
<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Referensi : </label>
    <div class="col-md-8">
        <a id="btn-reff-1" class="btn btn-outline btn-xs grey" onclick="detailKeputusan()"><i class="icon-tag"></i> Lihat PO</a>
        <a id="btn-reff-2" class="btn btn-outline btn-xs grey"><i class="icon-wallet"></i> Riwayat Saldo</a>
        <a id="btn-reff-3" class="btn btn-outline btn-xs grey"><i class="fa fa-download"></i> Penerimaan</a>
    </div>
</div>
<?php } else if($tipe == "PEMBAYARAN ASURANSI LOG SHIPPING"){ ?>
<div class="form-group" style="margin-bottom: 5px;">
    <label class="col-md-4 control-label">Referensi : </label>
    <div class="col-md-8">
        <a id="btn-reff-1" class="btn btn-outline btn-xs grey" onclick="detailAsuransi()"><i class="icon-tag"></i> Lihat Detail</a>
        <a id="btn-reff-2" class="btn btn-outline btn-xs grey"><i class="icon-wallet"></i> Riwayat Saldo</a>
        <a id="btn-reff-3" class="btn btn-outline btn-xs grey"><i class="fa fa-download"></i> Penerimaan</a>
    </div>
</div>
<?php } ?>