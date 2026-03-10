<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Pengajuan Asuransi'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th><?= Yii::t('app', 'Kode<br>Tanggal'); ?></th>
                                        <th></th>
                                        <th><?= Yii::t('app', 'Ditujukan Kepada'); ?></th>
                                        <th></th>
                                        <th><?= Yii::t('app', 'Tanggal Muat'); ?></th>
                                        <th><?= Yii::t('app', 'Tanggal<br>Berangkat'); ?></th>
                                        <th style="width: 150px;"><?= Yii::t('app', 'Deskripsi'); ?></th>
                                        <th style="width: 150px;"><?= Yii::t('app', 'Rute'); ?></th>
                                        <th style="width: 150px;"><?= Yii::t('app', 'Nama Kapal'); ?></th>
                                        <th><?= Yii::t('app', 'Freight'); ?></th>
                                        <th><?= Yii::t('app', 'Rate(%)'); ?></th>
                                        <th><?= Yii::t('app', 'Approval 1'); ?></th>
                                        <th><?= Yii::t('app', 'Approval 2'); ?></th>
                                        <th style="width: 8%;">&nbsp;</th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/asuransi/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc'],
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1,
                render: function ( data, type, full, meta ) {
					var date = new Date(full[2]);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+data+'<br>'+date+'</center>';
                }
            },
            { 	targets: 2,visible: false},
            { 	targets: 3, 
                render: function ( data, type, full, meta ) {
                                        var Lampiran = nl2br(full[4]);
					return '<left>'+nl2br(data)+'<br><br>Lampiran :<br>'+Lampiran+'</left>';
                }
            },
            { 	targets: 4,  visible: false},
            { 	targets: 5, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 6, 
                render: function ( data, type, full, meta ) {
					var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            { 	targets: 10,
                className: "text-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            { 	targets: 11, 
                className: "text-right",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            { 	targets: 12, 
                className: "text-right",
                render: function ( data, type, full, meta ) {
                    if (full[12] == "Not Confirmed") {
                        var btn = 'label-default';
                        var label = 'Not Confirmed'
                    } else if (full[12] == "APPROVED") {
                        var btn = 'label-success';
                        var label = 'APPROVED'
                    } else if (full[12] == "REJECTED") {
                        var btn = 'label-danger';
                        var label = 'REJECTED'
                    }
                    return '<center><button class="btn btn-xs btn-outline '+btn+'" style="color: #fff;">'+label+'</center>';
                }
            },
            { 	targets: 13, 
                className: "text-right",
                render: function ( data, type, full, meta ) {
                    if (full[13] == "Not Confirmed") {
                        var btn = 'label-default';
                        var label = 'Not Confirmed'
                    } else if (full[13] == "APPROVED") {
                        var btn = 'label-success';
                        var label = 'APPROVED'
                    } else if (full[13] == "REJECTED") {
                        var btn = 'label-danger';
                        var label = 'REJECTED'
                    }
                    return '<center><button class="btn btn-xs btn-outline '+btn+'" style="color: #fff;">'+label+'</center>';                }
            },
            {	targets: 14, 
                    render: function ( data, type, full, meta ) {
                            var display = "";
                            if((full[12]!="<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>") || (full[13]!="<?= app\models\TApproval::STATUS_NOT_CONFIRMATED ?>")){
                                    display =  'visibility: hidden;';
                            }
                            var ret =  '<center>\n\
                                                \n\<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Delete Pengajuan Asuransi" onclick="deletePengajuan('+full[0]+')"><i class="fa fa-trash-o"></i></a>\n\
                                                \n\<a style="'+display+'" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>\n\
                                                <a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail('+full[0]+')"><i class="fa fa-eye"></i></a>\n\
                                        </center>';
                            return ret;
                    }
            },
        ],
		autoWidth: false,
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
    });
}
function nl2br (str, is_xhtml) {   
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/view','asuransi_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/asuransi/index','asuransi_id'=>'']); ?>'+id+'&edit=1');
}
function deletePengajuan(id){
    var url = "<?= yii\helpers\Url::toRoute("/purchasinglog/asuransi/deletePengajuan") ?>?id="+id+"&tableid=table-aftersave";
	$(".modals-place-2").load(url, function() {
		$("#modal-delete-record .modal-dialog").css('width','50%');
		$("#modal-delete-record").modal('show');
		$("#modal-delete-record").on('hidden.bs.modal', function () {});
		spinbtn();
		draggableModal();
	});
}
</script>