<?php app\assets\DatatableAsset::register($this); 
use yii\helpers\Url;?>
<div class="modal fade" id="modal-scanned" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Scanned Log dari Pemotongan Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <span id="msg" class="text-bold" style="position: absolute; right: 20px; color: #f00; font-weight: bold; display: none;">Data berhasil dihapus</span>
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
                                    <th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'No. QRcode'); ?></th>
									<th><?= Yii::t('app', 'Kayu'); ?></th>
									<th><?= Yii::t('app', 'PIC Terima'); ?></th>
									<th><?= Yii::t('app', 'No. Grade'); ?></th>
									<th><?= Yii::t('app', 'No. Batang'); ?></th>
									<th><?= Yii::t('app', 'No. Lap'); ?></th>
									<th><?= Yii::t('app', 'Volume'); ?></th>
                                    <th><?= Yii::t('app', 'Status FSC'); ?></th> 
									<th><?= Yii::t('app', 'Lokasi'); ?></th>
									<th><?= Yii::t('app', ''); ?></th>
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
    dtScanned();
", yii\web\View::POS_READY); ?>
<script>
function dtScanned(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/scanterimaloghasilpotong/daftarScanned') ?>',data:{dt: 'modal-scanned'} },
        order: [
            [0, 'desc']
        ],
        autoWidth:false,
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(full[1]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 3, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 4, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 5, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 6, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 7, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 8, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 9, class:"text-align-center td-kecil", // TAMBAH FSC
                render: function ( data, type, full, meta ) {
					return data?'FSC 100%':'Non FSC';
                }
            },
            {	targets: 10, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					return data;
                }
            },
            {	targets: 11, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    <?php
                    $user_id = $_SESSION['__id'];
                    $sql = "select user_group_id from m_user where user_id = ".$user_id."";
                    $user_group_id = Yii::$app->db->createCommand($sql)->queryScalar();
                    if ($user_group_id == 1) {
                    ?>
                    // TAMBAH FSC - ubah field karna query tambah kolom fsc
                    var ret = '<center>\n\
                                    <a class="btn btn-xs btn-outline dark" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
                                    <a class="btn btn-xs btn-outline dark" id="print-btn-this" onclick="window.open(\'<?= Url::to(["/ppic/pemotonganlog/print", "id" => "" ]) ?>' + full[11]+'&no_barcode='+ full[2] + '&caraprint=PRINT\', \'Print Barcode\', \'width=1200\')"><i class="fa fa-qrcode"></i></a>\n\
                                    <a class="btn btn-xs btn-outline btn-danger tooltips" style="margin-right: 0px;" data-original-title="Hapus Detail" onclick="confirmHapusDetail('+full[0]+')"><i class="fa fa-trash-o"></i></a>\n\
                                </center>';
                    return ret;
                    <?php
                    } else {
                    ?>
                    var ret =  '<center>\n\
                                    <a class="btn btn-xs btn-outline dark" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
								</center>';
                    return ret;
                    <?php
                    }
                    ?>
                }
            },

        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimaloghasilpotong/lihatDetail','id'=>'']) ?>'+id,'modal-madul','95%');
}

function confirmHapusDetail(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/ppic/scanterimaloghasilpotong/confirmHapusDetail','id'=>'']) ?>'+id,'modal-confirm','250px');
}
</script>