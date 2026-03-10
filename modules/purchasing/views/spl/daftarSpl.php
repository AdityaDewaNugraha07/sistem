<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-daftar-spl" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar SPL yang telah dikeluarkan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-spl">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode SPL'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Menyetujui'); ?></th>
                                                                        <th style="width: 50px;"><?= Yii::t('app', 'Status'); ?></th>                                                                        
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
    dtSpl();
", yii\web\View::POS_READY); ?>
<script>
function dtSpl(){
    var dt_table =  $('#table-spl').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasing/spl/daftarSpl') ?>',data:{dt: 'table-spl'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 4, 
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret = "";
                    if(full[4] >0){
                        ret = "<center><span class='label label-sm label-danger'>ABORTED</span></center>";
                    }else{
                        ret = "";
                    }
                    
                    return ret;
                }
            },
            {	targets: 5, 
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail SPL" onclick="lihatSpl('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatSpl(spl_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasing/spl/index','spl_id'=>'']); ?>'+spl_id);
}
</script>