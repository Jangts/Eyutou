block([
    '$_/dom/Elements/'
], function(_, global) {
    var location = global.location,
        $ = _.dom.select;
    if (global.parent && global.parent.alert && window.parent.onhashchange) {
        global.alert = global.parent.alert;
        window.parent.location.hash = location.pathname + location.search;
        window.parent.onhashchange(window.parent.location.hash);
    }
}, true);