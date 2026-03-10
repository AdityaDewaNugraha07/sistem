<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat SPM'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th><?= Yii::t('app', 'Kode'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Tanggal SPM'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
                                    <th style="width: 75px;"><?= Yii::t('app', 'Nopol'); ?></th>
									<th><?= Yii::t('app', 'Nama Supir'); ?></th>
									<th><?= Yii::t('app', 'Alamat Bongkar'); ?></th>
									<th></th>
									<th><?= Yii::t('app', 'Status'); ?></th>
									<th style="width: 75px;"></th>
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
	formconfig();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 3, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            <?php if( (\Yii::$app->user->identity->user_group_id == \app\components\Params::USER_GROUP_ID_SUPER_USER) || (\Yii::$app->user->identity->pegawai_id == \app\components\Params::DEFAULT_PEGAWAI_ID_FITRIYANAH) ){ ?>
			{ 	targets: 6, class: "td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(!full[11]){
                        ret = '<a onclick="editKecil('+full[0]+')" class="tooltips" data-original-title="Edit Nopol" style="border-bottom: 1px dotted #000;">'+data+'</a>';
                    }
                    return ret;
                }
            },
			{ 	targets: 7, class: "td-kecil",
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(!full[11]){
                        ret = '<a onclick="editKecil('+full[0]+')" class="tooltips" data-original-title="Edit Nopol" style="border-bottom: 1px dotted #000;">'+data+'</a>';
                    }
                    return ret;
                }
            },
//            <?php } ?>
			{	targets: 9, visible: false },
			{	targets: 10, 
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(data){
						if(data == "<?= app\models\TSpmKo::REALISASI ?>"){
							ret = "<span class='label label-sm label-success'> "+data+" </span>";
						}
					}else if(full[9]){
						ret = "<span class='label label-sm label-danger'>ABORTED</span>";
					}
					return ret;
				}
			},
			{	targets: 11, 
				width:"75px",
				render: function ( data, type, full, meta ) {
					var display = "";
					if(full[9] || full[11]){
						display =  'visibility: hidden;';
					}
//					if(full[10]){
//						if(full[10] == "<?php // echo app\models\TSpmKo::REALISASI ?>"){
//							display =  'visibility: hidden;';
//						}
//					}
					var ret =  '<center>\n\
									<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Update" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/marketing/spm/index','spm_ko_id'=>'']); ?>'+id);
}

</script>