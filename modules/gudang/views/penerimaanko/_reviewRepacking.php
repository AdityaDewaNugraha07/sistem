<?php
$gudang_nm = Yii::$app->db->createCommand("select gudang_nm from m_gudang where gudang_id = ".$gudang_id."")->queryScalar();
?>
<div class="modal fade zzz" id="modal-review" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b>REVIEW SCAN TERIMA <span style="color: #f00;">GUDANG <?php echo $gudang_nm;?></b></div>
            <div class="modal-body text-center">
                <style>
                    td { text-align: left;}
                </style>
                <table style="margin-left: -5px;">
                    <tr>
                        <td style="width: 140px;">Product Type</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modBrgProduk->produk_group;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Grade</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modBrgProduk->grade;?></td>
                    </tr>
                    <?php
                    if ($modBrgProduk->produk_group == "Moulding" || $modBrgProduk->produk_group == "Plywood" || $modBrgProduk->produk_group == "Sawntimber") {
                        if ($modBrgProduk->produk_group == "Moulding") {
                            $label = "Profile";
                            $value = $modBrgProduk->profil_kayu;
                        } else if ($modBrgProduk->produk_group == "Plywood") {
                            $label = "Glue";
                            $value = $modBrgProduk->glue;
                        } else {
                            $label = "Condition";
                            $value = $modBrgProduk->kondisi_kayu;
                        }
                    ?>
                    <tr>
                        <td style="width: 140px;">Wood</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modBrgProduk->jenis_kayu;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;"><?php echo $label;?></td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $value;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td style="width: 140px;">Nomor Produksi</td>
                        <td> : </td>
                        <td style="padding-left: 10px; color: #f00;"><b><?php echo $modProduksi->nomor_produksi;?></b></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Length</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilRepacking->p." ".$modHasilRepacking->p_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Width</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilRepacking->l." ".$modHasilRepacking->l_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Thickness</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilRepacking->t." ".$modHasilRepacking->t_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Palet</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilRepacking->qty_palet;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Kecil</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilRepacking->qty_kecil;?> pcs</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kubikasi</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modHasilRepacking->qty_m3,4);?> m<sup>3</sup></td>
                    </tr>
                    <tr>
                        <td colspan="3"><hr></td>
                    </tr>
                    <tr>
                        <td class="text-center"><button type="button" id="btn-save-details" class="btn btn-primary btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Penerimaan"><span class="ladda-label">Simpan</span><span class="ladda-spinner"></span></button></td>
                        <td>&nbsp;</td>
                        <td class="text-center"><button type="button" id="btn-close" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Penerimaan"><span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button></td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<?php $this->registerJs(" 
formconfig(); 
", yii\web\View::POS_READY); ?>
<?php $this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css"); ?>

<script>
function saveItem () {
    $('#modal-review').modal('toggle');
    var gudang_id = <?php echo $gudang_id;?>;
    var nomor_produksi = '<?php echo $modProduksi->nomor_produksi;?>';
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/SaveNomorProduksiRepacking']); ?>',
        type   : 'POST',
        data   : {gudang_id:gudang_id, nomor_produksi:nomor_produksi},

        success: function (data) {
			if(data){
                window.location.href = "/cis3/web/gudang/penerimaanko/terimarepacking";
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function cancelItem () {
    $('#modal-review').modal('toggle');
}
</script>