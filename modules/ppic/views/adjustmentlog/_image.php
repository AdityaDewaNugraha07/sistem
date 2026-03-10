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
</style>
<div class="modal fade" id="modal-image" tabindex="-1" role="basic" aria-hidden="true" style="width: 100%">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Image ').':</b>'; ?></h4>
            </div>
			<div id="showdetails" class="text-center" style="padding: 50px;">
                <img src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/ppic/adjustmentLog/<?= $modAttachment->file_name; ?>" alt="" class="img-responsive img-fluid img-rounded imagesp center-block" class="img-responsive" style="max-width: 800px;"/>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


