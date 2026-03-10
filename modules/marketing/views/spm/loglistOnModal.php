<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-loglist2" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Log List'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-produk">
							<thead>
								<tr>
									<th rowspan="2"></th>
									<th rowspan="2"><?= Yii::t('app', 'No. QR Code') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Kode Log') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Jenis Kayu') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Range Diameter') ?></th>
                                    <th colspan="3"><?= Yii::t('app', 'Nomor') ?></th>
                                    <th colspan="2"><?= Yii::t('app', 'Ukuran') ?></th>
                                    <th colspan="4"><?= Yii::t('app', 'Diameter (cm)') ?></th>
                                    <th colspan="3"><?= Yii::t('app', 'Unsur Cacat (cm)') ?></th>
                                    <th rowspan="2"><?= Yii::t('app', 'Volume<br>(m<sup>3</sup>)') ?></th>
								</tr>
                                <tr>
                                    <th><?= Yii::t('app', 'Lapangan') ?></th>
                                    <th><?= Yii::t('app', 'Grade') ?></th>
                                    <th><?= Yii::t('app', 'Batang') ?></th>
                                    <th><?= Yii::t('app', 'Diameter') ?></th>
                                    <th><?= Yii::t('app', 'Panjang') ?></th>
                                    <th><?= Yii::t('app', 'Ujung 1') ?></th>
                                    <th><?= Yii::t('app', 'Ujung 2') ?></th>
                                    <th><?= Yii::t('app', 'Pangkal 1') ?></th>
                                    <th><?= Yii::t('app', 'Pangkal 2') ?></th>
                                    <th><?= Yii::t('app', 'Panjang') ?></th>
                                    <th><?= Yii::t('app', 'Gubal') ?></th>
                                    <th><?= Yii::t('app', 'Growong') ?></th>
                                </tr>
							</thead>
						</table>
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
<?php $this->registerJs("  dtProduk(); formconfig();", yii\web\View::POS_READY); ?>
<script>
function dtProduk(){
    var dt_table =  $('#table-produk').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute(['/marketing/spm/logListOnModal','tr_seq'=>'']) ?><?= $tr_seq ?>&jns_produk=<?= $jns_produk ?>&data_log_nama=<?= $data_log_nama ?>',data:{dt: 'table-produk'} },
        order: [
            [2, 'asc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1,
				width:"180px",
                render: function ( data, type, full, meta ) {
					return "<a onclick='pickLogList(\""+data+"\"<?= (!empty($tr_seq)?',\"'.$tr_seq.'\"':""); ?>)' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                }
            },
            {
                targets: 2,
                render: function ( data, type, full, meta ) {
                    return full[21];
                }
            },
            {
                targets: 3,
                render: function ( data, type, full, meta ) {
                    return full[2] + ' - ' + data;
                }
            },
            {
                targets: 4,
                render: function ( data, type, full, meta ) {
                    if (full[5] != null || full[5] != null){
                        var range_diameter = full[5] + 'cm - ' + full[6] + 'cm';
                    } else {
                        var range_diameter = ' ';
                    }
                    return '<center>'+range_diameter+'</center>';
                }
            },
            {
                targets: 5,
                render: function( data, type, full, meta ){
                    <?php //$sql = "select no_lap from h_persediaan_log where no_barcode = '" ?>+full[1]+"'";
                    <?php //$no_lap = Yii::$app->db->createCommand($sql)->queryOne();  ?>;
                    //no_lap = <?php //echo $no_lap['no_lap'] ?>;
                    return '<center>'+full[8]+'</center>';
                    // return '<center>'+no_lap+'</center>';
                }
            },
            {
                targets: 6,
                render: function( data, type, full, meta ){
                    return '<center>'+full[9]+'</center>';
                }
            },
            {
                targets: 7,
                render: function( data, type, full, meta ){
                    return '<center>'+full[10]+'</center>';
                }
            },
            {
                targets: 8,
                render: function( data, type, full, meta ){
                    return '<center>'+full[11]+'</center>';
                }
            },
            {
                targets: 9,
                render: function( data, type, full, meta ){
                    return '<center>'+full[12]+'</center>';
                }
            },
            {
                targets: 10,
                render: function( data, type, full, meta ){
                    return '<center>'+full[14]+'</center>';
                }
            },
            {
                targets: 11,
                render: function( data, type, full, meta ){
                    return '<center>'+full[15]+'</center>';
                }
            },
            {
                targets: 12,
                render: function( data, type, full, meta ){
                    return '<center>'+full[16]+'</center>';
                }
            },
            {
                targets: 13,
                render: function( data, type, full, meta ){
                    return '<center>'+full[17]+'</center>';
                }
            },
            {
                targets: 14,
                render: function( data, type, full, meta ){
                    return '<center>'+full[18]+'</center>';
                }
            },
            {
                targets: 15,
                render: function( data, type, full, meta ){
                    return '<center>'+full[19]+'</center>';
                }
            },
            {
                targets: 16,
                render: function( data, type, full, meta ){
                    return '<center>'+full[20]+'</center>';
                }
            },
            {
                targets: 17,
                render: function( data, type, full, meta ){
                    return '<center>'+full[13]+'</center>';
                }
            },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
</script>