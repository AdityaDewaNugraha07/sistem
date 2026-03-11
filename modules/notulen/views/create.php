<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MPicNotulen */

$this->title = 'Create Mpic Notulen';
$this->params['breadcrumbs'][] = ['label' => 'Mpic Notulens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mpic-notulen-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
