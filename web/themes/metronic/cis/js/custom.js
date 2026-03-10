function checkForm(form_id){
	var $form = $("#"+form_id), data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    if ($form.find('.has-error').length == 0) {
        return true;
    }
    return false;
}

//function openModals(url,modal_show,data){
function openModal(url,modal_id,width,callback){
    if((modal_id == "modal-delete-record") || (modal_id == "modal-global-confirm") || (modal_id == "modal-global-info") 
    || (modal_id == "modal-madul") || (modal_id == "modal-confirm")){
        $(".modals-place-confirm").load(url, function() {
            if(modal_id == "modal-madul" && width != null){
                $("#"+modal_id+" .modal-dialog").css('width',width);
            }
            $("#"+modal_id).modal({backdrop: 'static', keyboard: false});
            $("#"+modal_id).modal('show');
            $("#"+modal_id).on('hidden.bs.modal', function () {
//                $(".modals-place").load();
            });
            spinbtn();
            draggableModal();
        });
    }else{
        clearmodal();
        $(".modals-place").load(url, function() {
            if(width != null){
                $("#"+modal_id+" .modal-dialog").css('width',width);
            }
            $("#"+modal_id).modal({backdrop: 'static', keyboard: false});
            $("#"+modal_id).modal('show');
            $("#"+modal_id).on('hidden.bs.modal', function () {
                $("#"+modal_id).hide();
                $("#"+modal_id).remove();
                $('.modal-backdrop').remove();
                $(".modals-place").html("");
                if(callback != null){
                    eval(callback);
                }
            });
            spinbtn();
            draggableModal();
        });
    }
    return false;
}
    
function clearmodal(){
    $(".modals-place").children('.modal').hide();
    $(".modals-place").children('.modal').remove();
    $('.modal-backdrop').remove();
}

function formrequiredvalidate(ele){
    var $form = $(ele.closest('form')), data = $form.data("yiiActiveForm");
    $.each(data.attributes, function(i) {
        if($form.find($form.data("yiiActiveForm").attributes[i].input).length) {
            this.status = 3;
        }
    });

    $form.yiiActiveForm("validate");
    if ($form.find('.has-error').length == 0) {
        return true;
    }
    return false;
}

function disableformelement($form){
        $form.find('input').each(function(){ $(this).prop("disabled", false); });
	$form.find('input').each(function(){ $(this).attr("readonly","readonly"); });
        $form.find('select').each(function(){ $(this).prop("disabled", false); });
	$form.find('select').each(function(){ $(this).attr("readonly","readonly"); });
	$form.find('checkbox').each(function(){ $(this).attr("readonly","readonly"); });
	$form.find('button').each(function(){ $(this).attr("disabled","disabled"); });
	$form.find('textarea').each(function(){ $(this).prop("disabled", false); });
	$form.find('textarea').each(function(){ $(this).attr("readonly","readonly"); });
}
function enableformelement($form){
        $form.find('input').each(function(){ $(this).prop("disabled", false); });
	$form.find('input').each(function(){ $(this).removeAttr("readonly"); });
        $form.find('select').each(function(){ $(this).prop("disabled", false); });
	$form.find('select').each(function(){ $(this).removeAttr("readonly"); });
        $form.find('checkbox').each(function(){ $(this).prop("disabled", false); });
	$form.find('checkbox').each(function(){ $(this).removeAttr("readonly"); });
        $form.find('button').each(function(){ $(this).prop("disabled", false); });
	$form.find('button').each(function(){ $(this).removeAttr("disabled"); });
        $form.find('textarea').each(function(){ $(this).prop("disabled", false); });
	$form.find('textarea').each(function(){ $(this).removeAttr("readonly"); });
}

function getdefaultajaxerrorresponse(jqXHR){
        cisAlert("ERROR CODE : "+jqXHR.status+"<br> Sorry.. Please Contact IT Support!<br> EXT. 217", jqXHR.statusText); console.log(jqXHR.responseText);
	return false;
}

