<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Order Penjualan'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
                                <tr>
                                    <th><?= Yii::t('app', 'No.') ?></th>
                                    <th><?= Yii::t('app', 'Kode<br>Pengajuan') ?></th>
                                    <th><?= Yii::t('app', 'Tanggal') ?></th>
                                    <th><?= Yii::t('app', 'Keperluan') ?></th>
                                    <th><?= Yii::t('app', 'Status<br>Pengajuan') ?></th>
                                    <th><?= Yii::t('app', 'Keterangan') ?></th>
                                    <th><?= Yii::t('app', 'Status<br>Approve') ?></th>
                                    <th style="width: 65px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pengajuanmasterproduk/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
           {	targets: 0, visible: false },
           {	targets: 1, class:'td-kecil' },
           {	targets: 2, class:'text-align-center td-kecil',
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
           },
           {	targets: 3, class:'td-kecil' },
           {	targets: 4, class:'td-kecil text-align-center' },
           {	targets: 5, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    return data?data:'<center>-</center>';
                }
           },
           {	targets: 6, class:'td-kecil text-align-center',
                render: function ( data, type, full, meta ) {
                    if(full[7]){
                        status = '<span class="label label-sm label-default"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
                    } else {
                        if(data == "APPROVED"){
                            status = '<span class="label label-sm label-success">'+data+'</span>';
                        } else if(data == 'REJECTED'){
                            status = '<span class="label label-sm label-danger">REJECTED</span>';
                        } else {
                            status = '<span class="label label-sm label-warning">NOT CONFIRMED</span>';
                        }
                    }
                    return status;
                }
           },
           {	targets: 7, class:'td-kecil',
                orderable: false, class:"text-align-center",
                render: function ( data, type, full, meta ) {
                    if( (full[6]=="<?= app\models\TApproval::STATUS_APPROVED ?>")||(full[6]=="<?= app\models\TApproval::STATUS_REJECTED ?>") || full[7] ) {
						display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
                    }else{
                        display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="editPengajuan('+full[0]+')"><i class="fa fa-edit"></i></a>';
                    }
                    var ret =  '<center>\n\
									'+display+'\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatPengajuan('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
                    
                }
           },
            
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function lihatPengajuan(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/index','pengajuan_masterproduk_id'=>'']); ?>'+id);
}

function editPengajuan(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pengajuanmasterproduk/index','pengajuan_masterproduk_id'=>'']); ?>'+id+'&edit=1');
}

</script>