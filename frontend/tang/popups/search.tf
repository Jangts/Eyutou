// const searchComponent = { template: '#searchTemplate' }

// const popup = new Vue({
//     el: '#popup',
//     router: new VueRouter({
//         linkActiveClass: 'active',
//         linkExactActiveClass: 'active',
//         routes: [
//             { path: '/search/', component: searchComponent },
//             { path: '/provinces/', component: searchComponent }
//         ]
//     }),
//     watch: {
//         // 如果路由有变化，会再次执行该方法
//         '$route': 'fetchData'
//     },
//     // created(){
//     //     this.show(true);
//     // },
//     methods: {
//         fetchData(){
//             log 'bar', this.$route;
//             this.show(true);
//         },
//         show(show = true) {
//             _.dom.toggleClass(this.$el, 'active', !!show);
//             // console.log('foo', this.$el, this);
//         }
//     }
// });