function formattingDatatable(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='create()' data-original-title='Create New'><i class='fa fa-plus'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='search()' data-original-title='Advanced Search'><i class='fa fa-search'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='print()' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='topdf()' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='toxls()' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function formattingDatatableMaster(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='create()' data-original-title='Create New'><i class='fa fa-plus'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function formattingDatatableReport(sTableId){
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').html("\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PRINT\")' data-original-title='Print Out'><i class='fa fa-print'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"PDF\")' data-original-title='Export to PDF'><i class='fa fa-files-o'></i></a>\n\
        <a class='btn btn-icon-only btn-default tooltips' onclick='printout(\"EXCEL\")' data-original-title='Export to Excel'><i class='fa fa-table'></i></a>\n\
    ");
    $('#'+sTableId+'_wrapper').find('.dataTables_moreaction').addClass('visible-lg visible-md');
    $('#'+sTableId+'_wrapper').find('.dataTables_filter').addClass('visible-lg visible-md visible-sm visible-xs');
    $(".tooltips").tooltip({ delay: 50 });
}

function submitform(ele){
    unformatNumberAll();
    var $form = $('form');
    if(formrequiredvalidate($form)){
        disableformelement($form);
        setTimeout(function(){
           $form.yiiActiveForm("submitForm"); 
        },300);
    }
    return false;
}

function submitformajax(ele,success_callback){
    unformatNumberAll();
    var $form = $(ele.closest('form'));
//    var formData = $form.serialize();
//    var formData = new FormData($form[0]);
    if(formrequiredvalidate($form)){
    disableformelement($form);
    var formData = new FormData($form[0]);
    $.ajax({
        url    : $form.attr('action'),
        type   : 'POST',
        data   : formData,
        // Agar dapat membawa data FILES
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        // --------------- //
        success: function (data) {
            if(data.status){
                if(success_callback != null){
                    eval(success_callback);
                }
                if(data.callback){
                    eval(data.callback);
                }
            }else{
                if(data.message_validate){
                    if(!Array.isArray(data.message_validate)){
                        $form.yiiActiveForm('updateMessages', data.message_validate);
                    }
                }
            }
            if(data.message){
                cisAlert(data.message);
            }
            enableformelement($form);
            $form.find('.progress-success .bar').animate({'width':'0%'});
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
        progress: function(e) {
            if(e.lengthComputable) {
                var pct = (e.loaded / e.total) * 100;
                $form.find('.progress-success .bar').animate({'width':pct.toPrecision(3)+'%'});
            }else{
                console.warn('Content Length not reported!');
            }
        }
    });
    }
    return false;
}

function reloaddttabel(){
    dt_table.ajax.reload();
}

function duplicateType(text,ele_id)
{
    document.getElementById(ele_id).value = text.value.toUpperCase();
}

function upperCase(text,ele_id)
{
    document.getElementById(ele_id).value = text.value.toUpperCase();
}

function refreshForm(obj){
    window.location = $(obj).attr("href");
    return false;
}

function setNumbersOnly(obj){
    var d = $(obj).attr('numeric');
    var value = $(obj).val();
    var orignalValue = value;
    value = value.replace(/[0-9]*/g, "");
    var msg = "Only Integer Values allowed.";

    if (d == 'decimal') {
        value = value.replace(/\./, "");
        msg = "Only Numeric Values allowed.";
    }
    
    if (value != '') {
        orignalValue = orignalValue.replace(/([^0-9].*)/g, "")
        $(obj).val(orignalValue);
    }
}

// Untuk Ajax Progress
(function($, window, undefined) {
    //is onprogress supported by browser?
    var hasOnProgress = ("onprogress" in $.ajaxSettings.xhr());

    //If not supported, do nothing
    if (!hasOnProgress) {
        return;
    }
    
    //patch ajax settings to call a progress callback
    var oldXHR = $.ajaxSettings.xhr;
    $.ajaxSettings.xhr = function() {
        var xhr = oldXHR();
        if(xhr instanceof window.XMLHttpRequest) {
            xhr.addEventListener('progress', this.progress, false);
        }
        
        if(xhr.upload) {
            xhr.upload.addEventListener('progress', this.progress, false);
        }
        
        return xhr;
    };
})(jQuery, window);

function cisAlert(isi,judul){
//    var t = {
//            theme: 'teal',
//            sticky: 0,
//            horizontalEdge: 'top',
//            verticalEdge: 'right',
//        },
//        n = $(this);
//        "" != $.trim("*****") && (t.heading = $.trim(judul)), t.sticky || (t.life = 5000), 
//        $.notific8("zindex", 11500), $.notific8(isi, t),
//        n.attr("disabled", "disabled"), setTimeout(function() {
//            n.removeAttr("disabled")
//    }, 1e3)
    
    toastr.cis(isi,judul, {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-cis",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "progressBar" : true,
        toastClass: 'alert',
        iconClasses: {
            error: 'alert-error',
            info: 'alert-info',
            success: 'alert-success',
            warning: 'alert-warning',
            cis: "icon-bulb",
        }
    })
}

function cisConfirm(isi,judul,ok_callback,ok_warnabtn){
//    Usage
//    cisConfirm("Apakah anda yakin akan meng-close permintaan ini?<br /><br /><a class=\'btn btn-xs hijau\' onclick=\'updateStatusPmr("+pmr_id+",\""+$(ele).val()+"\")\'>Yaqin</a> &nbsp; <a class=\'btn btn-xs grey\'>Tidak</a>");
    if(!ok_callback){
        ok_callback = 'javascript:void(0)';
    }
    if(!ok_warnabtn){
        ok_warnabtn = 'hijau';
    }
    if(!judul){
        judul = "Apakah anda yakin?";
    }
    if(!isi){
        isi = judul+"<br /><br /><a class=\'btn btn-xs "+ok_warnabtn+"\' onclick=\'"+ok_callback+"\'>Tentu Saja</a> &nbsp; <a class=\'btn btn-xs grey\'>Tidak</a>";
    }
    toastr.cis(isi,'', {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-cis",
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "0",
        "extendedTimeOut": "0",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
        "progressBar" : true,
        toastClass: 'alert',
        iconClasses: {
            error: 'alert-error',
            info: 'alert-info',
            success: 'alert-success',
            warning: 'alert-warning',
            cis: "icon-bulb",
        }
    });
}

function draggableModal(){
    if($(".draggable-modal").length > 0){
        $(".draggable-modal").draggable({
            handle: ".modal-header"
        })
    }
}

function spinbtn(){
    $( "button.ciptana-spin-btn" ).each(function( index ) {
        $(this).addClass('ladda-button');
        $(this).attr('data-style','zoom-in');
        var htmlcontent = $(this).html();
        $(this).html('<span class="ladda-label">'+htmlcontent+'</span>');
        
    });
    Ladda.bind('.ciptana-spin-btn', {
        timeout: 1000
    });
    Ladda.bind( 'button[type=submit]' );
}

function spinbtnloading(btnhtml){
    $(btnhtml).addClass('ladda-button');
    $(btnhtml).attr('data-style','expand-right');
    var htmlcontent = $( btnhtml ).html();
    $( btnhtml ).html('<span class="ladda-label">'+htmlcontent+'</span>');
    var btn_ladda = Ladda.create( document.querySelector( btnhtml ) );
    btn_ladda.start();
}

function unformatNumberAll(){
    $('.integer').each(function(){
        $(this).val(parseInt(unformatNumber($(this).val())));
    });
    if(typeof unformatNumber !== "undefined"){
        $('.money-format').each(function(){
            $(this).val(parseInt(unformatNumber($(this).val())));
    //        $(this).maskMoney('destroy');
        });
        $('.float').each(function(){
            $(this).val(unformatNumber($(this).val()));
        });
        $('.float2').each(function(){
            $(this).val(unformatNumber($(this).val()));
        });
    }
}

function formatNumberAll(){
    $('.integer').each(function(){
        $(this).val(formatInteger($(this).val()));
    });
    if(typeof unformatNumber !== "undefined"){
        $('.money-format').each(function(){
            if (!isNaN($(this).val()) && $(this).val().toString().indexOf('.') != -1){
                $(this).val(formatFloat($(this).val()));
            }else{
                $(this).val(formatInteger($(this).val()));
            }            
        });
        $('.float').each(function(){
            if (!isNaN($(this).val()) && $(this).val().toString().indexOf('.') != -1){
                $(this).val(formatFloat($(this).val()));
            }else{
                $(this).val(formatInteger($(this).val()));
            }
        });
    }
}

//function setMenuActive(menuclass,submenuclass){
//    $(".cat-m-"+menuclass).find("span.arrow").addClass("open");
//}

function setMenuActive(menu,module_id=null,menu_group_id=null,menu_id=null){
    menu = $.parseJSON(menu);
    $(".nav-item").removeClass('active');
    if(menu_group_id){
        $('[class*="cat-m"]').find('span.arrow.open').removeClass("open");
        $('[class*="cat-m"]').find('ul').attr("style","display:");
        $(".cat-m-"+menu_group_id).addClass("open");
        $(".cat-m-"+menu_group_id+" > ul").css("display","block");
    }else{
        $(".cat-m-"+menu.menu_group_id).addClass("open");
        $(".cat-m-"+menu.menu_group_id+" > ul").css("display","block");
    }
    if(module_id){
        $('[class*="mod"]').find('span.arrow.open').removeClass("open");
        $('[class*="mod"]').find('ul').attr("style","display:");
        $(".mod-"+module_id).addClass("open");
        $(".mod-"+module_id+" > ul").css("display","block");
    }else{
        $(".mod-"+menu.module_id).addClass("open");
        $(".mod-"+menu.module_id+" > ul").css("display","block");
        $(".mod-" + menu.module_id + " > ul > li").each(function(i, e){
            if ($(e).find('span').text() == menu.name) {
                $(e).addClass('active');
            }
        })
    }
    if(menu_id){
        $(".menu-"+menu_id).addClass('active');
    }else{
        $(".menu-"+menu.menu_id).addClass('active');
    }
}

function formconfig(){
    $('.date-picker').datepicker('remove');
    jQuery().datepicker && $(".date-picker").datepicker({
        rtl: App.isRTL(),
        orientation: "left",
        autoclose: !0,
        format: "dd/mm/yyyy",
        clearBtn:true,
        todayHighlight:true
    }), $(document).scroll(function() {
        $(".modal-body .date-picker").datepicker("place")
    });
    jQuery().datetimepicker && ($(".form_datetime").datetimepicker({
        autoclose: !0,
        isRTL: App.isRTL(),
        format: "dd MM yyyy - hh:ii",
        fontAwesome: !0,
        pickerPosition: App.isRTL() ? "bottom-right" : "bottom-left"
    }), $("body").removeClass("modal-open"), $(document).scroll(function() {
        $("#form_modal1 .form_datetime, #form_modal1 .form_advance_datetime, #form_modal1 .form_meridian_datetime").datetimepicker("place")
    }));
    $('.numbers-only').on('input', function() {
        setNumbersOnly(this);
    });
    if ($.isFunction($.fn.maskMoney)) {
        $('.money-format').maskMoney(
            {'symbol':'','defaultZero':true,'allowZero':true,'decimal':'.','thousands':',','precision':0,allowNegative: true}
        );
        $('.money-format-float').maskMoney(
            {'symbol':'','defaultZero':true,'allowZero':true,'decimal':'.','thousands':',','precision':2,allowNegative: true}
        );
    }
    $('input.float, input.float2').on('focus', function() {
        if(!this.value || this.value==0){
            $(this).val('');
        }
    });
    $('input.float').on('input', function(event) {
        var ori_value = this.value;
        var count_strip = (ori_value.match(/-/g) || []).length;
        if (ori_value.indexOf("-") >= 0){
            var strip = "-";
        }else{
            var strip = "";
        }
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        if (strip === "-") { // klo ada negative
            if(count_strip > 1){
                this.value = this.value.replace("-", "");
            }else{
                this.value = "-" + this.value;
            }
        } else {
            this.value;
        }
    });
    $('input.float').on('blur', function() {
        this.value = formatNumberForUser(this.value);
    });
    $('input.float2').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
    });
}

