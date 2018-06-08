/*!
 * tanguage script compiled code
 *
 * Datetime: Thu, 07 Jun 2018 13:42:00 GMT
 */
;
// tang.config({});
tang.init().block([
	'$_/async/'
], function (pandora, root, imports, undefined) {
	var _ = pandora;
	var myStorage = {
		provinceCode: "42",
		list: null
	};
	var title = new Vue({
		el: '#title',
		data: {
			title: '鳄鱼投'
		}
	});
	var homeComponent = {
		template: '#homeTemplate',
		data: function () {
			var provinceCode = void 0;var provinceInfo = void 0;
			if (myStorage.provinceCode && provinces[myStorage.provinceCode]) {
				provinceCode = myStorage.provinceCode;
				provinceInfo = provinces[provinceCode];
			}
			else {
				provinceCode = "42";
				provinceInfo = provinces["42"];
			}
			var self = this;
			var url = '/json/home/hubei/projects.json';
			_.async.json(url, function (result) {
				root.console.log(self, result);
				self.list = result.data;
			});
			return {
				province: provinceInfo,
				tags: null,
				list: myStorage.list
			};
		},
		created: function () {
			var self = this;
			setTimeout(function () {
				self.tags = [{name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}, {name: "abc"}];
			}, 2000);
		}
	};
	var projectComponent = {template: '#projectTemplate'};
	var circleComponent = {template: '#circleTemplate'};
	var mineComponent = {template: '#mineTemplate'};
	var main = new Vue({
		el: '#main',
		data: {
			province: provinces[42],
			tabs: [{
				path: "/home",
				classname: "home",
				name: "首页"
			}, {
				path: "/project",
				classname: "project",
				name: "工程"
			}, {
				path: "/circle",
				classname: "circle",
				name: "圈子"
			}, {
				path: "/mine",
				classname: "mine",
				name: "我的"
			}]
		},
		router: new VueRouter({
			linkActiveClass: 'active',
			linkExactActiveClass: 'active',
			routes: [{
				path: '/',
				redirect: '/home'
			}, {
				path: '/home/',
				component: homeComponent
			}, {
				path: '/home/:province',
				component: homeComponent
			}, {
				path: '/project',
				component: projectComponent
			}, {
				path: '/circle',
				component: circleComponent
			}, {
				path: '/mine',
				component: mineComponent
			}]
		}),
		watch: {
			'$route': 'fetchData'
		},
		created: function () {
			this.storeData();
		},
		methods: {
			fetchData: function () {
				myStorage.provinceCode = this.$route.params.province || "42";
			},
			storeData: function () {
				root.console.log(this.$route.params);
			}
		}
	});
	var searchComponent = {template: '#searchTemplate'};
	var popup = new Vue({
		el: '#popup',
		router: new VueRouter({
			linkActiveClass: 'active',
			linkExactActiveClass: 'active',
			routes: [{
				path: '/search/',
				component: searchComponent
			}]
		}),
		created: function () {
			this.show(true);
		},
		methods: {
			show: function (show) {
				if (show === void 0) { show = true;}
				console.log(1111);
			}
		}
	});
}, true);
//# sourceMappingURL=main.js.map