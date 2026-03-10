<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Packinglist'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-op">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Order No.'); ?></th>
									<th><?= Yii::t('app', 'Contract No.'); ?></th>
									<th><?= Yii::t('app', 'Packinglist No.'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Container No.'); ?></th>
									<th><?= Yii::t('app', 'Bundles'); ?></th>
									<th><?= Yii::t('app', 'Pcs'); ?></th>
									<th><?= Yii::t('app', 'M<sup>3</sup>'); ?></th>
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
<?php // $this->registerJsFile($this->theme->baseUrl."/global/plugins/jquery-ui/jquery-ui.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<?php $this->registerJs("
	formconfig();
    dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-op').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/spmexport/openPackinglist') ?>',data:{dt: 'table-op'} },
		order: [],
        columnDefs: [
			{ 	targets: 0, 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[0]+"\",\""+full[7]+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },
			{ 	targets: 6, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 7, class: 'text-align-center' },
			{	targets: 8, class: 'text-align-center', searchable: false },
			{	targets: 9, class: 'text-align-right', searchable: false },
			{	targets: 10, class: 'text-align-right', searchable: false },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}
</script>