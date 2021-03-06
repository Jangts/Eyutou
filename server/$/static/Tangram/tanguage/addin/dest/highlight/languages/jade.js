/*!
 * tanguage script compiled code
 *
 * Datetime: Tue, 22 May 2018 02:31:31 GMT
 */
;
// tang.config({});
tang.init().block([
	'~/../',
	'~/../languages/twig'
], function (pandora, root, imports, undefined) {;
	var highlight = pandora.highlight;
	highlight.languages.jade = {
		'comment': {
			pattern: /(^([\t ]*))\/\/.*((?:\r?\n|\r)\2[\t ]+.+)*/m,
			lookbehind: true
		},
		'multiline-script': {
			pattern: /(^([\t ]*)script\b.*\.[\t ]*)((?:\r?\n|\r(?!\n))(?:\2[\t ]+.+|\s*?(?=\r?\n|\r)))+/m,
			lookbehind: true,
			inside: {
				rest: highlight.languages.javascript
			}
		},
		'filter': {
			pattern: /(^([\t ]*)):.+((?:\r?\n|\r(?!\n))(?:\2[\t ]+.+|\s*?(?=\r?\n|\r)))+/m,
			lookbehind: true,
			inside: {
				'filter-name': {
					pattern: /^:[\w-]+/,
					alias: 'variable'
				}
			}
		},
		'multiline-plain-text': {
			pattern: /(^([\t ]*)[\w\-#.]+\.[\t ]*)((?:\r?\n|\r(?!\n))(?:\2[\t ]+.+|\s*?(?=\r?\n|\r)))+/m,
			lookbehind: true
		},
		'markup': {
			pattern: /(^[\t ]*)<.+/m,
			lookbehind: true,
			inside: {
				rest: highlight.languages.markup
			}
		},
		'doctype': {
			pattern: /((?:^|\n)[\t ]*)doctype(?: .+)?/,
			lookbehind: true
		},
		'flow-control': {
			pattern: /(^[\t ]*)(?:if|unless|else|case|when|default|each|while)\b(?: .+)?/m,
			lookbehind: true,
			inside: {
				'each': {
					pattern: /^each .+? in\b/,
					inside: {
						'keyword': /\b(?:each|in)\b/,
						'punctuation': /,/
					}
				},
				'branch': {
					pattern: /^(?:if|unless|else|case|when|default|while)\b/,
					alias: 'keyword'
				},
				rest: highlight.languages.javascript
			}
		},
		'keyword': {
			pattern: /(^[\t ]*)(?:block|extends|include|append|prepend)\b.+/m,
			lookbehind: true
		},
		'mixin': [{
			pattern: /(^[\t ]*)mixin .+/m,
			lookbehind: true,
			inside: {
				'keyword': /^mixin/,
				'function': /\w+(?=\s*\(|\s*$)/,
				'punctuation': /[(),.]/
			}
		}, {
			pattern: /(^[\t ]*)\+.+/m,
			lookbehind: true,
			inside: {
				'name': {
					pattern: /^\+\w+/,
					alias: 'function'
				},
				'rest': highlight.languages.javascript
			}
		}],
		'script': {
			pattern: /(^[\t ]*script(?:(?:&[^(]+)?\([^)]+\))*[\t ]+).+/m,
			lookbehind: true,
			inside: {
				rest: highlight.languages.javascript
			}
		},
		'plain-text': {
			pattern: /(^[\t ]*(?!-)[\w\-#.]*[\w\-](?:(?:&[^(]+)?\([^)]+\))*\/?[\t ]+).+/m,
			lookbehind: true
		},
		'tag': {
			pattern: /(^[\t ]*)(?!-)[\w\-#.]*[\w\-](?:(?:&[^(]+)?\([^)]+\))*\/?:?/m,
			lookbehind: true,
			inside: {
				'attributes': [{
					pattern: /&[^(]+\([^)]+\)/,
					inside: {
						rest: highlight.languages.javascript
					}
				}, {
					pattern: /\([^)]+\)/,
					inside: {
						'attr-value': {
							pattern: /(=\s*)(?:\{[^}]*\}|[^,)\r\n]+)/,
							lookbehind: true,
							inside: {
								rest: highlight.languages.javascript
							}
						},
						'attr-name': /[\w-]+(?=\s*!?=|\s*[,)])/,
						'punctuation': /[!=(),]+/
					}
				}],
				'punctuation': /:/
			}
		},
		'code': [{
			pattern: /(^[\t ]*(?:-|!?=)).+/m,
			lookbehind: true,
			inside: {
				rest: highlight.languages.javascript
			}
		}],
		'punctuation': /[.\-!=|]+/
	};
	var filter_pattern = '(^([\\t ]*)):{{filter_name}}((?:\\r?\\n|\\r(?!\\n))(?:\\2[\\t ]+.+|\\s*?(?=\\r?\\n|\\r)))+';
	var filters = [{
		filter: 'atpl',
		language: 'twig'
	}, {
		filter: 'coffee',
		language: 'coffeescript'
	}, 'ejs', 'handlebars', 'hogan', 'less', 'livescript', 'markdown', 'mustache', 'plates', {
		filter: 'sass',
		language: 'scss'
	}, 'stylus', 'swig'];
	var all_filters = {};
	for (var i = 0, l = filters.length;i < l;i++) {
		var filter = filters[i];
		filter = typeof filter === 'string' ? {
			filter: filter,
			language: filter
		} : filter;
		if (highlight.languages[filter.language]) {
			all_filters['filter-' + filter.filter] = {
				pattern: RegExp(filter_pattern.replace('{{filter_name}}', filter.filter), 'm'),
				lookbehind: true,
				inside: {
					'filter-name': {
						pattern: /^:[\w-]+/,
						alias: 'variable'
					},
					rest: highlight.languages[filter.language]
				}
			};
		}
	}
	highlight.languages.insertBefore('jade', 'filter', all_filters);
}, true);
//# sourceMappingURL=jade.js.map