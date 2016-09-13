const Vue = require('vue');
const Vuex = require('vuex');
Vue.use(Vuex);


const state = 
{
	role : globalLoginInfo.role,      //全局判断登录身份：student teacher admin,
	myId : globalLoginInfo.id,
	route: globalLoginInfo.route+'/'+globalLoginInfo.role,
	message:
	{
		type:'ok',      // ok err
		content:'things goes well'
	}
};

const mutations = 
{
	newMessage({message},mess)
	{
		message.type = mess.type+"?"+Math.random();
		message.content = mess.content;
	}
};

window.actStore = module.exports = new Vuex.Store(
{
	state,
	mutations
});