<template>

</template>

<script>
    export default {
        name: "logout",
        data(){
            return{
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        },
        mounted() {
            window.axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            this.$store.dispatch("logout");
            document.location.href="/"

            // let user = this.$store.getters.getUser;
            // axios.post("/api/logout", user)
            // .then(resp => {
            //     console.log(resp)
            // });
        }
    }
</script>

