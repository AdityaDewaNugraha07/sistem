<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporan',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - Per_'.(!empty($model->per_tanggal)?$model->per_tanggal:date('d/m/Y')).'.xls"');
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
                        <i><h5 class="pull-right font-red-flamingo">Cetak Laporan Stok Log</h5></i>
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
                                            <th><?= Yii::t('app', 'Tanggal') ?></th>
                                            <th><?= Yii::t('app', 'Kayu') ?></th>
                                            <th><?= Yii::t('app', 'No. Grade') ?></th>
                                            <th><?= Yii::t('app', 'No. QRcode') ?></th>
                                            <th><?= Yii::t('app', 'No. Batang') ?></th>
                                            <th><?= Yii::t('app', 'No. Lap') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th><?= Yii::t('app', 'Reff No.') ?></th>
                                            <th><?= Yii::t('app', 'Lokasi') ?></th>
                                            <th><?= Yii::t('app', 'Diameter') ?></th>
                                            <th><?= Yii::t('app', 'Panjang') ?></th>
                                            <th><?= Yii::t('app', 'Reduksi') ?></th>
                                            <th><?= Yii::t('app', 'Volume') ?></th>
                                            <th><?= Yii::t('app', 'Pot') ?></th>
                                            <th><?= Yii::t('app', 'Keterangan') ?></th>
                                            <th><?= Yii::t('app', 'Pcs') ?></th>
                                            <th><?= Yii::t('app', 'Ujung 1') ?></th>
                                            <th><?= Yii::t('app', 'Ujung 2') ?></th>
                                            <th><?= Yii::t('app', 'Pangkal 1') ?></th>
                                            <th><?= Yii::t('app', 'Pangkal 2') ?></th>
                                            <th><?= Yii::t('app', 'Cacat<br>Panjang') ?></th>
                                            <th><?= Yii::t('app', 'Cacat<br>GB') ?></th>
                                            <th><?= Yii::t('app', 'Cacat<br>GR') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ 
                                            ?>
											<tr>
												<td class="td-kecil text-center"><?= $i+1; ?></td>
                                                <td class="td-kecil text-center"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($data['tgl_transaksi']);?></td>
                                                <td class="td-kecil text-center"><?php echo $data['kayu_nama'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['no_grade'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['no_barcode'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['no_btg'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['no_lap'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['status'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['reff_no'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['lokasi'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['fisik_diameter'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['fisik_panjang'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['fisik_reduksi'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['fisik_volume'];?></td>
                                                <td class="td-kecil text-center"><?php echo $data['pot'];?></td>
                                                <td class="td-kecil text-left"><?php echo $data['keterangan'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['fisik_pcs'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['diameter_ujung1'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['diameter_ujung1'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['diameter_pangkal1'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['diameter_pangkal2'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['cacat_panjang'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['cacat_gb'];?></td>
                                                <td class="td-kecil text-right"><?php echo $data['cacat_gr'];?></td>											</tr>
										    <?php 
                                            }
										}else{
											"<tr><td colspan='25'>".Yii::t('app', 'Data tidak ditemukan')."</td></tr>";
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