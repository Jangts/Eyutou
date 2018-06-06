;
(function() {
    let myStorage = {
        province: provinces["42"],
        list: null
    };
    const title = new Vue({
        el: '#title',
        data: {
            title: '鳄鱼投'
        }
    });

    const homeComponent = {
        template: '#homeTemplate',
        data() {
            return {
                province: myStorage.province,
                list: myStorage.list
            }
        }
    }
    const projectComponent = { template: '#projectTemplate' }
    const circleComponent = { template: '#circleTemplate' }
    const mineComponent = { template: '#mineTemplate' }

    const main = new Vue({
        el: '#main',
        data: {
            province: provinces[42],
            tabs: [{
                    path: "/home",
                    classname: "home",
                    name: "首页"
                },
                {
                    path: "/project",
                    classname: "project",
                    name: "工程"
                },
                {
                    path: "/circle",
                    classname: "circle",
                    name: "圈子"
                },
                {
                    path: "/mine",
                    classname: "mine",
                    name: "我的"
                }
            ]
        },
        router: new VueRouter({
            linkActiveClass: 'active',
            linkExactActiveClass: 'active',
            routes: [
                { path: '/', redirect: '/home' },
                { path: '/home/', component: homeComponent },
                { path: '/home/:province', component: homeComponent },
                { path: '/project', component: projectComponent },
                { path: '/circle', component: circleComponent },
                { path: '/mine', component: mineComponent }
            ]
        }),
        watch: {
            // 如果路由有变化，会再次执行该方法
            '$route': 'fetchData'
        },
        created() {
            // 组件创建完后获取数据，
            // 此时 data 已经被 observed 了
            this.storeData()
        },
        methods: {
            fetchData() {
                myStorage.province = (this.$route.params.province && provinces[this.$route.params.province]) || myStorage.province;
            },
            storeData() {
                console.log(this.$route.params);
            }
        }
    });
}());