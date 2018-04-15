/*!
 * tangram.js framework source code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
tangram.block('$_/see/highlight/highlight', function(_, global, undefined) {
    _.see.highlight.languages.nasm = {
        'comment': /;.*$/m,
        'string': /("|'|`)(\\?.)*?\1/m,
        'label': {
            pattern: /(^\s*)[A-Za-z._?$][\w.?$@~#]*:/m,
            lookbehind: true,
            alias: 'function'
        },
        'keyword': [
            /\[?BITS (16|32|64)\]?/m, {
                pattern: /(^\s*)section\s*[a-zA-Z\.]+:?/im,
                lookbehind: true
            },
            /(?:extern|global)[^;\r\n]*/im,
            /(?:CPU|FLOAT|DEFAULT).*$/m
        ],
        'register': {
            pattern: /\b(?:st\d|[xyz]mm\d\d?|[cdt]r\d|r\d\d?[bwd]?|[er]?[abcd]x|[abcd][hl]|[er]?(bp|sp|si|di)|[cdefgs]s)\b/i,
            alias: 'variable'
        },
        'number': /(\b|-|(?=\$))(0[hx][\da-f]*\.?[\da-f]+(p[+-]?\d+)?|\d[\da-f]+[hx]|\$\d[\da-f]*|0[oq][0-7]+|[0-7]+[oq]|0[by][01]+|[01]+[by]|0[dt]\d+|\d*\.?\d+(\.?e[+-]?\d+)?[dt]?)\b/i,
        'operator': /[\[\]*+\-\/%<>=&|$!]/
    };
});