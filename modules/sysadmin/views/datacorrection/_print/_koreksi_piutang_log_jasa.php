<?php
/** @var TPengajuanManipulasi $model */

use app\components\DeltaFormatter;
use app\models\TPengajuanManipulasi;
use app\models\TPiutangAlert;
use app\models\TPiutangAlertDetail;
use yii\helpers\Json;

$modPiutangAlert = TPiutangAlert::findOne(['piutang_nomor_nota' => $model->reff_no]);
//$modDetail = [];
//try {
//    $sql = "
//        SELECT *, (termin_tagihan - termin_terbayar) AS sisa_bayar
//        FROM t_piutang_alert_detail
//        WHERE piutang_alert_id = $modPiutangAlert->piutang_alert_id
//    ";
//    $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
//} catch (\yii\db\Exception $e) {
//    echo $e->getMessage();
//}
?>
<table style="width: 100%">
    <tr>
        <td style="width: 100px; vertical-align: top;"><b>Customer</b></td>
        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
        <td><?= $modPiutangAlert->cust->cust_an_nama ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><b>Alamat</b></td>
        <td style="vertical-align: top;"><b>:</b></td>
        <td><?= $modPiutangAlert->cust->cust_an_alamat ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><b>No. NPWP</b></td>
        <td style="vertical-align: top;"><b>:</b></td>
        <td>
            <?= !empty($modPiutangAlert->cust->cust_no_npwp) ?
                substr($modPiutangAlert->cust->cust_no_npwp, 0, 2) . "." .
                substr($modPiutangAlert->cust->cust_no_npwp, 3, 3) . "." .
                substr($modPiutangAlert->cust->cust_no_npwp, 6, 3) . "." .
                substr($modPiutangAlert->cust->cust_no_npwp, 9, 1) . "-" .
                substr($modPiutangAlert->cust->cust_no_npwp, 10, 3) . "." .
                substr($modPiutangAlert->cust->cust_no_npwp, 13, 3)
                : "-"
            ?>
        </td>
        <!--99.999.999.9-999.999-->
    </tr>
    <tr>
        <td style="width: 100px; vertical-align: top;"><b>Tanggal Nota</b></td>
        <td style="width: 30px; vertical-align: top;"><b>:</b></td>
        <td><?= DeltaFormatter::formatDateTimeForUser2($modPiutangAlert->tgl_nota) ?></td>
    </tr>
    <tr>
        <td style="vertical-align: top;"><b>Tempo Bayar</b></td>
        <td style="vertical-align: top;"><b>:</b></td>
        <td><?= $modPiutangAlert->tempo_bayar . " Hari" ?></td>
    </tr>
</table>

<table class="table table-bordered" id="table-koreksi">
    <thead>
    <tr>
        <th>Termin</th>
        <th>Tagihan</th>
        <th>Terbayar</th>
        <th>Sisa Piutang</th>
        <th>Potongan</th>
        <th>Koreksi Sisa Piutang</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $termin_terbayar = 0;
    $sisa_bayar = 0;
    $potongan = 0;
    $sisa_piutang = 0;
    $koreksi = 0;
    $modDetail = Json::decode($model->datadetail1, false)->new->t_piutang_alert_detail;
    if (count($modDetail) > 0) :
        foreach ($modDetail as $i => $detail) :
            $modPiutangAlertDetail = TPiutangAlertDetail::findOne(['piutang_alert_detail_id' => $detail->piutang_alert_detail_id]);
            $terbayar = $modPiutangAlertDetail->termin_tagihan - $modPiutangAlertDetail->termin_terbayar; ?>
            <tr>
                <td style="text-align: center; vertical-align: middle"><?= $detail->termin ?></td>
                <td style="text-align: right; vertical-align: middle"><?= DeltaFormatter::formatNumberForUser($modPiutangAlertDetail->termin_tagihan) ?></td>
                <td style="text-align: right; vertical-align: middle"><?= DeltaFormatter::formatNumberForUser($modPiutangAlertDetail->termin_terbayar) ?></td>
                <td style="text-align: right; vertical-align: middle"><?= DeltaFormatter::formatNumberForUser($terbayar) ?></td>
                <td style="text-align: right; vertical-align: middle"><?= DeltaFormatter::formatNumberForUser($detail->potongan) ?></td>
                <td style="text-align: right; vertical-align: middle"><?= DeltaFormatter::formatNumberForUser($detail->sisa_bayar_baru) ?></td>
            </tr>
    <?php
    $termin_terbayar += $modPiutangAlertDetail->termin_terbayar ?: 0;
    $sisa_bayar = $modPiutangAlert->tagihan_jml - $termin_terbayar;
    $potongan += $detail->potongan ?: 0;
    $koreksi += $detail->sisa_bayar_baru;
    endforeach;
    endif;
    ?>
    </tbody>
    <tfoot>
        <tr>
            <td style='text-align:right; vertical-align: middle;'><b>TOTAL</b></td>
            <td style='text-align:right; vertical-align: middle;'><b><?= DeltaFormatter::formatnumberforUser($modPiutangAlert->tagihan_jml) ?></b></td>
            <td style='text-align:right; vertical-align: middle;'><b><?= DeltaFormatter::formatnumberforUser($termin_terbayar) ?></b></td>
            <td style='text-align:right; vertical-align: middle;'><b><?= DeltaFormatter::formatnumberforUser($sisa_bayar) ?></b></td>
            <td style='text-align:right; vertical-align: middle;'><b><?= DeltaFormatter::formatnumberforUser($potongan) ?></b></td>
            <td style='text-align:right; vertical-align: middle;'><b><?= DeltaFormatter::formatnumberforUser($koreksi) ?></b></td>
        </tr>
    </tfoot>
</table>