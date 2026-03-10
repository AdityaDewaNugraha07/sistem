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
                                            <th><?= Yii::t('app', 'No'); ?></th>
                                            <th><?= Yii::t('app', 'Kode<br>Defect') ?></th>
                                            <th><?= Yii::t('app', 'Kode SPK'); ?></th>
                                            <th><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th><?= Yii::t('app', 'Line<br>Sawmill') ?></th>
                                            <th><?= Yii::t('app', 'Nomor<br>Bandsaw'); ?></th>
                                            <th><?= Yii::t('app', 'Size'); ?></th>
                                            <th><?= Yii::t('app', 'Panjang'); ?></th>
                                            <th><?= Yii::t('app', 'Kategori<br>Defect'); ?></th>
                                            <th><?= Yii::t('app', 'Qty'); ?></th>
                                            <th><?= Yii::t('app', 'Keterangan'); ?></th>
                                        </tr>
									</thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data['kode']; ?></td>
                                                <td style='text-align: center;'><?= $data['kode_spk']; ?></td>
												<td style='text-align: center;'><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
                                                <td style='text-align: center;'><?= $data['kayu_nama']; ?></td>
                                                <td style='text-align: center;'><?= $data['line_sawmill']; ?></td>
                                                <td style='text-align: center;'><?= $data['nomor_bandsaw']; ?></td>
                                                <td style='text-align: center;'><?= $data['produk_t'] . 'x' . $data['produk_l']; ?></td>
                                                <td style='text-align: center;'><?= $data['produk_p']; ?></td>
                                                <td style='text-align: center;'><?= $data['kategori_defect']; ?></td>
                                                <td style='text-align: center;'><?= $data['qty']; ?></td>
                                                <td><?= $data['keterangan']?$data['keterangan']:'<center?-</center>'; ?></td>
											</tr>
										<?php }
										}else{
											echo"<tr><td colspan='11' style='text-align: center;'>".Yii::t('app', 'Data tidak ditemukan')."<td></tr>";
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