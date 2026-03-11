<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MPicNotulen */

$this->title = 'Update Mpic Notulen: ' . $model->pic_notulen_id;
$this->params['breadcrumbs'][] = ['label' => 'Mpic Notulens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pic_notulen_id, 'url' => ['view', 'id' => $model->pic_notulen_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="mpic-notulen-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
