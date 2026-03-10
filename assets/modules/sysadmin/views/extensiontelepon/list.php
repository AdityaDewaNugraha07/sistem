<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-extension" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <center><h4 class="modal-title"><?= Yii::t('app', 'Extension Line Telepon'); ?></h4></center>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-extension">
							<thead>
								<tr>
									<th></th>
									<th style="width: 80px; line-height: 1"><?= Yii::t('app', 'Kode<br>Ext.') ?></th>
									<th style="width: 180px;"><?= Yii::t('app', 'Bagian') ?></th>
									<th><?= Yii::t('app', 'Nama') ?></th>
								</tr>
							</thead>
						</table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs("
    formconfig();
	dtMaster();
    $.fn.modal.Constructor.prototype.enforceFocus = function () {}; // agar select2 bisa diketik didalam modal
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-extension').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/sysadmin/extensiontelepon/list') ?>',data:{dt: 'table-extension'} },
        order: [
            [1, 'asc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class: "text-align-center", 
				function ( data, type, full, meta ) {
					return "<b>"+data+"</b>";
				},
			},
			{	targets: 2, class: "text-align-left" },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth" : false,
    });
}
</script>