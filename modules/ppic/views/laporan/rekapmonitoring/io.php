<?php

use app\models\MDefaultValue;
use yii\helpers\Json;
use yii\helpers\Url;
?>
<div class="row">
    <div class="col text-right" style="margin-right: 15px">
        <div class="col-md-12">
            <table class="table table-bordered table-hover" id="table-monitoring-rekap" style="width: 100%">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Tanggal Kupas</th>
                        <th class="text-center">Tanggal Produksi</th>
                        <th class="text-center">Kode</th>
                        <th class="text-center">Shift</th>
                        <th class="text-center">Status I/O</th>
                        <th class="text-center">Kategori Proses</th>
                        <th class="text-center">Jenis Kayu</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Grade</th>
                        <th class="text-center">Tebal</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Volume</th>
                        <th class="text-center">Patching</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    function init() {
        const table = $('#table-monitoring-rekap').DataTable({
            ajax: {
                url: '<?= Url::toRoute('/ppic/laporan/rekapmonitoring') ?>',
                type: 'POST',
                data: function(d) {
                    d.length = $('#filter-rekap :input[name=length]').val();
                    d.startdate = $('#filter-rekap :input[name=startdate]').val();
                    d.enddate = $('#filter-rekap :input[name=enddate]').val();
                    d.kode = $('#filter-rekap :input[name=kode]').val();
                    d.status_in_out = $('#filter-rekap :input[name=status_in_out]').val();
                    d.shift = $('#filter-rekap :input[name=shift]').val();
                    d.kategori_proses = $('#filter-rekap :input[name=kategori_proses]').val();
                    d.jenis_kayu = $('#filter-rekap :input[name=jenis_kayu]').val();
                },
            },
            pageLength: 25,
            responsive: true,
            searching: false,
            processing: true,
            serverSide: true,
            columnDefs: [{
                    targets: "_all",
                    className: 'text-center'
                },
                {
                    targets: 0,
                    render: (data, type, row, meta) => 1 + meta.row + meta.settings._iDisplayStart,
                },
                {
                    targets: 1,
                    render: data => formatDateForUser(data)
                },
                {
                    targets: 2,
                    render: data => formatDateTimeForUser(data)
                },
                {
                    targets: 11,
                    render: data => getKeySize(data)
                }
            ],
            drawCallback: function(settings) {
                formattingDatatableReport(settings.sTableId);
                toogleFont();
            }
        });

        $('#filter-rekap :input').each(function() {
            if ($(this).attr('name') === 'kode') {
                $(this).on('input', $.debounce(250, reload));
            } else {
                $(this).on('change', reload);
            }
        });

        function reload() {
            table.ajax.reload();
        }

        function toogleFont() {
            const font = $('#filter-rekap :input[name=font]').val();
            $('th').each(function() {
                $(this).removeClass('td-kecil')
                if (font === 'small') {
                    $(this).addClass('td-kecil');
                }
            });

            $('td').each(function() {
                $(this).removeClass('td-kecil')
                if (font === 'small') {
                    $(this).addClass('td-kecil');
                }
            });
        }
    }

    function printout(caraPrint) {
        window.open("<?= Url::toRoute('/ppic/laporan/rekapmonitoringprint') ?>?caraprint=" + caraPrint + "&" + $('#filter-rekap').serialize(), "",
            'location=_new, width=1200px, scrollbars=yes');
    }

    function getKeySize(value) {
        const items = '<?= Json::encode(MDefaultValue::getOptionList('size')) ?>';
        const data = JSON.parse(items);
        for (const key in data) {
            if (key === value) {
                return data[value];
            }
        }
    }
</script>