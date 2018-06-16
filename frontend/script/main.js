/*!
 * tanguage script compiled code
 *
 * Datetime: Fri, 15 Jun 2018 06:25:19 GMT
 */
;
// tang.config({});
tang.init().block([
	'$_/async/'
], function (pandora, root, imports, undefined) {
	var _ = pandora;
	var myStorage = {
		provinceCode: null,
		tags: null,
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
			root.console.log(provinceCode);
			var self = this;
			var tags_url = '/json/home/hubei/tags.json';
			var list_url = '/json/home/hubei/projects.json';
			_.async.json(tags_url, function (result) {
				self.tags = result.data;
			});
			_.async.json(list_url, function (result) {
				self.list = result.data;
			});
			return {
				province: provinceInfo,
				tags: self.tags,
				list: myStorage.list
			};
		},
		created: function () {
			var self = this;
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
				path: '/search/',
				redirect: '/home'
			}, {
				path: '/provinces/',
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
				if (this.$route.params.province) {
					myStorage.provinceCode = this.$route.params.province;
				};
			},
			storeData: function () {}
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
			}, {
				path: '/provinces/',
				component: searchComponent
			}]
		}),
		watch: {
			'$route': 'fetchData'
		},
		methods: {
			fetchData: function () {
				root.console.log('bar', this.$route);
				this.show(true);
			},
			show: function (show) {
				if (show === void 0) { show = true;}
				_.dom.toggleClass(this.$el, 'active', !!show);
			}
		}
	});
}, true);
//# sourceMappingURL=main.js.map