<?php
/* @var $this yii\web\View */
$this->title = 'Hasil Orientasi';
app\assets\DatepickerAsset::register($this);
app\assets\Select2Asset::register($this);
app\assets\InputMaskAsset::register($this);
app\assets\FileUploadAsset::register($this);
$tahun1 = date('Y')-1;
$tahun2 = date('Y')-2;
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
                        <a class="btn blue btn-sm btn-outline" onclick="daftarAfterSave()"><i class="fa fa-list"></i> <?= Yii::t('app', 'Hasil Orientasi Yang Telah Dibuat'); ?></a> 
                    </span>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold"><h4> Data Hasil Orientasi </h4></span>
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
                                        if(!isset($_GET['hasil_orientasi_id'])){
                                            echo $form->field($model, 'kode')->textInput(['disabled'=>'disabled','style'=>'font-weight:bold']);
                                        }else{ ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Kode'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 5px;">
                                                    <span class="input-group-btn" style="width: 90%">
                                                        <?= \yii\bootstrap\Html::activeTextInput($model, 'kode', ['class'=>'form-control','style'=>'width:100%']) ?>
                                                    </span>
                                                    <span class="input-group-btn" style="width: 10%">
                                                        <a class="btn btn-icon-only btn-default tooltips" data-original-title="Copy to Clipboard" onclick="copyToClipboard('<?= $model->kode ?>');">
                                                            <i class="icon-paper-clip"></i>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <?= $form->field($model, 'tanggal',[
                                                                    'template'=>'{label}<div class="col-md-7"><div class="input-group input-medium date date-picker bs-datetime">{input} <span class="input-group-addon">
                                                                    <button class="btn default" type="button" style="margin-left: 0px;"><i class="fa fa-calendar"></i></button></span></div> 
                                                                    {error}</div>'])->textInput(['readonly'=>'readonly']); ?>
                                        <?= $form->field($model, 'nama_iuphhk')->textInput(); ?>
                                        <?= $form->field($model, 'nama_ipk')->textInput(); ?>
                                        <?= $form->field($model, 'lokasi_muat')->textInput(); ?>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Target RKT'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 10px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td style="width: 120px;"><?= \yii\bootstrap\Html::activeTextInput($model, 'target_rkt', ['class'=>'form-control float','placeholder'=>'m3','style'=>'width: 336px; height:25px; margin-top: 3px;']) ?></td>
                                                            <td style=" font-size: 1.1rem;">&nbsp;&nbsp;&nbsp;m3</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Target RKT 2 Tahun<br><br>Sebelumnya/Realisasi'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 10px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td style="width: 10%;"><span style="font-size: 1.1rem;">Tahun </span></td>
                                                            <td style="width: 80%; font-size: 1.1rem" class="col-md-12">
                                                                <?php /*<?= \yii\bootstrap\Html::activeTextInput($model, 'tahun_target_rkt1', ['class'=>'form-control','style'=>'width:50%; height:25px; padding:1px;', 'placeholder' => 'yyyy']) ?> */?>
                                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'tahun_target_rkt1',[$tahun1=>$tahun1], ['class'=>'form-control col-md-2','style'=>'width: 70px; height:25px; margin-top: 5px; padding:1px;']) ?>
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'target_rkt1', ['class'=>'form-control float col-md-6','style'=>'width: 100px; height:25px; padding-right:13px; margin-top: 5px; margin-left: 5px;', 'placeholder' => 'm3']) ?>&nbsp;&nbsp;&nbsp;target /
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'realisasi_rkt1', ['class'=>'form-control float col-md-6','style'=>'width: 100px; height:25px; padding-right:13px; margin-top: 5px; margin-left: 5px;', 'placeholder' => 'm3']) ?>realisasi (m3)
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 10%;"><span style="font-size: 1.1rem;">Tahun </span></td>
                                                            <td style="width: 85%; font-size: 1.1rem" class="col-md-12">
                                                                <?php /* <?= \yii\bootstrap\Html::activeTextInput($model, 'tahun_target_rkt2', ['class'=>'form-control number','style'=>'width:50%; height:25px; padding:1px;', 'placeholder' => 'yyyy']) ?> */?>
                                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'tahun_target_rkt2',[$tahun2=>$tahun2], ['class'=>'form-control col-md-2 ','style'=>'width: 70px; height:25px; margin-top: 5px; padding:1px;']) ?>
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'target_rkt2', ['class'=>'form-control float col-md-6','style'=>'width: 100px; height:25px; padding-right:13px; margin-top: 5px; margin-left: 5px;', 'placeholder' => 'm3']) ?>&nbsp;&nbsp;&nbsp;target /
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'realisasi_rkt2', ['class'=>'form-control float col-md-6','style'=>'width: 100px; height:25px; padding-right:13px; margin-top: 5px; margin-left: 5px;', 'placeholder' => 'm3']) ?>realisasi (m3)
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>

                                        <?= $form->field($model, 'kondisi_logpond')->radioList(["Pasang Surut"=>"Pasang Surut","Tadah Hujan"=>"Tadah Hujan","Laut lepas"=>"Laut lepas","Non-Logpond"=>"Non-Logpond"],['onchange'=>'setKondisiLogpond()']); ?>
                                        
                                        <div id="place-datalogpond" style="display: none;">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Sistem Pemuatan'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 10px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td>
                                                                <div class="mt-checkbox-list" style="height: 30px;">
                                                                    <label class="mt-checkbox mt-checkbox-outline" style="margin-left: -15px;">
                                                                        <input name="THasilOrientasi[sp_langsung]" value="0" type="hidden">
                                                                        <input id="thasilorientasi-sp_langsung" name="THasilOrientasi[sp_langsung]" <?= ($model->sp_langsung=="1")?"checked":""; ?> type="checkbox" onchange="setSpLangsung();"> 
                                                                        <span class="help-block" style="border: 1px solid #888;"></span>
                                                                        <div style="padding-top: 3px; margin-left: 10px;">Langsung</div>
                                                                    </label> 
                                                                </div>
                                                            </td>
                                                            <td colspan="2">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'sp_langsung_feet', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;','placeholder'=>'Uk. Tongkang Maks (Feet)','disabled'=>true]) ?>
                                                            </td>
                                                            <td >
                                                                <span style="font-size: 1.2rem;">&nbsp; Feet</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width:35%;">
                                                                <div class="mt-checkbox-list" style="height: 30px;">
                                                                    <label class="mt-checkbox mt-checkbox-outline" style="margin-left: -15px;">
                                                                        <input name="THasilOrientasi[sp_estafet]" value="0" type="hidden">
                                                                        <input id="thasilorientasi-sp_estafet" name="THasilOrientasi[sp_estafet]" <?= ($model->sp_estafet=="1")?"checked":""; ?> type="checkbox" onchange="setSpEstafet();"> 
                                                                        <span class="help-block" style="border: 1px solid #888;"></span>
                                                                        <div style="padding-top: 3px; margin-left: 10px;">Estafet</div>
                                                                    </label> 
                                                                </div>
                                                            </td>
                                                            <td style="width:100px;">
                                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'sp_estafet_kendaraan',["Rakit"=>"Rakit","Tongkang"=>"Tongkang","Lainnya"=>"Lainnya"], ['class'=>'form-control','style'=>'width:100%; height:25px; padding:1px;','prompt'=>'','placeholder'=>'Uk. Tongkang Maks (Feet)','disabled'=>true]) ?>
                                                            </td>
                                                            <td style="width:70px;">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'sp_estafet_feet', ['class'=>'form-control float','style'=>'height:25px; padding:1px;','placeholder'=>'Ukuran (Feet)','disabled'=>true]) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.2rem;">&nbsp; Feet</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.2rem;">Tongkang Induk : </span>
                                                            </td>
                                                            <td>
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'sp_estafet_induk_feet', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;','placeholder'=>'Ukuran (Feet)','disabled'=>true]) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.2rem;">&nbsp; Feet</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Lama Pemuatan'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 10px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td style="width: 35%;">
                                                                <div class="mt-checkbox-list" style="height: 30px;">
                                                                    <label class="mt-checkbox mt-checkbox-outline" style="margin-left: -15px;">
                                                                        <input name="THasilOrientasi[lp_langsung]" value="0" type="hidden">
                                                                        <input id="thasilorientasi-lp_langsung" name="THasilOrientasi[lp_langsung]" <?= ($model->lp_langsung=="1")?"checked":""; ?> type="checkbox" onchange="setLpLangsung();"> 
                                                                        <span class="help-block" style="border: 1px solid #888;"></span>
                                                                        <div style="padding-top: 3px; margin-left: 10px;">Langsung</div>
                                                                    </label> 
                                                                </div>
                                                            </td>
                                                            <td style="width: 55%;" colspan="3">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'lp_langsung_hari', ['class'=>'form-control float','placeholder'=>'Lama Pemuatan (Hari)','style'=>'width:100%; height:25px;  padding:1px;','disabled'=>true]) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.2rem;">&nbsp; Hari</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 35%;">
                                                                <div class="mt-checkbox-list" style="height: 30px;">
                                                                    <label class="mt-checkbox mt-checkbox-outline" style="margin-left: -15px;">
                                                                        <input name="THasilOrientasi[lp_estafet]" value="0" type="hidden">
                                                                        <input id="thasilorientasi-lp_estafet" name="THasilOrientasi[lp_estafet]" <?= ($model->lp_estafet=="1")?"checked":""; ?> type="checkbox" onchange="setLpEstafet();"> 
                                                                        <span class="help-block" style="border: 1px solid #888;"></span>
                                                                        <div style="padding-top: 3px; margin-left: 10px;">Estafet</div>
                                                                    </label> 
                                                                </div>
                                                            </td>
                                                            <td style="width: 60px;">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'lp_estafet_m3', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;','disabled'=>true]) ?>
                                                            </td>
                                                            <td style="">
                                                                <span style="font-size: 1.2rem;">&nbsp; M<sup>3</sup></span> Per
                                                            </td>
                                                            <td style="width: 45px;">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'lp_estafet_hari', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;','disabled'=>true]) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.2rem;">&nbsp; Hari</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Jenis Alat Berat'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 5px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td style="width: 35%; text-align: right;">
                                                                <span>Traktor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                            </td>
                                                            <td style="width: 40%; ">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'jab_traktor', ['class'=>'form-control float','placeholder'=>'Jumlah (Unit)','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.1rem;">&nbsp; Unit</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style=" text-align: right;">
                                                                <span>Logging &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                            </td>
                                                            <td style=" ">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'jab_logging', ['class'=>'form-control float','placeholder'=>'Jumlah (Unit)','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.1rem;">&nbsp; Unit</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style=" text-align: right;">
                                                                <span>Loader &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                            </td>
                                                            <td style="">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'jab_loader', ['class'=>'form-control float','placeholder'=>'Jumlah (Unit)','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.1rem;">&nbsp; Unit</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style=" text-align: right;">
                                                                <span>Lainnya &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
                                                            </td>
                                                            <td style="">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'jab_lainnya', ['class'=>'form-control float','placeholder'=>'Jumlah (Unit)','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1.1rem;">&nbsp; Unit</span>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <?= $form->field($model, 'kondisi_alat_berat')->inline(true)->radioList(["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek","Tidak Ada"=>"Tidak Ada"]); ?>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label"><?= Yii::t('app', 'Lokasi Produksi'); ?></label>
                                                <div class="col-md-8" style="padding-bottom: 10px;">
                                                    <table style="width: 100%;" border="0">
                                                        <tr>
                                                            <td style="width: 25%;">
                                                                <span style="font-size: 1.1rem;">Blok ke TPN :</span>
                                                            </td>
                                                            <td style="width: 15%;">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'lpr_blok2tpn', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td><span style="font-size: 1.1rem;">&nbsp; KM. &nbsp; Kondisi Jalan :</span></td>
                                                            <td style="width: 25%;">
                                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'lpr_blok2tpn_kondisi',["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek","Tidak Ada Jalan"=>"Tidak Ada Jalan"], ['class'=>'form-control','style'=>'width:100%; height:25px; padding:1px;']) ?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="width: 25%;">
                                                                <span style="font-size: 1.1rem;">TPN ke TPK :</span>
                                                            </td>
                                                            <td style="width: 15%;">
                                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'lpr_tpn2tpk', ['class'=>'form-control float','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                            </td>
                                                            <td><span style="font-size: 1.1rem;">&nbsp; KM. &nbsp; Kondisi Jalan :</span></td>
                                                            <td style="width: 25%;">
                                                                <?= \yii\bootstrap\Html::activeDropDownList($model, 'lpr_tpn2tpk_kondisi',["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek","Tidak Ada Jalan"=>"Tidak Ada Jalan"], ['class'=>'form-control','style'=>'width:100%; height:25px; padding:1px;']) ?>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'perjanjian_scaling')->radioList(["Ukur Ulang"=>"Ukur Ulang","Ukuran Penjual"=>"Ukuran Penjual","Trimming"=>"Trimming ::"],['onchange'=>'setPerjanjianScaling();']); ?>

                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?= Yii::t('app', 'Jumlah Sampling Log'); ?></label>
                                            <div class="col-md-2" style="padding-bottom: 10px;">
                                                <?= \yii\bootstrap\Html::activeTextInput($model, 'jumlah_sampling_log', ['class'=>'form-control float','placeholder'=>'pcs','style'=>'height:25px; margin-top: 3px;']) ?>
                                            </div>
                                        </div>

                                        <?= $form->field($model, 'kualitas_kayu')->inline(true)->radioList(["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek"],[]); ?>

                                        <?php /* <div class="form-group">
                                            <label class="col-md-4 control-label"><?php // echo Yii::t('app', 'Rendemen Produksi'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 10px;">
                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <span style="font-size: 1.1rem;">Sawnmill :</span>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rp_sawnmill', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <span style="font-size: 1.1rem;">Plymill :</span>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rp_plymill', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <span style="font-size: 1.1rem;">&nbsp;&nbsp;&nbsp; - Face :</span>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rp_face', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <span style="font-size: 1.1rem;">&nbsp;&nbsp;&nbsp; - Back :</span>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rp_back', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 25%;">
                                                            <span style="font-size: 1.1rem;">&nbsp;&nbsp;&nbsp; - Core :</span>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rp_core', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label"><?php // echo Yii::t('app', 'Rendemen B/L'); ?></label>
                                            <div class="col-md-8" style="padding-bottom: 10px;">
                                                <table style="width: 100%;" border="0">
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <?php // echo \yii\bootstrap\Html::activeTextInput($model, 'rendemen_bl', ['class'=>'form-control text-align-right','style'=>'width:100%; height:25px;  padding:1px;']) ?>
                                                        </td>
                                                        <td><span>&nbsp; %</span></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div> */?>
                                        <?= $form->field($model, 'perlakuan_log_tidak_standard')->radioList(["Tinggal"=>"Tinggal","Trimming"=>"Trimming","Pisahkan"=>"Pisahkan","Lain-lain"=>"Lain-lain ___"],['onchange'=>'setPerlakuanLogTidakStandard();']); ?>

                                        <?= $form->field($model, 'kondisi_perusahaan')->inline(true)->radioList(["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek"],[]); ?>
                                        <?= $form->field($model, 'rekomendasi_grader')->inline(true)->radioList(["Beli"=>"Beli","Tidak Beli"=>"Tidak Beli"],[]); ?>
                                        <?= $form->field($model, 'selisih_ukur')->inline(true)->radioList(["Bagus"=>"Bagus","Sedang"=>"Sedang","Jelek"=>"Jelek","Tidak Ada"=>"Tidak Ada"],[]); ?>
                                        <?= $form->field($model, "alasan_pertimbangan")->textarea(); ?>
                                        <?= $form->field($model, "informasi_pembeli_sebelumnya")->textarea(); ?>
                                        <?= $form->field($model, 'by_kanit_name')->textInput(['disabled'=>true]); ?>
                                        <?= $form->field($model, 'by_gmpurch_name')->textInput(['disabled'=>true]); ?>
                                        <?php // echo $form->field($model, 'by_kadiv_name')->textInput(['disabled'=>true]); ?>
                                        <?php // echo $form->field($model, 'by_gmopr_name')->textInput(['disabled'=>true]); ?>
                                        <?= $form->field($model, 'by_dirut_name')->textInput(['disabled'=>true]); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'by_kanit'); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'by_gmpurch'); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'by_kadiv'); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'by_gmopr'); ?>
                                        <?= yii\bootstrap\Html::activeHiddenInput($model, 'by_dirut'); ?>
                                    </div>
                                </div><hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Kuantitas'); ?></h5>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kuantitas">
                                                <thead>
                                                    <?php
                                                    $ukuranganrange = \app\models\MDefaultValue::getOptionList('volume-range-log');
                                                    ?>
                                                    <tr>
                                                        <th style="width: 30px;" rowspan="3" style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 120px;" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th colspan="<?= (count($ukuranganrange)*2+2) ?>"><?= Yii::t('app', 'Diameter'); ?></th>
                                                        <th rowspan="3"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                        <th rowspan="3" style="width: 30px;"></th>
                                                    </tr>
                                                    <tr>
                                                        <?php foreach($ukuranganrange as $i => $range){ ?>
                                                        <th colspan="2"><?= $range ?></th>
                                                        <?php } ?>
                                                        <th colspan="2">Total</th>
                                                    </tr>
                                                    <tr>
                                                        <?php foreach($ukuranganrange as $i => $range){ ?>
                                                        <th style="width: 50px;">Pcs</th>
                                                        <th style="width: 70px;">M<sup>3</sup></th>
                                                        <?php } ?>
                                                        <th style="width: 60px;">Pcs</th>
                                                        <th style="width: 85px;">M<sup>3</sup></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;">Jumlah &nbsp; </td>
                                                        <?php foreach($ukuranganrange as $i => $range){ ?>
                                                        <td><?= yii\helpers\Html::textInput("THasilOrientasiKuantitas[".$range."][total_btg]",0,["class"=>'form-control float col-btg-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                        <td><?= yii\helpers\Html::textInput("THasilOrientasiKuantitas[".$range."][total_m3]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                        <?php } ?>
                                                        <td><?= yii\helpers\Html::textInput("THasilOrientasiKuantitas[total][total_btg]",0,["id"=>"total_btg","class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                        <td><?= yii\helpers\Html::textInput("THasilOrientasiKuantitas[total][total_m3]",0,["id"=>"total_m3","class"=>'form-control float',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="text-align: right;">% &nbsp; </td>
                                                        <?php foreach($ukuranganrange as $i => $range){ ?>
                                                        <td></td>
                                                        <td><?= yii\helpers\Html::textInput("THasilOrientasiKuantitas[".$range."][persen_m3]",0,["class"=>'form-control float col-m3-foot',"style"=>"width:100%; padding: 2px; height:25px; font-size:1.2rem;","disabled"=>true]) ?></td>
                                                        <?php } ?>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <a class="btn btn-xs blue" id="btn-add-kuantitas" onclick="addNewKuantitas()"><i class="fa fa-plus"></i> Add Kuantitas</a>
                                    </div>
                                </div><br><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Kualitas'); ?></h5>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-kualitas">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;" rowspan="3"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 120px;" rowspan="3"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                                        <th colspan="9"><?= Yii::t('app', 'Kuantitas'); ?></th>
                                                        <th rowspan="2" colspan="2"><?= Yii::t('app', 'Total'); ?></th>
                                                        <th rowspan="3"><?= Yii::t('app', 'Keterangan'); ?></th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 70px;" rowspan="2">Pcs</th>
                                                        <th style="width: 90px;" rowspan="2">M<sup>3</sup></th>
                                                        <th style="width: 50px;" rowspan="2">Bekas<br>Pilih</th>
                                                        <th style="width: 280px;" colspan="5" id="usia_tebang_persen">Usia Tebang (%)</th>
                                                        <th style="width: 70px;" rowspan="2">Gubal<br>cm</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 70px;">1 - 3 Bln</th>
                                                        <th style="width: 70px;">4 - 5 Bln</th>
                                                        <th style="width: 70px;">6 - 8 Bln</th>
                                                        <th style="width: 70px;">9 > 12 Bln</th>
                                                        <th style="width: 70px;">Total</th>
                                                        <th style="width: 70px;">GR (%)</th>
                                                        <th style="width: 70px;">Pecah (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--<a class="btn btn-xs blue" onclick="addNewKualitas()"><i class="fa fa-plus"></i> Add Kualitas</a>-->
                                    </div>
                                </div><br><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Grader Yang Terlibat'); ?></h5>
                                        <div class="table-scrollable">
                                            <table class="table table-striped table-bordered table-advance table-hover" id="table-detail-grader">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;"><?= Yii::t('app', 'No.'); ?></th>
                                                        <th style="width: 200px;"><?= Yii::t('app', 'Kode Dinas'); ?></th>
                                                        <th style="width: 150px;"><?= Yii::t('app', 'Tipe Dinas'); ?></th>
                                                        <th style="width: 300px;"><?= Yii::t('app', 'Nama Grader'); ?></th>
                                                        <th><?= Yii::t('app', 'Wilayah Dinas'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <a class="btn btn-xs blue" id="btn-add-dinas"  onclick="addGrader()"><i class="fa fa-plus"></i> Add Dinas</a>
                                    </div>
                                </div><br><br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5><?= Yii::t('app', 'Attachment'); ?></h5>
                                        <div id="place-attch">
                                            <div class="col-md-2">
                                                <?php
                                                echo $form->field($modAttachment, 'file',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                            <div class="col-md-2 hidden">
                                                <?php
                                                echo $form->field($modAttachment, 'file1',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                            <div class="col-md-2 hidden">
                                                <?php
                                                echo $form->field($modAttachment, 'file2',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                            <div class="col-md-2 hidden">
                                                <?php
                                                echo $form->field($modAttachment, 'file3',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                            <div class="col-md-2 hidden">
                                                <?php
                                                echo $form->field($modAttachment, 'file4',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                            <div class="col-md-2 hidden">
                                                <?php
                                                echo $form->field($modAttachment, 'file5',[
                                                    'template'=>'
                                                        <div class="col-md-12">
                                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                                <div class="fileinput-new thumbnail" style="width: 130px; height: 100px;">
                                                                    <img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="" /> </div>
                                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 130px; max-height: 100px;"> </div>
                                                                <div>
                                                                    <span class="btn btn-xs blue-hoki btn-outline btn-file">
                                                                        <span class="fileinput-new"> Select Image </span>
                                                                        <span class="fileinput-exists"> Change </span>
                                                                        {input} 
                                                                    </span> 
                                                                    <a href="javascript:;" class="btn btn-xs red fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                                    {error}
                                                                </div>
                                                            </div>
                                                        </div>'
                                                ])->fileInput();
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="thumbnail add-more" style="width: 150px; height: 115px; cursor: pointer;" onclick="addAttch();">
                                                <img src="<?= Yii::$app->view->theme->baseUrl ?>/cis/img/add-more.png" alt="" /> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions pull-right">
                            <div class="col-md-12 right">
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Save'),['id'=>'btn-save','class'=>'btn hijau btn-outline ciptana-spin-btn','onclick'=>'save();']); ?>
                                <?php // echo \yii\helpers\Html::button( Yii::t('app', 'Print'),['id'=>'btn-print','class'=>'btn blue btn-outline ciptana-spin-btn','onclick'=>"printout('PRINT')"]); ?>
                                <?php echo \yii\helpers\Html::button( Yii::t('app', 'Reset'),['id'=>'btn-reset','class'=>'btn grey-gallery btn-outline ciptana-spin-btn','onclick'=>'resetForm();']); ?>
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
if(isset($_GET['hasil_orientasi_id']) && !isset($_GET['edit'])){
    $pagemode = "afterSave(".$_GET['hasil_orientasi_id'].");";
}else if( isset($_GET['hasil_orientasi_id']) && isset($_GET['edit']) ){
    $pagemode = "afterSave(".$_GET['hasil_orientasi_id'].",".$_GET['edit'].");";
}else{
    $pagemode = "addNewKuantitas(); addGrader();";
}
?>
<?php $this->registerJs(" 
    $pagemode
    formconfig();
    setMenuActive('".json_encode(app\models\MMenu::getMenuByCurrentURL('Hasil Orientasi'))."');
    var trimming = $( 'label:contains(\"::\")' ).html();
    var lain = $( 'label:contains(\"__\")' ).html();
    $( 'label:contains(\"::\")' ).html(trimming+' ".( yii\helpers\Html::activeTextInput($model, "perjanjian_scaling_trimming",["style"=>"width:50px;","class"=>'text-align-right','disabled'=>true]) )." % <span style=\'font-size:1.1rem;\'>Dari Ukur Ulang / Penjual </span>');
    $( 'label:contains(\"__\")' ).html(lain+' ".( yii\helpers\Html::activeTextInput($model, "perlakuan_log_tidak_standard_lain",["style"=>"border: solid 1px #ccc;", "class"=>'form-control text-align-left col-md-12','disabled'=>true]) )."<span style=\'font-size:1.1rem;\'></span>');
    cekTotal();
    setKondisiLogpond();
", yii\web\View::POS_READY); ?>
<script>
function cekTotal() {
    $("#table-detail-kualitas > tbody > tr").each(function(){
        var no_urut = $(this).find("#no_urut").val();

        var ut_13 = $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[ut_13]']").val();
        var ut_45 = $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[ut_45]']").val();
        var ut_68 = $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[ut_68]']").val();
        var ut_99 = $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[ut_99]']").val();
        var jumlah = parseFloat(ut_13 * 1) + parseFloat(ut_45 * 1) + parseFloat(ut_68 * 1) + parseFloat(ut_99 * 1);

        if (jumlah != 100 ) {        
            $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[usia_tebang_persen]']").val(jumlah).css({'background-color': '#ff7575', 'color': '#fff'});
        } else {
            $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[usia_tebang_persen]']").val(jumlah).css({'background-color': '#cecece', 'color': '#000'});
        }
    });
}
function setSpLangsung(){
    if($("#<?= \yii\helpers\Html::getInputId($model, "sp_langsung") ?>").is(":checked")){
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_langsung_feet") ?>").prop("disabled",false);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_langsung_feet") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_langsung_feet") ?>").val("");
    }
}
function setSpEstafet(){
    if($("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet") ?>").is(":checked")){
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_feet") ?>").prop("disabled",false);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_kendaraan") ?>").prop("disabled",false);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_induk_feet") ?>").prop("disabled",false);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_feet") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_kendaraan") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_induk_feet") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_feet") ?>").val("");
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_kendaraan") ?>").val("");
        $("#<?= \yii\helpers\Html::getInputId($model, "sp_estafet_induk_feet") ?>").val("");
    }
}
function setLpLangsung(){
    if($("#<?= \yii\helpers\Html::getInputId($model, "lp_langsung") ?>").is(":checked")){
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_langsung_hari") ?>").prop("disabled",false);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_langsung_hari") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_langsung_hari") ?>").val("");
    }
}
function setLpEstafet(){
    if($("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet") ?>").is(":checked")){
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_m3") ?>").prop("disabled",false);
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_hari") ?>").prop("disabled",false);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_m3") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_hari") ?>").prop("disabled",true);
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_m3") ?>").val("");
        $("#<?= \yii\helpers\Html::getInputId($model, "lp_estafet_hari") ?>").val("");
    }
}
function setPerjanjianScaling(){
    if( $("input:radio[name*='[perjanjian_scaling]']:checked").val() == "Trimming" ){
        $("#<?= \yii\helpers\Html::getInputId($model, "perjanjian_scaling_trimming") ?>").prop("disabled",false);
    }else{
        $("#<?= \yii\helpers\Html::getInputId($model, "perjanjian_scaling_trimming") ?>").prop("disabled",true);
    }
}
function setPerlakuanLogTidakStandard(){
    if( $("input:radio[name*='[perlakuan_log_tidak_standard]']:checked").val() == "Lain-lain" ){
        $("#<?= \yii\helpers\Html::getInputId($model, "perlakuan_log_tidak_standard_lain") ?>").prop("disabled",false);
    }else{       
        $("#<?= \yii\helpers\Html::getInputId($model, "perlakuan_log_tidak_standard_lain") ?>").prop("disabled",true);
    }
}
function addNewKuantitas(){
    var last_tr =  $("#table-detail-kuantitas > tbody > tr:last").find("input,select").serialize();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/addNewKuantitas']); ?>',
        type   : 'POST',
        data   : {last_tr:last_tr},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail-kuantitas > tbody').fadeIn(100,function(){
                    reordertable('#table-detail-kuantitas');
                    addNewKualitas();
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function addNewKualitas(){
    var last_tr =  $("#table-detail-kualitas > tbody > tr:last").find("input,select").serialize();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/addNewKualitas']); ?>',
        type   : 'POST',
        data   : {last_tr:last_tr},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail-kualitas > tbody').fadeIn(100,function(){
                    reordertable('#table-detail-kualitas');
                    setKualitas();
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function setKualitas(){
    $("#table-detail-kuantitas > tbody > tr").each(function(){
        var no_urut = $(this).find("#no_urut").val();
        var kayu_id = $(this).find("select[name*='[kayu_id]']").val();
        var total_qty_batang = $(this).find("input[name*='[total][qty_batang]']").val();
        var total_qty_m3 = $(this).find("input[name*='[total][qty_m3]']").val();
        $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("select[name*='[kayu_id]']").val(kayu_id);
        $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[qty_batang]']").val(total_qty_batang);
        $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[qty_m3]']").val(total_qty_m3);
    });
}

function cancelItemThis(ele){
    var no_urut = $(ele).parents("tr").find("#no_urut").val();
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").remove();
        totalKuantitas();
        reordertable('#table-detail-kuantitas');
        reordertable('#table-detail-kualitas');
    });
}

