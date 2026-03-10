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
                        <i><h5 class="pull-right font-red-flamingo"></h5></i>
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
											<th rowspan="2"><?= Yii::t('app', 'No.'); ?></th>
											<th rowspan="2"><?= Yii::t('app', 'Kode<br>Tanggal'); ?></th>
											<th rowspan="2"><?= Yii::t('app', 'Status<br>Approval'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Jml Permintaan Barang'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Jml Dimutasi Dari Gudang'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Jml Diterima Oleh PPIC'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Jml Dikirim Ke Gudang'); ?></th>
											<th colspan="2"><?= Yii::t('app', 'Jml Dikirim Oleh Gudang'); ?></th>
										</tr>
										<tr>
											<th>Palet</th>
											<th>Vol (m<sup>3</sup>)</th>
											<th>Palet</th>
											<th>Vol (m<sup>3</sup>)</th>
											<th>Palet</th>
											<th>Vol (m<sup>3</sup>)</th>
											<th>Palet</th>
											<th>Vol (m<sup>3</sup>)</th>
											<th>Palet</th>
											<th>Vol (m<sup>3</sup>)</th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="td-kecil text-align-center"><?= $i+1; ?></td>
												<td class="td-kecil"><?= $data['kode'] . '<br>' . \app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']); ?></td>
												<td class="td-kecil text-align-center"><?= $data['approval_status']; ?></td>
												<td class="td-kecil text-align-center"><?= $data['pcs_permintaan']; ?></td>
												<td class="td-kecil text-align-right"><?= $data['vol_permintaan']; ?></td>
												<td class="td-kecil text-align-center"><?= $data['pcs_mk']; ?></td>
												<td class="td-kecil text-align-right"><?= $data['vol_mk']; ?></td>
												<td class="td-kecil text-align-center"><?= $data['pcs_tm']; ?></td>
												<td class="td-kecil text-align-right"><?= $data['vol_tm']; ?></td>
												<td class="td-kecil text-align-center"><?= $data['pcs_kg']; ?></td>
												<td class="td-kecil text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data['vol_kg'], 4); ?></td>
												<td class="td-kecil text-align-center"><?= $data['pcs_tg']; ?></td>
												<td class="td-kecil text-align-right"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($data['vol_tg'], 4); ?></td>
											</tr>
										<?php }
										}else{?>
											<tr>
												<td colspan="13" class="text-align-center"><?= Yii::t('app', 'Data tidak ditemukan'); ?></td>
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