<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-daftar-bpb" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar BPB yang telah dikeluarkan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-laporan" id="table-bpb">
								<thead>
									<tr>
										<th></th>
										<th style="width: 150px;"><?= Yii::t('app', 'Kode BPB'); ?></th>
										<th style="width: 150px;"><?= Yii::t('app', 'Kode SPB'); ?></th>
										<th><?= Yii::t('app', 'Tanggal Keluar'); ?></th>
										<th><?= Yii::t('app', 'Dept. Pemesan'); ?></th>
										<th><?= Yii::t('app', 'Tanggal Diterima'); ?></th>
										<th><?= Yii::t('app', 'Status'); ?></th>
										<th style="width: 50px;"></th>
									</tr>
								</thead>
							</table>
						</div>
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
    dtBpb();
", yii\web\View::POS_READY); ?>
<script>
function dtBpb(){
    var dt_table =  $('#table-bpb').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/bpb/daftarBpb') ?>',data:{dt: 'table-bpb'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, width: '15%' },
            {	targets: 2, width: '15%' },
            {	targets: 6,
                orderable: false,
				searchable: false,
                width: '10%',
                render: function ( data, type, full, meta ) {
                    var ret = ' - ';
                    if(data == "BELUM DITERIMA"){
                        ret = '<span class="label label-sm label-warning"> '+data+' </span>';
                    }else if(data == "SUDAH DITERIMA"){
                        ret = '<span class="label label-sm label-success"> '+data+' </span>';
                    }
                    return ret;
                }
            },
            
            {	targets: 7, 
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail BPB" onclick="lihatBpb('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatBpb(bpb_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/bpb/index','bpb_id'=>'']); ?>'+bpb_id);
}
</script>