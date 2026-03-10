<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row print-page">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12">
						<?php echo $header; ?>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan">
                                    <thead>
										<tr>
											<th rowspan="2" class="td-kecil" style="width: 5px;">No.</th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Nomor Dokumen') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Customer') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Jenis Kayu') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'No Lap<br>No Grade<br>No Batang') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Range Diameter<br>(cm)') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Panjang<br>(m)') ?></th>
											<th colspan="5" class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Ukuran Diameter (cm)') ?></th>
											<th colspan="3" class="td-kecil" style="line-height: 1;"><?= Yii::t('app', 'Unsur Cacat (cm)') ?></th>
											<th rowspan="2" class="td-kecil" style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Total<br>M<sup>3</sup>') ?></th>
										</tr>
										<tr>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Ujung 1'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Ujung 2'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Pangkal 1'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Pangkal 2'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Rata'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Panjang'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Gb'); ?></th>
											<th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'Gr'); ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanKb()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
                                            $total_kubikasi = 0;
											foreach($contents as $i => $data) { 
                                                $total_kubikasi = $total_kubikasi + $data['kubikasi'];
                                            ?>
											<tr>
												<td style="text-align: center;" class="td-kecil"><?= $i+1; ?></td>
												<td class="td-kecil"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="td-kecil"><?= $data['nomor_dokumen']; ?></td>
												<td class="td-kecil"><?= $data['cust_an_nama']; ?></td>
												<td class="td-kecil"><?= $data['group_kayu'].' - '. $data['kayu_nama']; ?></td>
												<td class="td-kecil"><?= $data['no_lap'].'<br>'. $data['no_grade'].'<br>'. $data['no_btg']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['range_awal'].' - '. $data['range_akhir']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['panjang']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['diameter_ujung1']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['diameter_ujung2']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['diameter_pangkal1']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['diameter_pangkal2']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['diameter_rata']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['cacat_panjang']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['cacat_gb']; ?></td>
												<td style="text-align: center;" class="td-kecil"><?= $data['cacat_gr']; ?></td>
												<td style="text-align: right;" class="td-kecil"><?= $data['kubikasi']; ?></td>
                                            </tr>
										<?php } ?>
											<tr>
												<td colspan="16" class="text-right"><b>Total</b></td>
												<td class="text-right"><b><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_kubikasi,2);?></b></td>
											</tr>
										<?php }else{ ?>
											<tr>
												<td colspan="17" class="text-center">
													Data tidak ditemukan
												</td>
											</tr>
											<tr>
												<td colspan="16" class="text-right"><b>Total</b></td>
												<td class="text-right"><b>0</b></td>
											</tr>
										<?php } ?>
									</tbody>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>