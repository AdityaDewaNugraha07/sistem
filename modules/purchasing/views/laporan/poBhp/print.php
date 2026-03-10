<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "";
}
?>
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
									<?php
									$contents = $model->searchLaporan()->all();
									$max_penawaran = 1;
									if(!empty($contents)){ 
										$sql = "SELECT map_penawaran_bhp.spod_id, COUNT(map_penawaran_bhp_id) AS max_penawaran FROM map_penawaran_bhp
												JOIN t_spo_detail ON map_penawaran_bhp.spod_id = t_spo_detail.spod_id 
												JOIN t_spo ON t_spo.spo_id = t_spo_detail.spo_id 
												WHERE ( t_spo.cancel_transaksi_id IS NULL ) AND (spo_tanggal BETWEEN '{$model->tgl_awal}' AND '{$model->tgl_akhir}' ) 
												GROUP BY map_penawaran_bhp.spod_id
												ORDER BY 2 DESC LIMIT 1";
										$max = Yii::$app->db->createCommand($sql)->queryOne()['max_penawaran'];
										$max_penawaran = !empty($max)?$max:$max_penawaran;
									}
									?>
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'No.'); ?></th>
											<th><?= Yii::t('app', 'Kode PO') ?></th>
											<th><?= Yii::t('app', 'Tanggal Order') ?></th>
											<th><?= Yii::t('app', 'Nama Item') ?></th>
											<th><?= Yii::t('app', 'Qty') ?></th>
											<th><?= Yii::t('app', 'Satuan') ?></th>
											<th><?= Yii::t('app', 'Status<br>Garansi') ?></th>
											<th><?= Yii::t('app', 'Harga') ?></th>
											<th><?= Yii::t('app', 'Suplier') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
											<th><?= Yii::t('app', 'Kode TBP') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Rencana Kirim') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Penerimaan') ?></th>
											<?php for($i=0;$i<$max_penawaran;$i++){ ?>
											<th style="line-height: 1">Penawaran <br><?= $i+1 ?></th>
											<?php } ?>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										if(!empty($contents)){
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->spo_kode ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->spo_tanggal) ?></td>
												<td><?= $data->bhp_nm ?></td>
												<td><?= $data->spod_qty ?></td>
												<td><?= $data->bhp_satuan ?></td>
												<td style="text-align: center;"><?= ($data->spod_garansi == true) ? 'Bergaransi' : '-'; ?></td>
												<td style="text-align: right;"><?= number_format($data->spod_harga); ?></td>
												<td><?= $data->suplier_nm ?></td>
												<td style="font-size: 1.1rem;"><?= $data->spod_keterangan ?></td>
												<td style="font-size: 1.2rem;"><?= !empty($data->terimabhp_kode)?$data->terimabhp_kode:"<center>-</center>" ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2( $data->tanggal_kirim ) ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2( $data->tglterima ) ?></td>
												<?php
												$sql = "SELECT * FROM map_penawaran_bhp 
														JOIN t_penawaran_bhp ON t_penawaran_bhp.penawaran_bhp_id = map_penawaran_bhp.penawaran_bhp_id 
														JOIN m_suplier ON m_suplier.suplier_id = t_penawaran_bhp.suplier_id 
														WHERE spod_id = ".$data->spod_id."
														ORDER BY t_penawaran_bhp.harga_satuan ASC";
												$modTawar = Yii::$app->db->createCommand($sql)->queryAll();
												if(count($modTawar)>0){
													foreach($modTawar as $ii => $tawar){
														if(!empty($tawar)){
															$ketpenawaran = " - ".$tawar['keterangan'];
														}else{
															$ketpenawaran = "";
														}
														echo '<td style="line-height: 1; width: 150px; font-size: 1rem;">';
														echo $tawar['suplier_nm']." (".app\components\DeltaFormatter::formatNumberForUserFloat($tawar['harga_satuan'])."){$ketpenawaran}<br>";
														echo '</td>';
													}
												}
												?>
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