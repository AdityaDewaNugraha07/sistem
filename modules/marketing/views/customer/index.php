<?php
/* @var $this yii\web\View */
$this->title = 'Master Customer';
app\assets\DatatableAsset::register($this);
isset($_GET['search']) ? $search = $_GET['search'] : $search = '';
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo Yii::t('app', 'Master Customer'); ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
                        <div class="portlet light bordered">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-cogs"></i>
                                    <span class="caption-subject hijau bold"><?= Yii::t('app', 'Master Customer'); ?></span>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="reload"> </a>
                                    <a href="javascript:;" class="fullscreen"> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <table class="table table-striped table-bordered table-hover" id="table-customer">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th><?= Yii::t('app', 'Kode Customer') ?></th>
                                            <th><?= Yii::t('app', 'Atas Nama') ?></th>
                                            <th><?= Yii::t('app', 'Perusahaan') ?></th>
                                            <th><?= Yii::t('app', 'Alamat') ?></th>
                                            <th><?= Yii::t('app', 'Approval') ?></th>
                                            <th><?= Yii::t('app', 'Status') ?></th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>

                <?php
                $model = new \app\models\MCustomer();
                ?>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs(" 
dtCustomer();
", yii\web\View::POS_READY); ?>

<script>
    function dtCustomer() {
        var dt_table = $('#table-customer').dataTable({
            ajax: {
                url: '<?= \yii\helpers\Url::toRoute('/marketing/customer/index') ?>',
                data: {
                    dt: 'table-customer'
                }
            },
            order: [
                [0, 'desc']
            ],
            columnDefs: [{
                    targets: 0,
                    visible: false
                },
                {
                    targets: 3,
                    render: function(data, type, full, meta) {
                        var ret = '<i>Perorangan</i>';
                        if (data) {
                            ret = data;
                        }
                        return ret;
                    }
                },
                {
                    targets: 4,
                    render: function(data, type, full, meta) {
                        var plafond = parseFloat(full[7]).toLocaleString(window.document.documentElement.lang);
                        return full[4] + ' (' + plafond + ')';
                    }
                },

                {
                    targets: 5,
                    orderable: false,
                    width: '80px',
                    align: 'center',
                    render: function(data, type, full, meta) {
                        if (full[5] == "REJECTED") {
                            var color = "#f00";
                        } else if (full[5] == "APPROVED") {
                            var color = "#38C68B";
                        } else {
                            var color = "#000";
                        }
                        return '<center><font style="color: ' + color + '">' + full[5] + '</font></center>';
                    }
                },
                {
                    targets: 6,
                    orderable: false,
                    width: '50px',
                    render: function(data, type, full, meta) {
                        var ret = ' - ';
                        if (data) {
                            ret = 'Active'
                        } else {
                            ret = '<span style="color:#B40404">Non-Active</span>'
                        }
                        return '<center>' + ret + '</   center>';
                    }
                },
                {
                    targets: 7,
                    orderable: false,
                    width: '5%',
                    render: function(data, type, full, meta) {
                        return '<center><a class=\"btn btn-xs blue-hoki btn-outline tooltips\" href=\"javascript:void(0)\" onclick=\"info(' + full[0] + ')\"><i class="fa fa-info-circle"></i></a></center>';
                    }
                },
            ],
        });
    }

    function create() {
        openModal('<?= \yii\helpers\Url::toRoute('/marketing/customer/create') ?>', 'modal-customer-create');
    }

    function info(id) {
        openModal('<?= \yii\helpers\Url::toRoute(['/marketing/customer/info', 'id' => '']) ?>' + id, 'modal-customer-info', '90%');
    }

    function printout(caraPrint) {
        var search = $('.form-control.input-sm.input-small.input-inline').val();
        window.open("<?= yii\helpers\Url::toRoute('/marketing/customer/CustomerPrint') ?>?" + $('#form-search-laporan').serialize() + "&caraprint=" + caraPrint + "&search=" + search, "", 'location=_new, width=1200px, scrollbars=yes');
    }
</script>