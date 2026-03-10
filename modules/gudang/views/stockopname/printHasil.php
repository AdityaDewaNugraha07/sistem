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
                    <div class="col-md-6" style="margin-bottom: -12px;">
                        <h5><?= "Summary Hasil" ?></h5>
                    </div>
                    <div class="col-md-6" style="margin-bottom: -12px;">
                        <i><h5 class="pull-right font-red-flamingo">TRIAL VERSION</h5></i>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-advance table-hover" style="width: 100%" id="table-detail">
                                <tbody>
                                    <tr>
                                        <td rowspan="2" class="" style="border-right: 1px solid #595959; border-bottom: 1px solid #595959;"></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #c9e6ff;">Fisik-<b>Yes</b> System-<b>Yes</b></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #ffdbe3;">Fisik-<b>Yes</b> System-<b>No</b></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #f9f1d4;">Fisik-<b>No</b> System-<b>Yes</b></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Total Fisik</b></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Total System</b></td>
                                        <td colspan="2" class="text-align-center" style="font-size: 1.1rem; background-color: #d6d6d6;"><b>Selisih</b></td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #000;">
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #c9e6ff; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #c9e6ff; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #ffdbe3; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #ffdbe3; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #f9f1d4; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #f9f1d4; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                        <td class="text-align-center" style="width: 60px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>Palet</b></td>
                                        <td class="text-align-center" style="width: 80px; font-size: 1.1rem; background-color: #d6d6d6; border-bottom: 1px solid #595959;"><b>M<sup>3</sup></b></td>
                                    </tr>
                                    <?php
                                    $rows=$jenis_produk;
                                    array_push($rows, "total");
                                    $modStockopname = new app\models\TStockopnameHasil();
                                    foreach($rows as $i => $jnsprod){
                                        if($jnsprod!="total"){
                                            $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>$jnsprod])['query'];
                                            $modStockopname->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$model->stockopname_agenda_id,'queryJenisProduk'=>$que]);
                                        }else{
                                            $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>(implode(",", $jenis_produk))])['query'];
                                            $modStockopname->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$model->stockopname_agenda_id,'queryJenisProduk'=>$que]);
                                        }
                                    ?>
                                    <tr class="text-align-right">
                                        <td class="text-align-right" style="border-right: 1px solid #595959; font-size: 1.1rem;"><?= strtoupper($jnsprod) ?></td>
                                        <td style="background-color: #c9e6ff; font-size: 1.1rem;">
                                            <span id="label-fisik_yes_system_yes_palet"><?= $modStockopname->fisik_yes_system_yes_palet ?></span>
                                        </td>
                                        <td style="background-color: #c9e6ff; font-size: 1.1rem;">
                                            <span id="label-fisik_yes_system_yes_m3"><?= $modStockopname->fisik_yes_system_yes_m3 ?></span>
                                        </td>
                                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                                            <span id="label-fisik_yes_system_no_palet"><?= $modStockopname->fisik_yes_system_no_palet ?></span>
                                        </td>
                                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                                            <span id="label-fisik_yes_system_no_m3"><?= $modStockopname->fisik_yes_system_no_m3 ?></span>
                                        </td>
                                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                                            <span id="label-fisik_no_system_yes_palet"><?= $modStockopname->fisik_no_system_yes_palet ?></span>
                                        </td>
                                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                                            <span id="label-fisik_no_system_yes_m3"><?= $modStockopname->fisik_no_system_yes_m3 ?></span>
                                        </td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                                            <span id="label-total_fisik_palet"><?= $modStockopname->total_fisik_palet ?></span>
                                        </td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                                            <span id="label-total_fisik_m3"><?= $modStockopname->total_fisik_m3 ?></span>
                                        </td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                                            <span id="label-total_system_palet"><?= $modStockopname->total_system_palet ?></span>
                                        </td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                                            <span id="label-total_system_m3"><?= $modStockopname->total_system_m3 ?></span>
                                        </td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php // echo abs($modStockopname->total_fisik_palet-$modStockopname->total_system_palet) ?></td>
                                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php // echo abs($modStockopname->total_fisik_m3-$modStockopname->total_system_m3) ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <i style="font-size: 0.9rem;">Data Fisik yang dimaksud adalah palet yang memiliki label QRCode dan sudah di scan.</i>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12" style="">
                        <h5 id=""><?= Yii::t('app', 'Detail Hasil Verifikasi Data'); ?></h5>
                    </div>
                </div>
                <div class="row" style="margin-left: -10px; margin-right: -10px;">
                    <div class="col-md-12" style="padding-left: 0px; padding-right: 0px;">
                        <!--<div class="table-scrollable">-->
                            <table class="table table-striped table-bordered table-advance table-hover table-laporan" style="width: 100%; border: 1px solid #A0A5A9;" id="table-master">
                                <thead>
                                    <tr>
                                        <th style="width: 25px; font-size: 1.3rem; line-height: 0.9; padding: 5px;">No.</th>
                                        <th style="width: 100px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Kode Barang Jadi'); ?></th>
                                        <th style="font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Produk'); ?></th>
                                        <th style="width: 40px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Lokasi<br>Gudang'); ?></th>
                                        <th style="width: 80px; font-size: 1.3rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'Agenda'); ?></th>
                                        <th style="width: 35px; line-height: 0.9; font-size: 1.3rem; padding: 3px;"><?= Yii::t('app', 'Qty'); ?></th>
                                        <th style="width: 45px; font-size: 1.2rem; line-height: 0.9; padding: 5px;"><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                        <th style="width: 110px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Scaned'); ?></th>
                                        <th style="width: 35px; line-height: 0.9; font-size: 1.1rem;"><?= Yii::t('app', 'Stat'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $queryDt = Yii::$app->runAction("/gudang/stockopname/getHasilDetailQuery",['stockopname_agenda_id'=>$model->stockopname_agenda_id,'queryJenisProduk'=>$paramJenisProduk['query'],'status'=>$filterstatus]);
                                        $dataPrints = \app\components\DtParamsToRawQuery::generate($queryDt); $ret = "";
                                        if(!empty($dataPrints['data'])){
                                            foreach($dataPrints['data'] as $i => $dtpr){
                                                $ret .= "<tr style='font-size:1rem;'>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:center;'>".($i+1)."</td>";
                                                $ret .= "   <td style='font-size:1.1rem;'>".$dtpr['nomor_produksi']."</td>";
                                                $ret .= "   <td style='font-size:1.1rem;'>".$dtpr['produk']."</td>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:center;'>".$dtpr['gudang_nm']."</td>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:center;'>".$dtpr['permintaan']."</td>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:center;'>".$dtpr['qty_kecil']."</td>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:right;'>". number_format($dtpr['qty_m3'],4)."</td>";
                                                $ret .= "   <td style='font-size:0.9rem; text-align:center;'>".("<b>".$dtpr['username']."</b><br>".(\app\components\DeltaFormatter::formatDateTimeForUser2($dtpr['created_at'])))."</td>";
                                                $ret .= "   <td style='font-size:1.1rem; text-align:center;'>".$dtpr['status']."</td>";
                                                $ret .= "</tr>";
                                            }
                                        }
                                        echo $ret;
                                    ?>
                                </tbody>
                            </table>
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
$this->registerJs(" 
    
", yii\web\View::POS_READY); ?>
<script>

</script>