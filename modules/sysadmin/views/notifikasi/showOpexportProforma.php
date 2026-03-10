<?php app\assets\DatatableAsset::register($this); ?>
<?php app\assets\InputMaskAsset::register($this); ?>
<?php 
/*
pastikan div id modal adalah id yang dipanggil di index.php
*/
$jenisProduk = "";
if($id == 5){
   $jenisProduk = " Moulding"; 
}elseif($id == 98 ){
   $jenisProduk = " Plywood, Lamineboard, Platform"; 
}
?>
<div class="modal fade" id="modal-notif" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'OP Export Yang Belum Diterbitkan Proforma Packinglist Untuk Produk ');?><?= $jenisProduk ?> </h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <?php // KONTEN MODAL ?>
                    <div id="yyy" class="portlet-body" id="ajax" style="padding-left: -15px; padding-right: -15px;">
                        <div class="table-scrollable">
                            <table class="table table-striped table-bordered table-hover table-laporan" id="table-notif">
                                <thead>
                                        <tr>
                                                <th></th>
                                                <th style="width: 150px; line-height: 1;"><?= Yii::t('app', 'Nomor<br>Kontrak') ?></th>
                                                <th style="width: 150px; line-height: 1;"><?= Yii::t('app', 'Nomor<br>Order') ?></th>
                                                <th style="width: 100px;"><?= Yii::t('app', 'Tanggal') ?></th>
                                                <th style="width: 200px;"><?= Yii::t('app', 'Applicant') ?></th>
                                                <th></th>
                                                <th style="width: 200px;"><?= Yii::t('app', 'Notify') ?></th>
                                                <th></th>
                                                <th><?= Yii::t('app', 'Goods Description') ?></th>
                                                <th style="width: 50px;"><?= Yii::t('app', 'Payment<br>Method') ?></th>
                                        </tr>
                                </thead>
                            </table>
                            
                        </div>
                    </div>
                    <?php /* EO KONTEN MODAL */ ?>
                </div>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>

<?php $this->registerJs(" dtNotif();", yii\web\View::POS_READY); ?>
<script>
function dtNotif(){
    var dt_table =  $('#table-notif').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/ppic/proforma/OpexblmProforma?id='.$id.'')?>',data:{dt: 'table-notif'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{	targets: 1, class:"td-kecil"},
			{ 	targets: 2, class : "text-align-center td-kecil" },
			{ 	targets: 3, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 4, class:"td-kecil", 
                render: function ( data, type, full, meta ) {
					return data+" "+full[5];
                }
            },
			{	targets: 5, visible: false },
			{ 	targets: 6, class:"td-kecil",
                render: function ( data, type, full, meta ) {
					if(data){
						return data+" "+full[5];
					}else{
						return "<center>-</center>";
					}
                }
            },
			{	targets: 7, visible: false },
			{	targets: 8, class:"td-kecil" },
			{	targets: 9, class:"td-kecil text-align-center" },
        ],
		"autoWidth":false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

</script>