const Vue = require('vue');
const actStore = require('./actStore.js');
//设置让vue处于可调式状态
Vue.config.debug = true;

module.exports = new Vue(
{
  el: '#interact',
  store:actStore,
  components: 
  {
    interact: require('./interact/teacher.vue')
  }
})