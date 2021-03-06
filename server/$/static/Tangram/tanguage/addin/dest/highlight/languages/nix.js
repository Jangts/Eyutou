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
	highlight.languages.nix = {
		'comment': /\/\*[\s\S]*?\*\/|#.*/,
		'string': {
			pattern: /"(?:[^"\\]|\\[\s\S])*"|''(?:(?!'')[\s\S]|''(?:'|\\|\$\{))*''/,
			inside: {
				'interpolation': {
					pattern: /(^|(?:^|(?!'').)[^\\])\$\{(?:[^}]|\{[^}]*\})*}/,
					lookbehind: true,
					inside: {
						'antiquotation': {
							pattern: /^\$(?=\{)/,
							alias: 'variable'
						}
					}
				}
			}
		},
		'url': [/\b(?:[a-z]{3,7}:\/\/)[\w\-+%~\/.:#=?&]+/, {
			pattern: /([^\/])(?:[\w\-+%~.:#=?&]*(?!\/\/)[\w\-+%~\/.:#=?&])?(?!\/\/)\/[\w\-+%~\/.:#=?&]*/,
			lookbehind: true
		}],
		'antiquotation': {
			pattern: /\$(?=\{)/,
			alias: 'variable'
		},
		'number': /\b\d+\b/,
		'keyword': /\b(?:assert|builtins|else|if|in|inherit|let|null|or|then|with)\b/,
		'function': /\b(?:abort|add|all|any|attrNames|attrValues|baseNameOf|compareVersions|concatLists|currentSystem|deepSeq|derivation|dirOf|div|elem(?:At)?|fetch(?:url|Tarball)|filter(?:Source)?|fromJSON|genList|getAttr|getEnv|hasAttr|hashString|head|import|intersectAttrs|is(?:Attrs|Bool|Function|Int|List|Null|String)|length|lessThan|listToAttrs|map|mul|parseDrvName|pathExists|read(?:Dir|File)|removeAttrs|replaceStrings|seq|sort|stringLength|sub(?:string)?|tail|throw|to(?:File|JSON|Path|String|XML)|trace|typeOf)\b|\bfoldl'\B/,
		'boolean': /\b(?:true|false)\b/,
		'operator': /[=!<>]=?|\+\+?|\|\||&&|\/\/|->?|[?@]/,
		'punctuation': /[{}()[\].,:;]/
	};
	highlight.languages.nix.string.inside.interpolation.inside.rest = _.copy(highlight.languages.nix);
}, true);
//# sourceMappingURL=nix.js.map