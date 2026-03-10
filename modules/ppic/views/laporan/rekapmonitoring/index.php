<?php

use app\assets\DatatableAsset;
use app\assets\DatepickerAsset;
use yii\web\JqueryAsset;

$tab = isset($_GET['tab']) ? (int)$_GET['tab'] : 1;
$menus = require __DIR__ . '/tab.php';

DatepickerAsset::register($this);
DatatableAsset::register($this);

$this->title = 'Rekap Monitoring';
$this->registerJsFile('https://benalman.com/code/projects/jquery-throttle-debounce/jquery.ba-throttle-debounce.js', ['depends' => [JqueryAsset::class]]);
$this->registerJs('init();search();');
?>

<h1 class="page-title"> <?php echo Yii::t('app', 'Rekap Monitoring'); ?></h1>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <?= renderTab($menus) ?>
                    </ul>
                </div>
            </div>
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-cogs"></i>
                    <span class="caption-subject hijau bold"><?= getActiveTab($menus) ?></span>
                </div>
            </div>
            <div class="portlet-body">
                <?= $this->render('search') ?>
                <?= renderContent($menus, $this) ?>
            </div>
        </div>
    </div>
</div>