<?php app\assets\DatepickerAsset::register($this); ?>
<?php
$model = \app\models\TApproval::find()->where(['reff_no'=>$modDkg->kode])->one();
?>
<style>
table tr td{
	padding: 3px;
	border: solid 3px #fff;
}
</style>
<div class="modal fade" id="modal-transaksi" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<?php $form = \yii\bootstrap\ActiveForm::begin([
				'id' => 'form-closing',
				'fieldConfig' => [
					'template' => '{label}<div class="col-md-7">{input} {error}</div>',
					'labelOptions'=>['class'=>'col-md-4 control-label'],
				],
			]); ?>
			<div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-modal" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<h4 class="modal-title text-align-center">Penugasan Dinas Luar Grader Purchasing Log</h4>
            </div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-5">
						<table style="width: 100%; background-color: #d5e1f3; color: #083881; border-bottom-color: #FFF">
							<tr>
								<td style="width: 30%">Kode Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->kode ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tipe Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->tipe ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tanggal Mulai</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($modDkg->tanggal) ?></td>
							</tr>
                            <tr>
                                <td style="width: 30%">Jenis Log</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%">
                                    <?php
                                    if ($modDkg->jenis_log == "LA") {
                                        echo "Log Alam";
                                    } else if ($modDkg->jenis_log == "LS") {
                                        echo "Log Sengon";
                                    } else {
                                        echo "Log Jabon";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                            <?php
                            if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "ORIENTASI") {
                            ?>
                                <td style="width: 30%">Kode Permintaan</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 65%">
                                <?php
                                $pmr_id = "";
                                foreach ($modMap as $kolom => $value) {
                                ?>
                                    <?php
                                    $sql_kode = "select kode from t_pmr where pmr_id = ".$value->pmr_id."";
                                    $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
                                    echo $kode."<br>";
                                    ?>
                                <?php
                                    $pmr_id = $value->pmr_id;
                                }
                                ?>
                                </td>
                            <?php
                            } else if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "GRADING") {
                                $i = 1;
                                ?>
                                <td style="width: 30%">Kode</td>
                                <td style="width: 5%; text-align: center;">:</td>
                                <td style="width: 65%">
                                <?php
                                $pengajuan_pembelianlog_id = "";
                                foreach ($modMap as $kolom => $value) {
                                ?>
                                    <?php
                                    $sql_kode = "select kode from t_pengajuan_pembelianlog where pengajuan_pembelianlog_id = ".$value->pengajuan_pembelianlog_id."";
                                    $kode = Yii::$app->db->createCommand($sql_kode)->queryScalar();
                                    echo $kode;
                                    ?>
                                <?php
                                     $pengajuan_pembelianlog_id = $value->pengajuan_pembelianlog_id;
                                }
                                ?>
                                </td>
                            <?php
                            }
                            ?>
                            </tr>
						</table>
					</div>
					<div class="col-md-5">
						<table style="width: 100%; background-color: #d5e1f3; color: #083881; border-bottom-color: #FFF">
							<tr>
								<td style="width: 30%">Nama Grader</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->graderlog->graderlog_nm ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Wilayah Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->wilayahDinas->wilayah_dinas_nama ?></td>
							</tr>
							<tr>
								<td style="width: 30%">Tujuan Dinas</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= !empty($modDkg->tujuan)?$modDkg->tujuan:"-"; ?></td>
							</tr>
                            <tr>
                                <td style="width: 30%">Keterangan</td>
								<td style="width: 5%; text-align: center;">:</td>
								<td style="width: 65%"><?= $modDkg->keterangan;?></td>
                            </tr>
						</table>
					</div>
					<div class="col-md-1"></div>
				</div>
                <hr>
                <div class="row">
                    <?php
                    if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "ORIENTASI") {
                    ?>
                    <div class="col-md-12" style="text-align: center;"><h4>Detail Permintaan Pembelian Log</h4></div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tr>
                                <th class="td-kecil text-center">Kode</th>
                                <th class="td-kecil text-center">Tanggal</th>
                                <th class="td-kecil text-center">Tujuan</th>
                                <th class="td-kecil text-center">Tanggal Dibutuhkan</th>
                                <th class="td-kecil text-center">Total Volume (m<sup>3<sup>)</th>
                            </tr>
                            <?php
                            foreach ($modMap as $kolom => $value) {
                            $modPmr = \app\models\TPmr::findOne($value->pmr_id);
                            ?>
                            <tr>
                                <td class="td-kecil text-center"><?php echo $modPmr->kode;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal);?></td>
                                <td class="td-kecil text-center"><?php echo $modPmr->tujuan;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_awal);?> s/d <?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPmr->tanggal_dibutuhkan_akhir);?></td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_qty_m3 = "select sum(qty_m3) as vol from t_pmr_detail where pmr_id = ".$pmr_id."";
                                    $qty_m3 = Yii::$app->db->createCommand($sql_qty_m3)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_m3);
                                    ?>
                                </td>
                                <?php
                                
                                ?>
                            </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                    } else if ($modDkg->jenis_log == "LA" && $modDkg->tipe == "GRADING") {
                    ?>
                    <div class="col-md-12" style="text-align: center;"><h4>Detail Pengajuan Pembelian Log</h4></div>
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-advance table-hover">
                            <tr>
                                <th class="td-kecil text-center">Kode</th>
                                <th class="td-kecil text-center">Tanggal</th>
                                <th class="td-kecil text-center">Volume Kontrak</th>
                                <th class="td-kecil text-center">Asal Kayu</th>
                                <th class="td-kecil text-center">Total Pcs</th>
                                <th class="td-kecil text-center">Total Volume (m<sup>3<sup>)</th>
                            </tr>
                            <?php
                            $modPengajuanPembelianlog = \app\models\TPengajuanPembelianlog::findOne($pengajuan_pembelianlog_id);
                            ?>
                            <tr>
                                <td class="td-kecil text-center"><?php echo $modPengajuanPembelianlog->kode;?></td>
                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser($modPengajuanPembelianlog->tanggal);?></td>
                                <td class="td-kecil text-right"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($modPengajuanPembelianlog->volume_kontrak);?></td>
                                <td class="td-kecil text-center"><?php echo $modPengajuanPembelianlog->asal_kayu;?></td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_jml = "select sum(qty_batang) as jml from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
                                    $qty_jml = Yii::$app->db->createCommand($sql_jml)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_jml);
                                    ?>
                                </td>
                                <td class="td-kecil text-right">
                                    <?php
                                    $sql_qty_m3 = "select sum(qty_m3) as vol from t_pengajuan_pembelianlog_detail where pengajuan_pembelianlog_id = ".$pengajuan_pembelianlog_id."";
                                    $qty_m3 = Yii::$app->db->createCommand($sql_qty_m3)->queryScalar();
                                    echo \app\components\DeltaFormatter::formatNumberForAllUser($qty_m3);
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                    } else {

                    }
                    ?>
                </div>
                <hr>
				<div class="row">
                    <div class="col-md-12" style="text-align: center;"><h4>Biaya - Biaya</h4></div>
					<div class="col-md-6">
						<h5 class="text-align-center"><b>Akomodasi Dinas</b></h5>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Dinas Grader</b></div>
							<table id="table-ajuan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 120px;">Tanggal<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 80px;">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 70px;">Payment<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center" style="width: 40px;"></th>
								</thead>
								<tbody>
									<?php
									$modAjuanDinas = \app\models\TAjuandinasGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modAjuanDinas)>0){
										foreach($modAjuanDinas as $i => $ajuan){
											echo "<tr>
													<td class='td-kecil'>".$ajuan->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->tanggal)."</td>
													<td class='td-kecil text-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($ajuan->total_ajuan)."</td>
													<td class='td-kecil'>".\app\models\TApproval::findOne(['reff_no'=>$ajuan->kode])->StatusLite."</td>
													<td class='td-kecil text-align-center'>".((!empty($ajuan->voucher_pengeluaran_id))?$ajuan->voucherPengeluaran->Status_bayarLite:"-")."</td>
													<td class='text-align-center' style='padding: 2px;'> <a class='btn btn-xs btn-outline blue-hoki' id='btn-info' onclick='detailAjuanDinas(".$ajuan->ajuandinas_grader_id.");'><i class='fa fa-info-circle'></i></a> </td>
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Dinas Greder</b></div>
							<table id="table-realisasi" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: #ccc;">
									<th class="td-kecil font-blue-dark text-align-center">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center">Periode</th>
									<th class="td-kecil font-blue-dark text-align-center">Total Realisasi</th>
                                    <th class="td-kecil font-blue-dark text-align-center">Approve<br>Status</th>
                                    <th class="td-kecil font-blue-dark text-align-center"></th>
								</thead>
								<tbody>
									<?php
									$modRealisasiDinas = \app\models\TRealisasidinasGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modRealisasiDinas)>0){
										foreach($modRealisasiDinas as $i => $realisasi){
                                            if ($realisasi->approval_status == "APPROVED") {
                                                $warna = "success";
                                            } else if ($realisasi->approval_status == "REJECTED") {
                                                $warna = "danger";
                                            } else {
                                                $warna = "default";
                                            }
											echo "<tr>
													<td class='td-kecil'>".$realisasi->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_awal).' sd '.\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_akhir)."</td>
													<td class='td-kecil text-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($realisasi->total_realisasi,2)."</td>
                                                    <td class='text-align-center'><span class=\"label label-".$warna." label-sm\" style=\"font-size: 9px;\">".$realisasi->approval_status."</span></td>
                                                    <td class='text-align-center'>
                                                        <a class='btn btn-xs btn-outline blue-hoki' id='btn-info' onclick='detailRealisasiDinas(". $realisasi->realisasidinas_grader_id .");'><i class='fa fa-info-circle'></i></a>
                                                    </td>
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<br>
					</div>
					<div class="col-md-6">
						<h5 class="text-align-center"><b>Uang Makan</b></h5>
						<div class="table-scrollable">
							<div class="pull-left font-grey-gallery" style="font-size: 1.2rem"><b>Pengajuan Uang Makan Grader</b></div>
							<table id="table-ajuanmakan" class="table table-striped table-bordered table-advance table-hover">
								<thead>
									<th class="td-kecil font-grey-gallery text-align-center">Kode</th>
									<th class="td-kecil font-grey-gallery text-align-center">Periode</th>
									<th class="td-kecil font-grey-gallery text-align-center">Total<br>Ajuan</th>
									<th class="td-kecil font-grey-gallery text-align-center">Approve<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center">Payment<br>Status</th>
									<th class="td-kecil font-grey-gallery text-align-center"></th>
								</thead>
								<tbody>
									<?php
									$modAjuanMakan = \app\models\TAjuanmakanGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modAjuanMakan)>0){
										foreach($modAjuanMakan as $i => $ajuan){
											echo "<tr>
													<td class='td-kecil'>".$ajuan->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->periode_awal)." sd ".\app\components\DeltaFormatter::formatDateTimeForUser2($ajuan->periode_akhir)."</td>
													<td class='td-kecil text-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($ajuan->total_ajuan)."</td>
													<td class='td-kecil'>".\app\models\TApproval::findOne(['reff_no'=>$ajuan->kode])->StatusLite."</td>
													<td class='td-kecil text-align-center'>".((!empty($ajuan->voucher_pengeluaran_id))?$ajuan->voucherPengeluaran->Status_bayarLite:"-")."</td>													
                                                    <td class=\"text-align-center\" style=\"padding: 2px;\"> <a class=\"btn btn-xs btn-outline blue-hoki\" id=\"btn-info\" onclick=\"detailAjuanMakan(".$ajuan->ajuanmakan_grader_id .");\"><i class=\"fa fa-info-circle\"></i></a> </td>
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Pengajuan</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<div class="table-scrollable">
							<div class="pull-left font-blue-dark" style="font-size: 1.2rem;"><b>Realisasi Uang Makan Grader</b></div>
							<table id="table-realisasimakan" class="table table-striped table-bordered table-advance table-hover">
								<thead style="background-color: #ccc;">
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Kode</th>
									<th class="td-kecil font-blue-dark text-align-center" >Periode</th>
									<th class="td-kecil font-blue-dark text-align-center" style="width: 110px;">Total Realisasi</th>
                                    <th class="td-kecil font-blue-dark text-align-center" style="width: 50px;">Approve<br>Status</th>
                                    <th class="td-kecil font-blue-dark text-align-center" style="width: 40px;"></th>
								</thead>
								<tbody>
									<?php
									$modRealisasiMakan = \app\models\TRealisasimakanGrader::find()->where(['dkg_id'=>$modDkg->dkg_id])->orderBy(['created_at'=>SORT_DESC])->all();
									if(count($modRealisasiMakan)>0){
										foreach($modRealisasiMakan as $i => $realisasi){
											echo "<tr>
													<td class='td-kecil'>".$realisasi->kode."</td>
													<td class='td-kecil'>".\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_awal).' sd '.\app\components\DeltaFormatter::formatDateTimeForUser2($realisasi->periode_akhir)."</td>
													<td class='td-kecil text-right'>".\app\components\DeltaFormatter::formatNumberForUserFloat($realisasi->total_realisasi)."</td>												
                                                    <td class='text-align-center'><span class=\"label label-success label-sm\" style=\"font-size: 9px;\">".$realisasi->approval_status."</span></td>
                                                    <td class='text-align-center'>
                                                        <a class='btn btn-xs btn-outline blue-hoki' id='btn-info' onclick='detailRealisasiMakan(". $realisasi->realisasimakan_grader_id .");'><i class='fa fa-info-circle'></i></a>
                                                    </td>
												 </tr>";
										}
									}else{
										echo "<tr><td colspan='7' class='text-align-center td-kecil'><i>Belum Ada Data Realisasi</i></td></tr>";
									}
									?>
								</tbody>
							</table>
						</div>
						<br>
					</div>
				</div>
			</div>
            <div class="modal-footer" style="text-align: center;">
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        // approve level 1
                        if(!empty($modDkg->approve_reason)){
                            $modApproveReason = \yii\helpers\Json::decode($modDkg->approve_reason);
                            foreach($modApproveReason as $kolom => $value){
                                $modDkg->jenis_log == "LA" ? $approver = 22 : $approver = 56;
                                if($value['by'] == $approver){
                                    $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                ?>
                                    <span>
                                    <br>&nbsp; <span class='font-green-seagreen text-bold'><?php echo $approver;?></span>
                                    <br>&nbsp; <span class='font-green-seagreen'>Approved at : <?php echo $tanggal;?></span>
                                    <br>&nbsp; <span class='font-green-seagreen'>Reason : <?php echo $value['reason'];?></span>
                                    </span>
                                <?php
                                }
                            }
                        }

                        // reject level 1
                        if(!empty($modDkg->reject_reason)){
                            $modApproveReason = \yii\helpers\Json::decode($modDkg->reject_reason);
                            foreach($modApproveReason as $kolom => $value){
                                $modDkg->jenis_log == "LA" ? $approver = 22 : $approver = 56;
                                if($value['by'] == $approver){
                                    $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                ?>
                                    <span>
                                    <br>&nbsp; <span class='text-danger text-bold'><?php echo $approver;?></span>
                                    <br>&nbsp; <span class='text-danger'>Rejected at : <?php echo $tanggal;?></span>
                                    <br>&nbsp; <span class='text-danger'>Reason : <?php echo $value['reason'];?></span>
                                    </span>
                                <?php
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?php
                        // approve level 2
                        if(!empty($modDkg->approve_reason)){
                            $modApproveReason = \yii\helpers\Json::decode($modDkg->approve_reason);
                            foreach($modApproveReason as $kolom => $value){
                                $approver = 124;
                                if($value['by'] == $approver){
                                    $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                ?>
                                    <span>
                                    <br>&nbsp; <span class='font-green-seagreen text-bold'><?php echo $approver;?></span>
                                    <br>&nbsp; <span class='font-green-seagreen'>Approved at : <?php echo $tanggal;?></span>
                                    <br>&nbsp; <span class='font-green-seagreen'>Reason : <?php echo $value['reason'];?></span>
                                    </span>
                                <?php
                                }
                            }
                        }

                        // reject level 2
                        if(!empty($modDkg->reject_reason)){
                            $modApproveReason = \yii\helpers\Json::decode($modDkg->reject_reason);
                            foreach($modApproveReason as $kolom => $value){
                                $approver = 124;
                                if($value['by'] == $approver){
                                    $approver = Yii::$app->db->createCommand("select pegawai_nama from m_pegawai where pegawai_id = ".$approver." ")->queryScalar();
                                    $tanggal = \app\components\DeltaFormatter::formatDateTimeForUser2($value['at']);
                                ?>
                                    <span>
                                    <br>&nbsp; <span class='text-danger text-bold'><?php echo $approver;?></span>
                                    <br>&nbsp; <span class='text-danger'>Rejected at : <?php echo $tanggal;?></span>
                                    <br>&nbsp; <span class='text-danger'>Reason : <?php echo $value['reason'];?></span>
                                    </span>
                                <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
			<?php \yii\bootstrap\ActiveForm::end(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
formconfig();
", yii\web\View::POS_READY); ?>
<script>

function yes(){
	$.ajax({
		url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/dinasgrader/changeStatus','dkg_id'=>$modDkg->dkg_id]); ?>',
		type   : 'POST',
		data   : {updaterecord:true},
		success: function (data) {
			$('#modal-delete-record').modal('hide');
			if(data.status){
				if(data.message){
                    cisAlert(data.message);
				}
				<?php if(isset($tableid)){ ?> 
					$('#<?= $tableid ?>').dataTable().fnClearTable(); 
				<?php } ?>
				if(data.callback){
					eval(data.callback);
				}else{
					
				}
			}else{
				if(data.message){
                    if(data.message.errorInfo){
                        cisAlert(data.message.errorInfo[2]);
                    }else{
                        cisAlert(data.message);
                    }
				}
			}
			$('#modal-delete-record').find('.progress-success .bar').animate({'width':'0%'});
		},
		error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
		progress: function(e) {
			if(e.lengthComputable) {
				var pct = (e.loaded / e.total) * 100;
				$('#modal-delete-record').find('.progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
			}else{
				console.warn('Content Length not reported!');
			}
		}
	});
}
function detailAjuanDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuandinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailAjuanMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailAjuanMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-ajuanmakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailRealisasiDinas(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiDinas','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasidinas';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
function detailRealisasiMakan(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/biayagrader/detailRealisasiMakan','id'=>'']) ?>'+id;
	var modal_id = 'modal-realisasimakan';
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function (e) {
			
		});
		spinbtn();
		draggableModal();
	});
	return false;
}
</script>