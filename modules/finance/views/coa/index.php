<?php
/* @var $this yii\web\View */
$this->title = 'Master Chart of Account';
app\assets\JstreeAsset::register($this);
?>
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title"> <?php echo $this->title; ?></h1>
<!-- END PAGE TITLE-->
<!-- END PAGE HEADER-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- BEGIN EXAMPLE TABLE PORTLET-->
						<?= \yii\bootstrap\Html::textInput('ajax_q',null,['placeholder'=>'Search','id'=>'ajax_q','class'=>'form-control input-small']) ?><br>
                        <div id="ajax" class="demo"></div>
                        <!-- END EXAMPLE TABLE PORTLET-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->registerJs("initJsTree(); ", yii\web\View::POS_READY); ?>
<script>
function initJsTree(){
	$('#ajax').jstree({
		'core' : {
			'data' : {
				"url" : "<?= yii\helpers\Url::toRoute('/finance/coa/source') ?>",
				"dataType" : "json" // needed only if you do not supply JSON headers
			},
			"themes" : { "stripes" : true },
		},
		"plugins" : [
			"search",
			"wholerow"
		],
		"search": {
			'show_only_matches': true,
		}
	});
	var to = false;
	$('#ajax_q').keyup(function () {
	  if(to) { clearTimeout(to); }
	  to = setTimeout(function () {
		var v = $('#ajax_q').val();
		$('#ajax').jstree(true).search(v);
	  }, 250);
	});
}

</script>