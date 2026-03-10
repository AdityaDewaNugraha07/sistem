<?php
/** @var MBrgProduk $modBrgProduk */
/** @var HPersediaanProduk $modPersediaan */
/** @var string $nomor_produksi */
/** @var integer $spm_ko_id */
/** @var integer $gudang_id */

use app\components\DeltaFormatter;
use app\models\HPersediaanProduk;
use app\models\MBrgProduk;
use yii\helpers\Url;

?>

<div class="modal fade zzz" id="modal-review" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left"><b>REVIEW SCAN PENGEMBALIAN BARANG</b></div>
            <div class="modal-body text-center">
                <style>
                    td {
                        text-align: left;
                    }
                </style>
                <table style="margin-left: -5px;">
                    <tr>
                        <td style="width: 140px;">Product Type</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modBrgProduk->produk_group ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Grade</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modBrgProduk->grade ?></td>
                    </tr>
                    <?php
                    if (in_array($modBrgProduk->produk_group, ["Moulding", "Plywood", "Sawntimber"])) {
                        if ($modBrgProduk->produk_group === "Moulding") {
                            $label = "Profile";
                            $value = $modBrgProduk->profil_kayu;
                        } else if ($modBrgProduk->produk_group === "Plywood") {
                            $label = "Glue";
                            $value = $modBrgProduk->glue;
                        } else {
                            $label = "Condition";
                            $value = $modBrgProduk->kondisi_kayu;
                        }
                        ?>
                        <tr>
                            <td style="width: 140px;">Wood</td>
                            <td> :</td>
                            <td style="padding-left: 10px;"><?= $modBrgProduk->jenis_kayu ?></td>
                        </tr>
                        <tr>
                            <td style="width: 140px;"><?= $label ?></td>
                            <td> :</td>
                            <td style="padding-left: 10px;"><?= $value ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    <tr>
                        <td style="width: 140px;">Nomor Produksi</td>
                        <td> :</td>
                        <td style="padding-left: 10px; color: #f00;"><b><?= $nomor_produksi ?></b></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Length</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modBrgProduk->produk_p ?><?= $modBrgProduk->produk_p_satuan ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Width</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modBrgProduk->produk_l ?><?= $modBrgProduk->produk_l_satuan ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Thickness</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modBrgProduk->produk_t ?><?= $modBrgProduk->produk_t_satuan ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Palet</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modPersediaan->in_qty_palet ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Kecil</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= $modPersediaan->in_qty_kecil ?><?= $modPersediaan->in_qty_kecil_satuan ?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kubikasi</td>
                        <td> :</td>
                        <td style="padding-left: 10px;"><?= DeltaFormatter::formatNumberForUser($modPersediaan->in_qty_m3, 4) ?>
                            m<sup>3</sup></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <button type="button" id="btn-save-details"
                                    class="btn btn-primary btn-outline ciptana-spin-btn ladda-button"
                                    onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Penerimaan"><span
                                        class="ladda-label">Simpan</span><span class="ladda-spinner"></span></button>
                        </td>
                        <td>&nbsp;</td>
                        <td class="text-center">
                            <button type="button" id="btn-close"
                                    class="btn btn-danger btn-outline ciptana-spin-btn ladda-button"
                                    onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Penerimaan">
                                <span class="ladda-label">Batal</span><span class="ladda-spinner"></span></button>
                        </td>
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
<?php $this->registerCssFile($this->theme->baseUrl . "/pages/css/profile.min.css"); ?>

<script>
    function saveItem() {
        $('#modal-review').modal('toggle');
        const spm_ko_id = '<?= $spm_ko_id?>';
        const nomor_produksi = '<?= $nomor_produksi?>';
        const gudang_id = '<?= $gudang_id ?>';

        $.ajax({
            url: '<?= Url::toRoute(['/gudang/scanprodukkembali/SaveNomorProduksi']) ?>',
            type: 'POST',
            data: {
                spm_ko_id: spm_ko_id,
                nomor_produksi: nomor_produksi,
                gudang_id: gudang_id
            },
            success: function (data) {
                if(data.status === false) {
                    if(!Array.isArray(data.msg)) {
                        for (const column in data.msg) {
                            data.msg[column].forEach(row => cisAlert(row, 'Scanning failed with message:'));
                        }
                    }else {
                        data.msg.forEach(row => cisAlert(row, 'Scanning failed with message:'));
                    }
                }
                if (data.status) {
                    data.msg.forEach(row => cisAlert(row));
                    $('.btn-refresh').trigger('click');
                }
            },
            error: function (jqXHR) {
                getdefaultajaxerrorresponse(jqXHR);
            },
        });
    }

    function cancelItem() {
        $('#modal-review').modal('toggle');
    }
</script>