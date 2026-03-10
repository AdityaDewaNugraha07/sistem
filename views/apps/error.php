<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $exception->statusCode;

?>

<div class="page-inner">
    <img src="<?= Yii::$app->view->theme->baseUrl; ?>/pages/media/pages/kantor-top.jpg" class="img-responsive" alt=""> 
</div>
<div class="container error-404">
    <h1><?= Html::encode($this->title); ?></h1>
    <h2>Oppss, ada masalah</h2>
    <p> <?= nl2br(Html::encode($exception->getMessage())) ?></p>
    <p>
        <a href="<?= Url::base(); ?>" class="btn hijau btn-outline"> Return home </a>
        <br> 
    </p>
</div>