function resetForm(){
    var currenturl = document.URL;
    var currenturl = currenturl.toLowerCase();
    var splitstring = currenturl.split('?');
    window.location.replace(splitstring[0]);
}

function print(){
    location.reload();
}

function setFlashAlert(){
    var destinationurl = '';
    var currenturl = document.URL;
    var currenturl = currenturl.toLowerCase();
    var str = "/index";
    var str = str.toLowerCase();
    if(currenturl.indexOf(str) != -1){
        destinationurl = currenturl.replace(str, "/setFlashAlert"); 
    }
    $('.page-content form:first .row:first').before('<div class="flashalert-place"></div>')
    $(".flashalert-place").load(destinationurl, function() {
        
    });
}

function openPickPanel(ele,par) {
    document.getElementById("pick-panel").style.width = "45%";
    $.ajax({
        url    : $(ele).data('url'),
        type   : 'POST',
        data   : {par:par},
        success: function (data) {
            if(data){
                $('#pick-panel').html(data);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function closePickPanel() {
    document.getElementById("pick-panel").style.width = "0";
    $('#pick-panel').html('');
}

function reordertable(obj_table){
    var row = 0;
    $(obj_table+' > tbody > tr').each(function(){
        $(this).find("#no_urut").val(row+1);
        $(this).find("span.no_urut").text(row+1);
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]");
            }
            if(old_name_arr.length == 4){
                    $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[2]+"_"+old_name_arr[3]);
                    $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[2]+"]["+old_name_arr[3]+"]");
            }
        });
        row++;
    });
    formconfig();
}

