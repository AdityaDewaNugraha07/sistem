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
											<th style="font-size: 1.1rem; line-height: 1; width: 30px;">No.</th>
											<th style="font-size: 1.1rem; line-height: 1; width: 80px;"><?= Yii::t('app', 'Kode SPM') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 110px;"><?= Yii::t('app', 'No Inv') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 65px;"><?= Yii::t('app', 'Tanggal<br>SPM') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 65px;"><?= Yii::t('app', 'Tanggal<br>Kirim') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Customer') ?></th>
											<th style="font-size: 1.1rem; line-height: 1;"><?= Yii::t('app', 'Alamat') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 80px;"><?= Yii::t('app', 'Final Destination') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 60px;"><?= Yii::t('app', 'Supir') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 60px;"><?= Yii::t('app', 'Nopol') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 80px;"><?= Yii::t('app', 'No. Cont') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 30px;"><?= Yii::t('app', 'Cont<br>Size') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 60px;"><?= Yii::t('app', 'Jenis<br>Produk') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 70px;"><?= Yii::t('app', 'Size') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 40px;"><?= Yii::t('app', 'Total<br>Palet') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 40px;"><?= Yii::t('app', 'Total<br>Qty') ?></th>
											<th style="font-size: 1.1rem; line-height: 1; width: 50px;"><?= Yii::t('app', 'Total<br>m<sup>3<sup>') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contents = $model->searchLaporanStuffing()->all();
										if(!empty($contents)){ 
                                                                                    $total_palet = 0;
                                                                                    $total_pcs = 0;
                                                                                    $total_kubikasi = 0;
											foreach($contents as $i => $data){
                                                                                            $total_palet += $data['total_palet'];
                                                                                            $total_pcs += $data['total_qty'];
                                                                                            $total_kubikasi += number_format($data['total_m3'],4);
											?>
											<tr>
												<td style="text-align: center; font-size: 1rem; "><?= $i+1; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->kode ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->no_inv ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal); ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_kirim); ?></td>
												<td style="font-size: 1rem;"><?= $data->cust_an_nama; ?></td>
												<td style="font-size: 1rem;"><?= $data->cust_an_alamat; ?></td>
												<td style="font-size: 1rem;"><?= $data->final_destination; ?></td>
												<td style="font-size: 1rem;"><?= $data->kendaraan_supir; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->kendaraan_nopol; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->kode_kontainer; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->size_kontainer; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->jenis_produk; ?></td>
												<td style="font-size: 1rem; ">
													<?php
													$sizes = \yii\helpers\Json::decode($data->size);
													if(count($sizes)){
														foreach($sizes as $i => $size){
															if($size['min_thick']!=$size['max_thick']){
																echo $size['min_thick']."/".$size['max_thick'].$size['thick_unit']." x ";
															}else{
																echo $size['min_thick'].$size['thick_unit']." x ";
															}
															if($size['min_width']!=$size['max_width']){
																echo $size['min_width']."/".$size['max_width'].$size['width_unit']." x ";
															}else{
																echo $size['min_width'].$size['width_unit']." x ";
															}
															if($size['min_length']!=$size['max_length']){
																echo $size['min_length']."-".$size['max_length'].$size['length_unit'];
															}else{
																echo $size['min_length'].$size['length_unit'];
															}
														}
													}
													?>
												</td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->total_palet; ?></td>
												<td style="font-size: 1rem; text-align: right;"><?= $data->total_qty; ?></td>
												<td style="font-size: 1rem; text-align: right;"><?= $data->total_m3; ?></td>
											</tr>
										<?php }
										}else{
											"<tr colspan='5'>".Yii::t('app', 'Data tidak ditemukan')."</tr>";
										}
										?>
                                                                                        <tr>
                                                                                            <td colspan='14'></td>
                                                                                            <td style="font-size: 1rem; text-align: center;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_palet);?></td>
                                                                                            <td style="font-size: 1rem; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_pcs);;?></td>
                                                                                            <td style="font-size: 1rem; text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($total_kubikasi,4);?></td>
                                                                                        </tr>
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