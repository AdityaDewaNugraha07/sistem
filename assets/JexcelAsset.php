<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class JexcelAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'global/plugins/jexcel/css/jquery.jexcel.css',
    ];
    public $js = [
        'global/plugins/jexcel/js/jquery.jexcel.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
