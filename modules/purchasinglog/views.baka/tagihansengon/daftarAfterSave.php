<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Daftar Tagihan Log Sengon/Jabon'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan" id="table-aftersave">
							<thead>
								<tr>
									<th></th>
                                                                        <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Kode<br>Tagihan'); ?></th>
									<th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Tagihan'); ?></th>
									<th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Kode PO'); ?></th>
									<th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Kode<br>Terima'); ?></th>
									<th style="line-height: 1"><?= Yii::t('app', 'Suplier'); ?></th>
                                                                        <th></th>
									<th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Reff No<br>(Nopol Truck)'); ?></th>
									<th style="width: 60px; line-height: 1"><?= Yii::t('app', 'Bayar<br>Langsung'); ?></th>
									<th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Total<br>Batang'); ?></th>
									<th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Total<br>Volume'); ?></th>
									<th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Total<br>Bayar'); ?></th>
									<th style="width: 80px;"></th>
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
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/purchasinglog/tagihansengon/daftarAfterSave') ?>',data:{dt: 'modal-aftersave'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {	targets: 0, visible: false },
            { 	targets: 1, class:"text-align-right td-kecil", 
                render: function ( data, type, full, meta ) { 
                    <?php if(!empty($pick)){ ?>
                        if( full[12] ){ // pernah di tarik openvoucher
                            var ret =   "<a class='btn btn-xs btn-icon-only btn-default grey tooltips' data-original-title='Not Qualified' style='width: 25px; height: 25px;' disabled=disabled><i class='fa fa-plus-circle'></i></a>"+data;
                        }else{
                            var ret =  "<a onclick='pickTagihanSengon(\""+data+"\",\""+full[3]+"\",\""+full[13]+"\",\""+full[14]+"\",\""+full[0]+"\",\""+full[15]+"\")' class='btn btn-xs btn-icon-only btn-default tooltips' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        }
                    <?php }else{ ?>
                        var ret = data;
                    <?php } ?>
                    
                    return ret;
                }
            }, 
			{	targets: 2, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
                    var date = new Date(data);
					date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{	targets: 3, class:"text-align-left td-kecil" },
			{	targets: 4, class:"text-align-center td-kecil" },
			{	targets: 5, class:"text-align-left td-kecil",
                render: function ( data, type, full, meta ) {
                        if(full[16] == 'PLS'){
                            var jnslog = 'Log Sengon';
                        }else {//if(substr(full[3],0,3) = 'PLJ'){
                            var jnslog = 'Log Jabon';
                        }
                    return jnslog+"<br><b>"+full[5]+"</b><br>"+full[6];
                }
            },
			{	targets: 6, visible: false },
			{	targets: 7, class:"text-align-center td-kecil" },
			{	targets: 8, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					if(data==true){
                        return "YA";
                    }else{
                        return "TIDAK";
                    }
                }
            },
			{	targets: 9, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 10, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
			{	targets: 11, class:"text-align-right td-kecil",
                render: function ( data, type, full, meta ) {
					return formatNumberForUser(data);
                }
            },
            {	targets: 12, class:"text-align-center td-kecil",
                render: function ( data, type, full, meta ) {
					var display = "";
//					if( (full[9]) ) {
//						display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit""><i class="fa fa-edit"></i></a>';
//					}else{
//                        display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
//                    }
                    
                    <?php if(empty($pick)){ ?>
                        if( full[12] ){ // pernah di tarik openvoucher
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
                        }else{
                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
                        }
                    <?php } ?>
                        
                    
                    
					var ret =  '<center>\n\
									'+display+'\n\
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
function lihatDetail(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/index','tagihan_sengon_id'=>'']); ?>'+id);
}
function edit(id){
    window.location.replace('<?= \yii\helpers\Url::toRoute(['/purchasinglog/tagihansengon/index','tagihan_sengon_id'=>'']); ?>'+id+'&edit=1');
}

</script>