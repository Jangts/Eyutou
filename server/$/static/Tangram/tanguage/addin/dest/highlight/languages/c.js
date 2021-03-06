/*!
 * tanguage script compiled code
 *
 * Datetime: Tue, 22 May 2018 02:31:31 GMT
 */
;
// tang.config({});
tang.init().block([
	'~/../',
	'~/../languages/clike'
], function (pandora, root, imports, undefined) {
	var highlight = pandora.highlight;
	highlight.languages.c = highlight.languages.extend('clike', {
		'keyword': /\b(asm|typeof|inline|auto|break|case|char|const|continue|default|do|double|else|enum|extern|float|for|goto|if|int|long|register|return|short|signed|sizeof|static|struct|switch|typedef|union|unsigned|void|volatile|while)\b/,
		'operator': /\-[>-]?|\+\+?|!=?|<<?=?|>>?=?|==?|&&?|\|?\||[~^%?*\/]/,
		'number': /\b-?(?:0x[\da-f]+|\d*\.?\d+(?:e[+-]?\d+)?)[ful]*\b/i
	});
	highlight.languages.insertBefore('c', 'string', {
		'macro': {
			pattern: /(^\s*)#\s*[a-z]+([^\r\n\\]|\\.|\\(?:\r\n?|\n))*/im,
			lookbehind: true,
			alias: 'property',
			inside: {
				'string': {
					pattern: /(#\s*include\s*)(<.+?>|("|')(\\?.)+?\3)/,
					lookbehind: true
				},
				'directive': {
					pattern: /(#\s*)\b(define|elif|else|endif|error|ifdef|ifndef|if|import|include|line|pragma|undef|using)\b/,
					lookbehind: true,
					alias: 'keyword'
				}
			}
		},
		'constant': /\b(__FILE__|__LINE__|__DATE__|__TIME__|__TIMESTAMP__|__func__|EOF|NULL|stdin|stdout|stderr)\b/
	});
	delete highlight.languages.c['class-name'];
	delete highlight.languages.c['boolean'];
}, true);
//# sourceMappingURL=c.js.map