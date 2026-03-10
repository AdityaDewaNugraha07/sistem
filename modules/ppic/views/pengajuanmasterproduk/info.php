<?php
if(Yii::$app->user->identity->user_group_id != \app\components\Params::USER_GROUP_ID_SUPER_USER){
        $disableAction = true;
}
?>
<div class="modal fade" id="modal-produk-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['produk_group'] ?></label>
                            <div class="col-md-7"><strong><?= $modDetail->produk_group ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['produk_kode'] ?></label>
                            <div class="col-md-7"><strong><?= $modDetail->produk_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['produk_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $modDetail->produk_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['jenis_kayu'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->jenis_kayu == "null" || $modDetail->jenis_kayu == null || $modDetail->jenis_kayu == "")?'-':$modDetail->jenis_kayu; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['grade'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->grade == "null" || $modDetail->grade == null || $modDetail->grade == "")?'-':$modDetail->grade; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['warna_kayu'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->warna_kayu == "null" || $modDetail->warna_kayu == null || $modDetail->warna_kayu == "")?'-':$modDetail->warna_kayu; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['glue'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->glue == "null" || $modDetail->glue == null || $modDetail->glue == "")?'-':$modDetail->glue; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['profil_kayu'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->profil_kayu == "null" || $modDetail->profil_kayu == null || $modDetail->profil_kayu == "")?'-':$modDetail->profil_kayu; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['kondisi_kayu'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->kondisi_kayu == "null" || $modDetail->kondisi_kayu == null || $modDetail->kondisi_kayu == "")?'-':$modDetail->kondisi_kayu; ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $modDetail->attributeLabels()['produk_dimensi'] ?></label>
                            <div class="col-md-7">
                                <strong><?= ($modDetail->produk_dimensi == "null" || $modDetail->produk_dimensi == null || $modDetail->produk_dimensi == "")?'-':$modDetail->produk_dimensi; ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Qty Per ').$modDetail->produk_satuan_besar; ?></label>
                            <div class="col-md-7">
                                <strong><?= $modDetail->produk_qty_satuan_kecil ?> <?= $modDetail->produk_satuan_kecil ?></strong>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Kapasitas ') ?></label>
                            <div class="col-md-7"><strong><?= $modDetail->kapasitas_kubikasi ?> M<sup>3</sup></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label"><?= $modDetail->attributeLabels()['produk_gbr'] ?></label>
                            <div class="col-md-8">
                                <a href="javascript:;" class="thumbnail">
                                    <?php
                                    if(!empty($modDetail->produk_gbr)){
                                        echo '<img src="'.\yii\helpers\Url::base().'/uploads/gud/req_produk/'.$modDetail->produk_gbr .'" alt="produk-pict" style="height: 100%; width: 100%; display: block;"> ';
                                    }else{
                                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="produk-pict" style="height: 200px; width: 100%; display: block;"> ';
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>