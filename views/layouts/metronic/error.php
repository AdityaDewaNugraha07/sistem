<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\helpers\Url;

MetronicAsset::register($this);

$this->registerCssFile($this->theme->baseUrl."/pages/css/error.min.css");
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
<!-- BEGIN BODY -->
<script>
    document.write("<BO"+"DY class='page-404-3'>");
</script>
<!--<BODY class="page-404-3">-->
<?php $this->beginBody() ?>
   <?= $content; ?>
<!-- END BODY -->
<?php $this->endBody() ?>
<script type="text/javascript">
    document.write("</BO"+"DY>"+"</HT"+"ML>");
</script>
<!--</BODY>
</HTML>-->
<?php $this->endPage() ?>
