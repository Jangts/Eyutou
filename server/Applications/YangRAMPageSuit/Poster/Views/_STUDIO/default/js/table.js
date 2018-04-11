tangram.block([
    '$_/dom/Elements/'
], function(_) {
    var
        $ = _.dom.select;

    if (window.parent) {
        window.alert = window.parent.alert;
    }
}, true);