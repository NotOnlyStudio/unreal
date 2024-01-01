
export default {
    actions:{
        addItem({commit}, data){
            commit("addToBasket",data)
        },
        addItemToServer({commit},data){
            console.log(data)
            window.axios.defaults.headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            window.axios.defaults.withCredentials = true;
            window.axios.post("/basket/add/"+data)
            .then(
                resp => {
                    if(resp.status != 200){
                        console.warn("Server send error")
                    }
                }
            )
        },
        clearBasketCounter({commit}){
            commit("clearBasketCount")
        },
        transferBasket({state}){
            window.axios.defaults.headers = {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                }
            window.axios.defaults.withCredentials = true;
            window.axios.post('/basket/transfer', {
                "basket": this.state.basket
            }).then(
                resp => {
                    if(resp.status == 200){
                        console.log("Basket data is transfered")
                    } else{
                        console.warn("Server transfering error")
                    }
                }
            )
        },
        deleteFromBasket: ({commit},user_id) => {
            commit("deleteFromBasket",user_id);
        }   

    },
    mutations:{
        addToBasket(state, data){
            if(state.basket  ==  null){
                let arr = [];
                arr.push(data)
                localStorage.setItem("basket",JSON.stringify(arr))
            }
            else{
                let basket = JSON.parse(localStorage.getItem("basket"))
                if(typeof basket == "number"){
                    basket = [basket]
                }
                if(basket.indexOf(data) == -1){
                    console.log(basket.indexOf(data))
                    basket.push(data)
                    localStorage.setItem("basket",JSON.stringify(basket))
                }
            }
            state.basket = localStorage.getItem("basket")
        },
        clearBasketCount: state => {
            state.basket = null
            console.log("basket was cleared")
            localStorage.removeItem("basket")
        },
        deleteFromBasket: (state, item) => {
            let basket = JSON.parse(localStorage.getItem("basket"))
            let item_id = item[0]
            if(basket.length == 1)
            {
                basket = null;
                localStorage.removeItem("basket")
            }
            else{
                basket = basket.splice(basket.indexOf(item_id),1)
            }
            if(item[1] != false){
                window.axios.delete("/basket/deleteItem/"+item[0])
                .then(
                    resp => {
                        if(resp.status != 200){
                            console.warn("Server error")
                        }
                    }
                )
            }
            if(basket != null){
                state.basket = basket
                localStorage.setItem("basket",JSON.stringify(basket))
            }
            else{
                localStorage.removeItem("basket")
                state.basket = null
            }
        }
    },
    state:{
        basket: localStorage.getItem("basket")
    },
    getters:{
        getBasket: state => {
            return state.basket 
        },
        baksetLength: state => {
            let lg = state.basket == null ? 0 : JSON.parse(state.basket).length;
            if(lg != null && lg > 99){
                lg = "99+"
            }
            return  lg
        }
    }
}