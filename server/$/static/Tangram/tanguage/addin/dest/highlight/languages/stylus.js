/*!
 * tanguage script compiled code
 *
 * Datetime: Tue, 22 May 2018 02:31:32 GMT
 */
;
// tang.config({});
tang.init().block([
	'~/../'
], function (pandora, root, imports, undefined) {
	var highlight = pandora.highlight;
	var inside = {
		'url': /url\((["']?).*?\1\)/i,
		'string': /("|')(?:[^\\\r\n]|\\(?:\r\n|[\s\S]))*?\1/,
		'interpolation': null,
		'func': null,
		'important': /\B!(?:important|optional)\b/i,
		'keyword': {
			pattern: /(^|\s+)(?:(?:if|else|for|return|unless)(?=\s+|$)|@[\w-]+)/,
			lookbehind: true
		},
		'hexcode': /#[\da-f]{3,6}/i,
		'number': /\b\d+(?:\.\d+)?%?/,
		'boolean': /\b(?:true|false)\b/,
		'operator': [
			/~|[+!\/%<>?=]=?|[-:]=|\*[*=]?|\.+|&&|\|\||\B-\B|\b(?:and|in|is(?: a| defined| not|nt)?|not|or)\b/
		],
		'punctuation': /[{}()\[\];:,]/
	};
	inside['interpolation'] = {
		pattern: /\{[^\r\n}:]+\}/,
		alias: 'variable',
		inside: _.copy(inside)
	};
	inside['func'] = {
		pattern: /[\w-]+\([^)]*\).*/,
		inside: {
			'function': /^[^(]+/,
			rest: _.copy(inside)
		}
	};
	highlight.languages.stylus = {
		'comment': {
			pattern: /(^|[^\\])(\/\*[\w\W]*?\*\/|\/\/.*)/,
			lookbehind: true
		},
		'atrule-declaration': {
			pattern: /(^\s*)@.+/m,
			lookbehind: true,
			inside: {
				'atrule': /^@[\w-]+/,
				rest: inside
			}
		},
		'variable-declaration': {
			pattern: /(^[ \t]*)[\w$-]+\s*.?=[ \t]*(?:(?:\{[^}]*\}|.+)|$)/m,
			lookbehind: true,
			inside: {
				'variable': /^\S+/,
				rest: inside
			}
		},
		'statement': {
			pattern: /(^[ \t]*)(?:if|else|for|return|unless)[ \t]+.+/m,
			lookbehind: true,
			inside: {
				keyword: /^\S+/,
				rest: inside
			}
		},
		'property-declaration': {
			pattern: /((?:^|\{)([ \t]*))(?:[\w-]|\{[^}\r\n]+\})+(?:\s*:\s*|[ \t]+)[^{\r\n]*(?:;|[^{\r\n,](?=$)(?!(\r?\n|\r)(?:\{|\2[ \t]+)))/m,
			lookbehind: true,
			inside: {
				'property': {
					pattern: /^[^\s:]+/,
					inside: {
						'interpolation': inside.interpolation
					}
				},
				rest: inside
			}
		},
		'selector': {
			pattern: /(^[ \t]*)(?:(?=\S)(?:[^{}\r\n:()]|::?[\w-]+(?:\([^)\r\n]*\))?|\{[^}\r\n]+\})+)(?:(?:\r?\n|\r)(?:\1(?:(?=\S)(?:[^{}\r\n:()]|::?[\w-]+(?:\([^)\r\n]*\))?|\{[^}\r\n]+\})+)))*(?:,$|\{|(?=(?:\r?\n|\r)(?:\{|\1[ \t]+)))/m,
			lookbehind: true,
			inside: {
				'interpolation': inside.interpolation,
				'punctuation': /[{},]/
			}
		},
		'func': inside.func,
		'string': inside.string,
		'interpolation': inside.interpolation,
		'punctuation': /[{}()\[\];:.]/
	};
}, true);
//# sourceMappingURL=stylus.js.map