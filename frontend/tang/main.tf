const homeComponent = {
    template: '#homeTemplate',
    data() {
        let provinceCode, provinceInfo;
        if (myStorage.provinceCode && provinces[myStorage.provinceCode]) {
            provinceCode = myStorage.provinceCode;
            provinceInfo = provinces[provinceCode];
        } else {
            provinceCode = "42";
            provinceInfo = provinces["42"];
        }

        log provinceCode;

        let self = this;
        let tags_url = '/json/home/hubei/tags.json',
            list_url = '/json/home/hubei/projects.json';
        _.async.json(tags_url, (result){
            // log self, result;
            self.tags = result.data;
        });
        _.async.json(list_url, (result){
            // log self, result;
            self.list = result.data;
        });

        return {
            province: provinceInfo,
            tags: self.tags,
            list: myStorage.list
        }
    },
    created() {
        let self = this;
        // log self.tags;
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
            { path: '/search/', redirect: '/home' },
            { path: '/provinces/', redirect: '/home' },
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
            // log 'foo', this.$route, popup.$route;
            if(this.$route.params.province){
                // log 'foo';
                myStorage.provinceCode = this.$route.params.province;
            }
            // else if(!myStorage.provinceCode){
            //     log 'bar';
            //     myStorage.provinceCode = "42";
            // }
            
        },
        storeData() {
            // log this.$route.params;
        }
    }
});