tangram.auto([
    '$_/dom/Elements/'
], function(_, global, undefined) {
    var $ = _.dom.select,
        parent = global.parent,
        target = parent.document.getElementById("area-main");

    $('li[data-href]').click(function() {
        if ($(this).hasClass('selected')) {
            return true;
        }
        $('.selected').removeClass('selected');
        target.src = $(this).addClass('selected').data('href');
    });
});