const Vue = require('vue');
const VueRouter = require('vue-router');
const actStore = require('./actStore.js');
//设置让vue处于可调式状态
Vue.config.debug = true;

// 安装路由
Vue.use(VueRouter)

// 创建路由
const router = new VueRouter();
const App = Vue.extend(
{
	store:actStore,
	components: 
	{
		interact: require('./interact/app.vue')
	}
});
router.start(App, '#interact');


/*
module.exports = new Vue(
{
  el: '#interact',
  store:actStore,
  components: 
  {
    interact: require('./components/app.vue')
  }
})
*/