function totalKuantitas(){
    var jml_subtotal_btg = 0;
    var jml_subtotal_m3 = 0;
    var jml_subpersen_btg = 0;
    var jml_subpersen_m3 = 0;

    
    // subtotal Horizontal
    $("#table-detail-kuantitas > tbody > tr").each(function(){
        var tr = $(this); var subtotal_btg = 0;
        $(tr).find(".col-btg").each(function(){
            subtotal_btg += unformatNumber( $(this).val() );
        }).promise().done( function(){ 
            $(tr).find("input[name*='[total][qty_batang]']").val( formatNumberForUser(subtotal_btg) );
        });
    });

    $("#table-detail-kuantitas > tbody > tr").each(function(){
        var tr = $(this); var subtotal_m3 = 0;
        $(tr).find(".col-m3").each(function(){
            subtotal_m3 += unformatNumber( $(this).val() );
        }).promise().done( function(){ 
            $(tr).find("input[name*='[total][qty_m3]']").val( formatNumberForUser(Math.round(subtotal_m3*100)/100) );
        });
    });
    
    // subtotal Vertical
    var sub_ver = []; 
    $("#table-detail-kuantitas > tfoot > tr:first").each(function(){
        $(this).find(".col-btg-foot").each(function(){
            var key = $(this).attr("name").replace(/]/g,"");
            key = key.split("["); key = key[1];
            sub_ver.push(key);
        });
    });

    $(sub_ver).each(function(key,val) {
        var sub_btg = 0; var sub_m3 = 0;
        var xjml_subtotal_btg = 0; var xjml_subtotal_m3 = 0;
        
        $("#table-detail-kuantitas > tbody > tr").each(function(){
            sub_btg += unformatNumber( $(this).find("input[name*='["+val+"][qty_batang]']").val() );
            sub_m3 += unformatNumber( $(this).find("input[name*='["+val+"][qty_m3]']").val() );
        });

        $("#table-detail-kuantitas > tbody > tr").each(function(){
            xjml_subtotal_btg += unformatNumber( $(this).find("input[name*='[total][qty_batang]']").val() );
            xjml_subtotal_m3 += unformatNumber( $(this).find("input[name*='[total][qty_m3]']").val() );
        });
        //val(formatNumberForUser((sub_m3/xjml_subtotal_m3)) * 100);
        $("#table-detail-kuantitas > tfoot").find("input[name*='["+val+"][total_btg]']").val( formatNumberForUser(sub_btg) );
        $("#table-detail-kuantitas > tfoot").find("input[name*='["+val+"][total_m3]']").val( formatNumberForUser(Math.round(sub_m3)) );
        $("#table-detail-kuantitas > tfoot").find("input[name*='["+val+"][persen_btg]']").val((sub_btg/xjml_subtotal_btg) * 100);
        $("#table-detail-kuantitas > tfoot").find("input[name*='["+val+"][persen_m3]']").val( formatNumberForUser(Math.round((sub_m3/xjml_subtotal_m3)*100)/100) * 100 );
    });

    //total
    setTimeout(function(){
        $("#table-detail-kuantitas > tbody > tr").each(function(){
            jml_subtotal_btg += unformatNumber( $(this).find("input[name*='[total][qty_batang]']").val() );
            jml_subtotal_m3 += unformatNumber( $(this).find("input[name*='[total][qty_m3]']").val() );
        });
        setTimeout(function(){
            $("#table-detail-kuantitas").find("input[name*='[total][total_btg]']").val( formatNumberForUser(jml_subtotal_btg) );
            $("#table-detail-kuantitas").find("input[name*='[total][total_m3]']").val( formatNumberForUser(Math.round(jml_subtotal_m3*100)/100) );
        },200);
        setKualitas();
    },200);
}

