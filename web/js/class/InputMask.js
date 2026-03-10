import {ucwords} from "../core/Utils.js";
import { Storage } from '../modules/mon-produksi.js';

export default class InputMask {

    constructor(options = { selector, label, grades, fields }) {
        this.selector       = options.selector;
        this.label          = options.label;
        this.grades         = options.grades;
        this.fields         = options.fields;
        this.render         = this.render.bind(this);
        this.total          = this.total.bind(this);
        this.countVolume    = this.countVolume.bind(this);
        this.removeElm      = this.removeElm.bind(this);
        this.submit         = this.submit.bind(this);
    }

    render() {
        const template = $(`
            <div class="form-group">
                <label class="col-md-4 control-label">${this.label}</label>
                <div class="col-md-7">
                    <table id="tb-detail" class="table table-hover" style="font-size: 1.2rem !important;">
                        <thead>
                        <tr>
                            ${this.fields.map(d => '<th>' + ucwords(d.name) + '</th>').join('')}
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        `);

        const renderOptions = function (options) {
            let opt = '';
            if(options !== undefined && Object.keys(options).length) {
                for (let attr in options) {
                    opt += ` ${attr}="${options[attr]}"`;
                }
            }
            return opt;
        }
        const renderField = function (fields, i, value) {
            return fields.map(d => {
                return `<td class="td-kecil">
                        <input  class="form-control ${d.class}" 
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
        template.find('#tb-detail > tbody').html(input);

        const footer = `
            <tr id="footer">
                <th class="text-right" colspan="3">Total</th>
                <th colspan="2" id="total">0.0000</th>
            </tr>
        `;
        template.find('#tb-detail > tbody').append(footer);
        $(this.selector).html(template);
    }

    countVolume() {
        const table = $('#tb-detail');
        if(table.length) {
            table.find('tbody > tr').each(function (i, tr) {
                if(tr.id !== 'footer') {
                    const size = $("input:radio[name*='size']:checked").val();
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

    removeElm(elm) {
        $(elm).parents('tr').fadeOut(300, () => {
            $(elm).remove();
            this.total();
        });
    }

    total() {
        const total = $('input[name*="volume"]').toArray().reduce((prev, current) => {
            const val = $(current).val() ? parseFloat($(current).val()) : 0;
            return prev + val;
        }, 0);
        $('#total').text(total.toFixed(4));
    }

    submit(form, url, keyLocalStorage, callback = function () {}) {
        const storage = new Storage(keyLocalStorage);
        $.ajax({
            url: url,
            type: 'POST',
            data: $(form).serialize(),
            success: function (result) {
                try {
                    const oldData = storage.local
                    let data = result;
                    if(oldData !== null && oldData.length > 0) {
                        data = oldData.concat(data)
                    }
                    storage.local = data.map((row, i) => ({...row, id: i + 1}));
                    callback();
                }catch (e) {
                    alert(e.message);
                }
            }
        });
    }
}