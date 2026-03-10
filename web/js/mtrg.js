window.mtrg = (function ($) {
    function Mask(els) {
        for (let i = 0; i < els.length; i++) {
            this[i] = els[i];
        }
        this.length = els.length;
    }

    function Storage(key) {
        this.key = key;
    }

    function Detail(ctx, section = 'output') {
        this.context = ctx;
        this.section = section;
    }

    // ======== MTRG UTILS ========

    Mask.prototype.label        = '';
    Mask.prototype.grades       = [];
    Mask.prototype.fields       = [];
    Mask.prototype.idTotalVol   = 'mtrg-total-volume';
    Mask.prototype.tableId      = 'mtrg-table-detail';
    Mask.prototype.labelId      = 'mtrg-label';
    Mask.prototype.context      = undefined;
    Mask.prototype.sizes        = { "1.67445": "3x6", "2.9768": "4x8", "2.6047": "4x7" };

    Mask.prototype.template = function () {
        return `<div class="form-group">
                    <label class="col-md-4 control-label" id="${this.labelId}"></label>
                    <div class="col-md-7">
                        <table id="${this.tableId}" class="table table-hover" style="font-size: 1.2rem !important;">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>`
    };

    Mask.prototype.setLabel = function (name) {
        this.label = name;
        return this;
    }

    Mask.prototype.setGrades = function (grades) {
        this.grades = grades;
        return this;
    }

    Mask.prototype.setFields = function (fields) {
        this.fields = fields;
        return this;
    }

    Mask.prototype.render = function () {
        const template = $(this.template());
        $(template).find('#mtrg-label').html(this.label);
        $(template).find('#mtrg-table-detail').find('thead').html(`<tr>${this.fields.map(d => '<th>' + this.ucwords(d.name) + '</th>').join('')}<th>Action</th></tr>`);

        const renderOptions = function (options) {
            let opt = '';
            if (options !== undefined && Object.keys(options).length) {
                for (let attr in options) {
                    opt += ` ${attr}="${options[attr]}"`;
                }
            }
            return opt;
        }

        const renderField = function (fields, i, value) {
            return fields.map(d => {
                return `<td class="td-kecil">
                        <input  class="form-control ${d.class || ''}" 
                                style="padding: 3px; font-size:1.2rem; height:30px; ${d.style || ''}"
                                type="${d.type}"
                                name="detail[${i}][${d.name}]" 
                                ${d.value ? 'value="' + value + '"' : ''}
                                ${renderOptions(d.options)}
                        >
                    </td>`;
            }).join('');
        }
        let i = 1;
        let input = '';
        for (const key in this.grades) {
            input += `
                <tr>
                    ${renderField(this.fields, i, this.grades[key])}
                    <td class="td-kecil text-center">
                        <button class="btn btn-xs red btn-hapus bold" type="button">x</button>
                    </td>
                </tr>
            `;

            i++;
        }

        template.find('#mtrg-table-detail > tbody').html(input);
        const footer = `
            <tr id="footer">
                <th class="text-right" colspan="3">Total</th>
                <th colspan="2" id="${this.idTotalVol}">0.0000</th>
            </tr>
        `;
        template.find('#mtrg-table-detail > tbody').append(footer);
        $(this).html(template);
        $(':input').on('change', () => this.countVolume());
        $('.btn-hapus').on('click', (e) => this.removeElm(e.target));
    }


    Mask.prototype.ucwords = function (str) {
        return str.toLowerCase().replace(/\b[a-z]/g, function (letter) {
            return letter.toUpperCase();
        });
    }

    Mask.prototype.total = function () {
        const total = $('input[name*="volume"]').toArray().reduce((prev, current) => {
            const val = $(current).val() ? parseFloat($(current).val()) : 0;
            return prev + val;
        }, 0);
        $(`#${this.idTotalVol}`).text(total.toFixed(4));
    }

    Mask.prototype.countVolume = function () {
        const table = $(`#${this.tableId}`);
        const that = this;
        if (table.length) {
            table.find('tbody > tr').each(function (i, tr) {
                if (tr.id !== 'footer') {
                    const size = $("input:radio[name*='size']:checked").val() ?? that.getKeyFromSize($(`input[name="detail[${i + 1}][size]"]`, tr).val());
                    const tebal = $(`input[name="detail[${i + 1}][tebal]"]`, tr).val();
                    const pcs = $(`input[name="detail[${i + 1}][pcs]"]`, tr).val();
                    const volume = $(`input[name="detail[${i + 1}][volume]"]`, tr);
                    if (tebal !== '' && pcs !== '' && size !== '') {
                        const vol = tebal / 1000 * size * pcs;
                        volume.val(vol.toFixed(4));
                    }
                }
            })
        }

        this.total();
    }

    Mask.prototype.removeElm = function (elm) {
        $(elm).parents('tr').fadeOut(300, () => {
            $(elm).remove();
            this.total();
        });
    }

    Mask.prototype.on = function (evt, cb) {
        $(this).on(evt, cb);
        return this;
    }

    Mask.prototype.setContext = function (context) {
        this.context = context;
        return this;
    }

    Mask.prototype.getSizeFromKey = function (value) {
        for (let key in this.sizes) {
            if (key === value) {
                return data[value];
            }
        }
    }

    Mask.prototype.getKeyFromSize = function (value) {
        for (let key in this.sizes) {
            if (this.sizes[key] === value) {
                return key;
            }
        }
    }

    //=======  DETAIL UTILS =======

    Detail.prototype.renderDetail = function () {
        const that = this;
        const tblDesktop = $(`#table-${this.section}-${this.context}-detail-desktop .data-details`);
        const tblMobile = $(`#table-${this.section}-${this.context}-detail-mobile .data-details`);
        tblDesktop.empty();
        tblMobile.empty();
        const storage = new Storage(`${this.section}-${this.context}-details`);
        if (storage.get().length > 0) {
            storage.get().forEach(function (row) {
                tblDesktop.append(`
                    <tr>
                        <td class="text-center">${row['id']}</td>
                        <td>${row['unit']}</td>
                        <td>${row['patching'] || row['grade']}</td>
                        <td>${row['tebal']}</td>
                        <td>${Detail.getKeySize(row['size'])}</td>
                        <td>${row['pcs']}</td>
                        <td>${row['volume']}</td>
                        <td class="text-center">
                            <button type="button" class="btn btn-warning btn-xs btn-edit" onclick="mtrg.detail('${that.context}', '${that.section}').editRow(${row['id']})"><i class="fa fa-pencil btn-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-xs btn-remove" onclick="mtrg.detail('${that.context}', '${that.section}').removeRow(${row['id']})"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                `);
                tblMobile.append(`
                    <tr>
                        <td class="text-center" style="vertical-align: middle">${row['id']}</td>
                        <td>
                            <table>
                                <tr>
                                    <td>Unit</td>
                                    <td>&nbsp;: ${row['unit']}</td>
                                </tr>
                                <tr>
                                    <td>Grade</td>
                                    <td>&nbsp;: ${row['patching'] || row['grade']}</td>
                                </tr>
                                <tr>
                                    <td>Tebal</td>
                                    <td>&nbsp;: ${row['tebal']}</td>
                                </tr>
                                <tr>
                                    <td>Size</td>
                                    <td>&nbsp;: ${Detail.getKeySize(row['size'])}</td>
                                </tr>
                                <tr>
                                    <td>PCS</td>
                                    <td>&nbsp;: ${row['pcs']}</td>
                                </tr>
                                <tr>
                                    <td>Volume</td>
                                    <td>&nbsp;: ${row['volume']}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="text-center" style="vertical-align: middle">
                            <button type="button" class="btn btn-warning btn-sm btn-edit margin-bottom-10" onclick="mtrg.detail('${that.context}', '${that.section}').editRow(${row['id']})"><i class="fa fa-pencil btn-edit"></i></button>
                            <button type="button" class="btn btn-danger btn-sm btn-remove" onclick="mtrg.detail('${that.context}', '${that.section}').removeRow(${row['id']})"><i class="fa fa-trash-o"></i></button>
                        </td>
                    </tr>
                `);
            });
        } else {
            tblDesktop.append('<tr><td colspan="9" style="text-align: center; font-style: italic">Tidak ada item</td></tr>');
            tblMobile.append('<tr><td colspan="3" style="text-align: center; font-style: italic">Tidak ada item</td></tr>');
        }
    }

    Detail.getKeySize = function (value) {
        const data = { "1.67445": "3x6", "2.9768": "4x8", "2.6047": "4x7" };
        for (let key in data) {
            if (key === value) {
                return data[value];
            }
        }
    }

    Detail.prototype.removeRow = function (id) {
        const storage = new Storage(`${this.section}-${this.context}-details`)
        storage.set(storage.get().filter(row => row.id !== id));
        this.renderDetail();
    }

    Detail.prototype.editRow = function (id) {
        const storage = new Storage(`${this.section}-${this.context}-details`);
        const data = storage.findById(id);
        const modalId = `modal-${this.section}-detail`;
        const path = window.location.pathname.split('/');
        path[path.length - 1] = `update${this.section}detail`; 
        openModal(`${path.join('/')}?${$.param(data)}`, modalId, null);
    }

    // ======= STORAGE UTILS =======
    Storage.prototype.get = function () {
        const storages = localStorage.getItem(this.key);
        if (storages !== null && storages.length) {
            return JSON.parse(storages).sort((a, b) => a.id - b.id);
        }

        return [];
    }

    Storage.prototype.insert = function (data) {
        localStorage.setItem(
            this.key, JSON.stringify(
                this.get().concat(data).map((row, i) => ({ ...row, id: i + 1 }))
            )
        );
        return this;
    }

    Storage.prototype.set = function (data) {
        localStorage.setItem(this.key, JSON.stringify(data));
        return this;
    }

    Storage.prototype.findById = function (id) {
        return this.get().length
            ? this.get().filter(row => row.id === id)[0]
            : false;
    }

    Storage.prototype.clear = function () {
        localStorage.removeItem(this.key);
        return this;
    }

    Storage.prototype.update = function (data) {
        this.set(this.get().map(d => d.id === data.id ? ({ ...d, ...data }) : d));
        return this;
    }

    //======= INIT =======

    return {
        $: e => new Mask($(e)),
        storage: key => new Storage(key),
        detail: (ctx, section) => new Detail(ctx, section),
        Detail
    }

}($));