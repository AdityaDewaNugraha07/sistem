<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade draggable-modal" id="modal-history" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Bon Sementara yang telah dibayar'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover" id="table-dt">
								<thead>
									<tr>
										<th></th>
										<th style="width: 150px;"><?= Yii::t('app', 'Kode Bon'); ?></th>
										<th><?= Yii::t('app', 'Tanggal Kasbon'); ?></th>
										<th><?= Yii::t('app', 'Waktu Terima'); ?></th>
										<th><?= Yii::t('app', 'Penerima'); ?></th>
										<th><?= Yii::t('app', 'Deskripsi'); ?></th>
										<th style="font-size: 1.1rem;"><?= Yii::t('app', 'Kasbon<br>Kas Kecil'); ?></th>
										<th><?= Yii::t('app', 'Nominal'); ?></th>
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
    dtTable();
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-dt').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/kasir/kasbesar/bonTerbayar') ?>',data:{dt: 'table-dt'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class: 'text-align-center',
			},
			{	targets: 2, class: 'text-align-center',
			},
            {	targets: 3, class: 'text-align-center',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
            {	targets: 4, class: 'text-align-center',
			},
            {	targets: 5, class: 'text-align-center',
			},
            {	targets: 6, class: 'text-align-center',
				render: function ( data, type, full, meta ) {
					if(data = 'KASBON KASBESAR KE KASKECIL'){
						return "Yes";
					}else{
						return "-";
					}
				}
			},
            {	targets: 7, class: 'text-align-right',
				render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
				}
			},
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>