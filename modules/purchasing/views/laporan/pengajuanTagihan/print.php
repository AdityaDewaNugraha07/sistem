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
                                            <th>No.</th>
											<th style="line-height: 1" class="td-kecil"><?= Yii::t('app', 'Tanggal<br>Pengajuan'); ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>Terima') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Kode<br>PO') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Supplier') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Nota') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Nota') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Kuitansi') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Berkas<br>Ajuan') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Nominal') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', 'Status') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', '14 WD') ?></th>
											<th style="line-height: 1"><?= Yii::t('app', '> 14 WD') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php
										$contents = $model->searchLaporan()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){ ?>
											<tr>
												<td style="text-align: center;"><?= $i+1; ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal) ?></td>
												<td><?= $data->terimabhp_kode ?></td>
												<td><?= $data->spo_kode ?></td>
												<td><?= $data->suplier_nm ?></td>
												<td><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_nota) ?></td>
												<td><?= $data->nomor_nota ?></td>
												<td><?= $data->nomor_kuitansi ?></td>
												<td style="font-size: 0.9rem;"><?php
													$berkas = \yii\helpers\Json::decode($data->kelengkapan_berkas);
													if(isset($berkas['is_notaasli'])){
														if($berkas['is_notaasli']=="1"){
															echo "<i class='font-green-haze'>&#10004;</i> Nota Asli<br>";
														}else{
															echo "<i class='font-red-flamingo'>&#10006;</i> Nota Asli<br>";
														}
													}
													if(isset($berkas['is_kuitansi'])){
														if($berkas['is_kuitansi']=="1"){
															echo "<i class='font-green-haze'>&#10004;</i> Kuitansi<br>";
														}else{
															echo "<i class='font-red-flamingo'>&#10006;</i> Kuitansi<br>";
														}
													}
													if(isset($berkas['is_fakturpajak'])){
														if($berkas['is_fakturpajak']=="1"){
															echo "<i class='font-green-haze'>&#10004;</i> Faktur Pajak<br>";
														}else{
															echo "<i class='font-red-flamingo'>&#10006;</i> Faktur Pajak<br>";
														}
													}
													if(isset($berkas['is_suratjalan'])){
														if($berkas['is_suratjalan']=="1"){
															echo "<i class='font-green-haze'>&#10004;</i> Surat Jalan<br>";
														}else{
															echo "<i class='font-red-flamingo'>&#10006;</i> Surat Jalan<br>";
														}
													}
												?></td>
												<td style="text-align: right;"><?= app\components\DeltaFormatter::formatNumberForUserFloat($data->nominal) ?></td>
												<td><?= $data->status ?></td>
												<td style="text-align: center;"><?php
													$date1=date_create($data->tanggal);
													$date2=date_create($data->tanggal_nota);
													$diff=date_diff($date1,$date2);
													$days = $diff->days;
													if($days <= 14){
														$ret = "1";
													}else{
														$ret = "0";
													}
													echo $ret;
												?></td>
												<td style="text-align: center;"><?php
													if($days > 14){
														$ret = "1";
													}else{
														$ret = "0";
													}
													echo $ret;
												?></td>
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