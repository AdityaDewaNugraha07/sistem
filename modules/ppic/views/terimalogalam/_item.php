<?php

use yii\helpers\Url;
use app\models\MKayu;
use yii\bootstrap\Html;
use app\models\TPengajuanPembelianlog;
use app\models\TTerimaLogalamDetail;

$disabled = isset($view) && $view;
$modDetail = isset($edit) || isset($view) ? TTerimaLogalamDetail::findOne($terima_logalam_detail_id) : new TTerimaLogalamDetail();
if ($modDetail->terima_logalam_detail_id) {
    switch ($modDetail->kode_potong) {
        case 'A':
            $modDetail->kode_potong = '01';
            break;
        case 'B':
            $modDetail->kode_potong = '02';
            break;
        case 'C':
            $modDetail->kode_potong = '03';
            break;
        default:
            $modDetail->kode_potong = '-';
            break;
    }
}

if (isset($pengajuan_pembelianlog_id)) {
    $modDetail->pengajuan_pembelianlog_id = $pengajuan_pembelianlog_id;
}

if (isset($kayu_id)) {
    $modDetail->kayu_id = $kayu_id;
}

if (empty($modDetail->cacat_gr)) {
    $modDetail->cacat_gr = 0;
}

// TAMBAH FSC - menentukan value dan kondisi checked pada checkbox status fsc 
if(isset($view) || isset($edit)){
    if($modDetail->fsc){
        $value = 1;
    } else {
        $value = 0;
    }
} else {
    if(isset($fsc) && $fsc == 1){
        $modDetail->fsc = true;
        $value = 1;
    } else {
        if($area_pembelian == "Luar Jawa"){
            $modDetail->fsc = false;
            $value = 0;
        } else {
            $model = TPengajuanPembelianlog::findOne($pengajuan_pembelianlog_id);
            $status_fsc = $model->status_fsc;
            if($status_fsc == 'FSC 100%'){
                $modDetail->fsc = true;
                $value = 1;
            } else {
                $modDetail->fsc = false;
                $value = 0;
            }
        }
    }
}
//eo FSC
?>
<tr>
    <td class="item-data">
        <?= $next_nomor ?>
        <?= Html::activeHiddenInput($modDetail, '[ii]next_nomor', ['value' => $next_nomor]); ?>
    </td>
    <td class="item-data is-jawa" style="display: <?= $area_pembelian == "Luar Jawa" ? 'block' : 'none' ?>">
        <?= Html::activeDropDownList($modDetail, '[ii]pengajuan_pembelianlog_id', $area_pembelian === "Luar Jawa" ? TPengajuanPembelianlog::getOptionListPenerimaanLogAlamLuarJawa($spk_shipping_id) : [], [
            'class' => 'form-control select2 item-input',
            'prompt' => '',
            'onchange' => 'setCheckboxfsc(this)', // TAMBAH FSC - untuk set checkbox status fsc
            'disabled' => $disabled
        ]);
        ?>
    </td>
    <td class="item-data">
        <?php
        echo Html::activeDropDownList($modDetail, '[ii]kayu_id', MKayu::getOptionListIlimiahKayu(), [
            'class' => 'form-control item-input',
            'prompt' => '',
            'disabled' => $disabled,
        ]);

        echo Html::activeHiddenInput($modDetail, "[ii]no_barcode");
        echo Html::activeHiddenInput($modDetail, "[ii]terima_logalam_detail_id");
        echo Html::activeHiddenInput($modDetail, "[ii]terima_logalam_id");
        ?>
    </td>
    <!-- <td class="item-data">
        <?php // Html::activeTextInput($modDetail, '[ii]no_grade', ['class' => 'form-control text-center item-input', 'disabled' => $disabled]); 
        ?>
    </td> -->
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]no_grade', ['class' => 'form-control text-center item-input', 'disabled' => $disabled, 'oninput' => 'numericInput(this)']); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]no_btg', ['class' => 'form-control text-center item-input', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]no_produksi', ['class' => 'form-control text-center item-input', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeDropDownList($modDetail, '[ii]kode_potong', array('-' => '- - -', '01' => 'A', '02' => 'B', '03' => 'C'), ['class' => 'form-control text-center item-input', 'disabled' => $disabled, 'onchange' => 'handleKodePotong(this)']); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]panjang', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]diameter_ujung1', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]diameter_ujung2', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]diameter_pangkal1', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]diameter_pangkal2', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]diameter_rata', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungRata(this); hitungVolume(this)', 'disabled' => $disabled, 'readonly' => true]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]cacat_panjang', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungVolume(this)', 'disabled' => $disabled, 'value' => $modDetail->cacat_panjang ? $modDetail->cacat_panjang : 0]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]cacat_gb', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungVolume(this)', 'disabled' => $disabled, 'value' => $modDetail->cacat_gb ? $modDetail->cacat_gb : 0]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]cacat_gr', ['class' => 'form-control float text-center item-input', 'onblur' => 'hitungVolume(this)', 'disabled' => $disabled, ['value' => $modDetail->cacat_gr]]); ?>
    </td>
    <td class="item-data">
        <?= Html::activeTextInput($modDetail, '[ii]volume', ['class' => 'form-control text-center item-input', 'disabled' => $disabled, 'readonly' => true]); ?>
    </td>
    <!-- TAMBAH FSC - checkbox status fsc -->
    <td class="item-data">
        <?= Html::activeCheckbox($modDetail, '[ii]fsc', ['class' => 'td-kecil', 'disabled' => 'disabled','label'=>'', 'value'=>$value]); ?>
    </td>
    <!-- eo FSC -->
    <td style="display:flex; justify-content: center;">
        <?php if (isset($view)) : ?>
            <a class="btn btn-xs green-jungle" onclick="openModal('<?= Url::toRoute('/ppic/terimalogalam/editdetail?id=' . $modDetail->terima_logalam_detail_id)?>', 'modal-logalam-edit')" title="Koreksi">
                <i class="fa fa-pencil"></i>
            </a>
        <?php endif ?>
        <?php if (!isset($view)) : ?>
            <a class="btn btn-xs red" id="close-btn-this" onclick="cancelItemThis(this, '<?= $modDetail->terima_logalam_detail_id ?>')" title="Hapus Detail"><i class="fa fa-remove"></i></a>
        <?php endif ?>
        <?php if (isset($view) && $peruntukan === 'Industri') : ?>
            <a class="btn btn-xs default" id="print-btn-this" onclick="window.open(
				'<?= Url::toRoute('/ppic/terimalogalam/print?id=' . $modDetail->terima_logalam_detail_id . '&caraprint=PRINT') ?>', 
				'Print Barcode',
				'width=1200',
				false,
				'_blank'
			)"><i class="fa fa-print"></i>
            </a>
        <?php endif ?>
    </td>
</tr>