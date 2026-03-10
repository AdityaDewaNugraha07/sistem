<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class SummernoteAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        'global/plugins/bootstrap-summernote/summernote.css'
    ];
    public $js = [
        'global/plugins/bootstrap-summernote/summernote.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
