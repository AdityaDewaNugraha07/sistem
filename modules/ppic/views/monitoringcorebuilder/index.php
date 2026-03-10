<?php

use app\assets\DatatableAsset;
use app\assets\DatepickerAsset;
use yii\helpers\Url;

DatepickerAsset::register($this);
DatatableAsset::register($this);

$this->title = 'Core Builder';
$this->registerCssFile(Url::base() . '/themes/metronic/global/plugins/loader/loader-github.css');
$this->registerCss("
@media only screen and (max-width: 576px) {
    .table-desktop {
        display: none;
    }

    .table-mobile {
        display: block;
    }
}

@media screen and (min-width: 576px) {
    .table-mobile {
        display: none;
    }
}
");
$this->registerJs("main()");
?>
<h1 class="page-title"> <?php echo Yii::t('app', 'Core Builder'); ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="javascript:void(0)"
                           data-url="<?= Url::toRoute("/ppic/monitoringcorebuilder/input") ?>"> <?= Yii::t('app', 'Input') ?> </a>
                    </li>
                    <li class="">
                        <a href="javascript:void(0)"
                           data-url="<?= Url::toRoute("/ppic/monitoringcorebuilder/output") ?>"> <?= Yii::t('app', 'Output') ?> </a>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-12">
                        <span class="loader"></span>
                        <div class="content-input"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function main() {
        $(document).ready(function () {
            let url = '<?= Url::toRoute("/ppic/monitoringcorebuilder/input") ?>';
            $('.loader').hide();
            $('.nav.nav-tabs li').each(function (i, item) {
                $(item).on('click', function () {
                    $('.nav.nav-tabs li').removeClass('active');
                    $(item).addClass('active');
                    url = $(item).children().data('url');
                    $('.loader').show();
                    loadContent(url);
                })
            });
            loadContent(url);
        });
    }

    function loadContent(url) {
        $('.content-input').empty().load(url, function () {
            $('.loader').hide();
        })
    }
</script>
