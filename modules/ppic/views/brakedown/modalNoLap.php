<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Produk'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
                                    <th rowspan="2"></th>
									<th rowspan="2"><?= Yii::t('app', 'No. Lap') ?></th>
									<th rowspan="2" style="width: 300px;"><?= Yii::t('app', 'No. Barcode') ?></th>
									<th rowspan="2" style="width: 300px;"><?= Yii::t('app', 'Grade') ?></th>
                                    <th colspan="4" style="width: 400px;"><?= Yii::t('app', 'Diameter (cm)') ?></th>
									<th rowspan="2" style="width: 240px;"><?= Yii::t('app', 'Vol (m<sup>3</sup>)') ?></th>
								</tr>
                                <tr>
                                    <th><?= Yii::t('app', 'Ujung 1') ?></th>
                                    <th><?= Yii::t('app', 'Ujung 2') ?></th>
                                    <th><?= Yii::t('app', 'Pangkal 1') ?></th>
                                    <th><?= Yii::t('app', 'Pangkal 2') ?></th>
                                </tr>
							</thead>
						</table>
						</div>
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
<?php 
$this->registerJs(" 
	dtProduk(); formconfig();
	", yii\web\View::POS_READY); 
?>
<script>
function dtProduk(){
    var tr_seq = '<?= $tr_seq ?>';
    var kayu_id = '<?= $kayu_id ?>';
    var id = '<?= $id ?>';
    var edit = '<?= $edit ?>';
    var dt_table =  $('#table-produk').dataTable({
        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/modalNoLap']) ?>',
            type: 'POST',
            data:{dt: 'table-produk', tr_seq:tr_seq, kayu_id:kayu_id, id:id, edit:edit} 
        },
        order: [
            [2, 'asc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, 
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickNoLap(\""+data+"\"<?= (!empty($tr_seq)?',\"'.$tr_seq.'\"':""); ?>)' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {	targets: 3,  class:'text-align-center',
                render: function ( data, type, full, meta ) {
					return data?data:'-';
                }
            },
            {	targets: 4,  class:'text-align-center' },
            {	targets: 5,  class:'text-align-center' },
            {	targets: 6,  class:'text-align-center' },
            {	targets: 7,  class:'text-align-center' },
            {	targets: 8,  class:'text-align-right' },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>
