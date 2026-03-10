<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Cari Voucher Pengeluaran'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 180px;"><?= Yii::t('app', 'Tipe Voucher'); ?></th>
									<th style="width: 50px;"><?= Yii::t('app', 'Kode Voucher'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th><?= Yii::t('app', 'Keterangan'); ?></th>
									<th><?= Yii::t('app', 'Total Nominal'); ?></th>
									<th><?= Yii::t('app', 'Status Bayar'); ?></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
									<th></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/finance/voucher/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            {	targets: 1, class: 'td-kecil' },
            {	targets: 2, "width": "20%" },
            {	targets: 3, class: 'td-kecil' },
            {	targets: 4, 
				class: 'td-kecil',
				render: function ( data, type, full, meta ) {
					var ret='<center>-</center>';
					if(full[4]){
						ret=full[4];
					}else if(full[9]){
						ret="<a onclick='gkk("+full[8]+")'>"+full[9]+"</a>";
					}else if(full[11]){
						ret="<a onclick='ppk("+full[10]+")'>"+full[11]+"</a>";
					}else if(full[13]){
						ret="<a onclick='ajuanDinas("+full[12]+")'>"+full[13]+"</a>";
					}else if(full[15]){
						ret="<a onclick='ajuanMakan("+full[14]+")'>"+full[15]+"</a>";
					}else if(full[17]){
						ret="<a onclick='infoAjuanDp("+full[16]+")'>"+full[17]+"</a>";
					}else if(full[19]){
						ret="<a onclick='infoPelunasan("+full[18]+")'>"+full[19]+"</a>";
					}else if(full[20]){
						ret=full[20];
					}else if(full[21]){
						ret=full[20]+" ("+full[21]+")";
					}else if(full[22]){
                        if(full[22]=="DP LOG SENGON"||full[22]=="PELUNASAN LOG SENGON"){
                            ret='<b>'+full[22]+'</b><br>'+full[24];
                        }else{
                            ret='<b>'+full[22]+'</b><br>'+full[23];
                        }
					}
					return ret;
				}
			},
            {	targets: 5, class: 'dt-body-right td-kecil', 
				render: function ( data, type, full, meta ) {
                    var ret =  'Rp. '+data;
                    return ret;
                }
			},
            {	targets: 6, className: 'dt-body-center td-kecil', 
				render: function ( data, type, full, meta ) {
					if(full[7]){
						var ret =  '<span class="label label-sm label-danger"><?= \app\models\TCancelTransaksi::STATUS_ABORTED; ?></span>';
					}else{
						if(data == "PAID"){
							var ret =  '<span class="label label-success">'+data+'<span>';
						}else{
							var ret =  '<span class="label label-warning" style="cursor:pointer" onclick="changeStatus('+full[0]+')">'+data+'<span>';
						}
					}
					
                    return ret;
                } 
			},
			{	targets: 7, visible: false },
            {	targets: 8, 
				class:'td-kecil',
                render: function ( data, type, full, meta ) {
					if(full[6] == "UNPAID" && !full[7]){
						var tbl_edit = '<a class="btn btn-xs btn-outline blue-hoki" data-original-title="Ubah data voucher" onclick="editVoucher('+full[0]+')"><i class="fa fa-edit"></i></a>';
					}else{
						var tbl_edit = '';
					}
                    var ret =  '<center><a class="btn btn-xs btn-outline dark" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>'+tbl_edit+'</center>';
                    return ret;
                }
            },
			{	targets: 9, visible: false },
			{	targets: 10, visible: false },
			{	targets: 11, visible: false },
			{	targets: 12, visible: false },
			{	targets: 13, visible: false },
			{	targets: 14, visible: false },
			{	targets: 15, visible: false },
			{	targets: 16, visible: false },
			{	targets: 17, visible: false },
			{	targets: 18, visible: false },
			{	targets: 19, visible: false },
			{	targets: 20, visible: false },
			{	targets: 21, visible: false },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/finance/voucher/index','voucher_pengeluaran_id'=>'']); ?>'+id);
}

</script>