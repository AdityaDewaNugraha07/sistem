<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master-limbah" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<!--<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>-->
                <h4 class="modal-title"><?= Yii::t('app', 'Master Limbah'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th style="width: 200px;"><?= Yii::t('app', 'Jenis Limbah') ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode Limbah') ?></th>
									<th><?= Yii::t('app', 'Nama') ?></th>
									<th><?= Yii::t('app', 'Satuan Angkut') ?></th>
								</tr>
							</thead>
						</table>
						</div>
                    </div>
                </div>
            <div class="modal-footer">
                <?= yii\bootstrap\Html::hiddenInput('reff_ele',$tr_seq) ?>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php $this->registerJs(" dtProduk();", yii\web\View::POS_READY); ?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/marketing/penerimaanjasa/openlimbah','jenis_produk'=>'']) ?><?= $jenis_produk ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 2,
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickProduk(\""+full[0]+"\"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>