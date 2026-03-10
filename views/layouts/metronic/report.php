<?php 
use yii\helpers\Html;

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
	<meta charset="<?= Yii::$app->charset ?>" />
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<?php if ($_GET['caraprint'] === 'EXCEL') : ?>
		<script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>
	<?php endif; ?>
	<style>
		body {
			background-color: white;
			color: #333;
			font-family: 'Open Sans', sans-serif;
			margin: 0;
			padding: 0;
		}

		.title {
			color: #A6C054;
			font-size: 24px;
			text-align: center;
			margin: 0;
			padding: 0;
		}

		.subtitle {
			font-size: 16px;
			text-align: center;
			margin-top: 6px;
			margin-bottom: 16px;
		}

		table {
			border-collapse: collapse;
			width: 100%;
			border: 1px solid #ccc;
		}

		th {
			background-color: #A6C054;
			color: white;
			padding: 8px;
			text-align: left;
			font-size: 13px;
		}

		td {
			padding: 8px;
			text-align: left;
			border-bottom: 1px solid #ccc;
			font-size: 11px;
		}

		.green-border {
			border: 1px solid #A6C054;
		}
	</style>
</head>

<body>
	<?php
	$this->beginBody();
	$title = 	'';
	$subtitle = '';

	if(isset($this->params['title'])) {
		$title = $this->params['title'];
		echo "<h1 class='title'>$title</h1>";
	}

	if(isset($this->params['subtitle'])) {
		$subtitle	= $this->params['subtitle'];
		echo "<p class='subtitle'>$subtitle</p>";
	}

	$filename = $title . '_' . time() . '.xlsx';

	echo $content;

	$this->endBody(); 
	if ($_GET['caraprint'] === 'PRINT') {
		echo '<script>window.print();</script>';
	} 

	if ($_GET['caraprint'] === 'EXCEL') {
		echo "<script>XLSX.writeFileXLSX(XLSX.utils.table_to_book(document.querySelector('table'), {sheet: 'CIS'}), '$filename')</script>";
	}

	?>
</body>

</html>
<?php $this->endPage() ?>