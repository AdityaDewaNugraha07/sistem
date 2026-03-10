<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
?>
<div class="modal fade" id="modal-catatan-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                    <?php // KONTEN MODAL ?>
                    <div class="col-md-12">
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label">User</label>
                            <?php /* <div class="col-md-7"><strong><?= $pegawai_nama;?></strong></div> */?>
                            <div class="col-md-10"><strong><?= $model->user->pegawai->pegawai_nama;?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label"><?= $model->attributeLabels()['tanggal'] ?></label>
                            <div class="col-md-10"><strong><?= $model->tanggal ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label"><?= $model->attributeLabels()['judul'] ?></label>
                            <div class="col-md-10"><strong><?= $model->judul ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-2 control-label"><?= $model->attributeLabels()['keterangan'] ?></label>
                            <div class="col-md-10"><strong><?= nl2br($model->keterangan) ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="col-md-2 control-label">Gambar</label>
                        <div class="form-group col-md-10">
                            <?php
                            if ($model->catatan_gambar != "") { 
                                $src = Yii::getAlias('@web')."/uploads/catatan/".$model->catatan_gambar;
                            } else {
                                $src = Yii::$app->view->theme->baseUrl."/cis/img/no-image.png";
                            }
                            ?>
                            <img src="<?php echo $src; ?>" class="img-responsive pic-bordered" alt="" width="120">
                        </div>
                    </div>
                    <?php /* EO KONTEN MODAL */ ?>

                </div>
            </div>
            <div class="modal-footer">
                <?= yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"edit(".$model->catatan_id.")"]); ?>
                <?= yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/sysadmin/catatan2/delete','id'=>$model->catatan_id,'tableid'=>'table-catatan'])."','modal-delete-record')"]); ?>
            </div>
        </div>
    </div>
</div>

<script>
function edit(id){
    window.location = '<?= \yii\helpers\Url::toRoute('/sysadmin/catatan2/edit?id=') ?>'+id;
}
</script>