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
                        <td style="padding-left: 10px; color: #f00;"><b><?php echo $modHasilProduksi->nomor_produksi;?></b></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Length</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilProduksi->p;?> <?php echo $modHasilProduksi->p_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Width</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilProduksi->l;?> <?php echo $modHasilProduksi->l_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Thickness</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilProduksi->t;?> <?php echo $modHasilProduksi->t_satuan;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Palet</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilProduksi->qty_palet;?></td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Qty Kecil</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo $modHasilProduksi->qty_kecil;?> pcs</td>
                    </tr>
                    <tr>
                        <td style="width: 140px;">Kubikasi</td>
                        <td> : </td>
                        <td style="padding-left: 10px;"><?php echo \app\components\DeltaFormatter::formatNumberForUser($modHasilProduksi->qty_m3,4);?> m<sup>3</sup></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer text-center">
                <div class="col-md-12">
                <table style="margin-left: -30px;">
                    <tr>
                        <?php
                        if ($modKirimGudangDetail->status == "REJECTED") {
                            if(!empty($modKirimGudangDetail->reject_reason)){
                                $modRejectReason = \yii\helpers\Json::decode($modKirimGudangDetail->reject_reason);
                                echo "<div class='col-md-12 text-center'>";
                                echo "<span style='color: #f00; font-weight: bold;'>REJECTED</span>";
                                foreach($modRejectReason as $a => $b){
                                    $modPegawai = \app\models\MPegawai::findOne($b["by"]);
                                    echo "<br><span class='font-red-flamingo'>by : ".$modPegawai->pegawai_nama."</span>";
                                    echo "<br><span class='font-red-flamingo'>at : ".\app\components\DeltaFormatter::formatDateTimeForUser2($b['at'])."</span>";
                                    echo "<br><span class='font-red-flamingo'>reason : ".$b['reason']."</span>";
                                    echo "<br><br><button type='button' id='btn-close' class='btn btn-default btn-outline ciptana-spin-btn ladda-button' onclick='cancelItem();' data-style='zoom-in' title='Close'><span class='ladda-label'>Close</span><span class='ladda-spinner'></span></button>";
                                }
                                echo "</div>";
                            }
                        } else {
                        ?>
                            <td class="col-md-4"><button type="button" id="btn-save-details" class="btn btn-success btn-outline ciptana-spin-btn ladda-button" onclick="saveItem();" data-style="zoom-in" title="Simpan Detail Penerimaan"><span class="ladda-label">Simpan</span><span class="ladda-spinner"></span></button></td>
                            <td class="col-md-4"><button type="button" id="btn-reject-details" class="btn btn-danger btn-outline ciptana-spin-btn ladda-button" onclick="rejectItem(<?php echo $modKirimGudangDetail->kirim_gudang_detail_id;?>);" data-style="zoom-in" title="Reject Detail Penerimaan"><span class="ladda-label">Reject</span><span class="ladda-spinner"></span></button></td>
                            <td class="col-md-4"><button type="button" id="btn-close" class="btn btn-default btn-outline ciptana-spin-btn ladda-button" onclick="cancelItem();" data-style="zoom-in" title="Batalkan Detail Penerimaan"><span class="ladda-label">Close</span><span class="ladda-spinner"></span></button></td>
                        <?php
                        }
                        ?>
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
    var nomor_produksi = '<?php echo $modHasilProduksi->nomor_produksi;?>';
    console.log(gudang_id+' '+nomor_produksi);
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/saveNomorProduksi']); ?>',
        type   : 'POST',
        data   : {gudang_id:gudang_id, nomor_produksi:nomor_produksi},

        success: function (data) {
			if(data){
                window.location.href = "/cis/web/gudang/penerimaanko/scanterima";
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function rejectItem (id) {
    openModal('<?= \yii\helpers\Url::toRoute(['/gudang/penerimaanko/rejectReasonNomorProduksi', 'id'=>''])?>'+id,'modal-madul','50%');
}

function cancelItem () {
    $('#modal-review').modal('toggle');
}
</script>