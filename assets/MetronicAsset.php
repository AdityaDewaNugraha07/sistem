<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class MetronicAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'global/plugins/bootstrap-toastr/toastr.min.css',
        'global/css/components-rounded.min.css',
        'global/css/plugins.min.css',
        'global/plugins/ladda/ladda-themeless.min.css',
        'cis/css/custom.min.css',
        'global/plugins/jquery-notific8/jquery.notific8.min.css'
    ];
    public $js = [
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/js.cookie.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        'global/plugins/bootstrap-toastr/toastr.min.js',
        'global/scripts/app.min.js',
        'global/plugins/ladda/spin.min.js',
        'global/plugins/ladda/ladda.min.js',
        'cis/js/custom.js',
        'global/plugins/jquery-notific8/jquery.notific8.min.js',
        'global/plugins/pace/pace.min.js',
        'pages/scripts/ui-notific8.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
