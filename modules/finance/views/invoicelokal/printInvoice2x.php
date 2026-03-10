<?php
/* @var $this yii\web\View */

use app\models\MBrgLog;
use app\models\MKayu;
use app\models\TNotaPenjualanDetail;
use app\models\TOpKo;
use app\models\TPoKo;
use app\models\TPoKoDetail;

$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
if(isset($_GET['caraprint'])){
    if($_GET['caraprint'] == "EXCEL"){
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
        header('Cache-Control: max-age=0');
        $header = "";
    }
}
$tablewidth = "19";
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>
<style>
table{
	font-size: 1.1rem;
}
table#table-detail{
	font-size: 1rem;
}
table#table-detail tr td{
	vertical-align: top;
}
</style>
<table style="width: <?= $tablewidth ?>cm; margin: 10px; border-collapse: collapse;" border="1">
	<tr>
		<td style="width: 70%; height: 3cm; vertical-align: middle; padding: 5px 10px; border-right: solid 1px transparent;">
			<table style="width: 100%;">
                <tr>
                    <td colspan="3" style="padding-right: 30px;">
                        Kepada Yth. <br>
                        <b><?php echo strtoupper($model->cust->cust_an_nama);?></b><br>
                        <?php echo strtoupper($model->cust->cust_an_alamat);?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
			</table>
		</td>
		<td style="width: 10%; vertical-align: top; padding: 10px 10px; border-right: solid 1px transparent;">
			<table style="width: 100%;">
				<tr>
					<td style="vertical-align: top; padding-top: 20px;"><b>NOMOR :</b></td>
				</tr>
			</table>
		</td>
        <td style="width: 20%; vertical-align: top; padding: 10px 10px;">
            <table style="width: 100%;">
                <tr>
                    <td style="vertical-align: top; padding-top: 20px;">
                        <?php 
                        $tanggal = explode('-', $model->tanggal);
                        $bln_tgl = $tanggal[1] . $tanggal[2];
                        $nota = explode('/', $model->kode);
                        $kode = 'LGN'.$bln_tgl.$nota[0] . '/' . \app\components\DeltaFunctions::Romawi(date('m')) . '/' .date("Y");
                        echo $kode;
                        ?>
                    </td>
                </tr>
            </table>
        </td>
	</tr>
    <tr>
        <td colspan="3" style="border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
            <span style="padding-left: 10px;">
                Berikut ini kami sampaikan tagihan dengan perincian sebagai berikut : 
            </span>
            <table style="width: 100%" id="table-invoice">
                <thead>
                    <tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
                        <th style="text-align: center; vertical-align: middle; height: 0.8cm; border-right: solid 1px #000;">No</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Keterangan</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Jumlah</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Harga Satuan</th>
                        <th style="text-align: center; vertical-align: middle;">Total</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td style="text-align: center; border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right;">&nbsp;</td>
                        </tr>
                        <?php 
                        $arr_nota = $model->nota_penjualan;
                        $nota = json_decode($arr_nota);
                        $notaList = implode(',', $nota); 
                        // $sql = "
                        //     SELECT harga_jual, COUNT(*) AS qty, SUM(kubikasi) AS kubikasi
                        //     FROM t_nota_penjualan_detail
                        //     WHERE nota_penjualan_id IN ($notaList)
                        //     GROUP BY harga_jual
                        // ";
                        $sql = "
                            SELECT t_nota_penjualan.tanggal, harga_jual, COUNT(*) AS qty, SUM(kubikasi) AS kubikasi
                            FROM t_nota_penjualan_detail
                            JOIN t_nota_penjualan on t_nota_penjualan.nota_penjualan_id = t_nota_penjualan_detail.nota_penjualan_id
                            WHERE t_nota_penjualan_detail.nota_penjualan_id IN ($notaList)
                            GROUP BY harga_jual, t_nota_penjualan.tanggal
                            ORDER BY t_nota_penjualan.tanggal
                        ";
                        $modDet = Yii::$app->db->createCommand($sql)->queryAll();
                        $no = 0; $subtotal = 0; $kubikasi = 0; $total = 0;
                        foreach($modDetails as $inv_detail){
                            $nota = \yii\helpers\Json::decode($inv_detail->deskripsi_nota);
                            foreach($nota as $n){
                                $modLog = MBrgLog::findOne($n['produk_id']);
                                $modKayu = MKayu::findOne($modLog->kayu_id);
                            }
                        }
                        if(count($modDet) > 0){
                            foreach ($modDet as $det){ 
                                $kubikasi += $det['kubikasi'];
                                if($model->include_ppn){
                                    $harga_jual = $det['harga_jual'] / 1.11;
                                } else {
                                    $harga_jual = $det['harga_jual'];
                                }
                                // $keterangan = $det['qty'] . ' Btg Log '. $modKayu->kayu_nama .' tgl ' . app\components\DeltaFormatter::formatDateTimeId($model->tanggal);
                                $keterangan = $det['qty'] . ' Btg Log '. $modKayu->kayu_nama .' tgl ' . app\components\DeltaFormatter::formatDateTimeId($det['tanggal']);
                                $subtotal = $harga_jual * $det['kubikasi'];
                                $total += $subtotal;
                                ?>
                                <tr>
                                    <td style="width: 1cm; text-align: center; padding: 5px; border-right: solid 1px #000;border-bottom: solid 1px #000;"><?= $no+1; ?></td>
                                    <td style="padding: 5px; border-right: solid 1px #000; border-bottom: solid 1px #000;"><?php echo $keterangan; ?></td>
                                    <td style="width: 70px; padding: 5px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"><?= $det['kubikasi'] . ' m<sup>3</sup>'; ?></td>
                                    <td style="width: 120px; padding: 5px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">
                                        <span class='pull-left'>Rp.</span>
                                        <span class='pull-right'><?php echo number_format($harga_jual); ?></span>
                                    </td>
                                    <td style="width: 120px; padding: 5px; text-align: right; border-bottom: solid 1px #000;">
                                        <span class='pull-left'>Rp.</span>
                                        <span class='pull-right'><?php echo number_format($subtotal); ?></span>
                                    </td>
                                </tr>
                            <?php $no++;
                            }
                        }
                        ?>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 5px;">Dikurangi Potongan</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-left: 5px; padding-right: 5px;">
                                <span class='pull-left'>Rp.</span>
                                <span class='pull-right'>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 5px;">Tax Base (DPP)</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-left: 5px; padding-right: 5px;">
                                <span class='pull-left'>Rp.</span>
                                <span class='pull-right'>
                                    <?php 
                                    $dpp = round($total * 11/12);
                                    echo number_format($dpp);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 5px;">PPN = 12% Dasar Pengenaan Pajak</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-left: 5px; padding-right: 5px;">
                                <span class='pull-left'>Rp.</span>
                                <span class='pull-right'>
                                    <?php 
                                    $ppn = round($total * 11 / 100);
                                    echo number_format($ppn);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php if($model->kawasan_berikat){ ?>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 5px;">PPN Tidak Dipungut (Kawasan Berikat)</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-left: 5px; padding-right: 5px;">
                                <span class='pull-left'>Rp.</span>
                                <span class='pull-right'>
                                    <?php 
                                    $ppn = round($total * 11 / 100);
                                    echo '-'.number_format($ppn);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if($model->ceklis_pph){ ?>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 5px;">PPh 22 = 0,25% Dasar Pengenaan Pajak</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-left: 5px; padding-right: 5px;">
                                <span class='pull-left'>Rp.</span>
                                <span class='pull-right'>
                                    <?php 
                                    if($model->jenis_produk == "Log"){
                                        $pph = round($total * 0.25 / 100);
                                    } else {
                                        $pph = round($total * 2 /100);
                                    }
                                    echo '-'.number_format($pph);
                                    ?>
                                </span>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">
                                <table style="width: 100%">
                                    <tr>
                                        <td style="padding-left: 150px; padding-top: 5px;"><b>PO No.</b></td>
                                        <td>
                                            <?php 
                                            $modPo = TPoKo::findOne(['invoice_lokal_id'=>$model->invoice_lokal_id]);
                                            echo '<b>'.$modPo->kode.'</b>';
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 150px; padding-bottom: 5px;"><b>Tgl</b></td>
                                        <td>
                                            <?php 
                                            $modPo = TPoKo::findOne(['invoice_lokal_id'=>$model->invoice_lokal_id]);
                                            echo '<b>'.app\components\DeltaFormatter::formatDateTimeId($modPo->tanggal).'</b>';
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding-left: 150px;"><b>JUMLAH TAGIHAN</b></td>
                            <td style="width: 70px; text-align: right; border-right: solid 1px #000; border-bottom: solid 1px #000; padding-right: 5px;"><b><?= $kubikasi . ' m<sup>3</sup>'; ?></b></td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="width: 120px; text-align: right; border-bottom: solid 1px #000; padding-right: 5px;">
                                <?php 
                                if($model->kawasan_berikat){
                                    if($model->ceklis_pph){
                                        $grand_total = $total - $pph;
                                    } else {
                                        $grand_total = $total;
                                    }
                                } else {
                                    if($model->ceklis_pph){
                                        $grand_total = $total + $ppn - $pph;
                                    } else {
                                        $grand_total = $total + $ppn;
                                    }
                                }
                                echo '<b>'.number_format($grand_total).'</b>';
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 1cm; text-align: center; border-right: solid 1px #000;border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-bottom: solid 1px #000; padding-left: 5px; padding-bottom: 5px; padding-top: 5px;" colspan="4">
                                Terbilang : <br>
                                <?php echo \app\components\DeltaFormatter::formatNumberTerbilang( number_format($grand_total) ); ?>
                            </td>
                        </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding-left: 10px; padding-top: 20px; border: solid 1px transparent;">
            Demak, <?= app\components\DeltaFormatter::formatDateTimeId($model->tanggal)  ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="padding-left: 10px; border: solid 1px transparent;">Hormat kami, </td>
    </tr>
    <tr><td colspan="3" style="padding-left: 10px; border: solid 1px transparent;">&nbsp;</td></tr>
    <tr>
        <td colspan="3" style="padding-left: 10px; border: solid 1px transparent;">
            <hr style="width: 100px; margin-right: 0; margin-top: 60px; border: 0; border-top: 1px solid #000;">
        </td>
    </tr>
</table>
<span style="page-break-after: always;">&nbsp;</span>