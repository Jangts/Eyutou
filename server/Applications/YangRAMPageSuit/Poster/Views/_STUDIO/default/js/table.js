tang.block([
    '$_/dom/Elements'
], function(_) {
    var
        $ = _.dom.$;

    if (window.parent) {
        window.alert = window.parent.alert;
    }
}, true);