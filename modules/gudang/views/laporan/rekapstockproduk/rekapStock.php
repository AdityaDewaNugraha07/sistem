<?php
$xplode = explode("/",$periode);
$xplode_periode = explode("-",$xplode[0]);
$periodeTh = $xplode_periode[0];
$periodeBln = $xplode_periode[1];
$targetPeriode = $periodeTh."-".$periodeBln ;

$bulan = \app\components\DeltaFormatter::getMonthId($periodeBln);

$jenisproduk = $xplode[1];
if($jenisproduk == 'Plywood'){
    $Jenis = "'Plywood','Platform','Lamineboard'";
    $labelJenis = "Plywood, Platform, Lamineboard";
}elseif($jenisproduk == 'Moulding'){
    $Jenis = "'$jenisproduk'";
    $labelJenis = "Moulding, Decking";
}

//$test = \app\components\DeltaFormatter::formatDateTimeForUser2($model['tanggal']);
//echo"<pre>";
//print_r($test);
//echo"</pre>";
//exit;

$modTargetPenjualan= \app\models\TTargetPenjualan::findOne(['target_jenis_produk'=>$jenisproduk,'target_periode'=>$targetPeriode,'type_penjualan'=>'Export','active'=>true]);


$sql = "SELECT  b.nomor,b.tanggal,
                b.peb_tanggal,
                b.bl_tanggal,
                b.bl_no,
                b.jenis_produk,
                count(DISTINCT a.container_no) AS jmlcontainer_no
        FROM t_packinglist_container a
        JOIN t_invoice b ON b.packinglist_id = a.packinglist_id
        JOIN t_packinglist as c on c.packinglist_id=a.packinglist_id
        WHERE b.bl_tanggal IS NOT NULL 
                AND EXTRACT(year FROM b.peb_tanggal)='".$periodeTh."'
                AND EXTRACT(month FROM b.peb_tanggal)='".$periodeBln."'
                and b.jenis_produk in(".$Jenis.")
        GROUP BY 1,2,3,4,5,6";
$modDetail = Yii::$app->db->createCommand($sql)->queryAll();
?>
<style>
.form-group {
    margin-bottom: 0 !important;
}
</style>
<div class="modal fade" id="modal-master-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Pencapaian Export'); ?></h4>
                <h7 class="text-danger">**Pencapaian Export pada Periode <?= $bulan." ".$periodeTh ?> ditampilkan sesuai dengan Tanggal PEB dimana Tanggal BL dan Nomor BL Sudah Update (Terisi)</h7>
            </div>
            
            <div class="modal-body" >
                    <div class="row" style="margin-bottom: 10px;">		
                            <div class="col-md-4">
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Export'); ?></label>
                                            <div class="col-md-7"><strong><?= $bulan." ".$periodeTh ?></strong></div>
                                    </div> 
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Target Export'); ?></label>
                                            <div class="col-md-7"><strong><?= $modTargetPenjualan->target_jml." ".$modTargetPenjualan->target_jml_satuan ?></strong></div>
                                    </div> 
                            </div>
                            <div class="col-md-4">
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
                                            <div class="col-md-7"><strong><?= $labelJenis ?></strong></div>
                                    </div>                                                       
                            </div>
                            
                    </div>
                    <div class="row">
                            <div class="col-md-12">
                                    <div class="portlet box blue-hoki bordered">
                                            <div class="portlet-title">
                                                    <div class="tools" style="float: left;">
                                                            <a href="javascript:;" class="collapse" data-original-title="" title=""> </a> &nbsp; 
                                                    </div>
                                                    <div class="caption"> <?= Yii::t('app', 'Show Detail'); ?> </div>
                                            </div>
                                            <div class="portlet-body" style="background-color: #d9e2f0" >
                                                    <div class="row">
                                                            <div class="col-md-12">
                                                                    <div class="table-scrollable">
                                                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail">
                                                                                    <thead>
                                                                                            <tr>
                                                                                                    <th style="width: 30px;">No.</th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Nomor Invoice'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Tanggal PEB'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Tanggal BL'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Nomor BL'); ?></th>
                                                                                                    <th style="width: 50px;">QTY <br>(Container)</th>
                                                                                            </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                            <?php
                                                                                            $total = 0;
                                                                                            $totalPalet = 0;
                                                                                            $totalPcs = 0;
                                                                                            $totalM3 = 0;
                                                                                            $totalContainer = 0;                                                                                           
                                                                                            $grandtotal = 0;
                                                                                            if(count($modDetail)>0){
                                                                                                    foreach($modDetail as $i => $detail){

//                                                                                                            $produk_id = $detail['produk_id'];
//                                                                                                            $modProduk = \app\models\MBrgProduk::findOne(['produk_id'=>$detail['produk_id']]);

                                                                                                            
                                                                                                            $totalContainer += $detail['jmlcontainer_no'];
                                                                                                            

                                                                                                            ?>

                                                                                                            <tr>
                                                                                                                    <td style="text-align: center;"><?= $i+1; ?></td>
                                                                                                                    <td style=""><?= $detail['nomor']; ?></td>
                                                                                                                    <td style="text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['tanggal']); ?></td>
                                                                                                                    <td style="text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['peb_tanggal']); ?></td>
                                                                                                                    <td style="text-align: center;"><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['bl_tanggal']); ?></td>  
                                                                                                                    <td style=""><?= $detail['bl_no']; ?></td>
                                                                                                                    <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['jmlcontainer_no']); ?></td>
                                                                                                            </tr>
                                                                                            <?php
                                                                                                    }
                                                                                            }
                                                                                            ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                            <tr>
                                                                                                    <td colspan="6" style="text-align: right;">Total Pencapaian &nbsp; </td>
                                                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($totalContainer);?></td>
                                                                                            </tr>
                                                                                    </tfoot>
                                                                            </table>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                    </div>
                            </div>
                    </div>
            </div>

        </div>
    </div>
</div>
