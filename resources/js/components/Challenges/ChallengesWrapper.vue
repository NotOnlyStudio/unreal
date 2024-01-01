<template>
    <div v-if="challenges != null">
        <challenge
            v-if="loaded && challenges.length != 0"
            :title="challenges.name"
            :img="challenges.challenge_photo"
            :class-list="classList"
            :styles="$store.state.challengeStyles"
            :alias="challenges.alias"
        />
        <div v-else class="challenges t-0 " style="margin-top: 3px;">
            <b-skeleton class="w-100"></b-skeleton>
            <b-skeleton type="button" class="w-50 my-3" ></b-skeleton>
            <div style="height: 250px;width: 100%">
                <b-skeleton class="w-100 h-100" style="height: 250px"></b-skeleton>
            </div>
        </div>
    </div>
</template>

<script>
    const challenge  = () => import("./Challenge");
    export default {
        name: "ChallengesWrapper",
        props:['classList'],
        components:{
            challenge
        },
        data(){
            return{
                challenges:null,
                loaded: false,
            }
        },
        mounted(){
            window.axios.get("/api/challenges-get")
            .then(
                resp => {
                    this.challenges = resp.data[0];
                    this.loaded = true;
                }
            )
        }
    }
</script>

