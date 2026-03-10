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
                                            <th style="line-height: 1; width: 35px;">No.</th>
                                            <th style="line-height: 1; width: 120px;"><?= Yii::t('app', 'Kode Afkir'); ?></th>
                                            <th><?= Yii::t('app', 'Suplier'); ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Tanggal<br>Terima') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Lokasi Muat') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Asal Kayu') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Nopol<br>Kendaraan') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Afkir<br>Pcs') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Afkir<br>M<sup>3</sup>') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Selisih<br>Pcs') ?></th>
                                            <th style="line-height: 1; width: 50px;"><?= Yii::t('app', 'Selisih<br>M<sup>3</sup>') ?></th>
                                            <th style="line-height: 1; width: 100px;"><?= Yii::t('app', 'Sudah<br>Dikirim') ?></th>
                                            <th style="line-height: 1; width: 50px;"></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ 
                                        ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td style="text-align: center;"><?= $data->kode ?></td>
												<td class="td-kecil"><?= $data->suplier_nm ?></td>
                                                <td style="text-align: center;"><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
                                                <td><?= $data->lokasi_muat ?></td>
                                                <td><?= $data->asal_kayu ?></td>
                                                <td><?= $data->nopol ?></td>
                                                <td  style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUser($data->qty_pcs) ?></td>
                                                <td  style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->qty_m3) ?></td>
                                                <td  style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUser($data->selisih_pcs) ?></td>
                                                <td  style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->selisih_m3) ?></td>
                                                <td  style="text-align: center;" class="td-kecil"><?= ($data->sudah_dikirim == true)?"Sudah Dikirim":"Belum"; ?></td>
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