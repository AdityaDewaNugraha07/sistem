<?php
$xplode = explode("/",$periode);
$salesid = $xplode[0];
$xplode_periode = explode("-",$xplode[1]);
$periodeTh = $xplode_periode[0];
$periodeBln = $xplode_periode[1];
$targetPeriode = $periodeTh."-".$periodeBln ;

$bulan = \app\components\DeltaFormatter::getMonthId($periodeBln);

$jenisproduk = $xplode[2];
if($jenisproduk == 'Plywood'){
    $Jenis = "'Plywood','Platform','Lamineboard'";
    $labelJenis = "Plywood, Platform, Lamineboard";
}elseif($jenisproduk == 'Sawntimber'){
    $Jenis = "'Sawntimber','Moulding'";
    $labelJenis = "Sawntimber, Moulding";
}else{
    $Jenis = "'$jenisproduk'";
    $labelJenis = "$jenisproduk";
}

$sql = "SELECT t_nota_penjualan.jenis_produk,t_nota_penjualan.tanggal FROM t_nota_penjualan 
        JOIN t_op_ko on t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
        WHERE EXTRACT(year FROM t_nota_penjualan.tanggal)='".$periodeTh."'
                AND EXTRACT(month FROM t_nota_penjualan.tanggal)='".$periodeBln."' AND t_nota_penjualan.jenis_produk in (".$Jenis.") and sales_id = '".$salesid."'";
$model = Yii::$app->db->createCommand($sql)->queryOne();
$modsales = \app\models\MSales::findOne(['sales_id'=>$salesid]);

//$test = \app\components\DeltaFormatter::formatDateTimeForUser2($model['tanggal']);
//echo"<pre>";
//print_r($test);
//echo"</pre>";
//exit;

$tanggal_batas = $model['tanggal'];

$modTargetSales = \app\models\TTargetPenjualanSales::findOne(['target_jenis_produk'=>$model['jenis_produk'],'target_periode'=>$targetPeriode,'type_penjualan'=>'Local','active'=>true]);

$sql2 = "SELECT t_nota_penjualan.nota_penjualan_id,t_nota_penjualan.kode,t_nota_penjualan.tanggal,t_op_ko.sales_id,t_nota_penjualan.jenis_produk,
        t_nota_penjualan_detail.nota_penjualan_detail_id,t_nota_penjualan_detail.produk_id, t_nota_penjualan_detail.qty_besar,t_nota_penjualan_detail.qty_kecil,t_nota_penjualan_detail.kubikasi,
        t_nota_penjualan_detail.harga_jual
        FROM t_nota_penjualan_detail 
        JOIN t_nota_penjualan on t_nota_penjualan.nota_penjualan_id = t_nota_penjualan_detail.nota_penjualan_id
        JOIN t_op_ko on t_op_ko.op_ko_id = t_nota_penjualan.op_ko_id
        WHERE EXTRACT(year FROM t_nota_penjualan.tanggal)='".$periodeTh."'
                AND EXTRACT(month FROM t_nota_penjualan.tanggal)='".$periodeBln."' AND t_nota_penjualan.jenis_produk in (".$Jenis.") and sales_id = '".$salesid."'";
