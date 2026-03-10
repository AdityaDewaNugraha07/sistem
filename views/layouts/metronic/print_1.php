<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\MetronicAsset;
use yii\helpers\Url;

MetronicAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= !empty(Html::encode($this->title))? Html::encode($this->title)." - ":""; ?> <?= Yii::$app->name; ?></title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Url::base();?>/favicon.ico" />
</head>
<!-- BEGIN BODY -->
<body class="page-header-fixed page-sidebar-closed-hide-logo" onload="print_win()">
<?php $this->beginBody() ?>
<div class="page-wrapper">
	<div class="page-container">
		<div class="page-content-wrapper">
			<div class="page-content">
				<?php echo $content ?>
			</div>
		</div>
	</div>
	<div class="page-footer"></div>
</div>
	
<?php $this->endBody() ?>
<script type="text/javascript">
	function chkstate(){
		if(document.readyState=="complete"){
			window.close()
		}else{
			setTimeout("chkstate()",2000)
		}
	}
	function print_win(){
		window.print();
	}
</script>

</body>
<!-- END BODY -->
</html>
<?php $this->endPage() ?>
