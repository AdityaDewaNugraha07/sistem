<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-detail" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Detail Penerimaan Log Sengon'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-modal-detail">
							<thead>
								<tr>
									<th></th>
									<th style="width: 50px;"><?= Yii::t('app', 'No. Urut'); ?></th>
									<th><?= Yii::t('app', 'No. Kode'); ?></th>
									<th><?= Yii::t('app', 'Batang Ke'); ?></th>
									<th><?= Yii::t('app', 'Jenis'); ?></th>
									<th><?= Yii::t('app', 'Diamater'); ?></th>
									<th><?= Yii::t('app', 'Panjang'); ?></th>
									<th><?= Yii::t('app', 'Pcs'); ?></th>
									<th><?= Yii::t('app', 'm'); ?></sup>3</sup></th>
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
    dtDetail();
", yii\web\View::POS_READY); ?>
<script>
function dtDetail(){
    var dt_table =  $('#table-modal-detail').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/terimasengon/detailPenerimaan') ?>',data:{dt: 'modal-detail',terima_sengon_detail_id:<?= $terima_sengon_detail_id; ?>} },
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 2, 
                render: function ( data, type, full, meta ) {
					return '<center>'+full[3]+'-'+full[2]+'</center>';
                }
            },
			{	targets: 3, visible: false },
        ],
		order:[],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>