<?php app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-master" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal" aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Order Penjualan (OP)'); ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover" id="table-op">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th><?= Yii::t('app', 'Kode') ?></th>
                                    <th><?= Yii::t('app', 'Jenis Produk') ?></th>
                                    <th><?= Yii::t('app', 'Tanggal') ?></th>
                                    <th><?= Yii::t('app', 'Sales') ?></th>
                                    <th><?= Yii::t('app', 'Sistem Bayar') ?></th>
                                    <th><?= Yii::t('app', 'Tanggal Kirim') ?></th>
                                    <th><?= Yii::t('app', 'Customer') ?></th>
                                    <th><?= Yii::t('app', 'Status Approval') ?></th>
<!--                                    <th>--><?php //= Yii::t('app', 'Level 1'); ?><!--</th>-->
<!--                                    <th>--><?php //= Yii::t('app', 'Level 2'); ?><!--</th>-->
<!--                                    <th>--><?php //= Yii::t('app', 'Level 3'); ?><!--</th>                                    -->
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
	formconfig();
    dtMaster();
", yii\web\View::POS_READY); ?>
<script>
function dtMaster(){
    var dt_table =  $('#table-op').dataTable({
        ajax: { url: '<?= \yii\helpers\Url::toRoute('/marketing/spm/openOP') ?>',data:{dt: 'table-op'} },
        order: [
            [0, 'desc']
        ],
        columnDefs: [
            {   targets: 0, visible: false },

			{	targets: 1,
                render: function ( data, type, full, meta ) {
                    // log langsung muncul tombol
                    if(full[2] == 'Log'){
                        if (full[10] === "APPROVED") {
                            return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        } else {
                            return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
                        }
                    } else {
                        if(full[9] === '') {
                            return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                        } else{
                            if (full[10] === "APPROVED") {
                                return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                            } else {
                                return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
                            }
                        }
                    }
                            /**if(full[9] === '') {
                               return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                            } else{
                                if (full[10] === "APPROVED") {
                                    return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                                } else {
                                    return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
                                }

                                // if (full[12] === " Low Price (2)") {
                                //     if (full[9]==="APPROVED" && full[11]==="APPROVED") {
                                //         return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                                //     } else {
                                //         return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
                                //     }
                                // } else {
                                //     if (full[9]==="APPROVED" && full[10]==="APPROVED" && full[11]==="APPROVED") {
                                //         return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
                                //     } else {
                                //         return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
                                //     }
                                // }
                            }
			    //return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
			    //return '9='+full[9]+' 10='+full[10]+' 11='+full[11]+' 12='+full[12]+' '+data;

//                                if(full[2]=="Limbah"){
//                                   return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
//                                }else{
//                                    if(full[9]=="Not Confirmed"){
//                                        return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
//                                    }else{
//                                        return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
//
//                                    }
//
//                                        if(full[9]=="APPROVED" && full[10]=="APPROVED"){
//
//						return "<a onclick='pick(\""+full[0]+"\",\""+data+"\")' class='btn btn-xs btn-icon-only btn-default' data-original-title='Pick' style='width: 25px; height: 25px;'><i class='fa fa-plus-circle'></i></a>"+data;
//					}else{
//						return "<a class='btn btn-xs btn-icon-only btn-default' style='width: 25px; height: 25px; visibility: hidden;'><i class='fa fa-plus-circle'></i></a>"+data;
//					}
//                                }*/

                }
            },
			{ 	targets: 3,
                render: function ( data, type, full, meta ) {
                    let date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
			{ 	targets: 6,
                render: function ( data, type, full, meta ) {
                    let date = new Date(data);
                    date = date.toString('dd/MM/yyyy');
					return '<center>'+date+'</center>';
                }
            },
            {   targets: 7, visible: true },
            {   targets: 8, visible: true, class: 'text-center',
                render: function(data, type, full) {
                    if(full[10] != null) {
                        return `<span class="btn btn-xs ${full[10] === 'APPROVED' ? 'green-haze' : 'red-sunglo'}">${full[10]}</span>`;
                    }

                    return '';
                }
            },
            // {   targets: 9, visible: true,
            //     render: function ( data, type, full, meta ) {
            //         var ret = ' - ';
            //         if(full[9]=='APPROVED'){
            //             ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[9]+'</span>';
            //         }else if(full[9]=='REJECTED'){
            //             ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[9]+'</span>';
            //         }else if(full[9]=='Not Confirmed'){
            //             ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[9]+'</span>';
            //         }
            //         return ret;
            //     }
            // },
            // {   targets: 10, visible: true,
            //     render: function ( data, type, full, meta ) {
            //         var ret = ' - ';
            //         if(full[10]=='APPROVED'){
            //             ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[10]+'</span>';
            //         }else if(full[10]=='REJECTED'){
            //             ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[10]+'</span>';
            //         }else if(full[10]=='Not Confirmed'){
            //             ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[10]+'</span>';
            //         }
            //         return ret;
            //     }
            // },
            // {   targets: 11, visible: true,
            //     render: function ( data, type, full, meta ) {
            //         var ret = ' - ';
            //         if(full[11]=='APPROVED'){
            //             ret = '<span class="label label-success" style="font-size:1.1rem;">'+full[11]+'</span>';
            //         }else if(full[11]=='REJECTED'){
            //             ret = '<span class="label label-danger" style="font-size:1.1rem;">'+full[11]+'</span>';
            //         }else if(full[11]=='Not Confirmed'){
            //             ret = '<span class="label label-default" style="font-size:1.1rem;">'+full[11]+'</span>';
            //         }
            //         return ret;
            //     }
            // },
        ],
		"dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
		"autoWidth":false
    });
}
</script>
