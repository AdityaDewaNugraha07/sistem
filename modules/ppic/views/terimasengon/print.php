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
                                    <thead>
                                        <tr>
                                            <th><?= Yii::t('app', 'No.'); ?></th>
											<th style="line-height: 1; width: 120px;"><?= Yii::t('app', 'Kode Input'); ?></th>
                                            <th><?= Yii::t('app', 'Suplier'); ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Tanggal<br>Terima') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Nopol<br>Kendaraan') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'No<br>Urut') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode<br>Terima') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Kedatangan<br>Ke') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Kode<br>Jenis') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'D') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'P') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Pcs') ?></th>
                                            <th style="line-height: 1; width: 60px;"><?= Yii::t('app', 'M<sup>3</sup>') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= $data->kode ?></td>
												<td><?= $data->suplier_nm ?></td>
                                                <td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
                                                <td><?= $data->nopol ?></td>
                                                <td><?= $data->nourut_log ?></td>
                                                <td><?= $data->kode_terima ?></td>
                                                <td><?= $data->nourut_datang ?></td>
                                                <td><?= $data->kode_jenis ?></td>
                                                <td><?= $data->diameter ?></td>
                                                <td><?= $data->panjang ?></td>
                                                <td><?= number_format($data->qty_pcs) ?></td>
                                                <td><?= number_format($data->qty_m3,3) ?></td>
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