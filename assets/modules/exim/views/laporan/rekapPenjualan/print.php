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
											<th rowspan="2"></th>
											<th colspan="3" style="line-height: 1;"><?= Yii::t('app', 'Invoice') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Volume<br>M<sup>3</sup>') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Buyer') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Address') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Produk') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Size') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Packinglist No.') ?></th>
											<th colspan="4" style="line-height: 1;"><?= Yii::t('app', 'Container') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Tanggal<br>Stuffing') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Payment<br>Method') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Term Of<br>Price') ?></th>
											<th colspan="2" style="line-height: 1;"><?= Yii::t('app', 'PEB') ?></th>
											<th colspan="3" style="line-height: 1;"><?= Yii::t('app', 'B/L') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'FOB') ?></th>
											<th rowspan="2" style="line-height: 1;"><?= Yii::t('app', 'Final<br>Destination') ?></th>
										</tr>
										<tr>
											<th style="line-height: 1;"><?= Yii::t('app', 'Kode') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'USD') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Qty') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Size') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Nomor') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Tanggal') ?></th>
											<th style="line-height: 1;"><?= Yii::t('app', 'Penerbit') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
												
											?>
											<tr>
												<td style="text-align: center; font-size: 1rem; "><?= $i+1; ?></td>
												<td style="font-size: 1rem;"><?= $data->nomor ?></td>
												<td style="font-size: 1rem;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_inv); ?></td>
												<td style="font-size: 1rem; text-align: right; ">
                                                    <?php
                                                    
                                                     // START KEBIJAKAN Perubahan Pembulatan dari Round Up ke Round Per tgl 14 Sept 2019
                                                    $tgl = date('Y-m-d', strtotime(\app\components\DeltaFormatter::formatDateTimeForDb($data->tanggal_inv)));
                                                    $tgl_kebijakan = date('Y-m-d', strtotime("2019-09-13"));
                                                    if( $tgl > $tgl_kebijakan ){
                                                        echo $xcvxcv = $xcvxcv;
                                                    }else{
                                                        echo \app\components\DeltaFormatter::roundUp($data->total_bayar, 2); 
                                                    }
                                                    // END KEBIJAKAN
                                                    \app\components\DeltaFormatter::roundUp($data->total_bayar, 2); 
                                                    ?>
                                                </td>
												<td style="font-size: 1rem; text-align: right; "><?= number_format($data->total_volume_inv,4) ?></td>
												<td style="font-size: 1rem; "><?= $data->cust_an_nama ?></td>
												<td style="font-size: 1rem; "><?= $data->cust_an_alamat ?></td>
												<td style="font-size: 1rem; "><?= $data->goods_description ?></td>
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
												<td style="font-size: 1rem; "><?= $data->nomor ?></td>
												<td style="font-size: 1rem; ">
													<?php
													$container_no = \yii\helpers\Json::decode($data->container_no);
													if(count($container_no)){
														foreach($container_no as $ii => $no){
															echo $no['container_kode']."<br>";
														}
													}
													?>
												</td>
												<td style="font-size: 1rem; "><?= !empty($data->tgl_etd)?\app\components\DeltaFormatter::formatDateTimeForUser2($data->tgl_etd):"" ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->total_container ?></td>
												<td style="font-size: 1rem; ">
													<?php
													$container_sizes = \yii\helpers\Json::decode($data->container_size);
													if(count($container_sizes)){
														foreach($container_sizes as $ii => $size){
															echo $size['container_size']." Feet<br>";
														}
													}
													?>
												</td>
												<td style="font-size: 1rem; ">
													<?php
													$tanggal_stuffing = \yii\helpers\Json::decode($data->tanggal_stuffing);
													if(count($tanggal_stuffing)){
														echo \app\components\DeltaFormatter::formatDateTimeForUser2($tanggal_stuffing[0]['tanggal']);
													}
													?>
												</td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->payment_method ?></td>
												<td style="font-size: 1rem; "><?= $data->term_of_price ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->peb_no)?$data->peb_no:""; ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->peb_tanggal)?app\components\DeltaFormatter::formatDateTimeForUser2($data->peb_tanggal):""; ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->bl_no)?$data->bl_no:""; ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->bl_tanggal)?app\components\DeltaFormatter::formatDateTimeForUser2($data->bl_tanggal):""; ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->penerbit)?$data->penerbit:""; ?></td>
												<td style="font-size: 1rem; text-align: right;"><?= !empty($data->fob)?$data->fob:""; ?></td>
												<td style="font-size: 1rem; "><?= !empty($data->final_destination)?$data->final_destination:""; ?></td>
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