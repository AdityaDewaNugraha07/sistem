<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftarLogKeluar" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Log Keluar'); ?></h4>
                <h6 class="text-danger">** Perhatian ..!! Tombol Hapus hanya bisa digunakan dihari yang sama (sesuai dengan tanggal transaksi)</h6>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-right text-danger" style="position: absolute; right: 20px; z-index: 9999; top: 12px; font-weight: bold;" id="msg"></div>
                        <table class="table table-striped table-bordered table-hover" id="table-daftarLogKeluar">
							<thead>
								<tr>
									<th></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'No. QRcode'); ?></th>
									<th class="td-kecil" style="width: 120px;"><?= Yii::t('app', 'Jenis Peruntukan'); ?></th>
									<th class="td-kecil" style="width: 100px;"><?= Yii::t('app', 'No. Referensi'); ?></th>
									<th class="td-kecil" style="width: 200px;"><?= Yii::t('app', 'Keterangan'); ?></th>
									<th class="td-kecil" style="width: 200px;"><?= Yii::t('app', 'PIC'); ?></th>
                                    <?php
                                    if ($user_group_id == 1) {
                                    ?>
                                    <th class="td-kecil" style="width: 80px;"><?= Yii::t('app', 'Hapus'); ?></th>
                                    <?php
                                    }
                                    ?>
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
    daftarLogKeluars();
", yii\web\View::POS_READY); ?>
<script>
function daftarLogKeluars(){
    var dt_table =  $('#table-daftarLogKeluar').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/logkeluar/daftarLogKeluar') ?>',data:{dt: 'modal-daftarLogKeluar'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"td-kecil text-center",},
			{	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:"td-kecil text-center"},
            {	targets: 4, class:"td-kecil text-center",
                render: function ( data, type, full, meta) {
                    return full[4];
                }
            },
            {	targets: 5, class:"td-kecil text-center"},
            {	targets: 6, class:"td-kecil"},
            {	targets: 7, class:"td-kecil"},
            {	targets: 8, class:"td-kecil, text-center",
                render: function ( data, type, full, meta) {
                    var log_keluar_id = full[0];
                    var dateini = moment().format('DD/MM/YYYY'); // Format tanggal hari ini
                    var date = moment(full[2]).format('DD/MM/YYYY'); // Format tanggal dari data
                    var display = "";
                    if(dateini === date) {
                            display = '<a class="btn btn-xs btn-outline btn-danger tooltips" style="margin-right: 0px;" data-original-title="Hapus Detail" onclick="confirmHapusDetail('+full[0]+')"><i class="fa fa-trash-o"></i></a>';	
                    }
                    var ret =  '<center>'+ display +'</center>';
                    return ret;
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function confirmHapusDetail(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/logkeluar/confirmHapusDetail','id'=>'']) ?>'+id,'modal-confirm','250px');
}
</script>
