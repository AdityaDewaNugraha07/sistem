<?php app\assets\DatatableAsset::register($this); ?>
<?php 
$modelTspShipping = \app\models\TSpkShipping::find()->where(['spk_shipping_id'=>$id])->one();
?>
<div class="modal fade" id="modal-madul" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table" style="border: 0px;">
                            <thead>
                                <tr>
                                    <td class="text-center text-bold">PLAN SPM LOG :: <b><?php echo $modelTspShipping->kode;?></b></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><b>Lokasi Muat</b><br><?php echo $modelTspShipping->lokasi_muat;?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><b>Tanggal Input</b><br><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modelTspShipping->tanggal);?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></td>
                                </tr>                                
                                <tr>
                                    <td style="border: 0px;" class="text-center"><b>ETD</b><br><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modelTspShipping->etd);?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><b>ETA Logpond</b><br><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modelTspShipping->eta_logpond);?></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></td>
                                </tr>
                                <tr>
                                    <td style="border: 0px;" class="text-center"><b>ETA</b><br><?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($modelTspShipping->eta);?></td>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table" style="border: 0px;">
                            <thead>
                                <tr>
                                    <td class="text-center text-bold">REALISASI SPM LOG :: <b><?php echo $modelTspShipping->kode;?></b></td>
                                </tr>
                                <?php
                                $model = \app\models\TSpkShippingTracking::find()->where(['spk_shipping_id'=>$id])->all();
                                $i = 1;
                                foreach ($model as $kolom) {
                                ?>
                                    <?php /*?>
                                    <tr>
                                        <td class="td-kecil text-left" style="width: 100px; border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Kode'); ?></td>
                                        <td class="td-kecil text-left" style="width: 200px; border: solid 1px #c8c8c8;"></td>
                                    </tr>
                                    <tr>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Tanggal'); ?></td>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"></td>
                                    </tr>
                                    <tr>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Jenis'); ?></td>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"></td>
                                    </tr>
                                    <tr>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"><?= Yii::t('app', 'Lokasi'); ?></td>
                                        <td class="td-kecil text-left" style="border: solid 1px #c8c8c8;"></td>
                                    </tr>
                                    <?php
                                    */?>
                                    <tr>
                                        <td style="border: 0px;" class="text-center">
                                        <b><?php echo $kolom['jenis'];?></b>
                                        <br>
                                        <?php echo $kolom['lokasi'];?>
                                        <br>
                                        <?php echo \app\components\DeltaFormatter::formatDateTimeForUser2($kolom['tanggal']);?>
                                        <br>
                                        <?php echo $kolom['keterangan'];?>
                                        </td>
                                    </tr>
                                    <?php
                                    if ($i < 5) {
                                    ?>
                                    <tr>
                                        <td style="border: 0px;" class="text-center"><i class="fa fa-arrow-circle-down" aria-hidden="true"></i></td>
                                    </tr>
                                    <?php
                                    }
                                    $i++;
                                }
                                ?>
							</thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

