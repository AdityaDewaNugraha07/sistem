<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Permintaan Pembelian Log yang sudah dibuat'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 110px;"><?= Yii::t('app', 'Kode'); ?></th>
									<th style="width: 85px;"><?= Yii::t('app', 'Tanggal'); ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Jenis Log'); ?></th>
									<th style="width: 100px;"><?= Yii::t('app', 'Kebutuhan'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></th>
                                    <th style="width: 100px;"><?= Yii::t('app', 'Total Qty'); ?></th>
									<th><?= Yii::t('app', 'Dibuat Oleh'); ?></th>
									<th><?= Yii::t('app', 'Approver 1'); ?></th>
									<th><?= Yii::t('app', 'Approver 2'); ?></th>
									<th><?= Yii::t('app', 'Approver 3'); ?></th>
									<th><?= Yii::t('app', 'Approver 4'); ?></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/pmr/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, 
                render: function ( data, type, full, meta ) {
                    <?php if(!empty($pick)){ ?>
                        if(full[13]=="<?= app\models\TApproval::STATUS_APPROVED ?>"){ // approve owner
                            var ret =  "<a onclick='pick(\""+data+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        }else{
                            var ret =   "<a class='btn btn-xs btn-icon-only btn-default grey tooltips' data-original-title='Not Qualified' style='width: 25px; height: 25px;' disabled=disabled><i class='fa fa-plus-circle'></i></a>"+data;
                        }
                    <?php }else{ ?>
                        var ret = data;
                    <?php } ?>
                    return ret;
                }
            }, 
			{ 	targets: 2, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            }, 
			{ 	targets: 3, class :"text-align-left", 
                render: function ( data, type, full, meta ) {
                    var ret = "";
					if(data == "LA"){
                        ret = "LOG ALAM";
                    }else if(data == "LS"){
                        ret = "LOG SENGON";
                    }
					return ret;
                }
            },
            { 	targets: 4, class :"text-align-center", },
            { 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+data+'</center>';
                }
            }, 
            { 	targets: 6, class :"text-align-right", 
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data)+" M<sup>3</sup>";
                }
            },
            { 	targets: 7, class :"text-align-left", },
			{ 	targets: 8, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[12];
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
			{ 	targets: 9, class :"text-align-center td-kecil2",
                render: function ( data, type, full, meta ) {
					var status = full[13];
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
			{ 	targets: 11, class :"text-align-center td-kecil2",
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
			{	targets: 12, class:"",
				render: function ( data, type, full, meta ) {
					var display = "";
                    <?php if(empty($pick)){ ?>
                        var disp_rencana = "";
                        if((full[14]=="<?= app\models\TApproval::STATUS_APPROVED ?>")){
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
                        }else{
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
                        }
                        if(full[3]=="LS"){
                            disp_rencana = '<a style="margin-left: -5px;" class="btn btn-xs btn-outline blue tooltips" data-original-title="Lihat Rencana PO" onclick="lihatRencanaPO('+full[0]+')"><i class="icon-basket-loaded"></i></a>';
                        }
                        var ret =  '<center>\n\
                                        '+disp_rencana+'\n\
                                        '+display+'\n\
                                        <a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat Permintaan Pembelian" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
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
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/index','pmr_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/pmr/index','pmr_id'=>'']); ?>'+id+'&edit=1');
}
function lihatRencanaPO(id){
    openModal('<?= \yii\helpers\Url::toRoute(['/purchasinglog/posengon/lihatRencanaPO']) ?>','modal-aftersave','90%');
}
</script>