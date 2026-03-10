<?php
/** @var TPengajuanManipulasi $model */

use app\models\MCustomer;
use app\models\TNotaPenjualan;
use app\models\TPengajuanManipulasi;
use app\models\TPiutangPenjualan;
use yii\helpers\Json;

$datadetail = Json::decode($model->datadetail1, false);
$customer = MCustomer::findOne(['cust_id' => $datadetail->new->t_piutang_penjualan->cust_id]);
$modNota = TNotaPenjualan::findOne(['kode' => $model->reff_no]);
$piutang = TPiutangPenjualan::find()->where(['bill_reff' => $modNota->kode])->andWhere(['cancel_transaksi_id' => null])->andWhere(['<>', 'cara_bayar', 'Potongan'])->all();
$terbayar = 0;
foreach ($piutang as $p) {
    $terbayar += $p->bayar;
}
$sisa_tagihan = $modNota->total_bayar - $terbayar !== null ? $modNota->total_bayar - $terbayar : 0;
$potongan = !empty($datadetail) ? $datadetail->new->t_piutang_penjualan->bayar : 0;
$sisa = $sisa_tagihan - $potongan;
?>
<style>
    #inc_potongan_piutang {
        margin: 0 auto;
        border-collapse: collapse;
        width: 100%;
    }
    #inc_potongan_piutang th {
        text-align: right;
        border: 1px solid #ccc;
    }

    #inc_potongan_piutang td {
        border: 1px solid #ccc;
    }

    #inc_potongan_piutang th {
        padding-left: 10px;
        padding-right: 10px;
        height: 30px;
    }

    #inc_potongan_piutang td span {
        width: 30%;
        display: block;
        text-align: right;
    }
</style>
<table id="inc_potongan_piutang">
    <tr>
        <th colspan="2" style="text-align: center">Data Piutang Customer : <?= $customer->cust_an_nama ?></th>
    </tr>
    <tr>
        <th style="text-align: right">Bill Reff</th>
        <td>
            <span><?= $model->reff_no ?></span>
        </td>
    </tr>
    <tr>
        <th>Nominal Bill</th>
        <td>
            <span><strong><?= number_format($modNota->total_bayar)?></strong></span>
        </td>
    </tr>
    <tr>
        <th>Pernah Terbayar</th>
        <td>
            <span><?= number_format($terbayar) ?></span>
        </td>
    </tr>
    <tr>
        <th>Sisa Tagihan</th>
        <td>
            <span><strong><?= number_format($sisa_tagihan)?></strong></span>
        </td>
    </tr>
    <tr>
        <th>Potongan</th>
        <td>
            <span><?= number_format($potongan)?></span>
        </td>
    </tr>
    <tr>
        <?php if($sisa < 0): ?>
        <th>Pengembalian</th>
        <?php else: ?>
        <th>Sisa Piutang</th>
        <?php endif ?>
        <td>
            <span><strong><?= number_format( abs($sisa) )?></strong></span>
        </td>
    </tr>
</table>