<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-permintaanlog" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Pilih Permintaan Pembelian Log'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
									<th style="width: 150px; line-height: 1"><?= Yii::t('app', 'Kode<br>Permintaan'); ?></th>
                                    <th style="line-height: 1"><?= Yii::t('app', 'Tanggal<br>Permintaan'); ?></th>
									<th><?= Yii::t('app', 'Jenis Log'); ?></th>
									<th><?= Yii::t('app', 'Kebutuhan'); ?></th>
									<th><?= Yii::t('app', 'Tanggal Dibutuhkan'); ?></th>
                                    <th><?= Yii::t('app', 'Total Qty'); ?></th>
									<th><?= Yii::t('app', 'Dibuat Oleh'); ?></th>
									<th><?= Yii::t('app', 'Approver 1'); ?></th>
									<th><?= Yii::t('app', 'Approver 2'); ?></th>
									<th><?= Yii::t('app', 'Approver 3'); ?></th>
									<th style="width: 120px;">Kode Reff</th>
									<th style="width: 80px;">Status</th>
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
        ajax: { url: '<?= (empty($url)?\yii\helpers\Url::toRoute('/purchasinglog/pengajuanpembelianlog/openpermintaanlog'):$url) ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
			{ 	targets: 1, 
                render: function ( data, type, full, meta ) {
                    <?php if(Yii::$app->controller->id == "posengon"){ ?>
                        var asd = full[12];  // approve level 2
                    <?php }else{ ?>
                        var asd = full[13];  // approve owner
                    <?php } ?>
                        
                    if(asd=="<?= app\models\TApproval::STATUS_APPROVED ?>" && (full[15]=="OPEN") ){
                        var ret =  "<a onclick='pick(\""+data+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                    }else{
                        var ret =   "<a class='btn btn-xs btn-icon-only btn-default grey tooltips' data-original-title='Not Qualified' style='width: 25px; height: 25px;' disabled=disabled><i class='fa fa-plus-circle'></i></a>"+data;
                    }
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
			{ 	targets: 3, class :"text-align-center", 
                render: function ( data, type, full, meta ) {
                    var ret = "";
					if(data == "LA"){
                        ret = "LOG ALAM";
                    }else if(data == "LS"){
                        ret = "LOG SENGON";
                    }
					return '<center>'+ret+'</center>';
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
					var status = full[11];
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
			{ 	targets: 10, class :"text-align-center td-kecil2",
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
			{	targets: 11, class :"text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
                    var ret = "";
					if(full[14]){
                        var asd = $.parseJSON(full[14]);
                        <?php if(Yii::$app->controller->id == "posengon"){ ?>
                            ret += "<span class='td-kecil2'>Rencana PO Sengon : </span><br>";
                        <?php }else{ ?>
                            ret += "<span class='td-kecil2'>Keputusan Pembelian : </span><br>";
                        <?php } ?>
                        $.each(asd,function(key,val){
                            ret += "<b>"+val.kode+"</b><br>";
                        });
                    }
					return ret;
				}
			},
			{	targets: 12, class :"text-align-center td-kecil",
				render: function ( data, type, full, meta ) {
                    <?php if(Yii::$app->controller->id == "posengon"){ ?>
                        var asd = full[12];  // approve level 2
                    <?php }else{ ?>
                        var asd = full[13];  // approve owner
                    <?php } ?>
                        
                    if(asd=="<?= app\models\TApproval::STATUS_APPROVED ?>"){
                        var ret =  "<select onchange='confirmStatus("+full[0]+",this)' "+( (full[15]=="CLOSE")?"disabled":"" )+">\n\
                                        <option value='OPEN' "+( (full[15]=="OPEN")?"selected":"" )+">Open</option>\n\
                                        <option value='CLOSE' "+( (full[15]=="CLOSE")?"selected":"" )+">Close</option>\n\
                                    </select>";
                    }else{
                        var ret =  "<select disabled>\n\
                                        <option value='OPEN' "+( (full[15]=="OPEN")?"selected":"" )+">Open</option>\n\
                                        <option value='CLOSE' "+( (full[15]=="CLOSE")?"selected":"" )+">Close</option>\n\
                                    </select>";
                    }
                    return ret;
				}
			},
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}

function confirmStatus(pmr_id,ele){
    // balikin dulu sebelum konfirm
    if($(ele).val() == "OPEN"){
        $(ele).val("CLOSE");
        var statusbaru = "OPEN";
    }else{
        $(ele).val("OPEN");
        var statusbaru = "CLOSE";
    }
    
    cisConfirm("Apakah anda yakin akan meng-close permintaan ini?<br /><br /><a class=\'btn btn-xs hijau\' onclick=\'updateStatusPmr("+pmr_id+",\""+$(ele).val()+"\")\'>Yaqin</a> &nbsp; <a class=\'btn btn-xs grey\'>Tidak</a>");
}

function updateStatusPmr(pmr_id,status){
    $.ajax({
        url    : '<?= \yii\helpers\Url::toRoute(['/purchasinglog/pengajuanpembelianlog/updateStatusPmr']); ?>',
        type   : 'POST',
        data   : {pmr_id:pmr_id,status:status},
        success: function (data){
            if(data){
                cisAlert("Berhasil di Close");
            }else{
                cisAlert("Failur");
            }
            $('#table-aftersave').dataTable().fnClearTable(); 
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}
</script>