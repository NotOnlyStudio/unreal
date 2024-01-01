<template>
    <header>
        <b-navbar toggleable="lg" type="light" variant="light">

            <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>

            <b-collapse id="nav-collapse" is-nav>

                <b-navbar-brand href="/" class="m-0">UnrealShop</b-navbar-brand>
                <!-- Right aligned nav items -->
                <b-navbar-nav  class="ml-auto">
                    <b-nav-form>
                        <b-form-input size="sm" class="mr-sm-2" placeholder="Search"></b-form-input>
                        <b-button size="sm" class="my-2 my-sm-0" type="submit">Search</b-button>
                    </b-nav-form>

                    <b-nav-item-dropdown >
                        <!-- Using 'button-content' slot -->
                        <template #button-content>

                            <img :src="user.photo != null ? '/storage/avatars/'+user.photo : '/img/user.png'" style="width: 2rem" />
                        </template>
                        <b-dropdown-item href="#" disabled>{{this.$store.getters.getUser.name}}</b-dropdown-item>
                        <b-dropdown-item href="#">Profile</b-dropdown-item>
                        <b-dropdown-item href="#">Sign Out</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
            </b-collapse>
        </b-navbar>
    </header>
</template>

<script>
    export default {
        name: "AdminHeader",
        data(){
            return{
                user: $cookies.get("user"),
            }
        },
        mounted(){
            console.log(this.user)
        }
    }
</script>

