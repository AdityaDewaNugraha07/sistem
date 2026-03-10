<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-daftar-terimabhp" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Penerimaan Bahan Pembantu'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
						<div class="table-scrollable">
							<table class="table table-striped table-bordered table-hover table-laporan" id="table-terimabhp">
								<thead>
									<tr>
										<th></th>
										<th style="width: 120px;"><?= Yii::t('app', 'Kode<br>Penerimaan'); ?></th>
										<th style="width: 130px;"><?= Yii::t('app', 'Kode SPL'); ?></th>
										<th style="width: 130px;"><?= Yii::t('app', 'Kode PO'); ?></th>
										<th style="width: 80px;"><?= Yii::t('app', 'Tgl Terima'); ?></th>
										<th style=""><?= Yii::t('app', 'Supplier'); ?></th>
										<th style="width: 80px;"><?= Yii::t('app', 'No. Invoice'); ?></th>
										<th style="width: 80px;"><?= Yii::t('app', 'No. Faktur'); ?></th>
										<th style="width: 80px;"><?= Yii::t('app', 'No. Surat Jalan'); ?></th>
										<th style="width: 50px;"><?= Yii::t('app', 'Cancel'); ?></th>
										<th style="width: 80px;"><?= Yii::t('app', 'Voucher<br>Pengeluaran'); ?></th>
										<th style="width: 100px; line-height: 1;"><?= Yii::t('app', 'Total<br>Bayar'); ?></th>
										<th style="width: 50px;"></th>
									</tr>
								</thead>
							</table>
						</div>
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
<?php

$this->registerCss("
    .td-kecil {
        font-size: 1.1rem !important;
    }
");
$this->registerJs("
    dtTerimabhp();
", yii\web\View::POS_READY); ?>
<script>
function dtTerimabhp(){
    var dt_table =  $('#table-terimabhp').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/logistik/penerimaanbhp/daftarTerimaBhp') ?>',data:{dt: 'table-terimabhp'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:'td-kecil', },
            {	targets: 2, class:'td-kecil', },
            {	targets: 3, class:'td-kecil', },
            {	targets: 4, class:'td-kecil' },
            {	targets: 5, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(!data){
                        ret = '<center> - </center>';
                    }
                    return ret;
                }
            },
            {	targets: 6, class:'td-kecil', },
            {	targets: 7, class:'td-kecil', },
            {	targets: 8, class:'td-kecil', },
            {	targets: 9, class:'td-kecil',
                render: function ( data, type, full, meta ) {
                    var ret = data;
                    if(data){
                        var ret = '<span class="label label-sm label-danger" style="font-size: 1rem; padding: 1px 3px;"><?= \app\models\TCancelTransaksi::STATUS_ABORTED ?></span>';
                        return ret;
                    } else {
                        var status_approval = full[13];
                        if (status_approval == 'REJECTED') {
                            return '<span class="label label-sm label-danger" style="font-size: 1rem; padding: 1px 3px;">REJECTED</span>';
                        } else if (status_approval == 'Not Confirmed') {
                            return '<span class="label label-sm label-default" style="font-size: 1rem; padding: 1px 3px;">'+status_approval+'</span>';
                        } else {
                            return '<span class="label label-sm label-success" style="font-size: 1rem; padding: 1px 3px;">'+status_approval+'</span>';
                        }
                        return status_approval;
                    }
                }
            },
            {	targets: 10,
				class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    let ret = '-';
                    if(data){
                        let date = new Date(full[11]);
                        const text = full[10] ? full[10] : 'UNPAID';
                        date = date.toString('dd/MM/yyyy');
                        const tgl = '<br><span style="font-size:1.1rem">' + date + '</span>';
                        ret = '<b>'+ text +'</b>'+tgl;
                    }
                    return ret;
                }
            },
			{	targets: 11,
                width: '50px', class:'text-align-right td-kecil',
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(full[12]);
                }
            },
            {	targets: 12,
                width: '50px',
                render: function ( data, type, full, meta ) {
                    var ret =  '<center><a class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat detail " onclick="lihatPenerimaan('+full[0]+')"><i class="fa fa-eye"></i></a></center>';
                    return ret;
                }
            },
            
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        'autoWidth':false
    });
}
function lihatPenerimaan(terima_bhp_id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/logistik/penerimaanbhp/index','terima_bhp_id'=>'']); ?>'+terima_bhp_id);
}
</script>