<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class DatatableAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'global/plugins/datatables/datatables.min.css',
        'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css',
        'global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
    ];
    public $js = [
//        'global/scripts/datatable.js',
		'global/plugins/datatables/datatables.min.js',
		'global/plugins/datatables/plugins/bootstrap/datatables.bootstrap_cis.js',
		'global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
//		'pages/scripts/table-datatables-buttons.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
