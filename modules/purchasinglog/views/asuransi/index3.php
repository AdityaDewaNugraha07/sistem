<?php
/* @var $this yii\web\View */
$this->title = 'Asuransi';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);

if ($disabled == 1 || (isset($_SESSION['error']) && $_SESSION['error'] == "Data sudah diapprove/direject")) {
    $disabledX = true;
    $disabledY = "disabled";
} else {
    $disabledX = false;
    $disabledY = "";
}

if ((isset($_GET['edit']) && $_GET['edit'] > 0) && (isset($_GET['asuransi_id']) && $_GET['asuransi_id'] > 0)) {
    $Hitung = "hitungTotalEdit()";
}else{
    $Hitung = "hitungTotal()";
}
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', $this->title); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<?php $form = \yii\bootstrap\ActiveForm::begin([
    'id' => 'form-transaksi',
    'fieldConfig' => [
        'template' => '{label}<div class="col-md-8">{input} {error}</div>',
        'labelOptions' => ['class' => 'col-md-4 control-label'],
    ],
]);
echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
    table.table thead tr th {
        font-size: 1.3rem;
        padding: 2px;
        border: 1px solid #A0A5A9;
    }
    .table-striped.table-bordered.table-hover.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover2.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover3.table-bordered>thead>tr>th,
    .table-striped.table-bordered.table-hover4.table-bordered>thead>tr>th {
        line-height: 1;
    }
    .add-more:hover {
        background: #58ACFA;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row" style="margin-top: -10px; margin-bottom: 10px;">
                    <span class="pull-right">
                        <a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Daftar Pengajuan Asuransi'); ?></a>
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold">
                                        <h4> Pengajuan Asuransi </h4>
                                    </span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?php
                                        if (isset($_GET['asuransi_id'])) {
                                            echo \yii\bootstrap\Html::activeHiddenInput($model, 'asuransi_id', ['value' => $model->asuransi_id, 'class' => 'form-control', 'style' => 'width:100%']);
                                        }
                                        ?>
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, 'kode', ['class' => 'form-control', 'style' => 'width:100%']) ?>
                                        <?= $form->field($model, 'kepada')->textarea(['disabled' => $disabledX, 'rows' => 4, 'value' => $model->kepada, 'required' => 'required']); ?>
                                        <?= $form->field($model, 'lampiran')->textInput(['disabled' => $disabledX, 'value' => $model->lampiran, 'required' => 'required']); ?>
                                        <?= $form->field($model, 'tanggal_muat', [
                                            'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'
                                        ])->textInput(['readonly' => 'readonly', 'disabled' => $disabledX, 'value' => \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_muat)]); ?>
                                        <?= $form->field($model, 'tanggal_berangkat', [
                                            'template' => '{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'
                                        ])->textInput(['readonly' => 'readonly', 'disabled' => $disabledX, 'value' => \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berangkat)]); ?>
                                        <?= $form->field($model, 'dop')->textarea(['disabled' => $disabledX, 'value' => $model->dop])->label('Deskripsi Obyek Pertanggungan'); ?>
                                    </div>
                                    <?php // KOLOM KEDUA 
                                    ?>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'rute')->textarea(['disabled' => $disabledX, 'rows' => 4, 'value' => $model->rute]); ?>
                                        <?= $form->field($model, 'nama_kapal')->textInput(['disabled' => $disabledX, 'value' => $model->nama_kapal]); ?>
                                        <!--has-success-->
                                        <div class="form-group field-tasuransi-rate">
                                            <label class="col-md-4 control-label" for="tasuransi-rate">Rate (%)</label>
                                            <div class="col-md-8">
                                                <input <?php echo $disabledY; ?> value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->rate, 2); ?>" type="text" id="tasuransi-rate" name="TAsuransi[rate]" class="form-control float" style="min-height: 30px; width:100%; padding: 2px; height:25px; font-size:1.2rem;">  <!-- onblur="hitungTotal();" -->
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group field-tasuransi-discount">
                                            <label class="col-md-4 control-label" for="tasuransi-discount">Discount (%)</label>
                                            <div class="col-md-8">
                                                <input <?php echo $disabledY; ?> value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->discount, 2); ?>" type="text" id="tasuransi-discount" name="TAsuransi[discount]" class="form-control float" style="min-height: 30px; width:100%; padding: 2px; height:25px; font-size:1.2rem;">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group field-tasuransi-freight">
                                            <label class="col-md-4 control-label" for="tasuransi-freight">Freight</label>
                                            <div class="col-md-8">
                                                <input <?php echo $disabledY; ?> value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->freight, 2); ?>" type="text" id="tasuransi-freight" name="TAsuransi[freight]" class="form-control float" style="min-height: 30px; width:100%; padding: 2px; height:25px; font-size:1.2rem;" onblur="<?php echo $Hitung; ?>">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group field-tasuransi-lumpsump">
                                            <label class="col-md-4 control-label" for="tasuransi-lumpsump">Lumpsump</label>
                                            <div class="col-md-8">
                                                <input type="checkbox" name="lumpsump" id="lumpsump" style="margin-top: 10px;" onchange="<?php echo $Hitung; ?>" <?= $model->lumpsump ? 'checked' : '' ?>>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-asuransi-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30%; text-align: left;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th style="width: 15%;"><?= Yii::t('app', 'Harga'); ?></th>                             
                                                        <th style="width: 15%;"><?= Yii::t('app', 'Kubikasi'); ?></th>
                                                        <th style="width: 20%;"><?= Yii::t('app', 'Sub Total'); ?></th>
                                                        <?php
                                                        if ((isset($_GET['edit']) && $_GET['edit'] > 0) && (isset($_GET['asuransi_id']) && $_GET['asuransi_id'] > 0)) {
                                                        } else {
                                                            echo "<th style='width: 1%' class='text-center'></th>";
                                                        }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if ((isset($_GET['edit']) && $_GET['edit'] > 0) && (isset($_GET['asuransi_id']) && $_GET['asuransi_id'] > 0)) {
                                                        // edit
                                                        $addItem = "";
                                                        $cancelItem = ""; 
                                                        $asuransi_id = $_GET['asuransi_id'];
                                                        // $sql = "select * from (select distinct on (jenis) * from t_asuransi_detail where asuransi_id = " . $asuransi_id . " order by jenis, asuransi_detail_id asc) isiasuransi  order by asuransi_detail_id asc";
                                                        $sql = "select * from t_asuransi_detail where asuransi_id = " . $asuransi_id . "  order by asuransi_detail_id asc";
                                                        $j = Yii::$app->db->createCommand($sql)->queryAll();
                                                        $i = 0;
                                                        foreach ($j as $u) {
                                                            // $x = \app\models\TAsuransiDetail::findOne(['asuransi_id' => $_GET['asuransi_id'], 'tipe' => $u['tipe']]);
                                                            $x = \app\models\TAsuransiDetail::findOne(['asuransi_detail_id' => $u['asuransi_detail_id']]);
                                                            
                                                            $harga = $x->harga;
                                                            $kubikasi = $x->kubikasi;
                                                            $total = $x->total;
                                                            $jenisA = explode(", ", $u['jenis']);
                                                            if (count($jenisA) > 1) {
                                                                $xjenisA = "";
                                                                foreach ($jenisA as $a => $su) {
                                                                    $xjenisA .= "<br>x = " . $su;
                                                                }
                                                            }
                                                            if($u['jenis'] == $x['jenis'] ){
                                                                if($x['tipe'] == 'DR' || $x['tipe'] == 'PSDH'){
                                                                    $InputJenis = "display:none;"; 
                                                                    $InputTipe = "";                                                                    
                                                                }else{                                                              
                                                                    $InputJenis = "";  
                                                                    $InputTipe = "display:none;"; 
                                                                }
                                                                // echo"<pre>";print_r($x['tipe']." Jenis : ".$InputJenis." Tipe : ".$InputTipe);echo"</pre>";
                                                                   
                                                    ?>
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" id="no_urut" name="no_urut" value="<?php echo $i; ?>" <?php echo $disabledY; ?>>
                                                                    <div style="<?php echo $InputJenis;?>">
                                                                    <select id="tasuransidetail-3-jenis" class="form-control select2 select2-hidden-accessible" name="TAsuransiDetail[<?php echo $i; ?>][jenis][]" value="Bintangur, Bipa" multiple="" size="4" style="width:100%;" tabindex="-1" aria-hidden="true" <?php echo $disabledY; ?>>
                                                                    
                                                                    <?php
                                                                        
                                                                        $options = \app\models\MKayu::find()->where(['active' => true])->orderBy(['kayu_nama' => 'asc'])->all();
                                                                        foreach ($options as $abc => $cba) {
                                                                            $jenisA = explode(", ", $u['jenis']);
                                                                            if (count($jenisA) > 1) {
                                                                                $xjenisA2 = "";
                                                                                foreach ($jenisA as $a => $su) {
                                                                                    $xjenisA2 .= $su . " ";
                                                                                }
                                                                                if (strpos($xjenisA2, $cba->kayu_nama) !== false) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = "";
                                                                                }
                                                                            } else {
                                                                                if ($u['jenis'] == $cba->kayu_nama) {
                                                                                    $selected = "selected";
                                                                                } else {
                                                                                    $selected = "";
                                                                                }
                                                                            }
                                                                        ?>
                                                                            <option value="<?php echo $cba->kayu_nama; ?>" <?php echo $selected; ?>><?php echo $cba->kayu_nama; ?> </option>
                                                                        <?php
                                                                         }
                                                                        
                                                                        ?>
                                                                    </select>
                                                                    </div>     
                                                                    <input value="<?php echo $x['tipe']; ?>" type="hidden" id="tasuransidetail-<?php echo $i; ?>-tipe" class="form-control text-right" name="TAsuransiDetail[<?php echo $i; ?>][tipe]" style="width:20%; padding: 2px;" readonly <?php echo $disabledY; ?>>
                                                                    <div style="text-align:right;<?= $InputTipe;?>"> <?= $x['tipe'];?></div>
                                                                </td>
                                                                <td>
                                                                    <div><input value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($harga, 2); ?>" type="text" id="tasuransidetail-<?php echo $i; ?>-harga" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][harga]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();" <?php echo $disabledY; ?>></div>
                                                                </td>
                                                                <td>
                                                                    <input value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($kubikasi, 2); ?>" type="text" id="TAsuransiDetail_<?php echo $i; ?>_kubikasi" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][kubikasi]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();" <?php echo $disabledY; ?>>
                                                                </td>
                                                                <td>
                                                                    <div><input type="text" id="TAsuransiDetail_<?php echo $i; ?>_total" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][total]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();" readonly></div>
                                                               </td>
                                                                <?= $cancelItem ?>
                                                            </tr>
                                                        <?php
                                                            $i++;
                                                            }
                                                        }
                                                        $j = $i - 1;
                                                        for ($k = $i; $k <= 100; $k++) {
                                                        ?>
                                                            <tr class="xxx" id="xxx-<?php echo $k; ?>">
                                                                <td>
                                                                    <input type="hidden" id="no_urut" name="no_urut" value="0">
                                                                    <?php echo yii\helpers\Html::activeDropDownList($x, '[' . $k . ']jenis', app\models\MKayu::getOptionListN(), ['value' => '', 'class' => 'form-control select2', 'multiple' => 'multiple', 'style' => 'width:100%;']); ?>
                                                                </td>
                                                                <td><input type="text" id="tasuransidetail-<?php echo $k; ?>-harga" class="form-control float text-right" name="TAsuransiDetail[<?php echo $k; ?>][harga]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();"></td>
                                                                <td><input type="text" id="TAsuransiDetail_<?php echo $k; ?>_kubikasi" class="form-control float text-right" name="TAsuransiDetail[<?php echo $k; ?>][kubikasi]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();"></td>
                                                                <td><input type="text" id="TAsuransiDetail_<?php echo $k; ?>_total" class="form-control float text-right" name="TAsuransiDetail[<?php echo $k; ?>][total]" style="width:100%; padding: 2px;" onblur="hitungTotalEdit();" readonly></td>
                                                                <td><a class="btn btn-xs red" onclick="cancelItemThis(this);"><i class="fa fa-remove"></i></a></td>
                                                            </tr>
                                                        <?php
                                                        }
                                                    } else {
                                                        // create new     
                                                        $addItem = "<tr>
                                                                        <td colspan='5'><br>
                                                                            <a class='btn btn-xs blue' id='btn-add-kuantitas' onclick='tambahBaris()'><i class='fa fa-plus'></i> Tambah Jenis Kayu</a>
                                                                        </td>
                                                                    </tr>";
                                                        $cancelItem = "<td style='vertical-align:middle;'><a class='btn btn-xs red' onclick='cancelItemThis(this);'><i class='fa fa-remove'></i></a></td>";
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" id="no_urut" name="no_urut" value="0">
                                                                <?php echo yii\helpers\Html::activeDropDownList($modAsuransiDetail, '[0]jenis', app\models\MKayu::getOptionListN(), ['class' => 'form-control select2', 'multiple' => 'multiple', 'style' => 'width:100%;']); ?>
                                                                <div style="text-align:right;">DR</div><br>
                                                                <div style="text-align:right;">PSDH</div>
                                                            </td>
                                                            <td>
                                                                <div><input type="text" id="tasuransidetail-ii-harga" class="form-control float text-right" name="TAsuransiDetail[0][harga]" style="width:100%; padding: 2px;" onblur="hitungTotal();"></div>
                                                                <div><input type="text" id="tasuransidetail-ii-harga" class="form-control float text-right" name="TAsuransiDetail[0][harga_dr]" style="width:100%; padding: 2px;" onblur="hitungTotal();"></div>
                                                                <div><input type="text" id="tasuransidetail-ii-harga" class="form-control float text-right" name="TAsuransiDetail[0][harga_psdh]" style="width:100%; padding: 2px;" onblur="hitungTotal();"></div>
                                                            <td>
                                                                <input type="text" id="TAsuransiDetail_0_kubikasi" class="form-control float text-right" name="TAsuransiDetail[0][kubikasi]" style="width:100%; padding: 2px;" onblur="hitungTotal();">
                                                            </td>
                                                            <td>
                                                                <div><input type="text" id="TAsuransiDetail_0_total" class="form-control float text-right" name="TAsuransiDetail[0][total]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                                <div><input type="text" id="TAsuransiDetail_0_total" class="form-control float text-right" name="TAsuransiDetail[0][total_dr]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                                <div><input type="text" id="TAsuransiDetail_0_total" class="form-control float text-right" name="TAsuransiDetail[0][total_psdh]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                            </td>
                                                            <?= $cancelItem ?>
                                                        </tr>
                                                        <?php
                                                        $i = 1;
                                                        for ($i = 1; $i <= 100; $i++) {
                                                        ?>
                                                            <tr class="xxx" id="xxx-<?php echo $i; ?>">
                                                                <td>
                                                                    <input type="hidden" id="no_urut" name="no_urut" value="<?php echo $i; ?>">
                                                                    <?php echo yii\helpers\Html::activeDropDownList($modAsuransiDetail, '[' . $i . ']jenis', app\models\MKayu::getOptionListN(), ['class' => 'form-control select2', 'multiple' => 'multiple', 'style' => 'width:100%;']); ?>
                                                                    <div style="text-align:right;">DR</div><br>
                                                                    <div style="text-align:right;">PSDH</div>
                                                                </td>
                                                                <td>
                                                                    <div><input type="text" id="tasuransidetail-<?php echo $i; ?>_harga" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][harga]" style="width:100%; padding: 2px;" onblur=" hitungTotal();"></div>
                                                                    <div><input type="text" id="tasuransidetail-<?php echo $i; ?>_harga" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][harga_dr]" style="width:100%; padding: 2px;" onblur=" hitungTotal();"></div>
                                                                    <div><input type="text" id="tasuransidetail-<?php echo $i; ?>_harga" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][harga_psdh]" style="width:100%; padding: 2px;" onblur=" hitungTotal();"></div>
                                                                </td>
                                                                <td>
                                                                    <input type="text" id="TAsuransiDetail_<?php echo $i; ?>_kubikasi" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][kubikasi]" style="width:100%; padding: 2px;" onblur="hitungTotal();">
                                                                </td>
                                                                <td>
                                                                    <div><input type="text" id="TAsuransiDetail_<?php echo $i; ?>_total" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][total]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                                    <div><input type="text" id="TAsuransiDetail_<?php echo $i; ?>_total" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][total_dr]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                                    <div><input type="text" id="TAsuransiDetail_<?php echo $i; ?>_total" class="form-control float text-right" name="TAsuransiDetail[<?php echo $i; ?>][total_psdh]" style="width:100%; padding: 2px;" onblur="hitungTotal();" readonly></div>
                                                                </td>
                                                                <?= $cancelItem ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <?= $addItem ?>
                                                    <tr class="tr_hidex">
                                                        <td colspan="1" class="text-right">Jumlah</td>
                                                        <td></td>
                                                        <td><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-kubikasiX" class="form-control float" style="width:100%; padding: 2px; font-size:1.2rem;"></td>
                                                        <td colspan="2"><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-totalX" name="TAsuransi[total]" class="form-control float" style="width:100%; padding: 2px; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->total * 1), 2) ?>"></td>
                                                    </tr>
                                                    <tr class="tr_hidex">
                                                        <td colspan="1" class="text-right">Freight x Kubikasi</td>
                                                        <td><input <?php echo $disabledY; ?> type="text" id="tasuransi-freightY" class="form-control float" style="width:100%; padding: 2px; font-size:1.2rem;" disabled="disabled" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser(($model->freight * 1), 2); ?>"></td>
                                                        <td><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-kubikasiY" class="form-control float" style="width:100%; padding: 2px; font-size:1.2rem;"></td>
                                                        <td colspan="2"><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-freight-kubikasi" name="TAsuransi[freight_kubikasi]" class="form-control float" style="width:100%; padding: 2px; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->freight_kubikasi * 1), 2); ?>"></td>
                                                    </tr>
                                                    <tr class="tr_hidex">
                                                        <td colspan="1" class="text-right">Jumlah Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="2"><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-jumlah" name="TAsuransi[jumlah]" class="form-control text-right float" style="width:100%; padding: 2px;; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->jumlah * 1), 2) ?>"></td>
                                                    </tr>
                                                    <tr class="tr_hidex">
                                                        <td colspan="1" class="text-right">PPN </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="2"><input  type="text" id="tasuransi-ppn" onblur="editPpn()" name="TAsuransi[ppn]" class="form-control text-right float" style="width:100%; padding: 2px; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->ppn * 1), 2) ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="1" class="text-right">Grand Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="2"><input <?php echo $disabledY; ?> readonly='readonly' type="text" id="tasuransi-grandtotal" name="TAsuransi[grandtotal]" class="form-control text-right float" style="width:100%; padding: 2px; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->grandtotal * 1), 2) ?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="1" class="text-right">Grand Total Dibulatkan </td>
                                                        <td></td>
                                                        <td></td>
                                                        <td colspan="2"><input <?php echo $disabledY; ?> type="text" id="tasuransi-pembulatan" name="TAsuransi[pembulatan]" class="form-control text-right float" style="width:100%; padding: 2px; font-size:1.2rem;" value="<?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->pembulatan * 1), 2) ?>"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row" style="margin-top: 50px;">
                                            <table style="width: 100%;">
                                                <tr>
                                                    <?php
                                                    $modApprover = \app\models\TApproval::findAll(['reff_no' => $model->kode]);
                                                    for ($i = 1; $i <= count($modApprover); $i++) {
                                                        $jumlah_kolom = 12 / count($modApprover);
                                                    ?>
                                                        <td style="width: 50%; text-align: center; vertical-align: top;">
                                                            <?php
                                                            $modApproval = \app\models\TApproval::findOne(['reff_no' => $model->kode, 'level' => $i]);
                                                            $modPegawai = \app\models\MPegawai::findOne(['pegawai_id' => $modApproval->assigned_to]);
                                                            if ($modApproval->status == "APPROVED") {
                                                                $color = "darkgreen";
                                                            } else if ($modApproval->status == "REJECTED") {
                                                                $color = "red";
                                                            } else {
                                                                $color = "grey";
                                                            }
                                                            echo "<p>" . $modPegawai->pegawai_nama;
                                                            echo "<br><span style='color: " . $color . "'>" . $modApproval->status . "";
                                                            if (!empty($model->approve_reason)) {
                                                                $modApproveReason = \yii\helpers\Json::decode($model->approve_reason);
                                                                foreach ($modApproveReason as $iap => $aprreas) {
                                                                    if ($aprreas['by'] == $modPegawai->pegawai_id) {
                                                                        echo "<br><span class='font-green-seagreen'>( " . $aprreas['reason'] . " )</span>";
                                                                        echo "<br><span class='font-green-seagreen'>( " . \app\components\DeltaFormatter::formatDateTimeForUser($aprreas['at']) . " )</span>";
                                                                    }
                                                                }
                                                            }
                                                            if (!empty($model->reject_reason)) {
                                                                $modRejectReason = \yii\helpers\Json::decode($model->reject_reason);
                                                                foreach ($modRejectReason as $irj => $rjcreas) {
                                                                    if ($rjcreas['by'] == $modPegawai->pegawai_id) {
                                                                        echo "<br><span class='font-red-flamingo'>( " . $rjcreas['reason'] . " )</span>";
                                                                        echo "<br><span class='font-red-flamingo'>( " . \app\components\DeltaFormatter::formatDateTimeForUser($rjcreas['at']) . " )</span>";
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            </p>
                                                        </td>
                                                    <?php
                                                    }
                                                    ?>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php
                                if (($model->status_approval == "Not Confirmed") && (isset($_GET['edit']) && $_GET['edit'] == 1)) {
                                ?>
                                    <?php //echo \yii\helpers\Html::button( Yii::t('app', 'Batal'),['id'=>'btn-danger','class'=>'btn btn-danger btn-outline ciptana-spin-btn','onclick'=>'batal('.$_GET["asuransi_id"].');']); 
                                    ?>
                                <?php
                                }
                                ?>
                                <?php echo \yii\helpers\Html::button(Yii::t('app', 'Reset'), ['id' => 'btn-reset', 'class' => 'btn grey-gallery btn-outline ciptana-spin-btn', 'onclick' => 'resetForm();']); ?>
                                <?php
                                if ($model->status_approval == "APPROVED") {
                                    echo \yii\helpers\Html::button(Yii::t('app', 'Print'), ['id' => 'btn-print', 'class' => 'btn blue btn-outline ciptana-spin-btn', 'onclick' => "printout('PRINT')"]);
                                }
                                ?>
                                <?php
                                if ($disabled != 1) {
                                ?>
                                    <?php echo \yii\helpers\Html::button(Yii::t('app', 'Save'), ['id' => 'btn-save', 'class' => 'btn hijau btn-outline ciptana-spin-btn', 'onclick' => 'save();']); ?>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php \yii\bootstrap\ActiveForm::end(); ?>
<?php
$pagemode = "";
if (isset($_GET['asuransi_id']) && !isset($_GET['edit'])) {
    $pagemode = "afterSave(" . $_GET['asuransi_id'] . ");";
} else if (isset($_GET['asuransi_id']) && isset($_GET['edit'])) {
    $pagemode = "afterSave(" . $_GET['asuransi_id'] . "," . $_GET['edit'] . ");";
} else {
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
    formconfig();
    hitungTotal();
    hitungTotalEdit();
    
    trXxx();
    trHide();
    buttonHide();
    // $('.float').keyup(function(){
    //     var val = this.value;
    //     val = val.replace(/[^0-9\.]/g,'');
        
    //     if(val != '') {
    //         valArr = val.split('.');
    //         valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
    //         val = valArr.join('.');
    //     }
        
    //     this.value = val;
    // });
    setMenuActive('" . json_encode(app\models\MMenu::getMenuByCurrentURL('Pengajuan Asuransi')) . "');
", yii\web\View::POS_READY); ?>
<script>
    function trXxx() {
        $('.xxx').hide();
    }
    function trHide() {
        $('.tr_hide').hide();
    }
    function buttonHide() {
        <?php
        if (isset($_SESSION['error']) && $_SESSION['error'] == "Data sudah diapprove/direject") {
        ?>
            $('#btn-add-kuantitas').hide();
            $('.btn.btn-xs.red').hide();
            $('#btn-danger').hide();
            $('#btn-save').hide();
        <?php
        }
        ?>
    }
    function batal(asuransi_id) {
        var c = confirm("Anda yakin akan membatalkan transaksi ini ?");
        if (c == true) {
            window.location.href = "/cis/web/purchasinglog/asuransi/batal?asuransi_id=" + asuransi_id + "&batal=1";
        }
    }

    function hitungTotal() {
        var hargaX = 0;
        var kubikasiX = 0;
        var totalX = 0;
        var jmlkubikasi = 0;
        // var dr = unformatNumber($("#dr").val());
        // var psdh = unformatNumber($("#psdh").val());
        var drX = 0;
        var psdhX = 0;

        $('#table-asuransi-detail').find('tr').each(function() {
            var harga = unformatNumber($(this).find("input[name*='[harga]']").val());            
            var harga_dr = unformatNumber($(this).find("input[name*='[harga_dr]']").val());
            var harga_psdh = unformatNumber($(this).find("input[name*='[harga_psdh]']").val());
            var kubikasi = unformatNumber($(this).find("input[name*='[kubikasi]']").val());
            
            var total = harga * kubikasi;
            var totaldr = harga_dr * kubikasi;
            var totalpsdh = harga_psdh * kubikasi;
            // console.log("harga "+harga+" harga dr "+harga_dr+" harga psdh "+harga_psdh);
            // console.log("total "+total+" totaldr "+totaldr+" totalpsdh "+totalpsdh);

            $(this).find("input[name*='[total]']").val(total.toLocaleString());
            $(this).find("input[name*='[total_dr]']").val(totaldr.toLocaleString());
            $(this).find("input[name*='[total_psdh]']").val(totalpsdh.toLocaleString());
            
            if($('#lumpsump').is(':checked')) {
                kubikasiX = 1;
            }else{
                kubikasiX += kubikasi;
            }
            jmlkubikasi += kubikasi;
            totalX += harga * kubikasi;
            // drX += dr * kubikasi;
            // psdhX += psdh * kubikasi;
            drX += harga_dr * kubikasi;
            psdhX += harga_psdh * kubikasi;
            $('#tasuransi-totalX').val((totalX + drX + psdhX).toLocaleString());
        });

        $('#tasuransi-kubikasiX').val((jmlkubikasi * 1).toLocaleString());

        var freight = unformatNumber($('#tasuransi-freight').val());
        $('#tasuransi-freightY').val(freight.toLocaleString());
        if($('#lumpsump').is(':checked')) {
            $('#tasuransi-kubikasiY').val((0).toLocaleString());
        }else {
            $('#tasuransi-kubikasiY').val((kubikasiX * 1).toLocaleString());
        }

        var freightxkubikasiX = freight * (kubikasiX * 1);
        $('#tasuransi-freight-kubikasi').val((freightxkubikasiX).toLocaleString());

        var jumlah = (totalX + drX + psdhX) + freightxkubikasiX;
        var ppn = jumlah * <?= \app\components\Params::DEFAULT_PPN?>;
        var grandtotal = jumlah + ppn;
        $('#tasuransi-jumlah').val((jumlah).toLocaleString());
        $('#tasuransi-ppn').val(ppn.toLocaleString());
        $('#tasuransi-grandtotal').val((grandtotal).toLocaleString());
        $('#tasuransi-pembulatan').val((grandtotal).toLocaleString());
        console.log('bkn '+grandtotal);
    }

    function hitungTotalEdit() {
        var hargaX = 0;
        var kubikasiX = 0;
        var totalX = 0;
        var jmlkubikasi = 0;
        var drX = 0;
        var psdhX = 0;

        $('#table-asuransi-detail').find('tr').each(function() {
            var harga = unformatNumber($(this).find("input[name*='[harga]']").val());            
            var kubikasi = unformatNumber($(this).find("input[name*='[kubikasi]']").val());
            var tipe = $(this).find("input[name*='[tipe]']").val();

            var total = harga * kubikasi;
            // console.log("harga "+harga+" harga dr "+harga_dr+" harga psdh "+harga_psdh);
            // console.log("total "+total+" totaldr "+totaldr+" totalpsdh "+totalpsdh);

            $(this).find("input[name*='[total]']").val(total.toLocaleString());
            // if(tipe != 'DR' && tipe != 'PSDH'){console.log(tipe);}else{console.log("tes");}
            // console.log(tipe);
            if($('#lumpsump').is(':checked')) {
                kubikasiX = 1;
            }else{
                kubikasiX += (tipe != 'DR' && tipe != 'PSDH') ? kubikasi : 0;
            }
            jmlkubikasi += (tipe != 'DR' && tipe != 'PSDH') ? kubikasi : 0;
            totalX += harga * kubikasi;

            $('#tasuransi-totalX').val((totalX).toLocaleString()); 
        });

        $('#tasuransi-kubikasiX').val((jmlkubikasi * 1).toLocaleString());

        var freight = unformatNumber($('#tasuransi-freight').val());
        $('#tasuransi-freightY').val(freight.toLocaleString());
        if($('#lumpsump').is(':checked')) {
            $('#tasuransi-kubikasiY').val((0).toLocaleString());
        }else {
            $('#tasuransi-kubikasiY').val((kubikasiX * 1).toLocaleString());
        }

        var freightxkubikasiX = freight * (kubikasiX * 1);
        // console.log(" kubikasiX : "+kubikasiX+" jmlkubikasi : "+jmlkubikasi);
        $('#tasuransi-freight-kubikasi').val((freightxkubikasiX).toLocaleString());

        var jumlah = (totalX) + freightxkubikasiX; // + drX + psdhX
        var ppn = jumlah * <?= \app\components\Params::DEFAULT_PPN?>;
        var grandtotal = jumlah + ppn;
        $('#tasuransi-jumlah').val((jumlah).toLocaleString());
        $('#tasuransi-ppn').val(ppn.toLocaleString());
        $('#tasuransi-grandtotal').val((grandtotal).toLocaleString());
        $('#tasuransi-pembulatan').val((grandtotal).toLocaleString());
        console.log('edit ' + ((grandtotal)).toLocaleString());
    }

    /**function hitungTotal() {
        var hargaX = 0;
        var kubikasiX = 0;
        var totalX = 0;
        var jmlkubikasi = 0;
        // var dr = unformatNumber($("#dr").val());
        // var psdh = unformatNumber($("#psdh").val());
        var drX = 0;
        var psdhX = 0;
        $('#table-asuransi-detail').find('tr').each(function() {
            var harga = unformatNumber($(this).find("input[name*='[harga]']").val());            
            var harga_dr = unformatNumber($(this).find("input[name*='[harga_dr]']").val());
            var harga_psdh = unformatNumber($(this).find("input[name*='[harga_psdh]']").val());
            var kubikasi = unformatNumber($(this).find("input[name*='[kubikasi]']").val());
            
            var total = harga * kubikasi;
            var totaldr = harga_dr * kubikasi;
            var totalpsdh = harga_psdh * kubikasi;
            // console.log("harga "+harga+" harga dr "+harga_dr+" harga psdh "+harga_psdh);
            // console.log("total "+total+" totaldr "+totaldr+" totalpsdh "+totalpsdh);
            $(this).find("input[name*='[total]']").val(formatNumberForUser(formatNumberFixed2(total)));
            $(this).find("input[name*='[total_dr]']").val(formatNumberForUser(formatNumberFixed2(totaldr)));
            $(this).find("input[name*='[total_psdh]']").val(formatNumberForUser(formatNumberFixed2(totalpsdh)));
            
            if($('#lumpsump').is(':checked')) {
                kubikasiX = 1;
            }else{
                kubikasiX += kubikasi;
            }
            jmlkubikasi += kubikasi;
            totalX += harga * kubikasi;
            // drX += dr * kubikasi;
            // psdhX += psdh * kubikasi;
            drX += harga_dr * kubikasi;
            psdhX += harga_psdh * kubikasi;
            $('#tasuransi-totalX').val(formatNumberForUser(totalX + drX + psdhX));
        });
        $('#tasuransi-kubikasiX').val(formatNumberForUser(jmlkubikasi * 1));
        var freight = unformatNumber($('#tasuransi-freight').val());

        // console.log(formatNumberForUser(formatNumberFixed2(freight)));
        $('#tasuransi-freightY').val(formatNumberForUser(formatNumberFixed2(freight)));
        if($('#lumpsump').is(':checked')) {
            $('#tasuransi-kubikasiY').val(formatNumberForUser(0));
        }else {
            $('#tasuransi-kubikasiY').val(formatNumberForUser(kubikasiX * 1));
        }
        var freightxkubikasiX = freight * (kubikasiX * 1);
        $('#tasuransi-freight-kubikasi').val(formatNumberForUser(formatNumberFixed2(freightxkubikasiX)));
        var jumlah = (totalX + drX + psdhX) + freightxkubikasiX;
        var ppn = jumlah * <?= \app\components\Params::DEFAULT_PPN?>;
        var grandtotal = jumlah + ppn;
        $('#tasuransi-jumlah').val(formatNumberForUser(formatNumberFixed2(jumlah)));
        $('#tasuransi-ppn').val(formatNumberForUser(formatNumberFixed2(ppn)));
        $('#tasuransi-grandtotal').val(formatNumberForUser(formatNumberFixed2(grandtotal)));
        $('#tasuransi-pembulatan').val(formatNumberForUser(formatNumberFixed2(grandtotal)));
        console.log('bkn '+grandtotal);
    }
    function hitungTotalEdit() {
        var hargaX = 0;
        var kubikasiX = 0;
        var totalX = 0;
        var jmlkubikasi = 0;
        var drX = 0;
        var psdhX = 0;
        $('#table-asuransi-detail').find('tr').each(function() {
            var harga = unformatNumber($(this).find("input[name*='[harga]']").val());            
            var kubikasi = unformatNumber($(this).find("input[name*='[kubikasi]']").val());
            var tipe = $(this).find("input[name*='[tipe]']").val();
            var total = harga * kubikasi;
            // console.log("harga "+harga+" harga dr "+harga_dr+" harga psdh "+harga_psdh);
            // console.log("total "+total+" totaldr "+totaldr+" totalpsdh "+totalpsdh);
            $(this).find("input[name*='[total]']").val(total.toLocaleString());//formatNumberForUser(formatNumberFixed2(total)));
            // if(tipe != 'DR' && tipe != 'PSDH'){console.log(tipe);}else{console.log("tes");}
            // console.log(tipe);
            if($('#lumpsump').is(':checked')) {
                kubikasiX = 1;
            }else{
                kubikasiX += (tipe != 'DR' && tipe != 'PSDH') ? kubikasi : 0;
            }
            jmlkubikasi += (tipe != 'DR' && tipe != 'PSDH') ? kubikasi : 0;
            totalX += harga * kubikasi;
            $('#tasuransi-totalX').val((totalX).toLocaleString()); 
        });
        $('#tasuransi-kubikasiX').val((jmlkubikasi * 1).toLocaleString());
        var freight = unformatNumber($('#tasuransi-freight').val());
        $('#tasuransi-freightY').val(freight.toLocaleString());//val(formatNumberForUser(formatNumberFixed2(freight)));
        if($('#lumpsump').is(':checked')) {
            $('#tasuransi-kubikasiY').val((0).toLocaleString());
        }else {
            $('#tasuransi-kubikasiY').val((kubikasiX * 1).toLocaleString());
        }
        var freightxkubikasiX = freight * (kubikasiX * 1);
        // console.log(" kubikasiX : "+kubikasiX+" jmlkubikasi : "+jmlkubikasi);
        $('#tasuransi-freight-kubikasi').val((freightxkubikasiX).toLocaleString());
        var jumlah = (totalX) + freightxkubikasiX; // + drX + psdhX
        var ppn = jumlah * <?= \app\components\Params::DEFAULT_PPN?>;
        var grandtotal = jumlah + ppn;
        $('#tasuransi-jumlah').val(((jumlah)).toLocaleString());
        $('#tasuransi-ppn').val(((ppn)).toLocaleString());
        $('#tasuransi-grandtotal').val(((grandtotal)).toLocaleString());
        $('#tasuransi-pembulatan').val(((grandtotal)).toLocaleString());
        console.log('edit ' + ((grandtotal)).toLocaleString());
    }*/


    function save() {
        var $form = $('#form-transaksi');
        if (formrequiredvalidate($form)) {
            var tableAsuransiDetail = $('#table-asuransi-detail').length;
            if (tableAsuransiDetail <= 0) {
                cisAlert('Isi detail terlebih dahulu');
                return false;
            } else {
                submitform($form);
            }
        }
        return false;
    }
    function validatingDetail($form) {
        //    var has_error = 0;
        //    $('#table-asuransi-detail > tbody > tr').each(function(){
        //        var field1 = $(this).find('select[name*="[jenis]"]');
        //        if(!field1.val()){
        //            $(this).find('select[name*="[jenis]"]').parents('td').addClass('error-tb-detail');
        //            has_error = has_error + 1;
        //        }else{
        //            $(this).find('select[name*="[jenis]"]').parents('td').removeClass('error-tb-detail');
        //        }
        //    });
        //    if(has_error === 0){
        //        return true;
        //    }
        //    return false;
    }
    function afterSave() {
    }
    function daftarAfterSave() {
        openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/daftarAfterSave']) ?>', 'modal-aftersave', '90%');
    }
    function printout(caraprint) {
        <?php
        if (isset($_GET['asuransi_id'])) {
        ?>
            var id = <?php echo $_GET['asuransi_id']; ?>;
            window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/asuransi/print') ?>?id=" + id + "&caraprint=" + caraprint, "", 'location=_new, width=1200px, scrollbars=yes');
        <?php
        }
        ?>
    }
    function tambahBaris() {
        var asuransi_id = $("#tasuransi-asuransi_id").val();
        var last_tr = $("#table-asuransi-detail > tbody > tr:last").find("input,select").serialize();
        var aaa = $('.xxx:hidden').length;
        var bbb = aaa - 1;
        var ccc = 100 - bbb;
        $("#xxx-" + ccc).show();
        console.log(ccc);
        /*for (i=1;i<=100;i++) {
            var bbb = aaa-i;
            console.log(bbb);
            
            if ($("#xxx-1").is(":hidden")) {
                $("#xxx-1").show();
            } else {
                console.log($('.xxx:hidden').length);
            }
        }*/
        /*$.ajax({
            url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/tambahBaris']); ?>',
            type   : 'POST',
            data   : {last_tr:last_tr, asuransi_id:asuransi_id},
            success: function (data){
                if(data.html){
                    $(data.html).hide().appendTo('#table-asuransi-detail > tbody').fadeIn(100,function(){
                        reordertable('#table-asuransi-detail');
                    });
                }
            },
            error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        });*/
    }
    function cancelItemThis(ele) {
        var no_urut = $(ele).parents("tr").find("#no_urut").val();
        $(ele).parents('tr').fadeOut(200, function() {
            $(this).remove();
            $("#table-asuransi-detail > tbody").find("input[name='no_urut'][value='" + no_urut + "']").parents("tr").remove();
            hitungTotal();
            reordertable('#table-asuransi-detail');
        });
    }
    function editPpn() {
        // let jumlah = $('#tasuransi-jumlah').val().replaceAll(',', '');
        // let ppn = $('#tasuransi-ppn').val().replaceAll(',', '');
        // let total = parseInt(jumlah) + parseInt(ppn);
        let jumlah = unformatNumber($('#tasuransi-jumlah').val());
        let ppn = unformatNumber($('#tasuransi-ppn').val());
        
        let total = jumlah + ppn;
        $('#tasuransi-grandtotal').val(formatNumberForUser(formatNumberFixed2(total)));
        console.log((total).toLocaleString());
    }
</script>