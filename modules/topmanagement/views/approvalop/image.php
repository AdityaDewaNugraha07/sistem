<style>
.imagesp {
  max-width: 1024px;
  height: auto;
  display: block;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
}

.modal-body {
    height: 80%; 
    max-height: 80vh;
    overflow-y: auto;
}

.modal-dialog {
    height: auto;
    max-height: 80%;
}
</style>

<div class="modal fade" id="modal-image" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title">
                <?php if($ext == "jpg" || $ext == "jpeg" || $ext == "bmp" || $ext == "png" || $ext == "gif" || $ext == "tiff"){ ?>
                    <?= Yii::t('app', 'Image ').': '.$attch->file_name.'</b>'; ?>
                <?php } else { ?>
                    <?= Yii::t('app', 'File ').': '.$attch->file_name.'</b>'; ?>
                <?php } ?>
                </h4>
            </div>
            <div class="modal-body" style="max-height: auto; overflow-y: auto;">
                <div id="showdetails" id="showdetails">
                    <?php if($ext == "jpg" || $ext == "jpeg" || $ext == "bmp" || $ext == "png" || $ext == "gif" || $ext == "tiff"){ ?>
                        <img src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/mkt/po/<?= $attch->file_name; ?>" style="" alt="" class="img-responsive imagesp" />
                    <?php }else if($ext == 'pdf'){ ?>
                        <embed src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/mkt/po/<?= $attch->file_name; ?>" width="100%" height="700px" type="application/pdf" />
                    <?php } else { ?>
                        <iframe src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/mkt/po/<?= $attch->file_name; ?>" width="100%" height="500px"></iframe>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


