<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\helpers\Url;

MetronicAsset::register($this);

//$this->registerCssFile($this->theme->baseUrl."/pages/css/login-4.min.css");
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<HTML lang="<?= Yii::$app->language ?>">
<HEAD>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= !empty(Html::encode($this->title))? Html::encode($this->title)." - ":""; ?> <?= Yii::$app->name; ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Url::base();?>/favicon.ico" />
</HEAD>
<script>
    document.write("<BO"+"DY class=''>");
</script>
<!--<BODY class="">-->
<div style="margin-top: 5%; margin-bottom: 5%; ">
<?php $this->beginBody() ?>
    <!-- BEGIN LOGO -->
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
    <?= $content ?>
    <!-- END LOGIN -->
<!-- BEGIN COPYRIGHT -->
<div class="copyright text-align-center"><strong>Copyright &copy; 2018 <?= Yii::$app->name ?></strong></div>
</div>
<?php $this->registerJsFile($this->theme->baseUrl."/global/plugins/backstretch/jquery.backstretch.min.js",['depends'=>[yii\web\YiiAsset::className()]]) ?>
<!-- End -->
<?php $this->endBody() ?>
<script type="text/javascript">
    document.write("</BO"+"DY>"+"</HT"+"ML>");
</script>
<!--</BODY>
</HTML>-->
<?php $this->endPage() ?>
