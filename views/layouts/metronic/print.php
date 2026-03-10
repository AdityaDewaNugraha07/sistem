<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use app\assets\PrintAsset;
use yii\helpers\Url;

PrintAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<HTML lang="<?= Yii::$app->language ?>">
<HEAD>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>
		<?php
		if(isset($_GET['caraprint'])){
			if($_GET['caraprint'] == 'PRINT'){
				echo (!empty(Html::encode($this->title))? Html::encode($this->title)." - ":"").Yii::$app->name;
			}
		}
		?>
	</title>
    <?php $this->head() ?>
    <link rel="shortcut icon" href="<?= Url::base();?>/favicon.ico" />
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
</HEAD>
<!-- BEGIN BODY -->
<script>
    document.write("<BO"+"DY class='page-header-fixed page-sidebar-closed-hide-logo' onload='print_win()'>");
</script>
<!--<BODY class="page-header-fixed page-sidebar-closed-hide-logo" onload="print_win()">-->
<?php $this->beginBody() ?>
<div class="page-wrapper">
	<div class="page-container">
		<div class="page-content-wrapper">
			<div class="page-content">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
	<div class="page-footer"></div>
</div>
<?php $this->endBody() ?>
<script type="text/javascript">
    document.write("</BO"+"DY>"+"</HT"+"ML>");
</script>
<!--</BODY>
</HTML>-->
<?php $this->endPage() ?>
