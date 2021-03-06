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
	highlight.languages.puppet = {
		'heredoc': [{
			pattern: /(@\("([^"\r\n\/):]+)"(?:\/[nrts$uL]*)?\).*(?:\r?\n|\r))(?:.*(?:\r?\n|\r))*?[ \t]*\|?[ \t]*-?[ \t]*\2/,
			lookbehind: true,
			alias: 'string',
			inside: {
				'punctuation': /(?=\S).*\S(?= *$)/
			}
		}, {
			pattern: /(@\(([^"\r\n\/):]+)(?:\/[nrts$uL]*)?\).*(?:\r?\n|\r))(?:.*(?:\r?\n|\r))*?[ \t]*\|?[ \t]*-?[ \t]*\2/,
			lookbehind: true,
			alias: 'string',
			inside: {
				'punctuation': /(?=\S).*\S(?= *$)/
			}
		}, {
			pattern: /@\("?(?:[^"\r\n\/):]+)"?(?:\/[nrts$uL]*)?\)/,
			alias: 'string',
			inside: {
				'punctuation': {
					pattern: /(\().+?(?=\))/,
					lookbehind: true
				}
			}
		}],
		'multiline-comment': {
			pattern: /(^|[^\\])\/\*[\s\S]*?\*\//,
			lookbehind: true,
			alias: 'comment'
		},
		'regex': {
			pattern: /((?:\bnode\s+|[^\s\w\\]\s*))\/(?:[^\/\\]|\\[\s\S])+\/(?:[imx]+\b|\B)/,
			lookbehind: true,
			inside: {
				'extended-regex': {
					pattern: /^\/(?:[^\/\\]|\\[\s\S])+\/[im]*x[im]*$/,
					inside: {
						'comment': /#.*/
					}
				}
			}
		},
		'comment': {
			pattern: /(^|[^\\])#.*/,
			lookbehind: true
		},
		'string': {
			pattern: /(["'])(?:\$\{(?:[^'"}]|(["'])(?:(?!\2)[^\\]|\\[\s\S])*\2)+\}|(?!\1)[^\\]|\\[\s\S])*\1/,
			inside: {
				'double-quoted': {
					pattern: /^"[\s\S]*"$/,
					inside:  {}
				}
			}
		},
		'variable': {
			pattern: /\$(?:::)?\w+(?:::\w+)*/,
			inside: {
				'punctuation': /::/
			}
		},
		'attr-name': /(?:\w+|\*)(?=\s*=>)/,
		'function': [{
			pattern: /(\.)(?!\d)\w+/,
			lookbehind: true
		}, /\b(?:contain|debug|err|fail|include|info|notice|realize|require|tag|warning)\b|\b(?!\d)\w+(?=\()/],
		'number': /\b(?:0x[a-f\d]+|\d+(?:\.\d+)?(?:e-?\d+)?)\b/i,
		'boolean': /\b(?:true|false)\b/,
		'keyword': /\b(?:application|attr|case|class|consumes|default|define|else|elsif|function|if|import|inherits|node|private|produces|type|undef|unless)\b/,
		'datatype': {
			pattern: /\b(?:Any|Array|Boolean|Callable|Catalogentry|Class|Collection|Data|Default|Enum|Float|Hash|Integer|NotUndef|Numeric|Optional|Pattern|Regexp|Resource|Runtime|Scalar|String|Struct|Tuple|Type|Undef|Variant)\b/,
			alias: 'symbol'
		},
		'operator': /\=[=~>]?|![=~]?|<(?:<\|?|[=~|-])?|>[>=]?|->?|~>|\|>?>?|[*\/%+?]|\b(?:and|in|or)\b/,
		'punctuation': /[\[\]{}().,;]|:+/
	};
	var interpolation = [{
		pattern: /(^|[^\\])\$\{(?:[^'"{}]|\{[^}]*\}|(["'])(?:(?!\2)[^\\]|\\[\s\S])*\2)+\}/,
		lookbehind: true,
		inside: {
			'short-variable': {
				pattern: /(^\$\{)(?!\w+\()(?:::)?\w+(?:::\w+)*/,
				lookbehind: true,
				alias: 'variable',
				inside: {
					'punctuation': /::/
				}
			},
			'delimiter': {
				pattern: /^\$/,
				alias: 'variable'
			},
			rest: _.copy(highlight.languages.puppet)
		}
	}, {
		pattern: /(^|[^\\])\$(?:::)?\w+(?:::\w+)*/,
		lookbehind: true,
		alias: 'variable',
		inside: {
			'punctuation': /::/
		}
	}];
	highlight.languages.puppet['heredoc'][0].inside.interpolation = interpolation;
	highlight.languages.puppet['string'].inside['double-quoted'].inside.interpolation = interpolation;
}, true);
//# sourceMappingURL=puppet.js.map