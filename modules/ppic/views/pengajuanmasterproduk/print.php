<?php
/* @var $this yii\web\View */

use app\models\TApproval;
use app\models\TPengajuanDrp;
use app\models\TPengajuanMasterproduk;

$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
// $header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanP',['model'=>$model,'paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<style>
table{
	font-size: 1.4rem;
}
</style>

<?php 
$pagebreak = '  <tr>
                    <td style="border-right: solid 1px #000; padding: 6px;" >&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="border-right: solid 1px #000; padding: 6px;">&nbsp;</td>
                    <td style="padding: 6px;">&nbsp;</td>
                </tr> ';
?>
<table style="width: 19cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%">
                <tr>
                    <td colspan="5">
                        <table style="width: 100%; " border="0">
							<tr style="">
								<td style="text-align: left; vertical-align: middle; padding: 8px; width: 5.5cm; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
									<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
								</td>
								<td rowspan="4" style="border-right: solid 1px transparent; ">
                                    <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                                </td>
								<td style="width: 5.5cm; height: 1cm; vertical-align: top; padding-top: 15px; padding-right: 8px;">
									<table>
										<tr style="font-size: 1.2rem;">
											<td style="width:2cm; ">Kode</td>
											<td>: &nbsp; <?= $model->kode; ?></td>
										</tr>
										<tr style="font-size: 1.2rem;">
											<td>Tanggal</td>
											<td>: &nbsp; <?= app\components\DeltaFormatter::formatDateTimeForUser2( $model->tanggal ); ?> </td>
										</tr>
                                        <?php if($model->cancel_transaksi_id != null){ ?>
                                            <tr style="font-size: 1.2rem;">
                                                <td colspan="2" style="padding-top: 15px; border-bottom: solid 1px transparent;">
                                                    <span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>
                                                    <?php
                                                        $modCancel = app\models\TCancelTransaksi::findOne($model->cancel_transaksi_id);
                                                        echo "<br><span style='font-size:1.1rem;' class='font-red-mint'>Dibatalkan karena ".$modCancel->cancel_reason."</span>";
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
									</table>
								</td>
							</tr>
						</table>
                    </td>
                </tr>
				<tr> <!-- style="width: 19cm; vertical-align: middle; border-bottom: solid 1px transparent; padding: 0px;" -->
                    <td colspan="5">
                        <table style="width: 100%; " border="0">
                            <tr style="border-bottom: solid 1px #000;border-top: solid 1px #000;">
                                <td style="width: 2cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">No.</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Jenis Produk</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Kode Produk</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Nama Produk</td>
                                <td style="width: 8cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Dimensi</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Jenis Kayu</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Grade</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Warna Kayu</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Glue</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Profil Kayu</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px;">Kondisi Kayu</td>
                            </tr>
                            <?php 
                            foreach($modDetail as $i => $detail){
                                $jenis_kayu = ($detail['jenis_kayu'] == null || $detail['jenis_kayu'] == 'null' || $detail['jenis_kayu'] == '')?'-':$detail['jenis_kayu'];
                                $grade = ($detail['grade'] == null || $detail['grade'] == 'null' || $detail['grade'] == '')?'-':$detail['grade'];
                                $warna_kayu = ($detail['warna_kayu'] == null || $detail['warna_kayu'] == 'null' || $detail['warna_kayu'] == '')?'-':$detail['warna_kayu'];
                                $glue = ($detail['glue'] == null || $detail['glue'] == 'null' || $detail['glue'] == '')?'-':$detail['glue'];
                                $profil_kayu = ($detail['profil_kayu'] == null || $detail['profil_kayu'] == 'null' || $detail['profil_kayu'] == '')?'-':$detail['profil_kayu'];
                                $kondisi_kayu = ($detail['kondisi_kayu'] == null || $detail['kondisi_kayu'] == 'null' || $detail['kondisi_kayu'] == '')?'-':$detail['kondisi_kayu'];
                            ?>
                            <tr>
                                <td style="border-right: solid 1px #000; font-size: 1.2rem; text-align: center; vertical-align: top; padding-top: 5px;"><?= $i+1; ?></td>
                                <td style="padding-left: 5px; padding-bottom: 10px; border-right: solid 1px #000; font-size: 1.2rem; vertical-align: top; padding-top: 5px;"><?= $detail['produk_group']; ?></td>
                                <td style="padding-left: 5px; border-right: solid 1px #000; font-size: 1.2rem; vertical-align: top; padding-top: 5px;"><?= $detail['produk_kode']; ?></td>
                                <td style="padding-left: 5px; border-right: solid 1px #000; font-size: 1.2rem; vertical-align: top; padding-top: 5px;"><?= $detail['produk_nama']; ?></td>
                                <td style="border-right: solid 1px #000; text-align: center; vertical-align: top; font-size: 1.2rem; padding-top: 5px;"><?= $detail['produk_dimensi']; ?></td>
                                <td style="border-right: solid 1px #000; text-align: center; font-size: 1.2rem; vertical-align: top; border-right: solid 1px #000; padding-top: 5px;"><?= $jenis_kayu; ?></td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: center; vertical-align: top; padding-right: 5px; border-right: solid 1px #000; padding-top: 5px;"><?= $grade ?></td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: center; vertical-align: top; padding-right: 5px; border-right: solid 1px #000; padding-top: 5px;"><?= $warna_kayu ?></td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: center; vertical-align: top; padding-right: 5px; border-right: solid 1px #000; padding-top: 5px;"><?= $glue ?></td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: center; vertical-align: top; padding-right: 5px; border-right: solid 1px #000; padding-top: 5px;"><?= $profil_kayu ?></td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: center; vertical-align: top; padding-right: 5px; padding-top: 5px;"><?= $kondisi_kayu ?></td>
                            </tr>
                            <?php } ?>
                            <?= $pagebreak; ?>
                        </table>
                    </td>
				</tr>
                <tr>
                    <td colspan="5">
                        <table style="width: 100%; font-size: 1.1rem; text-align: center; border: solid 1px #000; border-bottom: solid 1px #000; border-left: solid 1px transparent;" border="1">
							<tr style="height: 0.4cm;  border-right: solid 1px transparent; ">
								<td rowspan="3" style="width: 7cm; vertical-align: bottom; text-align: left; font-size: 0.9rem;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="vertical-align: middle; width: 4.6cm; background-color: #F1F4F7;">Diajukan Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Diketahui Oleh</td>
								<td style="vertical-align: middle; width: 2.8cm; background-color: #F1F4F7;">Disetujui Oleh</td>
							</tr>
							<tr>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; width: 2.3cm; font-size: 0.8rem; text-align: center;"><i>Kadep PPIC</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; font-size: 0.8rem; text-align: center;"><i>Kadiv Operasional</i></td>
								<td style="height: 55px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent; font-size: 0.8rem; text-align: center;"><i>Direktur</i></td>
							</tr>
							<tr style="background-color: #F1F4F7;">
								</td>
								<td style="height: 20px; vertical-align: middle; font-size: 0.9rem;">
									<?= $model->prepared_by?$model->preparedby->pegawai_nama:"" ?>
								</td>
								<td style="height: 20px; vertical-align: middle; font-size: 0.9rem;">
									<?= $model->reviewed_by?$model->reviewedby->pegawai_nama:"" ?>
								</td>
								<td style="height: 20px; vertical-align: middle;  border-right: solid 1px transparent;  ">
                                    <?= $model->approved_by?$model->approvedby->pegawai_nama:"" ?>
								</td>
							</tr>
						</table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
</table>