function reorderrepeater(obj_table){
    var row = 0;
    $(obj_table).find("tbody > tr").find('.repeater').each(function(){
        $(this).find('input,select,textarea').each(function(){ //element <input>
            var old_name = $(this).attr("name").replace(/]/g,"");
            var old_name_arr = old_name.split("[");
            if(old_name_arr.length == 3){
                $(this).attr("id",old_name_arr[0]+"_"+row+"_"+old_name_arr[1]+"_"+old_name_arr[2]);
                $(this).attr("name",old_name_arr[0]+"["+row+"]["+old_name_arr[1]+"]["+old_name_arr[2]+"]");
            }
        });
        row++;
    });
}

function cancelItem(ele,callback){
    $(ele).parents('tr').fadeOut(200,function(){
        $(this).remove();
        reordertable('#table-detail');
        if(callback != null){
            eval(callback);
        }
    });
}

function copyToClipboard(text) {
    var textArea = document.createElement( "textarea" );
    textArea.value = text;
    document.body.appendChild( textArea );
    textArea.select();
    try {
       var successful = document.execCommand( 'copy' );
       var msg = successful ? 'successful' : 'unsuccessful';
       console.log('Copying text command was ' + msg);
    } catch (err) {
       console.log('Oops, unable to copy');
    }
    document.body.removeChild( textArea );
}

