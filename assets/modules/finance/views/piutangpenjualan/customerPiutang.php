<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Customer Piutang'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-customer">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Atas Nama') ?></th>
									<th><?= Yii::t('app', 'Perusahaan') ?></th>
									<th><?= Yii::t('app', 'Alamat') ?></th>
									<th><?= Yii::t('app', 'Max Plafond') ?></th>
									<th><?= Yii::t('app', 'Piutang Aktif') ?></th>
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
    var dt_table =  $('#table-customer').dataTable({
        ajax: { url    : '<?= \yii\helpers\Url::toRoute([(isset($actionname)?$actionname:'-')]); ?>',data:{dt: 'table-customer'} },
        columnDefs: [
			{	targets: 0, visible: false },
            {	targets: 1,
                render: function ( data, type, full, meta ) {
					var ret = '<i>Perorangan</i>';
					var par = full[1];
                    if(full[3]){
                        par = full[1];
                    }
                    return "<a onclick='pick(\""+full[0]+"\",\""+par+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 3,
                render: function ( data, type, full, meta ) {
                    var ret = '<i>Perorangan</i>';
                    if(data){
                        ret = data;
                    }
                    return ret;
                }
            },
            {	targets: 4, class: "text-align-right",
                render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
            },
			{	targets: 5, class: "text-align-right",
                render: function ( data, type, full, meta ) {
                    return formatNumberForUser(data);
                }
            },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>