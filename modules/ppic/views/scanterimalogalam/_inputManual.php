<?php 
use yii\bootstrap\Html;
use yii\helpers\Url;
?>
<div class="modal fade zzz" id="modal-inputManual" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header text-left">
                <b>INPUT PENERIMAAN LOG</b>
            </div>
            <div class="modal-body" style="min-height: 70vh;">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <form action="" class="form-inline">
                            <?php 
                            echo Html::dropDownList('clause', 'no_barcode', [
                                'no_barcode' => 'No. QRcode', 
                                'no_btg' => 'No. Batang', 
                                'no_lap' => 'No. Lapangan', 
                                'no_grade' => 'No. Grade',
                                'no_produksi' => 'No. Produksi'
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
                <hr>
                <div id="loader" class="margin-top-10" style="display: none;">Please wait...</div>
                <div class="row margin-top-20" id="multiple-result" style="display: none;">
                    <div class="col-md-12 col-sm-12 col-lg-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle;">No</th>
                                    <th colspan="5" class="text-center">Nomor</th>
                                    <th rowspan="2"></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Batang</th>
                                    <th class="text-center">Produksi</th>
                                    <th class="text-center">Lapangan</th>
                                    <th class="text-center">QRCode</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
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
        url: '<?= Url::toRoute(['/ppic/scanterimalogalam/inputManuals']); ?>',
        type: 'POST',
        data: {
            clause: $('#clause').val(),
            keyword: $('#keyword').val()
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
            }else {
                multipleResult(res.datas) && $('#multiple-result').show();
            }
        },
        error: function(jqXHR) {
            getdefaultajaxerrorresponse(jqXHR);
        },
    });
}

function showDetail(datas) {
    $.ajax({
        url: '<?= Url::toRoute(['/ppic/scanterimalogalam/showDetail']); ?>',
        type: 'POST',
        data: {
            datas: datas
        },
        success: function(data) {
            if (data) {
                if (data['msg'] == "Data ok") {
                    modalReview('<?= Url::toRoute(['/ppic/scanterimalogalam/review','terima_logalam_detail_id'=>'']) ?>' + data['terima_logalam_detail_id']);
                } else if (data['msg'] == "Data sudah ada" || data['msg'] == "Data log alam untuk dijual") {
                    modalReview('<?= Url::toRoute(['/ppic/scanterimalogalam/view','terima_logalam_detail_id'=>'']) ?>' + data['terima_logalam_detail_id'] + '&peruntukan=' + data['peruntukan'])
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
    $('#modal-inputManual').modal('toggle');
}

function setKeywordPlaceholder(e) {
    switch ($(e).val()) {
        case 'no_btg':
            $('#keyword').attr('placeholder', 'Masukan nomor batang');
            break;
        case 'no_lap':
            $('#keyword').attr('placeholder', 'Masukan nomor lapangan');
            break;
        case 'no_grade':
            $('#keyword').attr('placeholder', 'Masukan nomor grade');
            break;
        case 'no_produksi':
            $('#keyword').attr('placeholder', 'Masukan nomor produksi');
            break;
        default:
            $('#keyword').attr('placeholder', 'Masukan nomor barcode');
            break;
    }
}

function multipleResult(datas){
    let html = '';
    datas.forEach(function(v, i) {
        html += `<tr>
                    <td class="text-center td-kecil">${i + 1}</td>
                    <td class="text-center td-kecil">${v.no_grade}</td>
                    <td class="text-center td-kecil">${v.no_btg}</td>
                    <td class="text-center td-kecil">${v.no_produksi}</td>
                    <td class="text-center td-kecil">${v.no_lap}</td>
                    <td class="text-center td-kecil">${v.no_barcode}</td>
                    <td class="text-center td-kecil">
                        <button onclick="showDetail('ID : ${v.terima_logalam_detail_id}\\nNo : ${v.no_barcode}')" class="btn btn-xs btn-info">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>`
    });
    $('#multiple-result').show();
    $('#multiple-result table tbody').html(html);
    return datas.length > 0;
}

function modalReview(url) {
    $('.modals-place-2').load(url, function(res) {
        $('#modal-review').modal('show');
    })
}
</script>