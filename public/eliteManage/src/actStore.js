const Vue = require('vue');
const Vuex = require('vuex');
Vue.use(Vuex);


const state = 
{
	role : globalLoginInfo.role,      //全局判断登录身份：student teacher admin,
	myId : globalLoginInfo.id,
	route: globalLoginInfo.route,
	message:
	{
		type:'ok',      // ok err
		content:'things goes well'
	}
};

const mutations = 
{
	setRole(state,newVal)
	{
		state.role = newVal;
	},
	setMyId(state,newVal)
	{
		state.myId = newVal;
	},
	setRoute(state,newVal)
	{
		state.route = newVal;
	},
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