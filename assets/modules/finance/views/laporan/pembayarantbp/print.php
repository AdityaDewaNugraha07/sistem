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
											<th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Tanggal'); ?></th>
											<th><?= Yii::t('app', 'Kode') ?></th>
											<th><?= Yii::t('app', 'Reff Kode') ?></th>
											<th><?= Yii::t('app', 'Nama Items') ?></th>
											<th><?= Yii::t('app', 'Qty') ?></th>
											<th><?= Yii::t('app', 'Satuan') ?></th>
											<th><?= Yii::t('app', 'Harga') ?></th>
											<th><?= Yii::t('app', 'Supplier') ?></th>
											<th><?= Yii::t('app', 'Invoice') ?></th>
											<th><?= Yii::t('app', 'Payment Status') ?></th>
											<th><?= Yii::t('app', 'Payment Reff') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tglterima']); ?></td>
												<td class="text-align-center"><?= $data['terimabhp_kode'] ?></td>
												<td class="text-align-center">
													<?php
													if(!empty($data['spo_kode'])){
														echo $data['spo_kode'];
													}
													if(!empty($data['spl_kode'])){
														echo $data['spl_kode'];
													}
													?>
												</td>
												<td class="text-align-left"><?= $data['bhp_nm'] ?></td>
												<td class="text-align-right"><?= $data['terimabhpd_qty'] ?></td>
												<td class="text-align-left"><?= $data['bhp_satuan'] ?></td>
												<td class="text-align-right"><?= str_replace(".", ",", (string)$data['terimabhpd_harga']) ?></td>
												<td><?= !empty($data['suplier_nm'])?$data['suplier_nm']:'-' ?></td>
												<td class="text-align-center"><?= !empty($data['nofaktur'])?$data['nofaktur']:'-' ?></td>
												<td class="text-align-center">
													<?php
													$res = "-";
													if(!empty($data['cancel_transaksi_id'])){
														$res = \app\models\TCancelTransaksi::STATUS_ABORTED;
													}else{
														if(!empty($data['voucher_pengeluaran_id'])){
															$modVoucher = \app\models\TVoucherPengeluaran::findOne($data['voucher_pengeluaran_id']);
															if(!empty($modVoucher)){
																$res = $modVoucher->status_bayar;
															}
														}else{
															$modKasKecil = \app\models\TKasKecil::findOne(['kas_kecil_id'=>$data['kas_kecil_id']]);
															if(!empty($modKasKecil)){
																$res = "PAID";
															}
														}
													}
													if($res=='UNPAID'){
														$res = "<b>UNPAID</b>";
													}else if($res=='PAID'){
														$res = "<b>PAID</b>";
													}
													echo $res;
													?>
												</td>
												<td class="text-align-center">
													<?php
													$res = "-";
													if(!empty($data['cancel_transaksi_id'])){
														$res = \app\models\TCancelTransaksi::STATUS_ABORTED;
													}else{
														if(!empty($data['voucher_pengeluaran_id'])){
															$modVoucher = \app\models\TVoucherPengeluaran::findOne($data['voucher_pengeluaran_id']);
															if(!empty($modVoucher)){
																$res = $modVoucher->kode;
															}
														}else{
															$modKasKecil = \app\models\TKasKecil::findOne(['kas_kecil_id'=>$data['kas_kecil_id']]);
															if(!empty($modKasKecil)){
																$res = $modKasKecil->kode;
															}
														}
													}
													echo $res;
													?>
												</td>
												<td class="text-align-right">
													<?= ($_GET['caraprint'] == "EXCEL")?$data['totalbayar']:\app\components\DeltaFormatter::formatNumberForUserFloat($data['totalbayar']) ?>
												</td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
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