<?php

use app\models\MDepartement;

	app\assets\DatatableAsset::register($this); 
	$modDepartement = MDepartement::findOne(['departement_id'=>Yii::$app->user->identity->pegawai->departement_id]);
?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Realisasi Pemakaian Budget Bahan Pembantu');?> <?= "<br>Departement : ".$modDepartement->departement_nama; ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>									
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Departement'); ?></th>
									<th><?= Yii::t('app', 'Item'); ?></th>
									<th><?= Yii::t('app', 'QTY'); ?></th>
									<th><?= Yii::t('app', 'Plan'); ?></th>
									<th><?= Yii::t('app', 'Perutukan'); ?></th>
									<th><?= Yii::t('app', 'Realisasi<br>Peruntukan'); ?></th>
									<th><?= Yii::t('app', 'Reff No'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<th></th>
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
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/realisasibhp/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ targets: 11 ,className: 'dt-body-center',
				// width: '40px',
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(full[11] != null){
						 ret = '<a class="btn btn-xs btn-outline red-flamingo" style="font-size: 1rem;baground-color:red;">Aborted</a>';
					}else{
						ret =  '<center>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
								// <a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
					}
					return ret;	
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"createdRow": function ( row, data, index ) {
            if(data[9]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/realisasibhp/index','pemakaian_bhpsub_id'=>'']); ?>'+id);
}

</script>