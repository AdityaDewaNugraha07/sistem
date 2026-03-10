<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Pemotongan lOG'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="line-height: 1; width: 110px;"><?= Yii::t('app', 'Kode<br>Pemotongan'); ?></th>
									<th style="line-height: 1; width: 130px;"><?= Yii::t('app', 'Nomor'); ?></th>
									<th style="line-height: 1; width: 110px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="line-height: 1; width: 200px;"><?= Yii::t('app', 'Petugas'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'Peruntukan'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'No. Barcode'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'Jenis Kayu'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'Panjang<br>(cm)'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)'); ?></th>
                                    <th style="line-height: 1; width: 110;"><?= Yii::t('app', 'Jumlah<br>Potong'); ?></th>
									<th style="line-height: 1;"><?= Yii::t('app', 'Keterangan'); ?></th>
									<th style="width: 70px;"></th>
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
<?php $this->registerJs("
    dtAfterSave();
", yii\web\View::POS_READY); ?>
<script>
function dtAfterSave(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/pemotonganlog/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:"text-align-center td-kecil",  },
            {	targets: 2, class:"text-align-center td-kecil" },
			{ 	targets: 3, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
            {	targets: 4, class:"td-kecil" },
            {	targets: 5, class:"td-kecil" },
            {	targets: 6, class:"td-kecil" },
            { 	targets: 7, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					return '<center>'+data + ' - ' + full[12] +'</center>';
                }
            }, 
            {   targets: 8, class:"text-align-right td-kecil"},
            {   targets: 9, class:"text-align-right td-kecil"},
            {   targets: 10, class:"text-align-center td-kecil"},
            {   targets: 11, class:"td-kecil"},
            {	targets: 12, 
                render: function ( data, type, full, meta ) {
                    var display = "";
					var ret =  '<center>\n\
									<a style="" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
					return ret;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/index','pemotongan_log_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/ppic/pemotonganlog/index','pemotongan_log_id'=>'']); ?>'+id+'&edit=1');
}
</script>