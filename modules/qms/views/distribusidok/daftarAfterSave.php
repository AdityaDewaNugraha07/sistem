<?php app\assets\DatatableAsset::register($this); ?>
<style>
.td-kecil4{
    line-height: 1 !important;
    padding: 3px !important;
    vertical-align: top !important;
    font-size:1.2rem !important;
} 
</style>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Distribusi Dokumen'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
                                    <th><?= Yii::t('app', 'Nomor Dokumen'); ?></th>
                                    <th><?= Yii::t('app', 'Revisi'); ?></th>
									<th><?= Yii::t('app', 'Nama Dokumen'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Dikirim'); ?></th>
									<th><?= Yii::t('app', 'Dikirim Oleh'); ?></th>
                                    <th><?= Yii::t('app', 'Penerima Dokumen'); ?></th>
									<th style="width: 120px;"></th>
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
    dtTable();
	formconfig(); 
", yii\web\View::POS_READY); ?>
<script>
function dtTable(){
    var dt_table =  $('#table-aftersave').dataTable({
        ajax: { 
            url: '<?= \yii\helpers\Url::toRoute('/qms/distribusidok/daftarAfterSave') ?>', 
            data:{dt: 'modal-aftersave'} 
        },
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class:'td-kecil4' },
            {	targets: 2, class:'text-align-center td-kecil4' },
            {	targets: 3, class:'td-kecil4' },
            {	targets: 4, class:'text-align-center td-kecil4',
                render: function(data) {
                    // var date = new Date(data);
					// date = date.toString('dd/MM/yyyy HH:mm:ss');
                    var date = ubahDateUser(data);
					return date;
                }
            },
            {	targets: 5, class:'text-align-center td-kecil4'},
            {	targets: 6, class:'td-kecil4', orderable: false },
            {	targets: 7, class:'text-align-center td-kecil4', orderable: false }, 
            
        ],
        "autoWidth":false,
        "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function lihatDetail(id, tgl){
    // var date = new Date(tgl);
	// date = date.toString('yyyy-mm-dd-HH-mm-ss');
    var date = ubahDate(tgl);
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date);
}
function edit(id, tgl){
    // var date = new Date(tgl);
	// date = date.toString('yyyy-mm-dd-HH-mm-ss');
    var date = ubahDate(tgl);
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/qms/distribusidok/index','dokumen'=>'']); ?>'+id+'&tgl_kirim='+date+'&edit=1');
}

function ubahDate(tgl){
    const date = new Date(tgl);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day}-${hours}-${minutes}-${seconds}`;
}

function ubahDateUser(tgl){
    const date = new Date(tgl);
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}
</script>