Number.prototype.countDecimals = function () {
    if(Math.floor(this.valueOf()) === this.valueOf()) return 0;
    return this.toString().split(".")[1].length || 0; 
}

function formatNumberForUser(number){
    if(number.toString().indexOf('.') != -1){
        number = unformatNumber(number);
        if(number.countDecimals() > 4){
            return Math.round(number*100000)/100000;
        }else{
            return formatFloat(number,number.countDecimals());
        }
    }else{
        return formatInteger(number);
    }
}

function formatNumberForUser0Digit(number){
    if(number.toString().indexOf('.') != -1){
        number = unformatNumber(number);
        if(number.countDecimals() > 0){
            return Math.round(number*1)/1;
        }else{
            return formatFloat(number,number.countDecimals());
        }
    }else{
        return formatInteger(number);
    }
}

function formatNumberForUser2Digit(number){
    if(number.toString().indexOf('.') != -1){
        number = unformatNumber(number);
        if(number.countDecimals() > 2){
            return Math.round(number*100)/100;
        }else{
            return formatFloat(number,number.countDecimals());
        }
    }else{
        return formatInteger(number);
    }
}

function formatNumberForUser3Digit(number){
    if(number.toString().indexOf('.') != -1){
        number = unformatNumber(number);
        if(number.countDecimals() > 3){
            return Math.round(number*1000)/1000;
        }else{
            return formatFloat(number,number.countDecimals());
        }
    }else{
        return formatInteger(number);
    }
}

