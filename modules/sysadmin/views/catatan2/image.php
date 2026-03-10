<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
?>
</style>
<div class="modal fade" id="modal-catatan-image" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Image'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    
                    <?php // KONTEN MODAL ?>
                    <div class="form-group col-md-12">
                        <img src="<?php echo Yii::getAlias('@web');?>/uploads/catatan/<?= $model->catatan_gambar ?>" class="img-responsive pic-bordered" alt="">
                    </div>
                    <?php /* EO KONTEN MODAL */ ?>

                </div>
            </div>
        </div>
    </div>
</div>