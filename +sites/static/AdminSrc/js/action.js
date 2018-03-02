block([
    '$_/dom/Elements/'
], function(_) {
    var
        $ = _.dom.select;
    if (window.parent) {
        $('a[href]').click(function() {
            if ($(this).attr('href') != 'javascript:;') {
                var target = $(this).attr('target');
                if (target == null || target == '_self') {
                    window.parent.location.hash = $(this).attr('href');
                }
            }
        });
    }
}, true);