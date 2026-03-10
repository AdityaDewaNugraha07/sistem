<div class="modal fade" id="modal-customer-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Informasi Customer'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_tipe_penjualan'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_tipe_penjualan ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_tanggal_join'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->cust_tanggal_join); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Termasuk PKP'); ?> </label>
                            <div class="col-md-7"><strong><?= ($model->cust_is_pkp)?'Ya':'Tidak' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nik'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nik ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_jk'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_jk ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_tgllahir'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser2($model->cust_an_tgllahir); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_nohp'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_nohp; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_agama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_agama ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_alamat'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_an_alamat ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_an_email'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_an_email)?$model->cust_an_email:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_max_plafond'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatNumberForUser($model->cust_max_plafond) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_no_npwp'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_no_npwp ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['active'] ?></label>
                            <div class="col-md-7"><strong><?= ($model->active)?'<span class="font-green-jungle">Active</span>':'<span class="font-red">Non-Active</span>' ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->created_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['created_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->created_by)) ? \app\models\MUser::findIdentity($model->created_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_at'] ?></label>
                            <div class="col-md-7"><strong><?= app\components\DeltaFormatter::formatDateTimeForUser($model->updated_at); ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['updated_by'] ?></label>
                            <div class="col-md-7"><strong><?php echo ( \app\models\MUser::findIdentity($model->updated_by)) ? \app\models\MUser::findIdentity($model->updated_by)->userProfile->fullname:"Unknown"; ?></strong></div>
                        </div>
                    </div>
                    <?php if(!empty($model->cust_pr_nama)){ ?>
                    <div class="col-md-4">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_nama'] ?></label>
                            <div class="col-md-7"><strong><?= $model->cust_pr_nama ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_direktur'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_direktur)?$model->cust_pr_direktur:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_alamat'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_alamat)?$model->cust_pr_alamat:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_phone'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_phone)?$model->cust_pr_phone:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_fax'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_fax)?$model->cust_pr_fax:" - " ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['cust_pr_email'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->cust_pr_email)?$model->cust_pr_email:" - " ?></strong></div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="row" style="margin-top: 30px;">
                    <div class="form-group col-md-3">
                        <div class="form-group col-md-12">
                            <h4 class=""><?= Yii::t('app', 'Term of Payment :'); ?></h4>
                            <?php if(!empty($modCustTop)){ ?>
                                <table class="table table-bordered table-hover col-md-5">
                            <?php foreach($modCustTop as $i => $top){ ?>
                                    <tr>
                                        <th style="padding: 5px"><?= $top->custtop_jns ?> </th>
                                        <td style="padding: 5px"><?= $top->custtop_top." ". Yii::t('app', 'Hari');; ?></td>
                                    </tr>
                            <?php } ?>
                                </table>
                            <?php }else{ ?>
                                <table class="table table-bordered table-hover col-md-5">
                                    <tr>
                                        <td style="padding: 5px"><center><?= Yii::t('app', 'Belum di Set'); ?> </center></td>
                                    </tr>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <h4 class=""><?= $model->attributeLabels()['cust_file_ktp']; ?> : </h4>
                        <div class="col-md-10">
                            <a href="javascript:;" class="thumbnail">
                                <?php
                                if(!empty($model->cust_file_ktp)){
                                    echo '<img src="'.\yii\helpers\Url::base().'/uploads/mkt/customer/'.$model->cust_file_ktp .'" alt="ktp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }else{
                                    echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <h4 class=""><?= $model->attributeLabels()['cust_file_npwp']; ?> : </h4>
                        <div class="col-md-10">
                            <a href="javascript:;" class="thumbnail">
                                <?php
                                if(!empty($model->cust_file_npwp)){
                                    echo '<img src="'.\yii\helpers\Url::base().'/uploads/mkt/customer/'.$model->cust_file_npwp .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }else{
                                    echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }
                                ?>

                            </a>
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <h4 class><?= $model->attributeLabels()['cust_file_photo']; ?> : </h4>
                        <div class="col-md-10">
                            <a href="javascript:;" class="thumbnail">
                                <?php
                                if(!empty($model->cust_file_photo)){
                                    echo '<img src="'.\yii\helpers\Url::base().'/uploads/mkt/customer/'.$model->cust_file_photo .'" alt="npwp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }else{
                                    echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="ktp-scan" style="height: 150px; width: 100%; display: block;"> ';
                                }
                                ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/marketing/customer/edit','id'=>$model->cust_id])."','modal-customer-edit')"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/marketing/customer/delete','id'=>$model->cust_id,'tableid'=>'table-customer'])."','modal-delete-record')"]); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>