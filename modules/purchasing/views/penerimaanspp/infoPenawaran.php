<div class="modal fade" id="modal-info-penawaran" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Info Penawaran Bahan Pembantu'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['kode'] ?></label>
                            <div class="col-md-7"><strong><?= $model->kode ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Tanggal Penawaran'); ?></label>
                            <div class="col-md-7"><strong><?= \app\components\DeltaFormatter::formatDateTimeForUser2($model->tanggal) ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['suplier_id'] ?></label>
                            <div class="col-md-7"><strong><?= $model->suplier->suplier_nm ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= Yii::t('app', 'Nama BHP'); ?></label>
                            <div class="col-md-7"><strong><?= $model->bhp->bhp_nm; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['keterangan'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->keterangan)?$model->keterangan:'-'; ?></strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['qty'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->qty)?$model->qty." ".$model->satuan_kecil:'-'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['harga_satuan'] ?></label>
                            <div class="col-md-7"><strong><?= !empty($model->harga_satuan)?\app\components\DeltaFormatter::formatNumberForUserFloat($model->harga_satuan):'0'; ?></strong></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-5 control-label"><?= $model->attributeLabels()['attachment'] ?></label>
                            <div class="col-md-7">
                                <a href="javascript:;" class="thumbnail">
                                    <?php
                                    if(!empty($model->attachment)){
                                        echo '<img src="'.\yii\helpers\Url::base().'/uploads/pur/penawaran/'.$model->attachment .'" alt="produk-pict" style="height: 100%; width: 100%; display: block;" onclick="image('.$model->penawaran_bhp_id.')"> ';
                                    }else{
                                        echo '<img src="'.Yii::$app->view->theme->baseUrl .'/cis/img/no-image.png" alt="produk-pict" style="height: 150px; width: 100%; display: block;"> ';
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
				<?php
					if(empty($disableEdit)){
						echo yii\helpers\Html::button(Yii::t('app', 'Edit'),['class'=>'btn blue btn-outline','onclick'=>"editPenawaran()"]);
					}
					if(empty($disableDelete)){
						echo yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/deletePenawaran','id'=>$model->penawaran_bhp_id,'tableid'=>'table-penawaran'])."','modal-delete-record')"]);
					}
				?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<script>
function editPenawaran(){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/editPenawaran','id'=>'']) ?><?= $model->penawaran_bhp_id ?>';
	var modal_id = 'modal-edit-penawaran';	
	$(".modals-place-2").load(url, function() {
		$("#"+modal_id).modal('show');
		$("#"+modal_id).on('hidden.bs.modal', function () {
			$("#"+modal_id).hide();
			$("#"+modal_id).remove();
			$("#table-penawaran").dataTable().fnClearTable();
		});
		spinbtn();
		draggableModal();
	});
}
function image(id){
	var url = '<?= \yii\helpers\Url::toRoute(['/purchasing/penerimaanspp/image','id'=>'']) ?>'+id;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1000px");
		spinbtn();
		draggableModal();
	});
}
</script>