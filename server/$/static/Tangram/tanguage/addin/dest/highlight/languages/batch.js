/*!
 * tanguage script compiled code
 *
 * Datetime: Tue, 22 May 2018 02:31:30 GMT
 */
;
// tang.config({});
tang.init().block([
	'~/../'
], function (pandora, root, imports, undefined) {
	var highlight = pandora.highlight;
	var variable = /%%?[~:\w]+%?|!\S+!/;
	var parameter = {
		pattern: /\/[a-z?]+(?=[ :]|$):?|-[a-z]\b|--[a-z-]+\b/im,
		alias: 'attr-name',
		inside: {
			'punctuation': /:/
		}
	};
	var string = /"[^"]*"/;
	var number = /(?:\b|-)\d+\b/;
	highlight.languages.batch = {
		'comment': [
			/^::.*/m,
			{
				pattern: /((?:^|[&(])[ \t]*)rem\b(?:[^^&)\r\n]|\^(?:\r\n|[\s\S]))*/im,
				lookbehind: true
			}
		],
		'label': {
			pattern: /^:.*/m,
			alias: 'property'
		},
		'command': [{
			pattern: /((?:^|[&(])[ \t]*)for(?: ?\/[a-z?](?:[ :](?:"[^"]*"|\S+))?)* \S+ in \([^)]+\) do/im,
			lookbehind: true,
			inside: {
				'keyword': /^for\b|\b(?:in|do)\b/i,
				'string': string,
				'parameter': parameter,
				'variable': variable,
				'number': number,
				'punctuation': /[()',]/
			}
		}, {
			pattern: /((?:^|[&(])[ \t]*)if(?: ?\/[a-z?](?:[ :](?:"[^"]*"|\S+))?)* (?:not )?(?:cmdextversion \d+|defined \w+|errorlevel \d+|exist \S+|(?:"[^"]*"|\S+)?(?:==| (?:equ|neq|lss|leq|gtr|geq) )(?:"[^"]*"|\S+))/im,
			lookbehind: true,
			inside: {
				'keyword': /^if\b|\b(?:not|cmdextversion|defined|errorlevel|exist)\b/i,
				'string': string,
				'parameter': parameter,
				'variable': variable,
				'number': number,
				'operator': /\^|==|\b(?:equ|neq|lss|leq|gtr|geq)\b/i
			}
		}, {
			pattern: /((?:^|[&()])[ \t]*)else\b/im,
			lookbehind: true,
			inside: {
				'keyword': /^else\b/i
			}
		}, {
			pattern: /((?:^|[&(])[ \t]*)set(?: ?\/[a-z](?:[ :](?:"[^"]*"|\S+))?)* (?:[^^&)\r\n]|\^(?:\r\n|[\s\S]))*/im,
			lookbehind: true,
			inside: {
				'keyword': /^set\b/i,
				'string': string,
				'parameter': parameter,
				'variable': [
					variable,
					/\w+(?=(?:[*\/%+\-&^|]|<<|>>)?=)/
				],
				'number': number,
				'operator': /[*\/%+\-&^|]=?|<<=?|>>=?|[!~_=]/,
				'punctuation': /[()',]/
			}
		}, {
			pattern: /((?:^|[&(])[ \t]*@?)\w+\b(?:[^^&)\r\n]|\^(?:\r\n|[\s\S]))*/im,
			lookbehind: true,
			inside: {
				'keyword': /^\w+\b/i,
				'string': string,
				'parameter': parameter,
				'label': {
					pattern: /(^\s*):\S+/m,
					lookbehind: true,
					alias: 'property'
				},
				'variable': variable,
				'number': number,
				'operator': /\^/
			}
		}],
		'operator': /[&@]/,
		'punctuation': /[()']/
	};
}, true);
//# sourceMappingURL=batch.js.map