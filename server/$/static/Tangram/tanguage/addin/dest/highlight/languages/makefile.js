/*!
 * tanguage script compiled code
 *
 * Datetime: Tue, 22 May 2018 02:31:31 GMT
 */
;
// tang.config({});
tang.init().block([
	'~/../'
], function (pandora, root, imports, undefined) {
	pandora.highlight.languages.makefile = {
		'comment': {
			pattern: /(^|[^\\])#(?:\\(?:\r\n|[\s\S])|.)*/,
			lookbehind: true
		},
		'string': /(["'])(?:\\(?:\r\n|[\s\S])|(?!\1)[^\\\r\n])*\1/,
		'builtin': /\.[A-Z][^:#=\s]+(?=\s*:(?!=))/,
		'symbol': {
			pattern: /^[^:=\r\n]+(?=\s*:(?!=))/m,
			inside: {
				'variable': /\$+(?:[^(){}:#=\s]+|(?=[({]))/
			}
		},
		'variable': /\$+(?:[^(){}:#=\s]+|\([@*%<^+?][DF]\)|(?=[({]))/,
		'keyword': [
			/-include\b|\b(?:define|else|endef|endif|export|ifn?def|ifn?eq|include|override|private|sinclude|undefine|unexport|vpath)\b/,
			{
				pattern: /(\()(?:addsuffix|abspath|and|basename|call|dir|error|eval|file|filter(?:-out)?|findstring|firstword|flavor|foreach|guile|if|info|join|lastword|load|notdir|or|origin|patsubst|realpath|shell|sort|strip|subst|suffix|value|warning|wildcard|word(?:s|list)?)(?=[ \t])/,
				lookbehind: true
			}
		],
		'operator': /(?:::|[?:+!])?=|[|@]/,
		'punctuation': /[:;(){}]/
	};
}, true);
//# sourceMappingURL=makefile.js.map