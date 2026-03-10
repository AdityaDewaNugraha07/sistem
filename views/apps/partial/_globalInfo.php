<div class="modal fade" id="modal-global-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" id="close-btn-globalconfirm" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title text-align-center"><?= (isset($judul))? $judul : '-'; ?></h4>
            </div>
			
			<div class="modal-body" >
				<div class="row">
					<div class="col-md-12">
						<?= (isset($pesan))? $pesan : '-'; ?>
					</div>
				</div>
			</div>
			
            <div class="modal-footer text-align-center">
                <?= yii\helpers\Html::button(Yii::t('app', 'Close'),['class'=>'btn blue btn-outline ciptana-spin-btn','data-dismiss'=>'modal']); ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>