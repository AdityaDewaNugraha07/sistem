<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Keputusan Pembelian Log Alam'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th style="width: 150px;"><?= Yii::t('app', 'Kode<br>Cardpad'); ?></th>
									<th><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Nomor<br>Kontrak'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Volume<br>Kontrak m<sup>3</sup>'); ?></th>
									<th><?= Yii::t('app', 'Suplier'); ?></th>
									<th><?= Yii::t('app', 'Asal Kayu'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Volume<br>Pembelian m<sup>3</sup>'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Reviewed By<br>Kadiv Purchasing'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Reviewed By<br>Kadiv Marketing'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Reviewed By<br>GM Operasional'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Reviewed By<br>Direktur Utama'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Approved By<br>Owner'); ?></th>
									<th style="width: 90px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/pengajuanpembelianlog/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, 
                render: function ( data, type, full, meta ) {
                    <?php if(!empty($pick)){ ?>
                        if( (full[17]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[0]=='1') ){ // approve owner
                            var ret =  "<a onclick='pick(\""+data+"\",\""+full[19]+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        }else{
                            var ret =   "<a class='btn btn-xs btn-icon-only btn-default grey tooltips' data-original-title='Not Qualified' style='width: 25px; height: 25px;' disabled=disabled><i class='fa fa-plus-circle'></i></a>"+data;
                        }
                    <?php }else{ ?>
                        var ret = data;
                    <?php } ?>
                    
                    return ret;
                }
            }, 
            {   target: 2, class :"text-center",
                render: function ( data, type, full, meta) {
                    return '<center></center>';
                }
            },
			{ 	targets: 3, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
			{ 	targets: 4, class :"text-align-center", },
			{ 	targets: 5, class :"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            }, 
			{ 	targets: 8, class :"text-align-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            }, 
			{ 	targets: 9, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[14];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            }, 
			{ 	targets: 10, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[15];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            }, 
			{ 	targets: 11, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[16];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            }, 
			{ 	targets: 12, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[17];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            }, 
			{ 	targets: 13, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[18];
					if(status=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
						status = "<span class='font-green-seagreen'>"+status+"</span>";
					}else if(status=="<?= app\models\TApproval::STATUS_REJECTED ?>"){
						status = "<span class='font-red-flamingo'>"+status+"</span>";
					}
					if(status){
						return "<span style='font-size:1rem;'><b>"+data+"</b></span><br>"+status+"";
					}else{
						return "";
					}
                }
            }, 
			{	targets: 14, 
				render: function ( data, type, full, meta ) {
					var display = "";
                    <?php if(empty($pick)){ ?>
                        if((full[14]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[14]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[15]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[16]=="<?= app\models\TApproval::STATUS_APPROVED ?>") || (full[17]=="<?= app\models\TApproval::STATUS_APPROVED ?>")){
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
                        }else{
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
                        }
                        if((full[18]=="<?= app\models\TApproval::STATUS_APPROVED ?>")){
                            var print_html =  '<a style="cursors:pointer;" class="btn btn-xs btn-outline blue tooltips" data-original-title="Print PO" onclick="printoutPO('+full[0]+')"><i class="fa fa-print"></i></a>';
                        }else{
                            var print_html =  '<a style="" class="btn btn-xs btn-outline grey tooltips" data-original-title="Owner Belum Approve / Reject"><i class="fa fa-print"></i></a>';
                        }
                        var ret =  '<center>\n\
                                        '+print_html+'\n\
                                        '+display+'\n\
                                        <a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
                                    </center>';
                    <?php }else{ ?>
                        var ret = "";
                    <?php } ?>
					return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/index','pengajuan_pembelianlog_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/index','pengajuan_pembelianlog_id'=>'']); ?>'+id+'&edit=1');
}

</script>