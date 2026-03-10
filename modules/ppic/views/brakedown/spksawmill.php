<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'SPK Sawmill'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-spk">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?= Yii::t('app', 'Kode') ?></th>
                                    <th><?= Yii::t('app', 'Revisi') ?></th>
                                    <th><?= Yii::t('app', 'Tgl Mulai') ?></th>
                                    <th><?= Yii::t('app', 'Tgl Selesai') ?></th>
                                    <th><?= Yii::t('app', 'Kode PO') ?></th>
                                    <th><?= Yii::t('app', 'Peruntukan') ?></th>
                                    <th><?= Yii::t('app', 'Line') ?></th>
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
    var id = '<?= $id ?>';
    var edit = '<?= $edit ?>';
    var dt_table =  $('#table-spk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/ppic/brakedown/openSPK']) ?>',
                type: 'POST',
                data:{dt: 'table-spk', id: id, edit:edit} 
              },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {   targets: 0, visible: false },
            {   targets: 1, class:'td-kecil',
                render: function ( data, type, full, meta ) {
					return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>" + data;
                }
            },
            {   targets: 2, class:'text-align-center td-kecil' },
			{ 	targets: 3, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    let date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 4, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    let date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 5, class:'text-align-center td-kecil' },
            {   targets: 6, class:'text-align-center td-kecil' },
            {   targets: 7, class:'text-align-center td-kecil' },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}
</script>