function formatNumberForUser4Digit(number){
    if(number.toString().indexOf('.') != -1){
        number = unformatNumber(number);
        if(number.countDecimals() > 4){
            return Math.round(number*10000)/10000;
        }else{
            return formatFloat(number,number.countDecimals());
        }
    }else{
        return formatInteger(number);
    }
}

function formatNumberFixed4(number){
    var ret = formatNumberForUser(number);
    ret = Number(number);
    return ret.toFixed(4);
}

function formatNumberFixed2(number){
    var ret = formatNumberForUser(number);
    ret = Number(number);
    return ret.toFixed(2);
}

// Pembulatan keatas 2 digit belakang koma (ex. nominal invoice export)
function formatNumberRoundUp2Digit(number){
    var ret = Number(number);
    ret = Math.ceil(ret*100)/100;
    ret = formatFloat(ret);
    return ret;
}

function dateDaysPeriode(start,end){
    var start = start.split("/");
    var end = end.split("/");
    var start = new Date(start[2]+'-'+start[1]+'-'+start[0]);
    var end = new Date(end[2]+'-'+end[1]+'-'+end[0]);
    var date_difference=parseInt((end-start)/(24*3600*1000));
    return date_difference;
}

function setDropdown(url,ele_id,par,callback){
    $('#'+ele_id).addClass('animation-loading');
    $('#'+ele_id).siblings('.select2').addClass('animation-loading');
    $.ajax({
        url    : url,
        type   : 'POST',
        data   : {par:par},
        success: function (data) {
            $("#"+ele_id).html(data.html);
            $("#"+ele_id).removeClass('animation-loading');
            $('#'+ele_id).siblings('.select2').removeClass('animation-loading');
            if ($.isFunction($.fn.selectpicker)) {
                $(".bs-select").selectpicker("refresh");
            }
            if(callback != null){
                eval(callback);
            }
        },
        error: function (jqXHR) { getdefaultajaxerrorresponse(jqXHR); },
    });
}

function formatDateForUser2(tgl){
    var date = new Date(tgl);
    date = date.toString('dd MMM yyyy');
    return date;
}

function formatDateForUser(tgl){
    var date = new Date(tgl);
    date = date.toString('dd/MM/yyyy');
    return date;
}

function formatDateTimeForUser(tgl){
    var date = new Date(tgl);
    date = date.toString('dd-MM-yyyy H:mm:ss');
    return date;
}

function createDate() {
var date    = new Date(),
    yr      = date.getFullYear(),
    month   = date.getMonth()+1,
    day     = date.getDate(),
    todayDate = yr + '-' + month + '-' + day;
    return todayDate;
}

function generateBarcode(value,type="code128"){
    // http://barcode-coder.com/
    var settings = {
        output:"css",
        bgColor: "#FFFFFF",
        color: "#000000",
        barWidth: "1",
        barHeight: "50",
        moduleSize: "5",
        posX: "10",
        posY: "20",
        addQuietZone: "1"
    };
    $("#place-barcode").html("").show().barcode(value,type, settings);
}

