<div class="modal fade" id="modal-jobdesc-info" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Jobdesc').' '.$model->pegawai->pegawai_nama; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php // KONTEN MODAL ?>
                    <div class="col-md-12">
                        <div class="form-group col-md-12">
                            <?php
                            if ($model->nama_file != "") { 
                                $files = explode(", ", $model->nama_file);
                                foreach ($files as $file) {
                                    $src = Yii::getAlias('@web')."/uploads/jobdesc/".$file;
                                    ?>
                                    <embed src="<?= $src ?>" type="application/pdf" frameborder="0" width="100%" height="550px;">
                                    <!-- <embed src="http://<?php //echo $_SERVER['HTTP_HOST'];?>/ext/pdfjs/lib/web/viewer.html?file=<?php echo $src;?>" type="application/pdf" frameborder="0" width="100%" height="550px;"> -->
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php /* EO KONTEN MODAL */ ?>

                </div>
            </div>
            <?php
            //if ($_SESSION['__id'] == 53 || $_SESSION['__id'] == 205) {
            ?>
            <!-- <div class="modal-footer">
                <?php //echo yii\helpers\Html::button(Yii::t('app', 'Delete'),['class'=>'btn red btn-outline','onclick'=>"openModal('".\yii\helpers\Url::toRoute(['/hrd/jobdesc/delete','id'=>$model->jobdesc_id,'tableid'=>'table-jobdesc'])."','modal-delete-record')"]); ?>
            </div> -->
            <?php
            //}
            ?>
        </div>
    </div>
</div>

<? // registrasikan fungsi javascript yang akan digunakan, pisahkan masing2 fungsi javascript yang akan didaftarkan dengan tandan titik koma (;) ?>
<?php 
$this->registerJs("
hideButton();
",yii\web\View::POS_READY);
?>

<script>
function edit(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/edit', 'id'=>'']) ?>'+id, 'modal-jobdesc-edit', '80%');
}

function image(id, gambar){
	var url = '<?= \yii\helpers\Url::toRoute(['/hrd/jobdesc/image','id'=>'']) ?>'+id+'&gambar='+gambar;
	$(".modals-place-2").load(url, function() {
		$("#modal-image").modal('show');
		$("#modal-image").on('hidden.bs.modal', function () { });
		$("#modal-image .modal-dialog").css('width',"1360px");
		spinbtn();
		draggableModal();
	});
}

function hideButton() {
    $('#toolbarViewer').hide();
}
</script>