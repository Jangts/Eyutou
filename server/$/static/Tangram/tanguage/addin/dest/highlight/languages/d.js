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
	highlight.languages.d = highlight.languages.extend('clike', {
		'string': [
			/\b[rx]"(\\.|[^\\"])*"[cwd]?/,
			/\bq"(?:\[[\s\S]*?\]|\([\s\S]*?\)|<[\s\S]*?>|\{[\s\S]*?\})"/,
			/\bq"([_a-zA-Z][_a-zA-Z\d]*)(?:\r?\n|\r)[\s\S]*?(?:\r?\n|\r)\1"/,
			/\bq"(.)[\s\S]*?\1"/,
			/'(?:\\'|\\?[^']+)'/,
			/(["`])(\\.|(?!\1)[^\\])*\1[cwd]?/
		],
		'number': [
			/\b0x\.?[a-f\d_]+(?:(?!\.\.)\.[a-f\d_]*)?(?:p[+-]?[a-f\d_]+)?[ulfi]*/i,
			{
				pattern: /((?:\.\.)?)(?:\b0b\.?|\b|\.)\d[\d_]*(?:(?!\.\.)\.[\d_]*)?(?:e[+-]?\d[\d_]*)?[ulfi]*/i,
				lookbehind: true
			}
		],
		'keyword': /\$|\b(?:abstract|alias|align|asm|assert|auto|body|bool|break|byte|case|cast|catch|cdouble|cent|cfloat|char|class|const|continue|creal|dchar|debug|default|delegate|delete|deprecated|do|double|else|enum|export|extern|false|final|finally|float|for|foreach|foreach_reverse|function|goto|idouble|if|ifloat|immutable|import|inout|int|interface|invariant|ireal|lazy|long|macro|mixin|module|new|nothrow|null|out|override|package|pragma|private|protected|public|pure|real|ref|return|scope|shared|short|static|struct|super|switch|synchronized|template|this|throw|true|try|typedef|typeid|typeof|ubyte|ucent|uint|ulong|union|unittest|ushort|version|void|volatile|wchar|while|with|__(?:(?:FILE|MODULE|LINE|FUNCTION|PRETTY_FUNCTION|DATE|EOF|TIME|TIMESTAMP|VENDOR|VERSION)__|gshared|traits|vector|parameters)|string|wstring|dstring|size_t|ptrdiff_t)\b/,
		'operator': /\|[|=]?|&[&=]?|\+[+=]?|-[-=]?|\.?\.\.|=[>=]?|!(?:i[ns]\b|<>?=?|>=?|=)?|\bi[ns]\b|(?:<[<>]?|>>?>?|\^\^|[*\/%^~])=?/
	});
	highlight.languages.d.comment = [
		/^\s*#!.+/,
		{
			pattern: /(^|[^\\])\/\+(?:\/\+[\w\W]*?\+\/|[\w\W])*?\+\//,
			lookbehind: true
		}
	].concat(highlight.languages.d.comment);
	highlight.languages.insertBefore('d', 'comment', {
		'token-string': {
			pattern: /\bq\{(?:|\{[^}]*\}|[^}])*\}/,
			alias: 'string'
		}
	});
	highlight.languages.insertBefore('d', 'keyword', {
		'property': /\B@\w*/
	});
	highlight.languages.insertBefore('d', 'function', {
		'register': {
			pattern: /\b(?:[ABCD][LHX]|E[ABCD]X|E?(?:BP|SP|DI|SI)|[ECSDGF]S|CR[0234]|DR[012367]|TR[3-7]|X?MM[0-7]|R[ABCD]X|[BS]PL|R[BS]P|[DS]IL|R[DS]I|R(?:[89]|1[0-5])[BWD]?|XMM(?:[89]|1[0-5])|YMM(?:1[0-5]|\d))\b|\bST(?:\([0-7]\)|\b)/,
			alias: 'variable'
		}
	});
}, true);
//# sourceMappingURL=d.js.map