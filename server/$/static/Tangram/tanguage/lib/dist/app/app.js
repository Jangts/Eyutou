/*!
 * tanguage script compiled code
 *
 * Datetime: Fri, 18 May 2018 13:35:11 GMT
 */
;
// tang.config({});
tang.init().block([
	'$_/app/Application'
], function (pandora, root, imports, undefined) {
	var module = this.module;
	var _ = pandora;
	var app = function (elem) {
		return new _.app.Application(elem);
	}
	pandora.app = app;
});
//# sourceMappingURL=app.js.map