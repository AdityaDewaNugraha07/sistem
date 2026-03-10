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
									<th><?= Yii::t('app', 'Kode SPM'); ?></th>
									<th><?= Yii::t('app', 'Jenis Produk'); ?></th>
									<th><?= Yii::t('app', 'Contract No.'); ?></th>
									<th><?= Yii::t('app', 'Packinglist No.'); ?></th>
									<th><?= Yii::t('app', 'Cont. No.'); ?></th>
									<th><?= Yii::t('app', 'Tanggal SPM'); ?></th>
									<th><?= Yii::t('app', 'Customer'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Kirim'); ?></th>
									<th><?= Yii::t('app', 'Nopol'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/exim/spmexport/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:"td-kecil" },
            {	targets: 2, class:"td-kecil" },
            {	targets: 3, class:"td-kecil" },
            {	targets: 4, class:"td-kecil" },
			{ 	targets: 5, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return '<center>'+full[13]+'</center>';
                }
            },
			{ 	targets: 6, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(full[5]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 7, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return full[6];
                }
            },
			{ 	targets: 8, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(full[7]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 9, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return full[8];
                }
            },
			{ 	targets: 10, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return full[9];
                }
            },
			{ 	targets: 11, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return full[10];
                }
            },
			{	targets: 12, visible: false, class:"td-kecil" },
			{	targets: 13, class:"td-kecil",
				render: function ( data, type, full, meta ) {
					var ret = "";
					if(full[12]){
						if(full[12] == "<?= app\models\TSpmKo::REALISASI ?>"){
							ret = "<span class='label label-sm label-success'> "+full[12]+" </span>";
						}
					}else if(full[11]){
						ret = "<span class='label label-sm label-danger'>ABORTED</span>";
					}
					return ret;
				}
			},
			{	targets: 14, class:"td-kecil",
				width:"75px",
				render: function ( data, type, full, meta ) {
					var display = "";
					if(full[11]){
						display =  'visibility: hidden;';
					}
					if(full[12]){
						if(full[12] == "<?php echo app\models\TSpmKo::REALISASI ?>"){
							display =  'visibility: hidden;';
						}
					}
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
            if(data[11]){
				$(row).addClass("cancelBackground");
			}
        }
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/exim/spmexport/index','spm_ko_id'=>'']); ?>'+id);
}

</script>