// QRCode Reader
function reading(){
	function Q(el) {
        if (typeof el === "string") {
            var els = document.querySelectorAll(el);
            return typeof els === "undefined" ? undefined : els.length > 1 ? els : els[0];
        }
        return el;
    }
    var txt = "innerText" in HTMLElement.prototype ? "innerText" : "textContent";
    var scannerLaser = Q(".scanner-laser"),
        play = Q("#play"),
        scannedQR = Q("#scanned-QR"),
        pause = Q("#pause"),
        stop = Q("#stop");
        
    var args = {
        beep: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/audio/beep.mp3',
        decoderWorker: '/'+window.location.pathname.split( '/' )[1]+'/'+window.location.pathname.split( '/' )[2]+'/themes/metronic/global/plugins/webcodecam/DecoderWorker.js',
        autoBrightnessValue: 100,
        zoom: 1.5,
		width: 280,
		height: 210,
        resultFunction: function(res) {
            [].forEach.call(scannerLaser, function(el) {
				el.style.opacity = 1;
				(function fade() {
					if ((el.style.opacity -= 0.1) < 0.5) {
						el.style.display = "none";
						el.classList.add("is-hidden");
					} else {
						requestAnimationFrame(fade);
					}
				})();
                setTimeout(function() {
                    if (el.classList.contains("is-hidden")) {
						el.classList.remove("is-hidden");
					}
					el.style.opacity = 0;
					el.style.display = "block";
					(function fade() {
						var val = parseFloat(el.style.opacity);
						if (!((val += 0.1) > 0.5)) {
							el.style.opacity = val;
							requestAnimationFrame(fade);
						}
					})();
                }, 300);
            });
//            scannedImg.src = res.imgData;
            scannedQR[txt] = res.format + ": " + res.code;
			pick(res.code);
			selesai_reading();
        },
        getDevicesError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        getUserMediaError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            for (p in error) {
                message += p + ": " + error[p] + "\n";
            }
            alert(message);
        },
        cameraError: function(error) {
            var p, message = "Error detected with the following parameters:\n";
            if (error.name == "NotSupportedError") {
                var ans = confirm("Your browser does not support getUserMedia via HTTP!\n(see: https:goo.gl/Y0ZkNV).\n You want to see github demo page in a new window?");
                if (ans) {
                    window.open("https://andrastoth.github.io/webcodecamjs/");
                }
            } else {
                for (p in error) {
                    message += p + ": " + error[p] + "\n";
                }
                alert(message);
            }
        },
        cameraSuccess: function() {
//            grabImg.classList.remove("disabled");
        }
    };
    
    var decoder = new WebCodeCamJS("#webcodecam-canvas").buildSelectMenu("#camera-select", "environment|back").init(args);
	$("#play").on("click",function(){
		if (!decoder.isInitialized()) {
            scannedQR[txt] = "Scanning ...";
        } else {
            scannedQR[txt] = "Scanning ...";
            decoder.play();
        }
	});
	$("#pause").on("click",function(){
		scannedQR[txt] = "Paused";
        decoder.pause();
	});
	$("#stop").on("click",function(){
		scannedQR[txt] = "Stopped";
        decoder.stop();
	});
//	$("#play").trigger("click");
}

function selesai_reading(){
    $("#pause").trigger("click");
}

// END QRCode reader

$(document).ready(function(){
    spinbtn();
});


function numberWithCommasWithoutPoint(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


function colorForApprover(status) {
    switch (status) {
        case 'APPROVED':
            return 'success';
        case 'REJECTED':
            return 'danger';
        case 'ABORTED':
            return 'danger';
        case 'Not Confirmed':
            return 'default';
        default:
            return 'default';
    }
}

/**
 *
 * @returns string
 */
function getCurrentTime() {
    const date = new Date();
    return [
        date.getHours(),
        date.getMinutes(),
        date.getSeconds()
    ].reduce((prev, curr, i) => prev + (i !== 0 ? ':' : '') + (curr < 10 ? '0' + curr : curr), '');
}