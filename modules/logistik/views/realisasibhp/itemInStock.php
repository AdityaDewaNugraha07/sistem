<?php

use app\models\MDepartement;

    app\assets\DatatableAsset::register($this); 
    app\assets\InputMaskAsset::register($this); 

    $modDepartement = MDepartement::findOne(['departement_id'=>Yii::$app->user->identity->pegawai->departement_id]);
?>
<div class="modal fade" id="modal-master-produk" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
				<!--<a class="btn btn-icon-only btn-default tooltips" onclick="create()" data-original-title="Create New" style="float: right; margin-right: 5px;"><i class="fa fa-plus"></i></a>-->
                <h4 class="modal-title"><?= Yii::t('app', 'Item In Stock Budget Bahan Pembantu'); ?><?= "<br>Departement : ".$modDepartement->departement_nama; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th><?= Yii::t('app', 'ID Item') ?></th>
									<th></th>
									<th></th>
                                    <th><?= Yii::t('app', 'Nama Item') ?></th>
                                    <th><?= Yii::t('app', 'Target Plan') ?></th>
                                    <th><?= Yii::t('app', 'Target Peruntukan') ?></th>
									<th><?= Yii::t('app', 'QTY') ?></th>
									<th></th>
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
	dtProduk();
	$('div.dataTables_filter input').addClass('autofocus');
	$('#modal-master-produk').on('shown.bs.modal', function () {
	    $('.input-sm').focus();
	})  
	", yii\web\View::POS_READY); 
?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/itemInStock']) ?>',data:{dt: 'table-produk'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0},
            {	targets: 1, visible: false},
			{	targets: 2, visible: false},
            {	targets: 3,className: 'dt-body-center'},
            {	targets: 4,className: 'dt-body-center'},
            {	targets: 5,className: 'dt-body-center'},
            {	targets: 6,className: 'dt-body-center'},
			{	targets: 7,
				className: 'dt-body-center',
				width: '50px',
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickItem("+full[2]+"<?= (!empty($tr_seq)?",".$tr_seq:""); ?>,\""+data+"\","+full[1]+")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>";
                }
            },           
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>
