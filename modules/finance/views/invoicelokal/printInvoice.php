<?php
/* @var $this yii\web\View */
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
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
					</td>
					<td style="width: 10cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
						<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="border-bottom: solid 1px #000;">
			<table style="width: 100%;" border="0">
				<tr style="">
					<td style="width: 5cm; text-align: left; vertical-align: middle; height: 1cm; border-right: solid 1px transparent;"></td>
					<td style="text-align: center; vertical-align: middle; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.9rem; font-weight: 600; text-decoration:underline;"><?= $paramprint['judul']; ?></span><br>
                        <span style="font-size: 1.5rem; font-weight: 600">No. <?= $paramprint['judul2']; ?></span>
					</td>
					<td style="width: 5cm; height: 1cm; vertical-align: top;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width: 70%; height: 3cm; vertical-align: middle; padding: 5px 10px; background-color: #F1F4F7; border-right: solid 1px transparent;">
			<table style="width: 100%;">
                <tr>
                    <td colspan="3" style="padding-right: 30px;">
                        <b>Kepada Yth. <br></b>
                        <b><?php echo strtoupper($model->cust->cust_an_nama);?></b><br>
                        <b><?php echo strtoupper($model->cust->cust_an_alamat);?></b>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
				<tr>
					<td style="width: 1cm; vertical-align: top;"><b>NPWP</b></td>
					<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
                    <td style="vertical-align: top; line-height: 1.3;">
                        <?php echo '<b>'. $model->cust_no_npwp . '</b>'; ?>
                    </td>
				</tr>
			</table>
		</td>
		<td style="width: 30%; vertical-align: top; padding: 10px 10px; background-color: #F1F4F7;">
			<table style="width: 100%;">
                <?php if($model->no_faktur_pajak){ ?>
                <tr>
					<td style="width: 2.5cm; vertical-align: top;"><b>No. Faktur Pajak :</b></td>
				</tr>
                <tr>
                    <td style="vertical-align: top; line-height: 1.3;">
                        <?php echo $model->no_faktur_pajak; ?>
                    </td>
                </tr>
                <?php } ?>
				<tr>
					<td style="vertical-align: top;"><b>Tanggal Invoice :</b></td>
				</tr>
                <tr>
                    <td style="vertical-align: top;"><?php echo (app\components\DeltaFormatter::formatDateTimeId($model->tanggal));?></td>
                </tr>
			</table>
		</td>
	</tr>
    <tr>
        <td colspan="3" style="border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
            <table style="width: 100%" id="table-invoice">
                <thead>
                    <tr style="border-bottom: solid 1px #000; border-top: solid 1px #000;">
                        <th style="text-align: center; vertical-align: middle; height: 0.8cm; border-right: solid 1px #000;">No</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Uraian</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Qty (m<sup>3</sup>)</th>
                        <th style="text-align: center; vertical-align: middle; border-right: solid 1px #000;">Harga/m<sup>3</sup></th>
                        <th style="text-align: center; vertical-align: middle;">Total (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                        <?php 
                        if(count($modDetails)>0){
                            foreach($modDetails as $m => $detail){
                                $deskripsi_invoice = json_decode($detail['deskripsi_invoice'], true);
                                $total_kubikasi_inv = 0; $total_harga_inv = 0; $total_row=0;
                                foreach ($deskripsi_invoice as $i => $di){
                                    $total_kubikasi_inv += $di['kubikasi_inv'];
                                    $total_harga_inv += $di['total_inv'];
                        ?>
                                <tr>
                                    <td style="width: 1cm; text-align: center; padding: 5px; border-right: solid 1px #000;"><?= $i+1; ?></td>
                                    <td style="padding: 5px; border-right: solid 1px #000;"><?= $di['uraian']; ?></td>
                                    <td style="width: 70px; padding: 5px; text-align: right; border-right: solid 1px #000;"><?= ($di['kubikasi_inv'] != 0)?$di['kubikasi_inv']:''; ?></td>
                                    <td style="width: 120px; padding: 5px; text-align: right; border-right: solid 1px #000;"><?= ($di['harga_inv'] != 0)?number_format($di['harga_inv']):''; ?></td>
                                    <td style="width: 120px; padding: 5px; text-align: right; "><?= number_format($di['total_inv']); ?></td>
                                </tr>
                        <?php 
                                $total_row++;
                                }
                            }
                        } 
                        ?>
                        <tr>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;">&nbsp;</td>
                            <td style="border-bottom: solid 1px #000;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding: 5px;"><b>Subtotal</b> &nbsp;</td>
                            <td style="text-align: right; padding: 5px; border-right: solid 1px #000; border-bottom: solid 1px #000;"><b><?= $total_kubikasi_inv; ?></b></td>
                            <td style="border-right: solid 1px #000; border-bottom: solid 1px #000;"></td>
                            <td style="text-align: right; padding: 5px; border-bottom: solid 1px #000;">
                                <b>
                                    <span class='pull-left'>Rp.</span>
                                    <span class='pull-right'><?= number_format($total_harga_inv); ?></span>
                                </b>
                            </td>
                        </tr>
                        <?php 
                        // $total_harga_inv -= $model->total_potongan;
                        if($model->total_potongan > 0){ ?>
                            <tr>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="border-right: solid 1px #000; padding-top: 15px; padding-left: 5px;"><?= $model->label_potongan; ?></td>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="padding-left: 5px; text-align: right; padding-right: 5px; padding-top: 15px;"><?= '-' . number_format($model->total_potongan); ?></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="border-right: solid 1px #000; padding-top: 15px; padding-left: 5px;"></td>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="border-right: solid 1px #000;"></td>
                                <td style="padding-left: 5px; text-align: right; padding-right: 5px; padding-top: 15px;"></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; padding-left: 5px;">Tax Base (DPP)</td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="padding-left: 5px; text-align: right; padding-right: 5px;">
                                <?php 
                                $dpp = round(($total_harga_inv - $model->total_potongan) * 11 /12);
                                echo number_format($dpp); 
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; padding-left: 5px;">PPN 12%</td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="padding-left: 5px; text-align: right; padding-right: 5px;">
                                <?php 
                                $ppn = round(($total_harga_inv - $model->total_potongan) * 11 /100);
                                echo number_format($ppn); 
                                ?>
                            </td>
                        </tr>
                        <?php if($model->kawasan_berikat){ ?>
                        <tr>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; padding-left: 5px;">PPN Tidak Dipungut (Kawasan Berikat)</td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="padding-left: 5px; text-align: right; padding-right: 5px;">
                                <?php 
                                $ppn = round(($total_harga_inv - $model->total_potongan) * 11 /100);
                                echo '-'.number_format($ppn); 
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if($model->ceklis_pph){ ?>
                        <tr>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000; padding-left: 5px;">
                                <?php 
                                if($model->jenis_produk == "Log"){
                                    echo 'PPh Pasal 22 0,25%';
                                } else {
                                    echo 'PPh Pasal 23 2%';
                                }
                                ?>
                            </td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="border-right: solid 1px #000;"></td>
                            <td style="padding-left: 5px; text-align: right; padding-right: 5px;">
                                <?php 
                                if($model->jenis_produk == "Log"){
                                    $pph = round($total_harga_inv * 0.25 / 100);
                                } else {
                                    $pph = round($total_harga_inv * 2 /100);
                                }
                                echo '-'.number_format($pph);
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000; padding-left: 5px;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="border-right: solid 1px #000;">&nbsp;</td>
                            <td style="padding-left: 5px; text-align: right; padding-right: 5px;">&nbsp;</td>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="border-bottom: solid 1px #000; border-top: solid 1px #000;"></td>
                        <td style="border-right: solid 1px #000; border-bottom: solid 1px #000; padding: 5px; border-top: solid 1px #000;"><b>Grand Total</b></td>
                        <td style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"></td>
                        <td style="border-bottom: solid 1px #000; border-right: solid 1px #000; border-top: solid 1px #000;"></td>
                        <td style="padding-left: 5px; text-align: right; padding-right: 5px; border-bottom: solid 1px #000; border-top: solid 1px #000;">
                            <?php 
                                if($model->kawasan_berikat){
                                    if($model->ceklis_pph){
                                        $grand_total = ($total_harga_inv - $model->total_potongan) - $pph;
                                    } else {
                                        $grand_total = ($total_harga_inv - $model->total_potongan);
                                    }
                                } else {
                                    if($model->ceklis_pph){
                                        $grand_total = ($total_harga_inv - $model->total_potongan) + $ppn - $pph;
                                    } else {
                                        $grand_total = ($total_harga_inv - $model->total_potongan) + $ppn;
                                    }
                                }
                                echo "<b>
                                        <span class='pull-left'>Rp.</span>
                                        <span class='pull-right'>". number_format($grand_total) . "</span>" .
                                     "</b>";
                            ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="height: 1.5cm; border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
            <table style="width: 100%">
                <?php 
                    
                    $nomorSertifikatFsc = ''; // Default kosong
                    // Decode JSON dari $model->nota_penjualan
                    $notaPenjualanIds = yii\helpers\Json::decode($model->nota_penjualan, true);

                    if (is_array($notaPenjualanIds)) {
                        foreach ($notaPenjualanIds as $notaId) {
                            $modPenjualan = \app\models\TNotaPenjualan::findOne(['nota_penjualan_id' => $notaId]);

                            if ($modPenjualan !== null) {
                                $modOp = \app\models\TOpKo::findOne(['op_ko_id' => $modPenjualan->op_ko_id]);

                                if ($modOp !== null) {
                                    $listPo = \app\models\TPoKoDetail::find()->where(['po_ko_id' => $modOp->po_ko_id])->all();
                                    
                                    foreach ($listPo as $po) {
                                        if ($po->fsc == true) {
                                            $nomorSertifikatFsc = "Certificate Code : " . \app\components\Params::NOMOR_SERTIFIKAT_FSC;
                                            break ; // keluar dari semua loop karena sudah ketemu
                                        }
                                    }
                                }
                            }
                        }
                    }
                ?>
                <tr>
                    <td colspan="3" style="padding-top: 15px; padding-left: 15px; font-size: 1.2rem"><?= $nomorSertifikatFsc ?><br><b>Terbilang :</b></td>
                </tr>
                <tr>
                    <td colspan="3"  style="padding-left: 15px; font-size: 1.2rem; border-bottom: solid 1px #000; padding-bottom: 15px;">
                        &nbsp;&nbsp;<b>>>&nbsp;&nbsp;<?= \app\components\DeltaFormatter::formatNumberTerbilang( $grand_total ); ?></b>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <?php 
    // $max = 8; 
    // $blankspace = $max - $total_row; 
    ?>
    <!-- <tr><td colspan="2" style="height: <?php //echo (0.5*$blankspace) ?>cm">&nbsp;</td></tr> -->
    <tr>
        <td colspan="3" style="height: 1.5cm; border-bottom: solid 1px transparent; border-top: solid 1px transparent;">
            <?php 
                $modBank = \app\models\MBank::findOne($model->bank_id);
                $bank = $modBank->nama;
                $rek = $modBank->nomor;
                $an = $modBank->atasnama;
            ?>
            <table style="width: 100%; font-size: 1.1rem;" border="0">
				<tr style="height: 0.5cm; padding-top: 10px;">
					<td style="vertical-align: bottom; border-bottom: solid 1px transparent; text-align: left; line-height: 1.3; padding-left: 10px;">
						<span style="font-size: 1.1rem;">Pembayaran mohon ditransfer ke :</span>
					</td>
					<td style="vertical-align: top; width: 5cm; text-align: center; padding-top: 10px;"><?= $modCompany->name ?></td>
				</tr>
				<tr>
					<td style="padding-left: 15px; line-height: 1.3; ">
						<table style="" border="0">
                            <tr>
								<td style="font-size: 1.1rem;">Bank</td>
								<td style="font-size: 1.1rem;">:</td>
								<td style="font-size: 1.1rem;"><?= $bank; ?></td>
							</tr>
                            <tr>
								<td style="font-size: 1.1rem;">No. Rekening</td>
								<td style="font-size: 1.1rem;">:</td>
								<td style="font-size: 1.1rem;"><?= $rek; ?></td>
							</tr>
							<tr>
								<td style="width:2.5cm; font-size: 1.1rem;">Atas Nama</td>
								<td style="width:0.25cm; font-size: 1.1rem;">:</td>
								<td style="width:5.75cm; font-size: 1.1rem;"><?= $an; ?></td>
							</tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
                            <tr><td>&nbsp;</td></tr>
						</table>
					</td>
					<td style="vertical-align: bottom; line-height: 1;  text-align: center;">
						<?php
						if(!empty($model->penerbit)){
							echo "<span style='font-size:0.9rem'><b><u> ". $model->penerbit0->pegawai_nama." </u></b></span><br>";
							echo "<span style='font-size:0.8rem'>Kepala Divisi Marketing </span>";
						}
						?>
					</td>
				</tr>
                <tr><td style="height: 0.5cm">&nbsp;</td></tr>
				<tr>
					<td style="vertical-align: bottom; height: 1.5cm;" colspan="2">
						<table style="width: 100%;">
							<tr>
								<td style="vertical-align: bottom; font-size: 0.9rem; padding:3px; border-bottom: solid 1px #000;">
									<?php
									echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
									echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
									?>
								</td>
								<td style="text-align: right; padding:3px; border-bottom: solid 1px #000;">
									<?php echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/sertifikat_tanpa_CARB.png" alt="" class="logo-default" style="width: 8cm;">'; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="font-size: 0.9rem; border: solid 1px transparent; text-align: right; padding-right: 5px;">
            CWM-FK-FIN-21
        </td>
    </tr>
</table>
<span style="page-break-after: always;">&nbsp;</span>
