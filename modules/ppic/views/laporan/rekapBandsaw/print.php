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
                                            <th><?= Yii::t('app', 'Kode SPK'); ?></th>
                                            <th><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                            <th><?= Yii::t('app', 'Nomor<br>Bandsaw'); ?></th>
                                            <th><?= Yii::t('app', 'Size'); ?></th>
                                            <th><?= Yii::t('app', 'Panjang'); ?></th>
                                            <th><?= Yii::t('app', 'Qty'); ?></th>
                                            <th><?= Yii::t('app', 'Volume (m<sup>3</sup>)'); ?></th>
                                        </tr>
									</thead>
									<tbody>
										<?php
										$sql = $model->searchLaporanRekap()->createCommand()->rawSql;
										$contents = Yii::$app->db->createCommand($sql)->queryAll();
										if(count($contents)>0){ 
											foreach($contents as $i => $data){ 
                                                $vol = $data['produk_t'] * $data['produk_l']  * $data['produk_p']  * $data['qty'] / 1000000;
                                                ?>
											<tr>
                                                <td style='text-align: center;'><?= $data['kode_spk']; ?></td>
                                                <td style='text-align: center;'><?= $data['kayu_nama']; ?></td>
                                                <td style='text-align: center;'><?= $data['nomor_bandsaw']; ?></td>
                                                <td style='text-align: center;'><?= $data['produk_t'] . 'x' . $data['produk_l']; ?></td>
                                                <td style='text-align: center;'><?= $data['produk_p']; ?></td>
                                                <td style='text-align: center;'><?= $data['qty']; ?></td>
                                                <td style='text-align: right;'><?= \app\components\DeltaFormatter::formatNumberForAllUser($vol, 4); ?></td>
											</tr>
										<?php }
										}else{
											echo"<tr><td colspan='5' style='text-align: center;'>".Yii::t('app', 'Data tidak ditemukan')."<td></tr>";
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