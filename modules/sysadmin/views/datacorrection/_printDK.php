<?php
/**
 * @var array $paramprint
 * @var TPengajuanManipulasi $model
 * @var $this yii\web\View
 */

use app\components\DeltaFormatter;
use app\models\TPengajuanManipulasi;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Print ' . $paramprint['judul'];

$kode = $model->kode;
$departement_nama = '';
try {
    $departement_nama = Yii::$app->db->createCommand("select departement_nama from m_departement where departement_id = " . $model->departement_id)->queryScalar();
    $approver1 = $model->approver1 !== null ? Yii::$app->db->createCommand("select * from view_approval where assigned_to = $model->approver1 AND reff_no = '$model->kode'")->queryOne() : '';
    $approver2 = $model->approver2 !== null ? Yii::$app->db->createCommand("select * from view_approval where assigned_to = $model->approver2 AND reff_no = '$model->kode'")->queryOne() : '';
    $approver3 = $model->approver3 !== null ? Yii::$app->db->createCommand("select * from view_approval where assigned_to = $model->approver3 AND reff_no = '$model->kode'")->queryOne() : '';
    $approver4 = $model->approver4 !== null ? Yii::$app->db->createCommand("select * from view_approval where assigned_to = $model->approver4 AND reff_no = '$model->kode'")->queryOne() : '';
    $approver5 = $model->approver5 !== null ? Yii::$app->db->createCommand("select * from view_approval where assigned_to = $model->approver5 AND reff_no = '$model->kode'")->queryOne() : '';
} catch (\yii\db\Exception $exception) {
    echo $exception->getMessage();
}

if ($_GET['caraprint'] === "EXCEL") {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - ' . date("d/m/Y") . '.xls"');
    header('Cache-Control: max-age=0');
    $header = "";
}
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert');?>
<link href="<?= Url::base() . '/themes/metronic/global/plugins/bootstrap/css/bootstrap.min.css' ?>" rel="stylesheet" media='all'>
<style>
    table {
        font-size: 12px;
    }

    table#table-detail tr td {
        vertical-align: top;
    }

    td {
        padding-left: 10px;
        padding-right: 10px;
        height: 30px;
    }
</style>

<table style="width: 20cm; margin: 10px; height: 3cm; border: solid 1px;">
    <tr>
        <td style="padding: 5px;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left; vertical-align: middle; padding: 0; width: 2cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
                        <img src="<?= Yii::$app->view->theme->baseUrl . '/cis/img/logo-ciptana.png' ?>" alt=""
                             class="logo-default" style="width: 80px;">
                    </td>
                    <td style="text-align: center; vertical-align: middle; padding: 10px; line-height: 1.3;">
                        <span style="font-size: 1.9rem; font-weight: 600">Pengajuan Koreksi</span><br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px; border-top: solid 1px;">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                        <table>
                            <tr>
                                <td>Kode</td>
                                <td> :</td>
                                <td> <?= $model->kode ?></td>
                            </tr>
                            <tr>
                                <td>Nomor</td>
                                <td>:</td>
                                <td> <?= $model->reff_no ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td> :</td>
                                <td> <?= DeltaFormatter::formatDateTimeForUser($model->tanggal) ?></td>
                            </tr>
                            <tr>
                                <td>Departement Pemohon</td>
                                <td> :</td>
                                <td> <?= $departement_nama ?></td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%; vertical-align: top; padding-left: 10px;">
                        <table>
                            <?php
                            $approver = "";
                            $jml_approver = 0;
                            for ($i = 1; $i <= 5; $i++):
                                $approver = "approver" . $i;
                                $approver = $$approver;
                                if ($approver !== ''): ?>
                                    <tr>
                                        <td>Approver <?= $i ?></td>
                                        <td>:</td>
                                        <td><?= $approver['assigned_nama'] ?></td>
                                    </tr>
                                <?php $jml_approver++; endif ?>
                            <?php endfor; ?>
                            <tr>
                                <td>Priority</td>
                                <td>:</td>
                                <td> <?= $model->priority ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px 10px 0;border-bottom: solid 1px;">
            <table style="width: 100%;">
                <tr style="border-bottom: solid 1px transparent;">
                    <td colspan="3" style="border-top: solid 1px transparent;">
                        <table>
                            <tr>
                                <td>Jenis Pengajuan</td>
                                <td> :</td>
                                <td><b><?= $model->tipe ?></b></td>
                            </tr>
                            <tr>
                                <td>Alasan</td>
                                <td> :</td>
                                <td><?= $model->reason ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style=" border-top: solid 1px transparent;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 10px;">
            <h5 class='text-center' style="height: 30px; font-weight: bold;">Koreksi Data</h5>
            <div id="place-koreksi-data">
                <?php
                switch ($_GET['tipe']) {
                    case 'POTONGAN PIUTANG':
                        echo $this->render('_print/_potongan_piutang', compact('model'));
                        break;
                    case 'KOREKSI HARGA JUAL':
                        echo $this->render('_print/_koreksi_harga_jual', compact('model'));
                        break;
                    case 'KOREKSI NOPOL MOBIL':
                        echo $this->render('_print/_koreksi_nopol_mobil', compact('model'));
                        break;
                    case 'KOREKSI ALAMAT BONGKAR':
                        echo $this->render('_print/_koreksi_alamat_bongkar', compact('model'));
                        break;
                    case 'KOREKSI PIUTANG LOG & JASA':
                        echo $this->render('_print/_koreksi_piutang_log_jasa', compact('model'));
                        break;
                    default:
                        echo "<span style='display: block;margin: 20px auto;text-align: center;color: red;font-style: italic;'>Jenis pengajuan tidak diketahui</span>";
                        break;
                }
                ?>

            </div>
        </td>
    </tr>
    <tr>
        <td class="text-center">
            <table style="width: 100%;">
                <tr>
                    <?php
                    $approver = "";
                    for ($i = $jml_approver; $i >= 1; $i--) :
                        $approver = "approver" . $i;
                        $approver = $$approver;
                        $tanggal_jam = DeltaFormatter::formatDateTimeForUser($approver['updated_at']);

                        if ($approver !== null) : ?>
                            <td class="text-center" style="border: solid 0;">
                                <?= strtoupper($approver['status']) ?>
                                <br>
                                <?= $approver['assigned_nama'] ?>
                                <br>(<span style="font-size: 10px;"><?= $tanggal_jam . " WIB" ?></span>)
                            </td>
                        <?php endif ?>
                    <?php endfor ?>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>

<div id="pick-panel"></div>
<script src="<?= Url::base() . '/themes/metronic/cis/plugins/accounting.js' ?>"></script>