export default {
    Login(state){
        state.Auth.user_id = localStorage.getItem("user_id");
        state.Auth.api_token = localStorage.getItem("api_token");
        state.Auth.name = localStorage.getItem("name");
        state.Auth.login =
            state.Auth.user_id !== null &&
            state.Auth.api_token !== null &&
            state.Auth.name !== null;
    }
}
