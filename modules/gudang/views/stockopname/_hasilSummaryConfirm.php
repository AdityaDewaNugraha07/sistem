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
                    foreach($rows as $i => $jnsprod){
                        if($jnsprod!="total"){
                            $model = new app\models\TStockopnameHasilDetail();
                            $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>$jnsprod])['query'];
                            $model->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$modAgenda->stockopname_agenda_id,'queryJenisProduk'=>$que]);
                        }else{
                            $model = new \app\models\TStockopnameHasil();
                            $que = Yii::$app->runAction("/gudang/stockopname/getParamJenisProduk",['jenis_produk'=>(implode(",", $jenis_produk))])['query'];
                            $model->attributes = Yii::$app->runAction("/gudang/stockopname/getDataSummary",['stockopname_agenda_id'=>$modAgenda->stockopname_agenda_id,'queryJenisProduk'=>$que]);
                        }
                    ?>
                    <tr class="text-align-right">
                        <td class="text-align-right" style="border-right: 1px solid #595959;"><?= strtoupper($jnsprod) ?></td>
                        <td style="background-color: #c9e6ff; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_yes_system_yes_palet") ?>
                            <span id="label-fisik_yes_system_yes_palet"><?= $model->fisik_yes_system_yes_palet ?></span>
                        </td>
                        <td style="background-color: #c9e6ff; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_yes_system_yes_m3") ?>
                            <span id="label-fisik_yes_system_yes_m3"><?= $model->fisik_yes_system_yes_m3 ?></span>
                        </td>
                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_yes_system_no_palet") ?>
                            <span id="label-fisik_yes_system_no_palet"><?= $model->fisik_yes_system_no_palet ?></span>
                        </td>
                        <td style="background-color: #ffdbe3; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_yes_system_no_m3") ?>
                            <span id="label-fisik_yes_system_no_m3"><?= $model->fisik_yes_system_no_m3 ?></span>
                        </td>
                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_no_system_yes_palet") ?>
                            <span id="label-fisik_no_system_yes_palet"><?= $model->fisik_no_system_yes_palet ?></span>
                        </td>
                        <td style="background-color: #f9f1d4; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]fisik_no_system_yes_m3") ?>
                            <span id="label-fisik_no_system_yes_m3"><?= $model->fisik_no_system_yes_m3 ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]total_fisik_palet") ?>
                            <span id="label-total_fisik_palet"><?= $model->total_fisik_palet ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]total_fisik_m3") ?>
                            <span id="label-total_fisik_m3"><?= $model->total_fisik_m3 ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]total_system_palet") ?>
                            <span id="label-total_system_palet"><?= $model->total_system_palet ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;">
                            <?= yii\helpers\Html::activeHiddenInput($model, "[$jnsprod]total_system_m3") ?>
                            <span id="label-total_system_m3"><?= $model->total_system_m3 ?></span>
                        </td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php // echo abs($model->total_fisik_palet-$model->total_system_palet) ?>-</td>
                        <td style="background-color: #d6d6d6; font-size: 1.1rem;" class="font-red-flamingo"><?php // echo abs($model->total_fisik_m3-$model->total_system_m3) ?>-</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <i style="font-size: 0.9rem;">Data Fisik yang dimaksud adalah palet yang memiliki label QRCode dan sudah di scan.</i>
        </div>
    </div>
</div>