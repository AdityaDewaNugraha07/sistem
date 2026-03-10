<div class="content">
    <div class="logo">
        <a href="<?= \yii\helpers\Url::base() ?>" style="text-align: center">
            <div class="visible-lg visible-md">
                <img class="login-logo" style="width: 275px;" src="<?php echo \Yii::$app->view->theme->baseUrl.'/cis/img/logo-login.png'; ?>" alt="" /> 
            </div>
            <div class="visible-sm visible-xs">
                <img class="login-logo" style="width: 160px; margin-left: -15px;" src="<?php echo \Yii::$app->view->theme->baseUrl.'/cis/img/logo-login.png'; ?>" alt="" /> 
            </div>
        </a>
    </div>
    <!-- BEGIN LOGIN FORM -->
    <?php $form = \yii\bootstrap\ActiveForm::begin([
        'id' => 'login-form',
        'fieldConfig' => [
            'template' => '{label}<div class="input-icon"><i></i>{input}</div>{error}',
            'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
            'inputOptions' => ['class' => 'form-control placeholder-no-fix'],
            'errorOptions' => ['tag' => 'span','class' => 'help-block',],
            'options' => ['class' => 'form-group',],
        ],
        'options'=>['class'=>'login-form'],
        'errorCssClass'=>'has-error',
    ]); ?>
    <!--<h3 class="form-title " style="text-align: center">Login to your account</h3>-->
    
    <?= $form->field($model, 'username')->textInput(['autofocus' => false,'placeholder'=>$model->getAttributeLabel('username')]) ?>
    <?= $form->field($model, 'password')->passwordInput(['placeholder'=>$model->getAttributeLabel('password'),'style'=>'']) ?>
    <br>
    <div class="form-actions">
        <label class="rememberme mt-checkbox mt-checkbox-outline">
            <input type="checkbox" name="remember" value="1" /> Remember me
            <span></span>
        </label>
        <?php echo \yii\helpers\Html::button( Yii::t('app', 'Login'),['class'=>'btn putih btn-outline pull-right ciptana-spin-btn','data-style'=>'zoom-in','type'=>'submit',])?>
    </div>
    <br>
    
    <?php \yii\bootstrap\ActiveForm::end(); ?>
    <!-- END LOGIN FORM -->
</div>

<?php $this->registerJs("
    $.backstretch(['".Yii::$app->view->theme->baseUrl.'/cis/img/login-bg/4a.jpg'."', '".Yii::$app->view->theme->baseUrl.'/cis/img/login-bg/4.png'."'], {
        duration:20000,
        fade:1500
    });
    $('#".yii\helpers\Html::getInputId($model, 'username')."').siblings('i').addClass('fa fa-user');
    $('#".yii\helpers\Html::getInputId($model, 'password')."').siblings('i').addClass('fa fa-lock');
", yii\web\View::POS_END); ?>