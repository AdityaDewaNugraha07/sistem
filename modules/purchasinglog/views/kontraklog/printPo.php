<?php
/* @var $this yii\web\View */
$this->title = 'Print ' . $paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
//$kode = explode("-", $model->kode)[0];
$kode = $model->kode;
if ($_GET['caraprint'] == "EXCEL") {
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="' . $paramprint['judul'] . ' - ' . date("d/m/Y") . '.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
$modCompany = \app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>
<style>
	table {
		font-size: 1.2rem;
	}

	table#table-detail {
		font-size: 1rem;
	}

	table#table-detail tr td {
		vertical-align: top;
	}
</style>
<?php

?>
<table style="width: 20cm; margin: 10px;" border="1">
	<tr>
		<td colspan="3" style="padding: 5px;">
			<table style="width: 100%; " border="0">
				<tr style="">
					<td style="width: 3cm; text-align: center; vertical-align: middle; padding: 0px; height: 1cm; border-bottom: solid 1px transparent; border-right: solid 1px transparent;">
						<img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;">
					</td>
					<td style="width: 8cm; text-align: left; vertical-align: top; padding: 5px; line-height: 1.1;">
						<span style="font-size: 1.3rem; font-weight: 600"><?= $modCompany->name; ?></span><br>
						<span style="font-size: 1rem;"><?= $modCompany->alamat; ?></span><br>
					</td>
					<td>&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="border-bottom: solid 1px transparent;">
			<table style="width: 100%;" border="0">
				<tr style="height:25px;">
					<td style="width: 5cm; text-align: left; vertical-align: middle;border-right: solid 1px transparent;"></td>
					<td style="text-align: center; vertical-align: middle; padding: 5px; line-height: 1;">
						<span style="font-size: 1.6rem; font-weight: 600"><?= $paramprint['judul']; ?></span>
					</td>
					<td style="width: 5cm; vertical-align: top;">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 10px; padding-bottom: 5px; background-color: #F1F4F7;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 50%; height: auto; vertical-align: top; padding-left: 5px;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>No. PO</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo $modPO->kode; ?></td>
							</tr>
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>Tanggal</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modPO->tanggal); ?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Perihal</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo "Permintaan Pembelian Log Alam"; ?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Kepada</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;">
									<?php echo $modPO->pihak1_nama . "<br>" . $modPO->pihak1_perusahaan; ?>
								</td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Alamat</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo $modPO->pihak1_alamat; ?></td>
							</tr>
							<tr>
								<td style="vertical-align: top;"><b>Telp</b></td>
								<td style="vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo $modPO->suplier->suplier_phone; ?></td>
							</tr>
						</table>
					</td>
					<td style="width: 50%; vertical-align: top; padding-left: 15px;">
						<table style="width: 100%;">
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>No. Kontrak</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><b><?php echo $model->nomor_kontrak; ?> </b></td>
							</tr>
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>Volume Kontrak</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo $model->volume_kontrak . " M<sup>3</sup>"; ?></td>
							</tr>
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>No. Keputusan</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo $model->kode; ?> Rev:<?= $model->revisi ?></td>
							</tr>
							<tr>
								<td style="width: 2.8cm; vertical-align: top;"><b>Tgl Keputusan</b></td>
								<td style="width: 0.5cm; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="vertical-align: top; line-height: 1.3;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal); ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 10px; background-color: #F1F4F7; border-top: solid 1px transparent;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 100%; height: auto; vertical-align: top; padding-left: 5px;">
						Dengan Hormat, <br> Berikut disampaikan permintaan pembelian Kayu Log Alam dengan spesifikasi tersebut dibawah ini :
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<?php
	$modDetail = \app\models\TPengajuanPembelianlogDetail::find()
		->select("pengajuan_pembelianlog_id, kayu_id, tipe")
		->groupBy("pengajuan_pembelianlog_id, kayu_id, tipe")
		->where(['pengajuan_pembelianlog_id' => $model->pengajuan_pembelianlog_id])->all();
	$ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
	?>
	<tr>
		<td colspan="3" style="padding: 0px;">
			<table style="width: 100%" id="table-detail">
				<tr style="height: 0.5cm; border-bottom: solid 1px #000;">
					<td rowspan="2" style="width: 0.5cm; padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle; padding:5px;"><b>
							<center>No.</center>
						</b></td>
					<td rowspan="2" style="padding: 7px 5px; border-right: solid 1px #000; vertical-align: middle; padding:5px;"><b>
							<center>Jenis Kayu</center>
						</b></td>
					<?php foreach ($ukuranganrange as $i => $range) { ?>
						<th colspan="2" style="border-right: solid 1px #000; padding:5px;">
							<center>Diameter <?= $range ?></center>
						</th>
					<?php } ?>
					<th colspan="2">
						<center>Subtotal</center>
					</th>
				</tr>
				<tr style="border-bottom: solid 1px #000;">
					<?php foreach ($ukuranganrange as $i => $range) { ?>
						<th style="width: 50px; border-right: solid 1px #000; padding:5px;">
							<center>M<sup>3</sup></center>
						</th>
						<th style="width: 70px; border-right: solid 1px #000; padding:5px;">
							<center>Harga/m<sup>3</sup></center>
						</th>
					<?php } ?>
					<th style="width: 60px; border-right: solid 1px #000; padding:5px; line-height: 1">Subtotal<br>
						<center>M<sup>3</sup></center>
					</th>
					<th style="width: 80px; padding:5px; line-height: 1">
						<center>Subtotal<br>Harga</center>
					</th>
				</tr>
				<?php
				$total_ver_m3 = [];
				$total_ver_harga = [];
				$total_m3 = 0;
				$total_harga = 0;
				foreach ($ukuranganrange as $i => $range) {
					$total_ver_m3[$range] = 0;
					$total_ver_harga[$range] = 0;
				}
				if (count($modDetail) > 0) {
					foreach ($modDetail as $i => $detail) {
						echo '<tr style="border-right: solid 1px transparent; font-weight:600" class="text-align-center">';
						echo    "<td style='padding:5px; border-right: solid 1px #000;'>" . ($i + 1) . "</td>";
						$nama_ilmiah = $detail->kayu->nama_ilmiah ? "<br><span style='font-size: .8rem; font-style: italic'>({$detail->kayu->nama_ilmiah})</span>" : "";
						echo    "<td style='padding:5px; border-right: solid 1px #000; text-align: left;'>" . $detail->kayu->kayu_nama . $nama_ilmiah . "</td>";
						$subtotal_m3 = 0;
						$subtotal_harga = 0;
						foreach ($ukuranganrange as $i => $range) {
							$mod = Yii::$app->db->createCommand("SELECT * FROM t_pengajuan_pembelianlog_detail WHERE pengajuan_pembelianlog_id = {$model->pengajuan_pembelianlog_id} AND kayu_id = {$detail->kayu_id} AND diameter_cm = '{$range}'")->queryOne();
							$subtotal_m3 += $mod['qty_m3'];
							$subtotal_harga += $mod['qty_m3'] * $mod['harga'];
							echo    "<td style='padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right'>" . \app\components\DeltaFormatter::formatNumberForUserFloat($mod['qty_m3']) . "</td>";
							echo    "<td style='padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right'>" . number_format($mod['harga']) . "</td>";
							$total_ver_m3[$range] += $mod['qty_m3'];
							$total_ver_harga[$range] += $mod['harga'];
						}
						echo    "<td style='padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right'>" . \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal_m3) . "</td>";
						echo    "<td style='padding:5px 2px 5px 2px; border-right: solid 1px transparent; text-align:right'>" . number_format($subtotal_harga) . "</td>";
						echo "</tr>";
						$total_m3 += $subtotal_m3;
						$total_harga += $subtotal_harga;
					}
				}
				?>
				<tr style="border-top: solid 1px #000;">
					<td colspan="2" style="text-align: right; border-right: solid 1px #000; padding:5px;"><b>TOTAL &nbsp; </b></td>
					<?php foreach ($ukuranganrange as $i => $range) { ?>
						<td style="padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_ver_m3[$range]) ?></b></td>
						<td style="padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right"><b></b></td>
					<?php } ?>
					<td style="padding:5px 2px 5px 2px; border-right: solid 1px #000; text-align:right"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_m3) ?></b></td>
					<td style="padding:5px 2px 5px 2px; text-align:right"><b><?= \app\components\DeltaFormatter::formatNumberForUserFloat($total_harga) ?></b></td>
				</tr>
				<?php
				//                        $approval = \app\models\TApproval::findOne(['reff_no'=>$model->kode,'parameter1'=>'ALIAS PACKINGLIST']);
				//						$total_pcs = 0; $total_volume = 0; $total_bundles = 0;
				//						$blankspace = $blankspace - count($modDetail);
				//						$modAlias = Yii::$app->db->createCommand("SELECT * FROM t_alias WHERE reff_no = '{$model->kode}' ORDER BY alias_id ASC")->queryAll();
				//						if(!empty($modAlias) && ($model->jenis_produk == "Moulding")){
				//                            $modAlias2 = Yii::$app->db->createCommand("SELECT * FROM t_alias WHERE reff_no = '{$model->kode}' AND alias_name='profil_kayu' ORDER BY alias_id ASC")->queryAll();
				//                            $groupings = $modAlias2;
				//						}else{
				////                            if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
				////								$selekgroup = "glue";
				////							}else{
				////								$selekgroup = "profil_kayu";
				////							}
				////							$groupings = Yii::$app->db->createCommand("SELECT $selekgroup AS value_original FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' GROUP BY 1 ORDER BY 1 DESC")->queryAll();
				////							foreach($groupings as $z => $ert){
				////								$groupings[$z]['alias_name'] = $selekgroup;
				////							}
				////                            echo "<pre>";
				////                            print_r("SELECT $selekgroup AS value_original FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' GROUP BY 1 ORDER BY 1 DESC");
				////                            exit;
				//                            // PL 175-2019 tidak urut 
				////                            if($model->packinglist_id == 237){
				//                                $groupings = Yii::$app->db->createCommand("SELECT container_no FROM t_packinglist_container WHERE packinglist_id = '{$model->packinglist_id}' AND container_no='{$container['container_no']}' GROUP BY 1 ORDER BY 1 DESC")->queryAll();
				////                            }
				//						}
				//						foreach($groupings as $igroup => $grouping){
				//                            if(!empty($modAlias) && ($model->jenis_produk == "Moulding")){
				//                                $modDetails = \app\models\TPackinglistContainer::find()
				//                                            ->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no'],$grouping['alias_name']=>$grouping['value_original']])
				//                                            ->orderBy("packinglist_container_id ASC")->all();
				//                            }else{
				//                                $modDetails = \app\models\TPackinglistContainer::find()
				//                                                ->where(['packinglist_id'=>$model->packinglist_id,'container_no'=>$container['container_no']])
				//                                                ->orderBy("packinglist_container_id ASC")->all();
				//                            }
				//							if(count($modDetails)>0){
				//								if(!empty($modAlias) && $model->jenis_produk == "Moulding"){
				//									echo '<tr style="border: solid 1px #000; border-right: solid 1px transparent; font-weight:600" class="text-align-center">';
				//									echo	'<td colspan=6>'.(($approval->status == \app\models\TApproval::STATUS_APPROVED)?$grouping['value_alias']:$grouping['value_original']).'</td>';
				//									echo '</tr>';
				//								}
				//								$subtotal_bundles = 0; $subtotal_pcs = 0; $subtotal_volume=0;
				//                                $grade = ""; $glue = "";
				//								foreach($modDetails as $i => $detail){
				//									if(($i+1) != $subtotal_bundles){
				//										$subtotal_bundles += 1;
				//									}
				//									$subtotal_pcs += $detail->pcs;
				//									$subtotal_volume += number_format($detail->volume,4);
				//									$total_pcs += $detail->pcs;
				//									$total_volume += number_format($detail->volume,4);
				//									$total_bundles = $detail->bundles_no;
				//                                    
				//                                    $garing=""; $colspan = "4";
				//                                    if($model->jenis_produk == "Plywood" || $model->jenis_produk == "Lamineboard" || $model->jenis_produk == "Platform"){
				//                                        $colspan = "5";
				//                                    }
				//                                    if(!empty($detail['jenis_kayu'])){
				//
				//                                    }
				//                                    if(!empty($detail['grade'])){
				//                                        $aliasgrade = app\models\TAlias::findOne(['reff_no'=>$model->kode,'alias_name'=>'grade','value_original'=>$detail['grade']]);
				//                                        if(!empty($aliasgrade)&&($approval->status == \app\models\TApproval::STATUS_APPROVED)){
				//                                            $grade = $aliasgrade->value_alias;
				//                                        }else{
				//                                            $grade = $detail['grade'];
				//                                        }
				//                                    }
				//                                    if(!empty($detail['glue'])){
				//                                        $aliasglue = app\models\TAlias::findOne(['reff_no'=>$model->kode,'alias_name'=>'glue','value_original'=>$detail['glue']]);
				//                                        if(!empty($aliasglue)&&($approval->status == \app\models\TApproval::STATUS_APPROVED)){
				//                                            $glue = $aliasglue->value_alias;
				//                                        }else{
				//                                            $glue = $detail['glue'];
				//                                        }
				//                                    }
				//                                    if(!empty($detail['profil_kayu'])){
				//
				//                                    }
				//                                    if(!empty($detail['kondisi_kayu'])){
				//
				//                                    }
				?>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 10px; padding-bottom: 5px; background-color: #F1F4F7;">
			<table style="width: 100%;">
				<tr>
					<td style="width: 100%; height: auto; vertical-align: top; padding-left: 5px;">
						<table style="width: 100%; line-height: 5px;">
							<tr>
								<td style="padding: 5px; width: 3.5cm;"><b>Jenis Kayu Log</b></td>
								<td style="padding: 5px; width: 0.5cm;  text-align: center;"><b>:</b></td>
								<td style="padding: 5px; line-height: 1.3;"><?php echo $modPO->jenis_log; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Asal Kayu</b></td>
								<td style="padding: 5px; vertical-align: top;  text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top;  line-height: 1.3;"><?php echo $modPO->asal_log; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Kualitas</b></td>
								<td style="padding: 5px; vertical-align: top;  text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top;  line-height: 1.3;"><?php echo $modPO->kualitas; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Diameter/Komposisi</b></td>
								<td style="padding: 5px; vertical-align: top;  text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top;  line-height: 1.3;"><?php echo $modPO->komposisi; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Harga / M<sup>3</sup></b></td>
								<td style="padding: 5px; vertical-align: top;  text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top;  line-height: 1.3;"><?php echo $modPO->hargafob; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Term of Price</b></td>
								<td style="padding: 5px; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top; line-height: 1.3;"><?php echo $modPO->term_of_price . (($modPO->is_ppn10 == true) ? "(Termasuk PPN 10%)" : ""); ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Lokasi Muat</b></td>
								<td style="padding: 5px; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top; line-height: 1.3;"><?php echo $modPO->lokasi_muat; ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Waktu Penyerahan</b></td>
								<td style="padding: 5px; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top; line-height: 1.3;"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($model->waktu_penyerahan_awal) . " s/d " . \app\components\DeltaFormatter::formatDateTimeForUser($model->waktu_penyerahan_akhir); ?></td>
							</tr>
							<tr>
								<td style="padding: 5px; vertical-align: top; "><b>Status FSC</b></td>
								<td style="padding: 5px; vertical-align: top; text-align: center;"><b>:</b></td>
								<td style="padding: 5px; vertical-align: top;"><?= $model->status_fsc ?></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="height:25px;">
		<td colspan="3" style="padding: 10px; background-color: #F1F4F7; border-top: solid 1px transparent; padding-bottom: 2cm; ">
			<table style="width: 100%;height:25px;">
				<tr>
					<td style="width: 100%; vertical-align: top; padding-left: 5px;">
						Demikian Surat Permintaan Pembelian ini dibuat, atas perhatian dan kerjasama yang baik kami ucapkan terima kasih.
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding: 5px; height: 2cm; text-align: center;">
			<?php // echo '<img src="'.\Yii::$app->view->theme->baseUrl.'/cis/img/new-sertifikat.png" alt="" class="logo-default" style="width: 8cm;">'; 
			?>
			<img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikat_tanpa_CARB.png' ?>" alt="sertifikat" class="logo-default" style="width: 8cm;">
			<!-- <img src="<?= \Yii::$app->view->theme->baseUrl . '/cis/img/sertifikatplusFSC.jpg' ?>" alt="sertifikat" class="logo-default" style="width: 8cm;"> -->
		</td>
	</tr>
</table>