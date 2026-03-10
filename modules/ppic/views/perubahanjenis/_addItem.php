<?php 
use yii\bootstrap\Html;
use yii\helpers\Url;
?>
<div class="modal fade zzz" id="modal-addItem" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left">
                <b>INPUT DATA LOG</b>
            </div>
            <div class="modal-body" style="min-height: 20vh;">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <form action="" class="form-inline">
                            <?php 
                            echo Html::dropDownList('clause', 'no_barcode', [
                                'no_barcode' => 'No. QRcode', 
                                'no_lap' => 'No. Lapangan'
                            ], [
                                'class' => 'form-control margin-top-10',
                                'id' => 'clause',
                                'onchange' => 'setKeywordPlaceholder(this)'
                            ]);
                            echo '&nbsp;'; 
                            echo Html::textInput('keyword', null, [
                                'class' => 'form-control margin-top-10',
                                'id' => 'keyword',
                                'placeholder' => 'Masukan nomor barcode'
                            ]);
                            echo '&nbsp;';
                            echo Html::button('Cek', ['class' => 'btn btn-primary margin-top-10', 'onclick' => 'kirim()']);
                            echo '&nbsp;';
                            echo Html::button('Batal', ['class' => 'btn btn-outline red margin-top-10', 'onclick' => 'cancel()']);
                            ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

<?php 
$this->registerCssFile($this->theme->baseUrl."/pages/css/profile.min.css");
$this->registerJs("formconfig();"); 
?>
<script>
function kirim() {
    $('#multiple-result').hide();
    $('#loader').show();
    $.ajax({
        url: '<?= Url::toRoute(['/ppic/perubahanjenis/addItems']); ?>',
        type: 'POST',
        data: {
            clause: $('#clause').val(),
            keyword: $('#keyword').val(),
            peruntukan: '<?= $peruntukan; ?>'
        },
        success: function(res) {
            $('#loader').hide();
            if (!res.status) {
                cisAlert(res.message);
                return;
            }
            if(res.datas.length === 0) {
                cisAlert('Data tidak ditemukan');
                return;
            }

            if(!Array.isArray(res.datas)) {
                showDetail(res.datas);
            }
        },
        error: function(jqXHR) {
            getdefaultajaxerrorresponse(jqXHR);
        },
    });
}

function showDetail(datas) {
    $.ajax({
        url: '<?= Url::toRoute(['/ppic/perubahanjenis/showDetail']); ?>',
        type: 'POST',
        data: {
            datas: datas
        },
        success: function(data) {
            if (data) {
                var no_barcode = data[1].split(':')[1].trim();
                if (data['msg'] == "Data ok") {
                    modalReview('<?= Url::toRoute(['/ppic/perubahanjenis/review','no_barcode'=>'']) ?>' + no_barcode);
                } else {
                    cisAlert(data['msg']);
                }
            }
        },
        error: function(jqXHR) {
            getdefaultajaxerrorresponse(jqXHR);
        },
    });
}

function cancel() {
    $('#modal-addItem').modal('toggle');
}

function setKeywordPlaceholder(e) {
    switch ($(e).val()) {
        case 'no_lap':
            $('#keyword').attr('placeholder', 'Masukan nomor lapangan');
            break;
        default:
            $('#keyword').attr('placeholder', 'Masukan nomor barcode');
            break;
    }
}

function modalReview(url) {
    $('.modals-place-2').load(url, function(res) {
        $('#modal-review').modal('show');
    })
}
</script>