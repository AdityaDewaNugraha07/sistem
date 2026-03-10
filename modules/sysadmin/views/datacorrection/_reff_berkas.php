<?php if($tipe == "KOREKSI HARGA JUAL" || $tipe == "KOREKSI NOPOL MOBIL" || $tipe == "KOREKSI ALAMAT BONGKAR" || $tipe == "POTONGAN PIUTANG"){ ?>

<div class="form-group">
    <label class="col-md-4 control-label">Dokumen Terkait</label>
    <div class="col-md-7">
        <a class="btn btn-xs btn-outline purple" onclick="infoNota('<?= $model->kode ?>')"><i class="icon-tag"></i> Lihat Nota</a>
    </div>
</div>

<?php } ?>
