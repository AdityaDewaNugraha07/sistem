export function $$(selector, event, callback) {
    $(document).on(event, function (e) {
        if(selector.charAt(0) === '.') {
            if($(e.target).hasClass(selector.replace('.',''))) {
                callback(e);
            }
        }else if(selector.charAt(0) === '#') {
            if(e.target.id === selector.replace('#', '')) {
                callback(e);
            }
        }else {
            if($(selector).length) {
                callback(e);
            }
        }
    })
}
