<?php
/* @var $this yii\web\View */
$this->title = 'Print '.$paramprint['judul'];
?>
<?php
$header = Yii::$app->controller->render('@views/apps/print/defaultHeaderLaporanP',['paramprint'=>$paramprint]);
if($_GET['caraprint'] == "EXCEL"){
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$paramprint['judul'].' - '.date("d/m/Y").'.xls"');
	header('Cache-Control: max-age=0');
	$header = "Laporan Faktur Pajak Belum Diterima<br>Periode : ".\app\components\Deltaformatter::formatDateTimeForUser($tgl_awal)." - ".\app\components\Deltaformatter::formatDateTimeForUser($tgl_akhir);
}
?>
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row print-page" style="width: 793px;">
    <div class="col-md-12">
        <div class="portlet">
            <div class="portlet-body">
				<div class="row">
                    <div class="col-md-12" style="font-size: 15px;">
						<h4><?php echo $header; ?></h4>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet">
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-laporan" style="width: 793px;">
                                    <thead>
                                        <tr>
                                            <th class="td-kecil" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
											<th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Tanggal Terima') ?></th>
											<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'SPL Kode') ?></th>
											<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'SPO Kode') ?></th>
											<th class="td-kecil" style="width: 283px;"><?= Yii::t('app', 'Suplier') ?></th>
											<th class="td-kecil" style="width: 100px;"><?php echo Yii::t('app', 'No. Faktur') ?></th>
											<th class="td-kecil" style="width: 100px;"><?php echo Yii::t('app', 'Total Bayar') ?></th>
                                        </tr>
                                    </thead>
									<tbody>
                                        <?php 
                                        $contents = $model->searchLaporanFilterNoFaktur($tgl_awal, $tgl_akhir,$suplier_id)->all();
										if(!empty($contents)){ 
                                            $i = 1;
											foreach($contents as $i => $data) { 
                                                $terima_bhp_id = $data->terima_bhp_id;
                                                $spl_id = Yii::$app->db->createCommand("select spl_id from t_terima_bhp where terima_bhp_id = ".$terima_bhp_id." ")->queryScalar();
                                                $spo_id = Yii::$app->db->createCommand("select spo_id from t_terima_bhp where terima_bhp_id = ".$terima_bhp_id." ")->queryScalar();
                                                $spl_id > 0 ? $spl_kode = Yii::$app->db->createCommand("select spl_kode from t_spl where spl_id = ".$spl_id." ")->queryScalar() : $spl_kode = '';
                                                $spo_id > 0 ? $spo_kode = Yii::$app->db->createCommand("select spo_kode from t_spo where spo_id = ".$spo_id." ")->queryScalar() : $spo_kode = '';
                                                $suplier_id = Yii::$app->db->createCommand("select suplier_id from t_terima_bhp where terima_bhp_id = ".$terima_bhp_id." ")->queryScalar();
                                                $suplier_nm = Yii::$app->db->createCommand("select suplier_nm from m_suplier where suplier_id = ".$suplier_id." ")->queryScalar();
                                            ?>
											<tr>
                                                <td class="text-center td-kecil"><?php echo $i + 1;?></td>
                                                <td class="text-center td-kecil"><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($data->tglterima);?></td>
                                                <td class="text-center td-kecil"><?php echo $spl_kode;?></td>
                                                <td class="text-center td-kecil"><?php echo $spo_kode;?></td>
                                                <td class="td-kecil"><?php echo $suplier_nm;?></td>
                                                <td class="text-center td-kecil"><?php echo $data->nofaktur;?></td>
                                                <td class="text-right td-kecil"><?php echo \app\components\DeltaFormatter::formatNumberForAllUser($data->totalbayar);?></td>
											</tr>
                                            <?php 
                                            $i++;
                                            }
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