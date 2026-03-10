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
						<?php //echo $header; ?>
                        <table style="width: 100%; margin-left: -10px;" border="0">
                            <tr>
                                <td colspan="19" style="padding: 5px; border-bottom: solid 1px transparent;">
                                    <table style="width: 100%; " border="0">
                                        <tr style="border-bottom: 1px solid black;">
                                            <td style="text-align: left; vertical-align: middle; padding: 0px; width: 4cm; height: 1cm; border-right: solid 1px transparent;">
                                                <img src="<?php echo \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" alt="" class="logo-default" style="width: 80px;"> 	
                                            </td>
                                            <td colspan="18" style="text-align: center; vertical-align: top; padding: 10px; line-height: 1.3;">
                                                <span style="font-size: 1.9rem; font-weight: 600"><?= $paramprint['judul']; ?></span><br>
                                                <?php echo (isset($paramprint['judul2'])?$paramprint['judul2']:"") ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
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
                                            <th rowspan="2" class="td-kecil">No.</th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Kode'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Tanggal'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Sales'); ?></th>										
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Customer'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Tanggal<br>Terima/Hasil'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Nopol'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'No. Palet'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Produk'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Dimensi<br>(t x l x p)'); ?></th>
                                            <th colspan="2" class="td-kecil"><?= Yii::t('app', 'Dokumen'); ?></th>
                                            <th colspan="2" class="td-kecil"><?= Yii::t('app', 'Penerimaan Aktual'); ?></th>
                                            <th rowspan="2" class="td-kecil"><?= Yii::t('app', 'Ket'); ?></th>
                                            <th colspan="3" class="td-kecil"><?= Yii::t('app', 'Dikirim'); ?></th>
                                        </tr>
                                        <tr>
                                            <th class="td-kecil">Qty</th>
                                            <th class="td-kecil">Vol</th>
                                            <th class="td-kecil">Qty</th>
                                            <th class="td-kecil">Vol</th>
                                            <th class="td-kecil">Kode</th>
                                            <th class="td-kecil">Tanggal</th>
                                            <th class="td-kecil">Vol</th>
                                        </tr>
                                    </thead>
									<tbody>
										<?php 
                                        $sql = $model->searchLaporanPenerimaanJasaKD()->createCommand()->rawSql;
                                        $modDetail = Yii::$app->db->createCommand($sql)->queryAll();
                                        $total_qty = 0; $total_vol = 0;
                                        $total_qty_act = 0; $total_vol_act = 0;
                                        $totals = 0;
                                        if(count($modDetail) > 0){
                                            foreach($modDetail as $i => $detail){
                                                $total_qty += $detail['qty_kecil'];
                                                $total_vol += $detail['kubikasi'];
                                                $total_qty_act += $detail['qty_kecil_actual'];
                                                $total_vol_act += $detail['kubikasi_actual'];
                                                if($detail['status'] == 'REALISASI'){
                                                    $totals += $detail['kubikasi_actual'];
                                                }
                                                ?>
                                            <tr>
                                                <td class="td-kecil"><?= $i+1; ?></td>
                                                <td class="td-kecil"><?= $detail['kode']; ?></td>
                                                <td class="td-kecil"><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal']); ?></td>
                                                <td class="td-kecil"><?= $detail['sales_nm']; ?></td>
                                                <td class="td-kecil"><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tanggal_kirim']); ?></td>
                                                <td class="td-kecil"><?= $detail['cust_pr_nama']?$detail['cust_pr_nama']:$detail['cust_an_nama']; ?></td>
                                                <td class="td-kecil"><?= \app\components\DeltaFormatter::formatDateTimeForUser($detail['tgl_terima']); ?></td>
                                                <td class="td-kecil"><?= $detail['nopol']; ?></td>
                                                <td class="td-kecil"><?= $detail['nomor_palet']; ?></td>
                                                <td class="td-kecil"><?= $detail['nama']; ?></td>
                                                <td class="td-kecil"><?= $detail['dimensi']; ?></td>
                                                <td class="td-kecil text-align-right"><?= $detail['qty_kecil']; ?></td>
                                                <td class="td-kecil text-align-right"><?= number_format($detail['kubikasi'], 4); ?></td>
                                                <td class="td-kecil text-align-right"><?= $detail['qty_kecil_actual']; ?></td>
                                                <td class="td-kecil text-align-right"><?= number_format($detail['kubikasi_actual'], 4); ?></td>
                                                <td class="td-kecil"><?= $detail['keterangan']; ?></td>
                                                <td class="td-kecil"><?= $detail['status']=='REALISASI'?$detail['kode_spm']:'-'; ?></td>
                                                <td class="td-kecil"><?= $detail['status']=='REALISASI'?\app\components\DeltaFormatter::formatDateTimeForUser($detail['tgl_spm']):'-'; ?></td>
                                                <td class="td-kecil text-align-right"><?= $detail['status']=='REALISASI'?number_format($detail['kubikasi_actual'], 4):'-'; ?></td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="19" class="text-align-center td-kecil">Data tidak ditemukan</td>
                                            </tr>
                                        <?php }
                                        ?>
									</tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="td-kecil text-align-right" colspan="11" style="font-weight: bold;">TOTAL</th>
                                            <th class="td-kecil text-align-right" style="font-weight: bold;"><?= $total_qty; ?></th>
                                            <th class="td-kecil text-align-right" style="font-weight: bold;"><?= number_format($total_vol, 4) ?></th>
                                            <th class="td-kecil text-align-right" style="font-weight: bold;"><?= $total_qty_act; ?></th>
                                            <th class="td-kecil text-align-right" style="font-weight: bold;"><?= number_format($total_vol_act, 4); ?></th>
                                            <th colspan="3"></th>
                                            <th class="td-kecil text-align-right" style="font-weight: bold;"><?= number_format($totals, 4); ?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width: 100%; margin-left: -10px;" border="0">
                            <tr>
                                <td colspan="19" style="font-size: 0.9rem; border: solid 1px transparent; border-top: solid 1px #000; height: 20px; vertical-align: top;">
                                    <?php
                                    echo Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;";
                                    echo Yii::t('app', 'at : '). date('d/m/Y H:i:s');
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>