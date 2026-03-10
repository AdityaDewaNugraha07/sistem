<table class="table table-bordered " id="table-koreksi">
    <thead>
    <tr>
        <th>No.</th>
        <th>Produk</th>
        <th>Pcs</th>
        <th>M<sup>3</sup></th>
        <th>Harga</th>
        <th>Harga Koreksi</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    <?php

    use app\components\DeltaFormatter;
    use app\models\TNotaPenjualan;
    use app\models\TNotaPenjualanDetail;
    use app\models\TPengajuanManipulasi;
    use yii\helpers\Json;

    /** @var TPengajuanManipulasi $model */

    $modNota = TNotaPenjualan::findOne(['kode' => $model->reff_no]);
    $modNotaDetails = TNotaPenjualanDetail::findAll(['nota_penjualan_id' => $modNota->nota_penjualan_id]);
    $total_harga = 0;
    foreach ($modNotaDetails as $i => $detail) {
        $subtotal = in_array($detail->produk->produk_group, ['Plywood', 'Lamineboard', 'Platform'])
            ? $detail->harga_jual * $detail->qty_kecil
            : $detail->harga_jual * number_format($detail->kubikasi, 4);
        $detail->ppn = $modNota->cust_is_pkp ? DeltaFormatter::formatNumberForUserFloat($detail->ppn) : 0;
        $detail->harga_jual_lama = DeltaFormatter::formatNumberForUserFloat($detail->harga_jual);
        $detail->subtotal = DeltaFormatter::formatNumberForUserFloat($subtotal);

        if (!empty($modAjuan->pengajuan_manipulasi_id)) {
            $datadetail = Json::decode($modAjuan->datadetail1);
            $hargabaru = 0;

            foreach ($datadetail['old']['t_nota_penjualan_detail'] as $hh => $detail_old) {
                if ($detail_old['nota_penjualan_detail_id'] === $detail->nota_penjualan_detail_id) {
                    $detail->harga_jual_lama = $detail_old['harga_jual'];
                }
            }

            foreach ($datadetail['new']['t_nota_penjualan_detail'] as $ii => $detttt) {
                if ($detttt['nota_penjualan_detail_id'] === $detail->nota_penjualan_detail_id) {
                    $detail->harga_jual_baru = $detttt['harga_jual'];

                }
            }
        }

        $produk = $detail->notaPenjualan->jenis_produk === 'Limbah'
            ? $detail->limbah->limbah_kode . ' - ' . '( ' . $detail->limbah->limbah_produk_jenis . ' ) ' . $detail->limbah->limbah_nama
            : $detail->produk->produk_nama;
        echo "<tr>";
        echo "  <td style='text-align:center;'>" . ($i + 1) . "</td>";
        echo "  <td style='text-align:left;'>" . $produk . "</td>";
        echo "  <td style='text-align:right;'>" . DeltaFormatter::formatNumberForUserFloat($detail->qty_kecil) . "</td>";
        echo "  <td style='text-align:right;'>" . DeltaFormatter::formatNumberForUserFloat($detail->kubikasi) . "</td>";
        echo "  <td style='text-align:right;'>" . DeltaFormatter::formatnumberforUserFloat($detail->harga_jual_lama) . "</td>";
        echo "  <td style='text-align:right;'>" . DeltaFormatter::formatnumberforUserFloat($detail->harga_jual_baru) . "</td>";
        echo "  <td style='text-align:right;'>" . DeltaFormatter::formatnumberforUserFloat($detail->subtotal) . "</td>";
        echo "</tr>";
        $total_harga += $subtotal;
    }
    $modNota->total_harga = number_format($total_harga);
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="6" class="text-align-right"><b>Total Harga &nbsp;</b></td>
        <td style="text-align: right">
            <?= DeltaFormatter::formatNumberForUserFloat($modNota->total_harga) ?>
        </td>
    </tr>
    <tr>
        <td colspan="5"></td>
        <td class="text-align-right"><b>TOTAL &nbsp;</b></td>
        <td style="text-align: right">
            <?= DeltaFormatter::formatNumberForUserFloat($modNota->total_bayar) ?>
        </td>
    </tr>
    </tfoot>
</table>