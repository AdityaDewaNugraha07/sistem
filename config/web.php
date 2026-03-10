<?php

use kartik\mpdf\Pdf;

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'cis',
	'name' => 'Ciptana Integrated Systems',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log','app\components\DeltaGlobalClass'],
    'timeZone' => 'Asia/Jakarta',
	'defaultRoute' => 'apps/index',
	'layoutPath' => '@app/views/layouts/metronic',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wdwz1cEcF0DhofJb934S5NCfoTyD2BZ9',
			'enableCookieValidation' => true,
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'session' => [
            'class' => 'yii\web\Session',
//            'name' => 'cissession',
            'timeout' => 60*60*24,
            'cookieParams' => ['lifetime' => 60*60*24]
        ],
        'user' => [
            'identityClass' => 'app\models\MUser',
            'enableAutoLogin' => true,
			'authTimeout' => 60*60*24, // default 400 detik
            'enableSession' => true,
			'loginUrl'=> ['apps/login'],
            'identityCookie'=>['name' => 'cis_identity', 'httpOnly' => true]
        ],
        'errorHandler' => [
            'errorAction' => 'apps/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            // gmail
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => 'it.ciptana@gmail.com',
                'password' => 'eccpygqixafgwfxi',
                'port' => '587',
                'encryption' => 'tls',
            ],
            // hostinger
            /*'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.hostinger.co.id',
                'username' => 'it@ciptana.net',
                'password' => 'c1pt4n4296',
                'port' => '587',
                'encryption' => 'tls',
            ],*/
            /* yahoo
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.yahoo.com',
                'username' => 'it.ciptana@yahoo.com',
                'password' => 'c1pt4n4296',
                'port' => '465',
                'encryption' => 'ssl',
            ],*/
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                /*if logs need to save in files                
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['application'],
                    'logVars' => ['_POST','_GET'],
                ],
                */
 
                // if logs need to save in database. You need to create a db table as well.
                /*[
                    'class' => 'yii\log\DbTarget',
                    'prefix' => function ($message) {
                        $username = Yii::$app->user->identity->username;
                        return $username;
                    },
                    'levels' => ['info', 'warning', 'error'],
                    'categories' => ['yii\db\Command::execute'], // all database queries
                    'except' => ['application'], // appilcation buang
                    'logVars' => [],

                ],*/
                
                /*if logs need to send email
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['error'],
                    'categories' => ['yii\db\*'], // all database queries
                    'message' => [
                       'from' => ['log@example.com'],
                       'to' => ['admin@example.com', 'developer@example.com'],
                       'subject' => 'Database errors at example.com',
                    ],
                ],
                */
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'db2' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=10.10.10.3;dbname=cis',
            'username' => 'postgres',
            'password' => 'c1pt4n42017',
            'charset' => 'utf8',
        ],        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'view' => [
            'theme' => [
                'pathMap' => ['@app/views' => '@app/themes/metronic'],
                'basePath' => '@webroot/themes/metronic',
                'baseUrl' => '@web/themes/metronic',
            ],
        ], 
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@webroot/themes/metronic/global/plugins/',
                    'js' => [
                        'jquery.min.js',    // jQuery v1.12.4 min
                    ]
                ],
            ],
        ],
        'createDatatable' => [
            'class' => 'app\components\Datatables'
        ],
		'pdf' => [
			'class' => Pdf::class,
			'mode' => Pdf::MODE_UTF8,
			'format' => Pdf::FORMAT_LEGAL,
			'orientation' => Pdf::ORIENT_PORTRAIT,
			'destination' => Pdf::DEST_BROWSER,
			'marginLeft' => 10,
			'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
			'cssInline' => '.kv-heading-1{font-size:6px}',
			'methods' => [ 
				'SetHeader'=>[''],
				'SetFooter'=>['|{PAGENO}|'],
			],
		],
		'gridview' => ['class' => 'kartik\grid\Module',],
    ],
    'params' => $params,
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'allow' => true,
                'actions' => ['login','error','api'],
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
        'except' => [
            'monitoring/index',
            'monitoring/rotary',
            'monitoring/drying',
            'monitoring/cb',
            'monitoring/plytech',
            'monitoring/repair',
            'monitoring/setting',
            'monsprd/tv1',
            'monsprd/tv2',
            'monsprd/tv3',
            'monsprd/tv4',
        ],
        'denyCallback' => static function () {
            return Yii::$app->response->redirect(['apps/login']);
        },
    ],
    'aliases' => [
        '@views' => '@app/views',
        '@apps_js' => '@app/views/apps/js',
        '@apps_modal' => '@app/views/apps/modal',
        '@module' => '@app/modules',
        '@module_logistik' => '@app/modules/logistik/views',
        '@module_topmanagement' => '@app/modules/topmanagement/views', 
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
		'gridview' =>  [
			'class' => '\kartik\grid\Module'
		],
        'marketing' => [
            'class' => 'app\modules\marketing\Module',
            'defaultRoute' => '/customer/index',
        ],
        'sysadmin' => [
            'class' => 'app\modules\sysadmin\Module',
        ],
        'gudang' => [
            'class' => 'app\modules\gudang\Module',
        ],
        'finance' => [
            'class' => 'app\modules\finance\Module',
        ],
        'ppic' => [
            'class' => 'app\modules\ppic\Module',
        ],
        'tuk' => [
            'class' => 'app\modules\tuk\Module',
        ],
        'logistik' => [
            'class' => 'app\modules\logistik\Module',
        ],
        'purchasing' => [
            'class' => 'app\modules\purchasing\Module',
        ],
        'hrd' => [
            'class' => 'app\modules\hrd\Module',
        ],
		'topmanagement' =>  [
			'class' => 'app\modules\topmanagement\Module',
		],
		'kasir' =>  [
			'class' => 'app\modules\kasir\Module',
		],
		'support' =>  [
			'class' => 'app\modules\support\Module',
		],
		'exim' =>  [
			'class' => 'app\modules\exim\Module',
		],
        'purchasinglog' =>  [
            'class' => 'app\modules\purchasinglog\Module',
        ],
        'openticket' =>  [
            'class' => 'app\modules\openticket\Module',
        ],
        'tes' =>  [
            'class' => 'app\modules\tes\Module',
        ],
        'grafik' =>  [
            'class' => 'app\modules\grafik\Module',
        ],   
        'qms' => [
            'class' => 'app\modules\qms\Module',
        ], 
        'qc' => [
            'class' => 'app\modules\qc\Module',
        ],     

    ],
    'language' => 'en-US',
    'sourceLanguage' => 'id-ID'
];
if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '10.10.10.33', '10.10.10.98'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '10.10.10.33', '10.10.10.98'],
    ];
}

// ########## Default Other Configurations ##########

Yii::$container->set('yii\bootstrap\ActiveForm', [
    'errorCssClass'=>'has-error',
	'successCssClass'=>'has-success',
    'options'=>['class'=>'form-horizontal'],
]);
Yii::$container->set('yii\bootstrap\ActiveField', [
    'options' => ['class' => 'form-group'],
	'template' => '{label} <div class="controls">{input}{error}</div>',
//	'inputOptions' => ['class' => 'm-wrap'],
	'errorOptions' => ['tag' => 'span','class' => 'help-block',],
]);
Yii::$classMap['SSP'] = '@app/components/SSP.php';
Yii::$classMap['SSP2'] = '@app/components/SSP2.php';
Yii::$classMap['ExcelFile'] = '@app/components/ExcelFile.php';
Yii::$classMap['PHPExcel'] = '@app/components/PHPExcel.php';

// ########## ########################### ##########

return $config;

