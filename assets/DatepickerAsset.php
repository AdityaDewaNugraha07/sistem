<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class DatepickerAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
        'global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
        'global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
        'global/plugins/clockface/css/clockface.css',
    ];
    public $js = [
        'global/plugins/moment.min.js',
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        'global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        'global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js',
        'global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        'global/plugins/clockface/js/clockface.js',
        'cis/js/date.js',
        'global/scripts/app.min.js',
//        'pages/scripts/components-date-time-pickers.min-custom.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
