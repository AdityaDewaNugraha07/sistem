<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-daftar-pengajuanbiaya" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Pengajuan Biaya'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-pengajuanbiaya">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode Pengajuan'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Total Biaya'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<th style="width: 50px;"></th>
								</tr>
							</thead>
						</table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
    dtPengajuan();
", yii\web\View::POS_READY); ?>
<script>
function dtPengajuan(){
    var dt_table =  $('#table-pengajuanbiaya').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/pengajuanbiayagrader/daftarPengajuanBiayaGrader') ?>',data:{dt: 'table-pengajuanbiaya'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 5,
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data=='PAID'){
                        ret = '<span class="label label-sm label-success"> '+data+' </span>'
                    }else{
                        ret = '<span class="label label-sm label-warning"> '+data+' </span>'
                    }
                    return ret;
                }
            },
            {	targets: 6, 
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail Pengajuan" onclick="lihatPengajuan('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatPengajuan(biaya_grader_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasing/pengajuanbiayagrader/index','biaya_grader_id'=>'']); ?>'+biaya_grader_id);
}
</script>