function addGrader(){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/addGrader']); ?>',
        type   : 'POST',
        data   : {},
        success: function (data){
            if(data.html){
                $(data.html).hide().appendTo('#table-detail-grader > tbody').fadeIn(100,function(){
                    $(this).find("select[name*='[gt_dkg_id]']").select2({
                        allowClear: !0,
                        placeholder: 'Ketik Nama',
                        width: null
                    });
                });
                reordertable('#table-detail-grader');
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function addAttch(){
    $("#place-attch .col-md-2.hidden:first").removeClass('hidden');
}

function masterDkg(ele){
    var tr_seq = $(ele).parents("tr").find("#no_urut").val();
    var url = '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/masterDkg']); ?>?tr_seq='+tr_seq;
    $(".modals-place-3-min").load(url, function() {
        $("#modal-master .modal-dialog").css('width','90%');
        $("#modal-master").modal('show');
        $("#modal-master").on('hidden.bs.modal', function () {});
        spinbtn();
        draggableModal();
    });
}
function pick(gt_dkg_id,tr_seq,gt_dkg_kode){
    $("#modal-master").find('button.fa-close').trigger('click');
    $("#table-detail-grader > tbody #no_urut[value='"+tr_seq+"']").parents("tr").find("select[name*='[gt_dkg_id]']").val(gt_dkg_id).trigger('change');
}
function setGrader(ele){
    var dkg_id = $(ele).parents("tr").find("select[name*='[gt_dkg_id]']").val();
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/setGrader']); ?>',
        type   : 'POST',
        data   : {dkg_id:dkg_id},
        success: function (data){
            if(data){
                $(ele).parents("tr").find("input[name*='[gt_tipe_dinas]']").val(data.tipe);
                $(ele).parents("tr").find("input[name*='[gt_nama_grader]']").val(data.graderlog_nm);
                $(ele).parents("tr").find("input[name*='[gt_wilayah_dinas]']").val(data.wilayah_dinas_nama);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
function cancelItemGrader(ele){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail-grader');
    });
}

function save(){
    var $form = $('#form-transaksi');
 


    if(formrequiredvalidate($form)){
        var item_kuantitas = $('#table-detail-kuantitas tbody tr').length;
        var item_kualitas = $('#table-detail-kualitas tbody tr').length;
        var item_grader = $('#table-detail-grader tbody tr').length;
 
        if((item_kuantitas <= 0) || (item_kualitas <= 0) || (item_grader <= 0)){
            cisAlert('Isi detail terlebih dahulu');
            return false;
        }

        $("#table-detail-kualitas > tbody > tr").each(function(){
            var no_urut = $(this).find("#no_urut").val();
            var jumlah =  $("#table-detail-kualitas > tbody").find("input[name='no_urut'][value='"+no_urut+"']").parents("tr").find("input[name*='[usia_tebang_persen]']").val();

            if (jumlah != 100 ) {
                cisAlert('Total usia tebang harus 100%');
                return false;
            }
        });

        if(validatingDetail()){
            submitform($form);
        }

    }
    return false;
}

function validatingDetail($form){
    var has_error = 0;
    $("#table-detail-kuantitas tbody tr").each(function(){
        var field1 = $(this).find('select[name*="[kayu_id]"]');
        if(!field1.val()){
            $(this).find('select[name*="[kayu_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('select[name*="[kayu_id]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    $("#table-detail-grader tbody tr").each(function(){
        var field1 = $(this).find('input[name*="[gt_dkg_id]"], select[name*="[gt_dkg_id]"]');
        if(!field1.val()){
            $(this).find('input[name*="[gt_dkg_id]"], select[name*="[gt_dkg_id]"]').parents('td').addClass('error-tb-detail');
            has_error = has_error + 1;
        }else{
            $(this).find('input[name*="[gt_dkg_id]"], select[name*="[gt_dkg_id]"]').parents('td').removeClass('error-tb-detail');
        }
    });
    
    if(has_error === 0){
        return true;
    }
    return false;
}

function afterSave(id){
    getItemsById(id,"<?= isset($_GET['edit'])?$_GET['edit']:""; ?>");
    <?php if( (isset($_GET['hasil_orientasi_id'])) && !isset($_GET['edit'])){ ?>
        $('form').find('input').each(function(){ $(this).prop("disabled", true); });
        $('form').find('select').each(function(){ $(this).prop("disabled", true); });
        $('form').find('textarea').each(function(){ $(this).prop("disabled",true); });
        $('#<?= yii\bootstrap\Html::getInputId($model, 'tanggal') ?>').siblings('.input-group-addon').find('button').prop('disabled', true);
        $("#btn-add-kuantitas").removeClass("blue").addClass("grey");
        $("#btn-add-kuantitas").removeAttr("onclick");
        $("#btn-add-dinas").removeClass("blue").addClass("grey");
        $("#btn-add-dinas").removeAttr("onclick");
        $('.btn-file').remove();
        $('.add-more').remove();
        $('#btn-save').attr('disabled','');
        $('#btn-print').removeAttr('disabled');
        $('#btn-print2').removeAttr('disabled');
        setTimeout(function(){
            $('a.btn-xs.red').remove();
        },800);
    <?php }else{ ?>
        $('#<?= yii\bootstrap\Html::getInputId($model, 'kode') ?>').prop("disabled", true);
        setTimeout(function(){
            $("#table-detail-kuantitas > tbody > tr").each(function(){
                $(this).find('select').prop("disabled", false);
                $(this).find('input').prop("disabled", false);
                $(this).find('input[name*="[total]"]').prop("disabled", true);
            });
        },800);
        setTimeout(function(){
            $("#table-detail-kualitas > tbody > tr").each(function(){
                $(this).find('select').prop("disabled", false);
                $(this).find('input').prop("disabled", false);
                $(this).find('select[name*="[kayu_id]"]').prop("disabled", true);
                $(this).find('input[name*="[qty_batang]"]').prop("disabled", true);
                $(this).find('input[name*="[qty_m3]"]').prop("disabled", true);
            });
        },800);
        setTimeout(function(){
            $("#table-detail-grader > tbody > tr").each(function(){
                $(this).find('input[name*="[gt_dkg_id]"], select[name*="[gt_dkg_id]"]').prop("disabled", false);
            });
        },800);
        setSpLangsung();
        setSpEstafet();
        setLpLangsung();
        setLpEstafet();
        setPerjanjianScaling();
    <?php } ?>
}

function getItemsById(id,edit=null){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/getItemsById']); ?>',
        type   : 'POST',
        data   : {id:id,edit:edit},
        success: function (data) {
            if(data.html_kuantitas){
                $("#table-detail-kuantitas > tbody").html(data.html_kuantitas);
                totalKuantitas();
                reordertable('#table-detail-kuantitas');
            }
            if(data.html_kualitas){
                $("#table-detail-kualitas > tbody").html(data.html_kualitas);
                reordertable('#table-detail-kualitas');
            }
            if(data.html_grader){
                $("#table-detail-grader > tbody").html(data.html_grader);
                reordertable('#table-detail-grader');
            }
            if(data.attch){
                $(data.attch).each(function(i,val){
                    var asd = (i==0)?"":i;
                    var src = "<?= Yii::$app->urlManager->baseUrl ?>/uploads/pur/hasilorientasi/"+val.file_name; 
                    $(".field-tattachment-file"+asd).find("img").attr("src",src);
                    $(".field-tattachment-file"+asd).parents(".col-md-2").removeClass("hidden");
                    if(edit){
                        $(".field-tattachment-file"+asd).find(".btn-file").addClass("hidden");
                        $(".field-tattachment-file"+asd).find(".fileinput.fileinput-new").append("<a class='btn btn-xs btn-outline red-flamingo' onclick='deleteAttch("+val.attachment_id+",\""+asd+"\");'><i class='fa fa-trash-o'></i> Hapus</a>");
                    }
                });
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
    setTimeout(function(){
        formconfig();
    },500);
}

function daftarAfterSave(){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/daftarAfterSave']) ?>','modal-aftersave','90%');
}
function deleteAttch(attachment_id,fileno){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/hasilorientasi/deleteAttch']) ?>?id='+attachment_id+'&fileno='+fileno,'modal-delete-record');
}
function setNormalPickAttch(fileno){
    if(!fileno){
        fileno = "";
    }
    $(".field-tattachment-file"+fileno).find(".btn-file").removeClass("hidden");
    $(".field-tattachment-file"+fileno).find(".fileinput.fileinput-new > a.red-flamingo").remove();
    $(".field-tattachment-file"+fileno).find("img").attr("src","<?= Yii::$app->view->theme->baseUrl; ?>/cis/img/no-image.png");
}

function setKondisiLogpond(){
    if( $("input:radio[name*='[kondisi_logpond]']:checked").val() == "Non-Logpond" ){
        $("#place-datalogpond").slideUp();
    }else{
        $("#place-datalogpond").slideDown();
    }
}


</script>