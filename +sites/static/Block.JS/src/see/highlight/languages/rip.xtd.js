/*!
 * Block.JS Framework Source Code
 *
 * static see.highlight.language
 *
 * Date: 2017-04-06
 */
;
block('$_/see/highlight/highlight.xtd', function(_, global, undefined) {
    _.see.highlight.languages.rip = {
        'comment': /#.*/,

        'keyword': /(?:=>|->)|\b(?:class|if|else|switch|case|return|exit|try|catch|finally|raise)\b/,

        'builtin': /@|\bSystem\b/,

        'boolean': /\b(?:true|false)\b/,

        'date': /\b\d{4}-\d{2}-\d{2}\b/,
        'time': /\b\d{2}:\d{2}:\d{2}\b/,
        'datetime': /\b\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\b/,

        'character': /\B`[^\s`'",.:;#\/\\()<>\[\]{}]\b/,

        'regex': {
            pattern: /(^|[^/])\/(?!\/)(\[.+?]|\\.|[^/\\\r\n])+\/(?=\s*($|[\r\n,.;})]))/,
            lookbehind: true
        },

        'symbol': /:[^\d\s`'",.:;#\/\\()<>\[\]{}][^\s`'",.:;#\/\\()<>\[\]{}]*/,
        'string': /("|')(\\?.)*?\1/,
        'number': /[+-]?(?:(?:\d+\.\d+)|(?:\d+))/,

        'punctuation': /(?:\.{2,3})|[`,.:;=\/\\()<>\[\]{}]/,

        'reference': /[^\d\s`'",.:;#\/\\()<>\[\]{}][^\s`'",.:;#\/\\()<>\[\]{}]*/
    };
});