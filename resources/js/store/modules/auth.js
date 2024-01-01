import cookie from 'vue-cookies'

export default {
    actions:{

        login({commit}, data){
            window.localStorage.setItem("user_id",data.user_id);
            window.localStorage.setItem("api_token",data.api_token);
            window.localStorage.setItem("name",data.name);
            window.localStorage.setItem("authStatus", true)
            commit("setAuthData",{
                "user_id":data.user_id,
                "api_token": data.api_token,
                "name": data.name,
            })
        },

        logout({commit}){
            cookie.remove("user")
            commit("logout")
        },



    },
    mutations:{
        setAuthData(state, data){
            state.user_id = data.user_id;
            state.api_token = data.api_token;
            state.name = data.name;
            state.authStatus = true;
        },
        login(state,data){
            state.user_id = data.user_id;
            state.api_token = data.api_token;
            state.name = data.name;
            state.authStatus = true;
        },
        logout(state){
            state.user_id = null;
            state.api_token = null;
            state.name = null;
            state.authStatus = false;
        },
        getAuthMut: state => {
            if(state.user_id !== null && state.api_token !== null && state.name !== null){
                return true;
            }
            return false;
        },
        authStatus: (state) => {
            if(state.user_id !== null && state.api_token !== null && state.name !== null){

                state.authStatus = true;
            }
            else{
                state.authStatus =  false;
            }
        }
    },
    state:{
        user_id: cookie.get("user") ? cookie.get("user").id: null,
        api_token: cookie.get("user") ? cookie.get("user").api_token: null,
        name: cookie.get("user") ? cookie.get("user").name : null,
    },
    getters:{
        userToken : (state) => {
            if(state.api_token == cookie.get("user").api_token)
                 return cookie.get("user").api_token;
            else return false;
        },
        getUser: (state) => {
            return {
                "name": state.name,
                "api_token": state.api_token,
                "id": state.user_id,
            }
        },
        userId: state =>{
            return state.user_id;
        },
        getAuth(state){
            if(state.api_token != null){

                return true;
            }
            else{
                return false;
            }
        },
        getUserId: state => {
            return state.user_id
        }

    }
}
