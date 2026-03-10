<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class RekorekoAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all',
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
		
        'global/plugins/bootstrap-table/bootstrap-table.min.css',
        
        'global/css/components-rounded.min.css',
        'global/css/plugins.min.css',
        'layouts/layout/css/layout.min.css',
        'layouts/layout/css/themes/darkblue.min.css',
        'layouts/layout/css/custom.min.css',
        
    ];
    public $js = [
        'global/plugins/jquery.min.js',
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/js.cookie.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'global/plugins/moment.min.js',
		
        'global/plugins/bootstrap-table/bootstrap-table.min.js',
		
        'global/scripts/app.min.js',
		
        'pages/scripts/table-bootstrap.min.js',
		
        'layouts/layout/scripts/layout.min.js',
        'layouts/layout/scripts/demo.min.js',
        'layouts/global/scripts/quick-sidebar.min.js',
        'layouts/global/scripts/quick-nav.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
