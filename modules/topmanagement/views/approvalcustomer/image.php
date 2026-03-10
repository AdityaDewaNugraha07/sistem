<?php

/** @var MCustomer $model */
use app\models\MCustomer;

$file_name = '';
if (!empty($tipe)) switch ($tipe) {
    case 'ktp':
        $file_name  = $model->cust_file_ktp;
        break;
    case 'npwp':
        $file_name  = $model->cust_file_npwp;
        break;
    case 'photo':
        $file_name  = $model->cust_file_photo;
        break;
}

?>

<style>

    .img-modal {
        width: fit-content;
        left: 0;
        bottom: 0;
        position: relative;
        right: 0;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-close-modal {
        position: relative;
        color: white;
        display: block !important;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }
</style>

<div class="modal fade" id="modal-image" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content img-modal">
            <img src="<?= Yii::$app->urlManager->baseUrl ?>/uploads/mkt/customer/<?= $file_name; ?>" style="" alt="" class="img-responsive" />
        </div>
        <button type="button" class="btn btn-icon-only btn-danger fa fa-close btn-close-modal" data-dismiss="modal" aria-hidden="true"></button>
    </div>
</div>

