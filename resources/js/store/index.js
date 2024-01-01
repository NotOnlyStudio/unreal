
import Vue from 'vue'
import Vuex from 'vuex'
import auth from "./modules/auth"
import VueCookies from 'vue-cookies'
// import basket from './modules/basket'
Vue.use(Vuex)
Vue.use(VueCookies)

export default new Vuex.Store({
    // strict: proc.env.NODE_ENV !== 'production'
    state:{
        logTxt: "loggged",
        challengeStyles: "top: 5px",
        notifications: null,
    },
    getters:{
        getData(state){
            return state.logTxt;
        },
        challengeStyles({state}){
            return state.challengeStyles
        },
        getNotifications(state){
            return state.notifications
        }
    },
    mutations:{
        getConsole(state){
            return state.logTxt;
        },
        setChallengeStyles(state,data){
            state.challengeStyles = data.styles
        },
        updateNotificationsData(state,data){
            state.notifications =  data
        }
    },
    actions:{
        getCats(){
            context.commit("getConsoleLog","123");
        },
        setChallengeStyles({commit},data){
            let styles = ``;
            commit("setChallengeStyles",{
                "styles":data,
            })
        },
        updateNotifications({commit},data){
           commit("updateNotificationsData",data)
        }    
    },
    modules:{
        auth: auth,
        // basket: basket,
    }
})
