<?php
/* @var $this yii\web\View */

use app\models\TApproval;
use app\models\TPengajuanDrp;

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
@media print {
    tr{
        page-break-inside: auto;
    }
}
</style>
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
                                <td style="width: 2cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Kode</td>
                                <td style="width: 2cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Kategori</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Tipe<br>Voucher</td>
                                <td style="width: 3cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Penerima Pembayaran</td>
                                <td style="width: 8cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Keterangan Voucher</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px; border-right: solid 1px #000;">Total</td>
                                <td style="width: 2.5cm;font-size:1.2rem; font-weight: bold; text-align: center; padding-top: 15px; padding-bottom: 15px;">Status<br>Pengajuan</td>
                            </tr>
                            <?php 
                                $sql = "SELECT t_voucher_pengeluaran.kode, t_open_voucher.tipe as tipe_ov, m_suplier.suplier_nm, t_gkk.gkk_id,	t_gkk.kode AS gkk_kode,
                                        t_ppk.ppk_id, t_ppk.kode AS ppk_kode,t_ajuandinas_grader.ajuandinas_grader_id, t_ajuandinas_grader.kode AS pdg_kode,
                                        t_ajuanmakan_grader.ajuanmakan_grader_id, t_ajuanmakan_grader.kode AS pmg_kode, t_log_bayar_dp.log_bayar_dp_id, t_log_bayar_dp.kode AS kode_dp,
                                        t_log_bayar_muat.log_bayar_muat_id, t_log_bayar_muat.kode AS kode_pelunasan,m_penerima_voucher.nama_penerima AS nama_penerima, 
                                        m_penerima_voucher.nama_perusahaan AS nama_perusahaan, m_suplierov.suplier_nm AS suplier_ov,
                                        t_voucher_pengeluaran.total_nominal, t_voucher_pengeluaran.voucher_pengeluaran_id, t_asuransi.kepada, t_pengajuan_drp_detail.kategori,
                                        t_voucher_pengeluaran.penerima_pembayaran,
                                        a.graderlog_nm as grader_makan,
 	                                    c.graderlog_nm as grader_dinas,
                                        status_pengajuan, 
                                        m_suplier.suplier_nm_company,
                                        m_suplierov.suplier_nm_company as company_ov, 
                                        t_voucher_pengeluaran.tipe,
                                        m_suplier.suplier_bank,
                                        m_suplier.suplier_norekening,
                                        m_suplier.suplier_an_rekening
                                        FROM t_pengajuan_drp 
                                        LEFT JOIN t_pengajuan_drp_detail on t_pengajuan_drp_detail.pengajuan_drp_id = t_pengajuan_drp.pengajuan_drp_id
                                        LEFT JOIN t_voucher_pengeluaran on t_voucher_pengeluaran.voucher_pengeluaran_id = t_pengajuan_drp_detail.voucher_pengeluaran_id
                                        LEFT JOIN t_open_voucher on t_open_voucher.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN m_suplier ON m_suplier.suplier_id = t_voucher_pengeluaran.suplier_id 
                                        LEFT JOIN t_gkk ON t_gkk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN t_ppk ON t_ppk.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN t_ajuandinas_grader ON t_ajuandinas_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN t_ajuanmakan_grader ON t_ajuanmakan_grader.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN t_log_bayar_dp ON t_log_bayar_dp.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN t_log_bayar_muat ON t_log_bayar_muat.voucher_pengeluaran_id = t_voucher_pengeluaran.voucher_pengeluaran_id
                                        LEFT JOIN m_penerima_voucher ON m_penerima_voucher.penerima_voucher_id = t_open_voucher.penerima_voucher_id
                                        LEFT JOIN m_suplier AS m_suplierov ON m_suplierov.suplier_id = t_open_voucher.penerima_reff_id
                                        LEFT JOIN t_asuransi ON t_asuransi.kode = t_open_voucher.reff_no
                                        LEFT JOIN m_graderlog AS a ON a.graderlog_id = t_ajuanmakan_grader.graderlog_id
                                        LEFT JOIN m_graderlog AS c ON c.graderlog_id = t_ajuandinas_grader.graderlog_id
                                        WHERE t_pengajuan_drp.pengajuan_drp_id = {$model->pengajuan_drp_id} 
                                        ORDER BY t_pengajuan_drp_detail.pengajuan_drp_detail_id";
                                $mods = \Yii::$app->db->createCommand($sql)->queryAll();
                                $total = 0; $total_setuju = 0;
                                foreach($mods as $i => $detail){
                                    $total += $detail['total_nominal'];
                                    if($detail['status_pengajuan'] == 'Disetujui'){
                                        $total_setuju += $detail['total_nominal'];
                                    }

                                    if($detail['tipe_ov'] == null) {
                                        $tipe_ov = "-";
                                    } else {
                                        $tipe_ov = $detail['tipe_ov'];
                                    }

                                    $supplier='<center>-</center>';
                                    if($detail['suplier_nm_company'] !== null){
                                        $supplier = $detail['suplier_nm_company'];
                                    }else if($detail['suplier_nm'] !== null){
                                        $supplier = $detail['suplier_nm'];
                                    }else if($detail['gkk_kode'] !== null){
                                        $supplier= "<a onclick='gkk(".$detail['gkk_id'].")'>".$detail['gkk_kode']."</a>";
                                    }else if($detail['ppk_kode'] !== null){
                                        $supplier= "<a onclick='ppk(".$detail['ppk_id'].")'>".$detail['ppk_kode']."</a>";
                                    }else if($detail['pdg_kode'] !== null){
                                        $supplier="<a onclick='ajuanDinas(".$detail['ajuandinas_grader_id'].")'>".$detail['pdg_kode']."</a><br>". $detail['grader_dinas'];
                                    }else if($detail['pmg_kode'] !== null){
                                        $supplier="<a onclick='ajuanMakan(".$detail['ajuanmakan_grader_id'].")'>".$detail['pmg_kode']."</a><br>". $detail['grader_makan'];
                                    }else if($detail['kode_dp'] !== null){
                                        $supplier= "<a onclick='infoAjuanDp(".$detail['log_bayar_dp_id'].")'>".$detail['kode_dp']."</a>";
                                    }else if($detail['kode_pelunasan'] !== null){
                                        $supplier= "<a onclick='infoPelunasan(".$detail['log_bayar_muat_id'].")'>".$detail['kode_pelunasan']."</a>";
                                    }else if($detail['tipe_ov'] !== null){
                                        if($detail['tipe_ov'] == "PEMBAYARAN ASURANSI LOG SHIPPING"){
                                            $supplier = $detail['kepada'];
                                        }else if($detail['tipe_ov'] == "REGULER"){
                                            $supplier = $detail['nama_penerima'];
                                        }else{
                                            if($detail['company_ov'] !== null){
                                                $supplier = $detail['company_ov'];
                                            } else {
                                                $supplier = $detail['suplier_ov'];
                                            }
                                        }
                                    }

                                    $sql =  "SELECT keterangan FROM t_voucher_pengeluarandetail WHERE voucher_pengeluaran_id = {$detail['voucher_pengeluaran_id']}";
                                    $modes = Yii::$app->db->createCommand($sql)->queryAll(); 
                                    $ket= '';
                                    foreach($modes as $m => $mod){ 
                                        if(count($modes) > 1){
                                            $ket .= "- ". $mod['keterangan'] ." <br>";
                                        } else {
                                            $ket .=  $mod['keterangan'] ;
                                        }
                                    }  

                                    if($detail['tipe'] == 'Open Voucher'){
                                        if($detail['penerima_pembayaran'] !== null){
                                            $penerima = json_decode($detail['penerima_pembayaran']);
                                            $bank = $penerima[0]->nama_bank;
                                            $rek = $penerima[0]->rekening;
                                            $rek_an = $penerima[0]->an_bank;
                                        } else {
                                            $bank = '';
                                            $rek = '';
                                            $rek_an = '';
                                        }
                                    } else {
                                        $bank = $detail['suplier_bank'];
										$rek = $detail['suplier_norekening'];
										$rek_an = $detail['suplier_an_rekening'];
                                    }
                                ?>
                            <tr>
                                    <td style="padding-left: 5px; padding-bottom: 15px; border-right: solid 1px #000; font-size: 1.2rem; vertical-align: top; padding-right: 5px;"><?= $detail['kode']; ?></td>
                                    <td style="border-right: solid 1px #000; font-size: 1.2rem; text-align: center; vertical-align: top; padding-bottom: 15px;"><?= $detail['kategori']; ?></td>
                                    <td style="border-right: solid 1px #000; font-size: 1.2rem; text-align: center; vertical-align: top; padding-bottom: 15px;">
                                        <?php $modVoucherPengeluaran = app\models\TVoucherPengeluaran::findOne($detail['voucher_pengeluaran_id']); ?>
                                        <?= $modVoucherPengeluaran->tipe; ?><br><b><?= $detail['tipe_ov']?></b>
                                    </td>
                                    <td style="border-right: solid 1px #000; text-align: center; vertical-align: top; font-size: 1.2rem; padding-bottom: 15px;">
                                        <?php
                                        if($bank == null || $bank == ""){
                                            $ret = $supplier;
                                        } else {
                                            $ret = $supplier.'<br>'. $bank .' - '. $rek .'<br> a.n. '. $rek_an;
                                        }
                                        echo $ret;
                                        // if($detail['tipe_ov'] == "PEMBAYARAN ASURANSI LOG SHIPPING") {
                                        //     echo $supplier; 
                                        // } else {
                                        //     if($bank !== null || $bank !== ''){
                                        //         echo $supplier .'<br>'. $bank .' - '. $rek .'<br> a.n. '. $rek_an;
                                        //         // echo $supplier .'<br>'. $detail['bank'] .' - '. $detail['rek'] .'<br> a.n. '. $detail['rek_an'];
                                        //     } else {
                                        //         echo $supplier;
                                        //     }
                                        // }
                                        ?>
                                    </td>
                                    <td style="padding-left: 10px; border-right: solid 1px #000; font-size: 1.2rem; vertical-align: top; padding-bottom: 15px;"><?= $ket; ?></td>
                                    <td style="vertical-align: top; font-size: 1.2rem; text-align: right; padding-right: 5px; padding-bottom: 15px;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['total_nominal']); ?></td>
                                    <td style="border-left: solid 1px #000; font-size: 1.2rem; text-align: center; vertical-align: top; padding-bottom: 15px;"><?= $detail['status_pengajuan']; ?></td>
                            </tr>
                            <?php } ?>
                            <tr style=" border-top: solid 1px #000; ">
                                <td colspan="5" class="text-align-right" style="padding-left: 20px; font-weight: bold;border-right: solid 1px #000;">TOTAL PENGAJUAN &nbsp; </td>
                                <td style="vertical-align: top; font-size: 1.2rem; text-align: right; vertical-align: middle; padding-right: 5px; border-right: solid 1px #000;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total); ?></td>
                            </tr>
                            <?php if($model->status_approve == 'APPROVED'){ ?>
                                <tr style=" border-top: solid 1px #000; ">
                                    <td colspan="5" class="text-align-right" style="padding-left: 20px; font-weight: bold;border-right: solid 1px #000;">TOTAL YANG DISETUJUI &nbsp; </td>
                                    <td style="vertical-align: top; font-size: 1.2rem; text-align: right; vertical-align: middle; padding-right: 5px; border-right: solid 1px #000;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_setuju); ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
				</tr>
                <tr>
                    <td colspan="5">
                    <?php 
                        $lvl1 = ''; $lvl2 = ''; $lvl3 = ''; $kadepdiv=''; $direktur = '';
                        $tgl_1 = ''; $tgl_2 = ''; $tgl_3 = ''; 
                        $modDrp = TPengajuanDrp::findOne($_GET['id']);
                        if(!empty($modDrp)){
                            if($modDrp->status_approve == 'APPROVED'){
                                $kode = $modDrp['kode'];
                                $approvals = TApproval::find()->where(['reff_no'=>$kode])->all();
                                foreach($approvals as $a => $approval){
                                    $sql = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = {$approval->assigned_to}")->queryOne();
                                    if($approval['level'] == 1){
                                        $lvl1 = $sql['pegawai_nama'];
                                        $tgl_1 = $approval->tanggal_approve;
                                    } else if($approval['level'] == 2){
                                        $lvl2 = $sql['pegawai_nama'];
                                        $tgl_2 = $approval->tanggal_approve;
                                    } else if($approval['level'] == 3){
                                        $lvl3 = $sql['pegawai_nama'];
                                        $tgl_3 = $approval->tanggal_approve;
                                    } 
                                    $kadepdiv = 'APPROVED<br>'.$lvl1.'<br>'.$lvl2;
									$direktur = 'APPROVED<br>'.$lvl3;
                                }
                            }
                        }
                        //staff
                        $user = app\models\MUser::findOne($model->created_by);
                        $pegawai = app\models\MPegawai::findOne($user->pegawai_id);
                        ?>
                        <table style="width: 100%; font-size: 1.1rem; text-align: center; " border="1">
                            <tr style="height: 0.4cm;">
                                <td style="width:20%; vertical-align: middle; border-left: solid 1px transparent;">Diterima Oleh</td>
                                <td style="width:20%; vertical-align: middle;">Dibukukan Oleh</td>
                                <td style="width:20%; vertical-align: middle;">Diperiksa Oleh</td>
                                <td style="width:20%; vertical-align: middle;">Disetujui Oleh</td>
                                <td style="width:20%; vertical-align: middle; border-right: solid 1px transparent;">Dibuat Oleh</td>
                            </tr>
                            <tr>
                                <td style="height: 25px; vertical-align: middle; padding-left: 5px; text-align: center; border-left: solid 1px transparent; border-bottom: solid 1px transparent;"></td>
                                <td style="height: 25px; vertical-align: middle; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"></td>
                                <td style="height: 25px; vertical-align: middle; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"><?= $kadepdiv; ?></td>
                                <td style="height: 25px; vertical-align: middle; padding-left: 5px; text-align: center; border-bottom: solid 1px transparent;"><?= $direktur; ?></td>
                                <td style="height: 25px; vertical-align: middle; padding-left: 5px; text-align: center; border-right: solid 1px transparent; border-bottom: solid 1px transparent;"><?= $pegawai->pegawai_nama; ?></td>
                            </tr>
                            <tr>
                                <td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left; border-left: solid 1px transparent;">Tgl :</td>
                                <td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl : </td>
                                <td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl : <?= app\components\DeltaFormatter::formatDateTimeForUser2($tgl_2); ?></td>
                                <td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left;">Tgl : <?= app\components\DeltaFormatter::formatDateTimeForUser2($tgl_3); ?></td>
                                <td style="height: 25px; vertical-align: bottom; padding-left: 5px; text-align: left; border-right: solid 1px transparent;">Tgl : <?= app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
                            </tr>
                            <tr>
                                <td style="height: 20px; vertical-align: middle; border-left: solid 1px transparent;"></td>
                                <td style="height: 20px; vertical-align: middle; font-size: 1rem"></td>
                                <td style="height: 20px; vertical-align: middle; font-size: 1rem">Kadept Fin & Kadiv FA</td>
                                <td style="height: 20px; vertical-align: middle; font-size: 1rem">Direktur / Direktur Utama</td>
                                <td style="height: 20px; vertical-align: middle; border-right: solid 1px transparent;  font-size: 1rem">Kanit Bank</td>
                            </tr>
                        </table>
                    </td>
                </tr>
			</table>
		</td>
	</tr>
    <tr>
        <td colspan="5" style="font-size: 0.9rem; border: solid 1px transparent;">
             <?php
                echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
                echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
            ?>
            <span class="pull-right">
                <!-- CWM-FK-FIN-xx-x -->
            </span>
        </td>
    </tr>
</table>

