<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-loglist" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Loglist'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th></th>
									<th style="width: 180px;"><?= Yii::t('app', 'Kode Loglist') ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Kode BAJG') ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Kode Keputusan') ?></th>
									<th style="width: 120px;"><?= Yii::t('app', 'Kode PO'); ?></th>
									<th style="width: 200px;"><?= Yii::t('app', 'No. Kontrak') ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Tongkang'); ?></th>
									<th><?= Yii::t('app', 'Lokasi Muat'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/purchasinglog/keberangkatantongkang/openLoglist']) ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1,
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickLoglist(\""+full[0]+"\"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>