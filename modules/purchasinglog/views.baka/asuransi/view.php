<?php
/* @var $this yii\web\View */
$this->title = 'Asuransi';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
$disabledX = "disabled";
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
        'labelOptions'=>['class'=>'col-md-4 control-label'],
    ],
]); echo Yii::$app->controller->renderPartial('@views/apps/partial/_flashAlert'); ?>
<style>
table.table thead tr th{
    font-size: 1.3rem;
    padding: 2px;
    border: 1px solid #A0A5A9;
}
.table-striped.table-bordered.table-hover.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover2.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover3.table-bordered > thead > tr > th, .table-striped.table-bordered.table-hover4.table-bordered > thead > tr > th {
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
                                    <span class="caption-subject bold"><h4>Pengajuan Asuransi </h4></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= \yii\bootstrap\Html::activeHiddenInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
                                        <?= $form->field($model, 'kepada')->textarea(['disabled'=>$disabled, 'value'=>$model->kepada]); ?>
                                        <?= $form->field($model, 'lampiran')->textInput(['disabled'=>$disabled, 'value'=>$model->lampiran]); ?>
                                        <?= $form->field($model, 'tanggal_muat',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly', 'disabled'=>$disabled, 'value'=>\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_muat)]); ?>
                                        <?= $form->field($model, 'tanggal_berangkat',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly', 'disabled'=>$disabled, 'value'=>\app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal_berangkat)]); ?>
                                        <?= $form->field($model, 'dop')->textarea(['disabled'=>$disabled, 'value'=>$model->dop])->label('Deskripsi Obyek Pertanggungan'); ?>
                                    </div>

                                    <?php // KOLOM KEDUA SU ;?>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'rute')->textarea(['disabled'=>$disabled, 'value'=>$model->rute]); ?>
                                        <?= $form->field($model, 'nama_kapal')->textInput(['disabled'=>$disabled, 'value'=>$model->nama_kapal]); ?>
                                        <div class="form-group field-tasuransi-rate has-success">
                                            <label class="col-md-4 control-label" for="tasuransi-rate">Rate</label>
                                            <div class="col-md-8">
                                                <input <?php echo $disabledX;?> value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->rate,2);?>" type="text" id="tasuransi-rate" name="TAsuransi[rate]" class="form-control float" style="min-height: 30px; width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser(($model->freight * 1));?>">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group field-tasuransi-freight has-success">
                                            <label class="col-md-4 control-label" for="tasuransi-freight">Freight</label>
                                            <div class="col-md-8">
                                                <input <?php echo $disabledX;?> value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->freight);?>" type="text" id="tasuransi-freight" name="TAsuransi[freight]" class="form-control float" style="min-height: 30px; width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser(($model->freight * 1));?>">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group field-tasuransi-freight">
                                            <label class="col-md-4 control-label" for="tasuransi-freight">Lumpsump</label>
                                            <div class="col-md-8">
                                                <input type="checkbox" name="lumpsump" id="lumpsump" style="margin-top: 10px;" <?= $model->lumpsump ? 'checked' : '' ?> disabled>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Detail Rincian'); ?></h5>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-asuransi-detail">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 150px; text-align: left;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th style="width: 100px;"><?= Yii::t('app', 'Harga'); ?></th>
                                                        <th style="width: 100px;"><?= Yii::t('app', 'Kubikasi'); ?></th>
                                                        <th style="width: 100px;"><?= Yii::t('app', 'Sub Total'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                    $x              = '';
                                                    $i              = 0;
                                                    $Total          = 0;
                                                    $TotKubikasi    = 0;
                                                    $TotFreight     = 0;
                                                    $Ppn            = 0;
                                                    $jmlkubikasi    = 0;
                                                    foreach ($modAsuransiDetail as $f => $v) {                                                        
                                                        
                                                    ?>
                                                    <tr>
                                                        <td class="text-center">
                                                            <?php
                                                            if ($x != $v->jenis) {
                                                                $i = $i + 1;
                                                                echo $i;
                                                                //hitung total kubikasi
                                                                if($model->lumpsump) {
                                                                    $TotKubikasi = 1;
                                                                }else {
                                                                    $TotKubikasi += $v->kubikasi;
                                                                }
                                                                $jmlkubikasi += $v->kubikasi;
                                                                $PaddingRight ='';
                                                            }else{
                                                                $PaddingRight ="style='color:red;padding-right:70px;'"; 
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $v->tipe;?>
                                                        </td>
                                                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->harga,2);?></td>
                                                        <td class="text-right" <?= $PaddingRight ?>><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->kubikasi,2);?></td>
                                                        <td class="text-right"><?php echo \app\components\DeltaFormatter::formatNumberForUser($v->total,2);?></td>
                                                    </tr>
                                                    <?php
                                                        $x = $v->jenis;
                                                        $Total += $v->total ;
                                                    }
                                                    
                                                    $TotFreight = $model->freight * $TotKubikasi;
//                                                    $Ppn = ($TotFreight + $Total) * \app\components\Params::DEFAULT_PPN ;
                                                    $Ppn = $model->ppn ;

                                                    ?>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Total</td>
                                                        <td></td>
                                                        <td class='text-right'><?= \app\components\DeltaFormatter::formatNumberForUser($jmlkubikasi,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-kubikasiX" class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($TotKubikasi,2);?>"></td>
                                                        <td class='text-right'><?= \app\components\DeltaFormatter::formatNumberForAllUser($Total,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-totalX" name="TAsuransi[total]"class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($Total,2);?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Freight x Kubikasi</td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser($model->freight,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-freightY" class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" disabled="disabled" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($model->freight,2);?>"></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForUser($TotKubikasi == 1 ? 0 : $TotKubikasi,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-kubikasiY" class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($TotKubikasi,2);?>"></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser($TotFreight,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-freight-kubikasi" name="TAsuransi[freight_kubikasi]"class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($TotFreight,2);?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Jumlah</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser($Total + $TotFreight,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-jumlah" name="TAsuransi[jumlah]"class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($Total + $TotFreight,2);?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Ppn</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser($Ppn,2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-ppn" name="TAsuransi[ppn]"class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser($Ppn,2);?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Grand Total</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser(($Total + $TotFreight + $Ppn),2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-grandtotal" name="TAsuransi[grandtotal]"class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser(($Total + $TotFreight + $Ppn),2);?>"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" class="text-right">Grand Total Dibulatkan</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td class="text-right"><?= \app\components\DeltaFormatter::formatNumberForAllUser(($model->pembulatan * 1),2) ?><input <?php echo $disabledX;?> type="hidden" id="tasuransi-pembulatan" name="TAsuransi[pembulatan]" class="form-control float" style="width:100%; padding: 2px; height:25px; font-size:1.2rem;" value="<?php echo \app\components\DeltaFormatter::formatNumberForAllUser(($model->pembulatan * 1),2);?>"></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <div class="row" style="margin-top: 50px;">
                                            <table style="width: 100%;">
                                                <tr>
                                                <?php
                                                $modApprover = \app\models\TApproval::findAll(['reff_no'=>$model->kode]);
                                                for ($i=1; $i<=count($modApprover); $i++) {
                                                    $jumlah_kolom = 12/count($modApprover);
                                                    ?>
                                                    <td style="width: 50%; text-align: center; vertical-align: top;">
                                                        <?php
                                                        $modApproval = \app\models\TApproval::findOne(['reff_no'=>$model->kode, 'level'=>$i]);
                                                        $modPegawai = \app\models\MPegawai::findOne(['pegawai_id'=>$modApproval->assigned_to]);
                                                        if ($modApproval->status == "APPROVED") {
                                                            $color = "darkgreen";
                                                        } else if ($modApproval->status == "REJECTED") {
                                                            $color = "red";
                                                        } else {
                                                            $color = "grey";
                                                        }
                                                        echo "<p>".$modPegawai->pegawai_nama;
                                                        echo "<br><span style='color: ".$color."'>".$modApproval->status."";

                                                        if(!empty($model->approve_reason)){
                                                            $modApproveReason = \yii\helpers\Json::decode($model->approve_reason);
                                                            foreach($modApproveReason as $iap => $aprreas){
                                                                if($aprreas['by'] == $modPegawai->pegawai_id){
                                                                    echo "<br><span class='font-green-seagreen'>( ".$aprreas['reason']." )</span>";
                                                                    echo "<br><span class='font-green-seagreen'>( ".\app\components\DeltaFormatter::formatDateTimeForUser($aprreas['at'])." )</span>";
                                                                }
                                                            }
                                                        }

                                                        if(!empty($model->reject_reason)){
                                                            $modRejectReason = \yii\helpers\Json::decode($model->reject_reason);
                                                            foreach($modRejectReason as $irj => $rjcreas){
                                                                if($rjcreas['by'] == $modPegawai->pegawai_id){
                                                                    echo "<br><span class='font-red-flamingo'>( ".$rjcreas['reason']." )</span>";
                                                                    echo "<br><span class='font-red-flamingo'>( ".\app\components\DeltaFormatter::formatDateTimeForUser($rjcreas['at'])." )</span>";
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
                                    <?php echo \yii\helpers\Html::button( Yii::t('app', 'Batal'),['id'=>'btn-danger','class'=>'btn btn-danger btn-outline ciptana-spin-btn','onclick'=>'batal('.$_GET["asuransi_id"].');']); ?>
                                    <?php
                                }
                                ?>

                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'setFormInput();']); ?>

                                <?php 
                                if ($model->status_approval == "APPROVED") {
                                    echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); 
                                }
                                ?>

                                <?php
                                if ($disabled != 1) { 
                                    ?>
                                    <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
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
if(isset($_GET['asuransi_id']) && !isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['asuransi_id'].");";
}else if( isset($_GET['asuransi_id']) && isset($_GET['edit']) ){
    $pagemode = "afterSave(".$_GET['asuransi_id'].",".$_GET['edit'].");";
}else{
    $pagemode = "";
}
?>
<?php $this->registerJs(" 
    $pagemode
    formconfig();
    $('.float').keyup(function(){
        var val = this.value;
        val = val.replace(/[^0-9\.]/g,'');
        
        if(val != '') {
            valArr = val.split('.');
            valArr[0] = (parseInt(valArr[0],10)).toLocaleString();
            val = valArr.join('.');
        }
        
        this.value = val;
    });

    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Asuransi'))."');
", yii\web\View::POS_READY); ?>

<script>

function batal(asuransi_id) {
    var c = confirm("Anda yakin akan membatalkan transaksi ini ?");
    if (c == true) {
        window.location.href = "/cis/web/purchasinglog/asuransi/batal?asuransi_id="+asuransi_id+"&batal=1";
    }
}

function afterSave () {

}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/daftarAfterSave']) ?>','modal-aftersave','90%');
}

function printout(caraprint){
	<?php
    if (isset($_GET['asuransi_id'])) {
    ?>
        var id = <?php echo $_GET['asuransi_id'];?>;
	    window.open("<?= yii\helpers\Url::toRoute('/purchasinglog/asuransi/print') ?>?id="+id+"&caraprint="+caraprint,"",'location=_new, width=1200px, scrollbars=yes');
    <?php
    }
    ?>
}
function setFormInput(){
    window.location.href = "<?= yii\helpers\Url::toRoute('/purchasinglog/asuransi/index') ?>";
}
</script>