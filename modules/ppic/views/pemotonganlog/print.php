<?php

use yii\helpers\Url;
?>
<div style="width: 77mm;height: 68mm; border: .5mm solid black;margin: 3mm 1mm; padding: 1mm;">
    <div style="display: flex;justify-content: space-between;">
        <div style="width: 8mm; height: 33m;border: .5mm dotted black; border-radius: 2px;position: relative;">
            <div style="font-size: 10px; position: absolute;width: 30mm;transform: rotate(90deg);top: 13mm;left: -11mm;text-align: center;">
                Area Staples
            </div>
        </div>
        <div style="height: 33mm">
            <div class="place-qrcode" style="margin-top: 4mm;margin-right: 1mm"></div>
        </div>
        <div style="height: 33mm;">
            <div class="place-qrcodek" style="margin-top: 2mm;margin-left: 1mm"></div>
        </div>
        <div style="width: 8mm; height: 33mm;border: .5mm dotted black; border-radius: 2px;position: relative;">
            <div style="font-size: 10px; position: absolute;width: 30mm;transform: rotate(-90deg);top: 13mm;left: -12mm;text-align: center;">
                Area Staples
            </div>
        </div>
    </div>
    <div style="display: flex; width: 100%;height: 12mm; text-align: center;align-items: center;margin-top: 2mm;margin-bottom: 2mm;">
        <div style="width: 16mm;">
            <img src="<?= Url::base() . '/themes/metronic/cis/img/logo-ciptana.png'?>" style="width: 10mm;">
        </div>
        <div style="width: 56mm; font-size: 5mm;font-weight: 600;">
            <?= $modDetpot->no_barcode_baru;?>
        </div>
    </div>
    <div style="display: flex; width: 100%;border: .5mm solid black;height: 8mm;font-weight: 600; border-bottom: none;">
        <div style="border-right: .5mm solid black; display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Produksi</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $modDetail->no_produksi;?></span>
        </div>
        <div style="display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Batang</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $modDetail->no_btg;?></span>
        </div>
    </div>
    <div style="display: flex; width: 100%;border: .5mm solid black;height: 8mm;font-weight: 600;">
        <div style="border-right: .5mm solid black; display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Grade</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $modDetail->no_grade;?></span>
        </div>
        <div style="display: flex; align-items: center;padding: 0 1mm;width: 100%;justify-content: center; position: relative;">
            <label style="position: absolute;top: 0;left: 0;font-size: 8px;border: 1px solid black;border-top: none;padding: 0 3px;border-left: none; width: 44px;">Lapangan</label>
            <span style="margin-top: 10px; margin-left: 30px;"><?= $modDetpot->no_lap_baru;?></span>
        </div>
    </div>
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery.qrcode.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    jQuery('.place-qrcode').qrcode({fill: '#666',width: 90,height: 90, text: '".$qrCodeContent."' });
    jQuery('.place-qrcodek').qrcode({fill: '#666',width: 110,height: 110, text: '".$qrCodeContent."' });
    ", yii\web\View::POS_READY); ?>
