<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<?php if(empty($disableAction)){ ?>
				<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>
				<?php } ?>
                <h4 class="modal-title"><?= Yii::t('app', 'OP Export yang telah dibuat'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-master">
							<thead>
								<tr>
									<th></th>
									<th style="width: 250px;"><?= Yii::t('app', 'Nomor Kontrak') ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Tanggal') ?></th>
									<th style="width: 350px;"><?= Yii::t('app', 'Shipment To') ?></th>
									<th><?= Yii::t('app', 'Goods Description') ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Payment Method') ?></th>
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
<?php $this->registerJs(" dtMaster();", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-master').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/packinglist/masterOpex') ?>',data:{dt: 'table-master'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1,
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[0]+"\"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>