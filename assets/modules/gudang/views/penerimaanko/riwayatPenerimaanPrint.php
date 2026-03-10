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
										<th>No.</th>
                                        <th style="width: 70px;"><?= Yii::t('app', 'Kode Terima'); ?></th>
                                        <th style="width: 80px;"><?= Yii::t('app', 'Jenis Terima'); ?></th>
                                        <th style="width: 130px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
                                        <th style="width: 120px;"><?= Yii::t('app', 'Kode Produk'); ?></th>
                                        <th style=""><?= Yii::t('app', 'Nama Produk'); ?></th>
                                        <th style="width: 75px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Terima'); ?></th>
                                        <th style="width: 75px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Produksi'); ?></th>
                                        <th style="width: 50px; "><?= Yii::t('app', 'Gudang'); ?></th>
                                        <th style="width: 40px; "><?= Yii::t('app', 'Qty'); ?></th>
                                        <th style="width: 50px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                        <th style="width: 50px;"><?= Yii::t('app', 'Waktu Terima'); ?></th>
                                        <th></th>
									</thead>
									<tbody>
										<?php
										$contents = $model->searchLaporanRiwayatTerima()->all();
										if(!empty($contents)){ 
											foreach($contents as $i => $data){
												
											?>
											<tr>
												<td style="text-align: center; font-size: 1rem; "><?= $i+1; ?></td>
												<td style="font-size: 1rem; text-align: center;"><?= $data->kode ?></td>
                                                <td style="font-size: 1rem; text-align: center;">
                                                    <?php
                                                    if(!empty($data->hasil_repacking_id)){
                                                        echo "Hasil Mutasi";
                                                    }else{
                                                        echo "Reguler";
                                                    }
                                                    ?>
                                                </td>
												<td style="font-size: 1rem; text-align: center; "><?= $data->nomor_produksi; ?></td>
												<td style="font-size: 1rem; text-align: left; "><?= $data->produk_kode; ?></td>
												<td style="font-size: 1rem; text-align: left; "><?= $data->produk_nama; ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal); ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= app\components\DeltaFormatter::formatDateTimeForUser2($data->tanggal_produksi); ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= $data->gudang_nm; ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= $data->qty_kecil; ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= number_format($data->qty_m3,4); ?></td>
                                                <td style="font-size: 1rem; text-align: center; "><?= substr(app\components\DeltaFormatter::formatDateTimeForUser2($data->created_at), 11,5)."<br>".$data->username; ?></td>
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