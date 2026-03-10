<?php use yii\helpers\Url;

app\assets\DatatableAsset::register($this); ?>
<div class="modal fade" id="modal-aftersave" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn btn-icon-only btn-default fa fa-close" data-dismiss="modal"
                        aria-hidden="true" style="float: right; padding-top: 2px; padding-bottom: 2px;"></button>
                <h4 class="modal-title"><?= Yii::t('app', 'Riwayat Pengajuan Koreksi Data') ?></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-laporan"
                               id="table-aftersave">
                            <thead>
                            <tr>
                                <th></th>
                                <th style="width: 120px; line-height: 1"><?= Yii::t('app', 'Kode<br>Pengajuan') ?></th>
                                <th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Tanggal<br>Pengajuan') ?></th>
                                <th style="width: 160px; line-height: 1"><?= Yii::t('app', 'Tipe') ?></th>
                                <th style="width: 100px; line-height: 1"><?= Yii::t('app', 'Reff No') ?></th>
                                <th style="width: 90px; line-height: 1"><?= Yii::t('app', 'Priority') ?></th>
                                <th style="line-height: 1"><?= Yii::t('app', 'Alasan') ?></th>
                                <th style="width: 160px; line-height: 1"><?= Yii::t('app', 'Approver 1') ?></th>
                                <th></th>
                                <th style="width: 160px; line-height: 1"><?= Yii::t('app', 'Approver 2') ?></th>
                                <th></th>
                                <th style="width: 160px; line-height: 1"><?= Yii::t('app', 'Approver 3') ?></th>
                                <th></th>
                                <th style="width: 160px; line-height: 1"><?= Yii::t('app', 'Approver 4') ?></th>
                                <th></th>
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
    function dtAfterSave() {
        $('#table-aftersave').dataTable({
            ajax: {
                url: '<?= Url::toRoute('/sysadmin/datacorrection/daftarAfterSave') ?>',
                data: {dt: 'modal-aftersave'}
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [
                {targets: 0, visible: false},
                {targets: 1, class: "text-align-center td-kecil",},
                {
                    targets: 2, class: "text-align-center td-kecil",
                    render: function (data) {
                        let date = new Date(data);
                        date = date.toString('dd/MM/yyyy');
                        return '<div style="text-align: center;">' + date + '</div>';
                    }
                },
                {targets: 3, class: "text-align-left td-kecil",},
                {targets: 4, class: "text-align-center td-kecil",},
                {targets: 5, class: "text-align-center td-kecil",},
                {targets: 6, class: "text-align-left td-kecil",},
                {
                    targets: 7, class: "text-align-center td-kecil2",
                    render: function (data, type, full) {
                        let status = full[8];
                        if (status === "<?= app\models\TApproval::STATUS_APPROVED ?>") {
                            status = "<span class='font-green-seagreen'>" + status + "</span>";
                        } else if (status === "<?= app\models\TApproval::STATUS_REJECTED ?>") {
                            status = "<span class='font-red-flamingo'>" + status + "</span>";
                        }
                        if (status) {
                            return "<span style='font-size:1rem;'><b>" + data + "</b></span><br>" + status + "";
                        } else {
                            return "";
                        }
                    }
                },
                {targets: 8, visible: false},
                {
                    targets: 9, class: "text-align-center td-kecil2",
                    render: function (data, type, full) {
                        let status = full[10];
                        if (status === "<?= app\models\TApproval::STATUS_APPROVED ?>") {
                            status = "<span class='font-green-seagreen'>" + status + "</span>";
                        } else if (status === "<?= app\models\TApproval::STATUS_REJECTED ?>") {
                            status = "<span class='font-red-flamingo'>" + status + "</span>";
                        }
                        if (status) {
                            return "<span style='font-size:1rem;'><b>" + data + "</b></span><br>" + status + "";
                        } else {
                            return "";
                        }
                    }
                },
                {targets: 10, visible: false},
                {
                    targets: 11, class: "text-align-center td-kecil2",
                    render: function (data, type, full) {
                        let status = full[12];
                        if (status === "<?= app\models\TApproval::STATUS_APPROVED ?>") {
                            status = "<span class='font-green-seagreen'>" + status + "</span>";
                        } else if (status === "<?= app\models\TApproval::STATUS_REJECTED ?>") {
                            status = "<span class='font-red-flamingo'>" + status + "</span>";
                        }
                        if (status) {
                            return "<span style='font-size:1rem;'><b>" + data + "</b></span><br>" + status + "";
                        } else {
                            return "";
                        }
                    }
                },
                {targets: 12, visible: false},
                {
                    targets: 13, class: "text-align-center td-kecil2",
                    render: function (data, type, full) {
                        let status = full[14];
                        if (status === "<?= app\models\TApproval::STATUS_APPROVED ?>") {
                            status = "<span class='font-green-seagreen'>" + status + "</span>";
                        } else if (status === "<?= app\models\TApproval::STATUS_REJECTED ?>") {
                            status = "<span class='font-red-flamingo'>" + status + "</span>";
                        }
                        if (status) {
                            return "<span style='font-size:1rem;'><b>" + data + "</b></span><br>" + status + "";
                        } else {
                            return "";
                        }
                    }
                },
                {targets: 14, visible: false},
                {
                    targets: 15, class: "text-align-center td-kecil",
                    render: function (data, type, full) {
                        const display = "";
                        <?php // if(empty($pick)){ ?>
//                        if( full[12] ){ // pernah di tarik openvoucher
//                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline grey tooltips" data-original-title="Edit"><i class="fa fa-edit"></i></a>';
//                        }else{
//                            display =  '<a style=" margin-left: -5px;" class="btn btn-xs btn-outline blue-hoki tooltips" data-original-title="Edit" onclick="edit('+full[0]+')"><i class="fa fa-edit"></i></a>';
//                        }
                        <?php // } ?>

                        return '<div style="text-align: center;">\n\
									' + display + '\n\
									<a style="margin-left: -5px;" class="btn btn-xs btn-outline dark tooltips" data-original-title="Lihat" onclick="lihatDetail(' + full[0] + ')"><i class="fa fa-eye"></i></a>\n\
								</div>';
                    }
                },

            ],
            autoWidth: false,
            "dom": "<'row'<'col-md-6 col-sm-12'f><'col-md-6 col-sm-12'>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>",
        });
    }

    function lihatDetail(id) {
        window.location.replace('<?= Url::toRoute(['/sysadmin/datacorrection/index', 'pengajuan_manipulasi_id' => '']) ?>' + id);
    }

    function edit(id) {
        window.location.replace('<?= Url::toRoute(['/sysadmin/datacorrection/index', 'pengajuan_manipulasi_id' => '']) ?>' + id + '&edit=1');
    }

</script>