$modDetail = Yii::$app->db->createCommand($sql2)->queryAll();

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
                <h4 class="modal-title"><?= Yii::t('app', 'Info Pencapaian Penjualan')." ".$labelJenis; ?></h4>
            </div>
            
            <div class="modal-body" >
                    <div class="row" style="margin-bottom: 10px;">		
                            <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama Sales'); ?></label>
                                            <div class="col-md-7"><strong><?= $modsales->sales_nm ?></strong></div>
                                    </div>
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Jenis Produk'); ?></label>
                                            <div class="col-md-7"><strong><?= $labelJenis ?></strong></div>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Periode Penjualan'); ?></label>
                                            <div class="col-md-7"><strong><?= $bulan." ".$periodeTh ?></strong></div>
                                    </div> 
                                    <div class="form-group col-md-12">
                                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Target Penjualan'); ?></label>
                                            <div class="col-md-7"><strong><?= $modTargetSales->target_jml." ".$modTargetSales->target_jml_satuan ?></strong></div>
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
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Nomor Penjualan'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Tanggal'); ?></th>
                                                                                                    <th style="text-align: center;"><?= Yii::t('app', 'Nama Produk'); ?></th>
                                                                                                    <th style="width: 50px;"><?= Yii::t('app', 'Palet'); ?></th>
                                                                                                    <th style=""><?= Yii::t('app', 'Qty'); ?></th>
                                                                                                    <th style=""><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
                                                                                                    <th style="display:none;"><?= Yii::t('app', 'Harga Jual Terendah'); ?></th>
                                                                                                    <th style=""><?= Yii::t('app', 'Harga Jual'); ?></th>
                                                                                                    <th style=""><?= Yii::t('app', 'Subtotal'); ?></th>
                                                                                            </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                            <?php
                                                                                            $total = 0;
                                                                                            $totalPalet = 0;
                                                                                            $totalPcs = 0;
                                                                                            $totalM3 = 0;
                                                                                                                                                                                       
                                                                                            $grandtotal = 0;
                                                                                            if(count($modDetail)>0){
                                                                                                    foreach($modDetail as $i => $detail){

                                                                                                            $produk_id = $detail['produk_id'];
                                                                                                            $modProduk = \app\models\MBrgProduk::findOne(['produk_id'=>$detail['produk_id']]);
                                                                                                            
                                                                                                            $sql_m_harga_produk = "select harga_enduser from m_harga_produk 
                                                                                                                                   where produk_id = '".$produk_id."'
                                                                                                                                   and harga_tanggal_penetapan <= '".$tanggal_batas."'
                                                                                                                                   and status_approval = 'APPROVED'
                                                                                                                                   order by harga_tanggal_penetapan desc limit 1 ";
                                                                                                            
                                                                                                            $harga_enduser = Yii::$app->db->createCommand($sql_m_harga_produk)->queryScalar();

                                                                                                            if($model['jenis_produk'] == "Plywood" || $model['jenis_produk'] == "Lamineboard" || $model['jenis_produk'] == "Platform"){
                                                                                                                    $subtotal = $detail['harga_jual'] * $detail['qty_kecil'];
                                                                                                            }elseif($model['jenis_produk'] == "Limbah"){
                                                                                                                    $subtotal = $detail['harga_jual'] * $detail['qty_kecil'];
                                                                                                            }else{
                                                                                                                    $subtotal = $detail['harga_jual'] * $detail['kubikasi'];
                                                                                                            }

                                                                                                            $harga_enduser > $detail['harga_jual'] ? $low_price = 'font-red-flamingo font-weight-bold' : $low_price = '';

                                                                                                            $total += $subtotal;   
                                                                                                            $totalPalet += $detail['qty_besar'];
                                                                                                            $totalPcs += $detail['qty_kecil'];
                                                                                                            $totalM3 += $detail['kubikasi'];
                                                                                                            

                                                                                                            if ($model['jenis_produk'] == "JasaKD" || $model['jenis_produk'] == "JasaGesek" || $model['jenis_produk'] == "JasaMoulding" ) {
                                                                                                                $sql_produk_nama = "select nama from m_produk_jasa where produk_jasa_id = '".$produk_id."' ";
                                                                                                                $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                                                                                $harga_enduser = 0;
                                                                                                            } else if ($model['jenis_produk'] == "Limbah") {
                                                                                                                //PPC - (Limbah) Limbah
                                                                                                                $sql_produk_nama = "select concat(limbah_kode,' - (',limbah_produk_jenis,') ',limbah_nama) from m_brg_limbah where limbah_id = '".$produk_id."' ";
                                                                                                                $produk_nama = Yii::$app->db->createCommand($sql_produk_nama)->queryScalar();
                                                                                                                $harga_enduser = 0;
                                                                                                            } else {
                                                                                                                $produk_nama = $modProduk->produk_nama;
                                                                                                            }

                                                                                                            ?>

                                                                                                            <tr>
                                                                                                                    <td style="text-align: center;"><?= $i+1; ?></td>
                                                                                                                    <td style=""><?= $detail['kode']; ?></td>
                                                                                                                    <td style=""><?= \app\components\DeltaFormatter::formatDateTimeForUser2($detail['tanggal']); ?></td>
                                                                                                                    <td style=""><?= $produk_nama; ?></td>
                                                                                                                    <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['qty_besar']); ?></td>
                                                                                                                    <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['qty_kecil']); ?></td>
                                                                                                                    <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['kubikasi']); ?></td>
                                                                                                                    <td style="text-align: right;display:none;" class="<?php echo $low_price;?>"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($harga_enduser); ?></td>
                                                                                                                    <td style="text-align: right;" class="<?php echo $low_price;?>"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($detail['harga_jual']); ?></td>
                                                                                                                    <td style="text-align: right;"><?= \app\components\DeltaFormatter::formatNumberForUserFloat($subtotal); ?></td>
                                                                                                            </tr>
                                                                                            <?php
                                                                                                    }
                                                                                            }
                                                                                            ?>
                                                                                    </tbody>
                                                                                    <tfoot>
                                                                                            <tr>
                                                                                                    <td colspan="4" style="text-align: right;">Total Pencapaian &nbsp; </td>
                                                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($totalPalet);?></td>
                                                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($totalPcs);?></td>
                                                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($totalM3);?></td>
                                                                                                    <td style="text-align: right;display:none;">&nbsp; </td>
                                                                                                    <td style="text-align: right;">&nbsp; </td>
                                                                                                    <td style="text-align: right;"><?php echo \app\components\DeltaFormatter::formatNumberForUserFloat($total);?></td>
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
