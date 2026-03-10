<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

class InputMaskAsset extends AssetBundle
{
    public $basePath = '@webroot/themes/metronic';
    public $baseUrl = '@web/themes/metronic';
//    public $sourcePath = '@webroot/themes/metronic';
    
    public $css = [
        
    ];
    public $js = [
        'global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js',
        'global/plugins/jquery.input-ip-address-control-1.0.min.js',
        'global/plugins/jquery.maskMoney.js',
        'global/scripts/app.min.js',
        'pages/scripts/form-input-mask.min-custom.js',
        'cis/plugins/accounting.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
    ];
}
