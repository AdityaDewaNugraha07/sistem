<?php 
	$modCompany = app\models\CCompanyProfile::findOne(app\components\Params::DEFAULT_COMPANY_PROFILE);
?>

<style>
    .lap-header-wrapper {
        text-align: center;
        border-bottom: 1px solid black;
        margin: 20px 16px 20px 16px;
        padding-bottom: 10px;
    }

    .disclaimer {
        display: flex;
        justify-content: space-between;
        margin-top: -40px;
    }

    .logo-default {
        width: 80px;
    }

    .heading {
        font-size: 18px; 
        font-weight: 600;
    }

    .header-author {
        font-size: 8px;
        display: flex;
        flex-direction: column;
    }

    .author-bottom {
        margin-top: auto;
    }

    .lap-header-wrapper-print {
        border-bottom: 1px solid black;
        margin: 20px 16px 20px 16px;
        padding-bottom: 10px;
        display: none;
    }

    .title-print {
        display: flex;
        flex-direction: column;
    }

    .title-print .heading {
        margin-top: auto;
    }

    @media print and (max-width: 21cm) {
        .lap-header-wrapper {
            display: none;
        }

        .lap-header-wrapper-print {
            display: block;
        }
    }

</style>

<div class="lap-header-wrapper">
    <div class="title">
        <span class="heading">
            <?= $paramprint['judul']; ?>
        </span>
        <br>
        <?= isset($paramprint['judul2']) ? $paramprint['judul2'] : '' ?>
    </div>
    <div class="disclaimer">
        <div class="header-kop">
            <img src="<?= \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" class="logo-default"> 
        </div>
        <div class="header-author">
            <span></span>
            <span class="author-bottom">
                <?= isset($_GET['caraprint']) && $_GET['caraprint'] === 'PRINT' 
                    ? Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;" . Yii::t('app', 'at : '). date('d/m/Y H:i:s') 
                    : ''
                ?>
            </span>
        </div>
    </div>
</div>

<!-- <div class="lap-header-wrapper-print">
    <div class="row">
        <div class="col-md-6">
            <div class="title-print">
                <div></div>
                <div>
                    <span class="heading">
                        <?= $paramprint['judul']; ?>
                    </span>
                    <br>
                    <?= isset($paramprint['judul2']) ? $paramprint['judul2'] : '' ?>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <div class="header-kop">
                <img src="<?= \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" class="logo-default"> 
            </div>
            <span style="font-size: 8px;">
                <?= isset($_GET['caraprint']) && $_GET['caraprint'] === 'PRINT' 
                    ? Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;" . Yii::t('app', 'at : '). date('d/m/Y H:i:s') 
                    : ''
                ?>
            </span>
        </div>
    </div>
</div> -->

<div class="lap-header-wrapper-print">
    <table style="width: 100%;">
        <tr>
            <td style="vertical-align: middle;">
            <span class="heading">
                <?= $paramprint['judul']; ?>
            </span>
            <br>
            <?= isset($paramprint['judul2']) ? $paramprint['judul2'] : '' ?>
            </td>
            <td style="text-align: right;">
                <div class="header-kop">
                    <img src="<?= \Yii::$app->view->theme->baseUrl; ?>/cis/img/logo-ciptana.png" class="logo-default"> 
                </div>
                <span style="font-size: 8px">
                    <?= isset($_GET['caraprint']) && $_GET['caraprint'] === 'PRINT' 
                        ? Yii::t('app', 'Printed By : ').Yii::$app->user->getIdentity()->userProfile->fullname. "&nbsp;" . Yii::t('app', 'at : '). date('d/m/Y H:i:s') 
                        : ''
                    ?>
                </span>
            </td>
        </tr>
    </table>
</div>