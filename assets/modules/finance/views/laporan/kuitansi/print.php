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
                                <table class="table table-striped table-bordered table-hover" id="table-laporan" style="table-layout: fixed;">
                                    <thead>
                                        <tr>
											<th style="width: 50px;"><?= Yii::t('app', 'No.'); ?></th>
											<th style="width: 100px;"><?= Yii::t('app', 'Nomor') ?></th>
											<th style="width: 80px;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="width: 100px;"><?= Yii::t('app', 'Cara Bayar') ?></th>
											<th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Reff Penerimaan'); ?></th>
											<th style="width: 180px;"><?= Yii::t('app', 'Terima Dari') ?></th>
											<th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Untuk Pembayaran') ?></th>
											<th style="width: 100px;"><?= Yii::t('app', 'Nominal') ?></th>
											<th><?= Yii::t('app', 'Keterangan') ?></th>
										</tr>
                                    </thead>
									<tbody>
										<?php
										$sql = $model->searchLaporan()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											$num = 1;
											foreach($contents as $i => $data){ ?>
											<tr>
												<td class="td-kecil" style="text-align: center;"><?= $i+1 ?></td>
												<td class="td-kecil text-align-center"><?= $data['nomor'] ?></td>
												<td class="td-kecil text-align-center"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data['tanggal']) ?></td>
												<td class="td-kecil text-align-center"><?= $data['cara_bayar'] ?></td>
												<td class="td-kecil text-align-center"><?= $data['reff_penerimaan'] ?></td>
												<td class="td-kecil text-align-left"><?= $data['terima_dari'] ?></td>
												<td class="td-kecil text-align-left"><?= $data['untuk_pembayaran'] ?></td>
												<td class="td-kecil text-align-right"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data['nominal']) ?></td>
												<td class="td-kecil text-align-left"><?= $data['keterangan'] ?></td>
												
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