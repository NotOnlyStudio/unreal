<template>
    <header class="header">
        <div class="header__inner">
            <a href="/" class="logo">
                <img src="/assets/logo-gray.png" alt="UnrealShop" class="logo-img" style="width: 70px; height: 34.19px">
            </a>
            <preloader v-if="loading"/>

            <nav :class="[isMenu ? 'menu' : '']" class="header_navig">
                <ul class="list-unstyled mb-0 header_ul">
                    <div class="header_dv">
                        <li translate="no">
                            <a href="/download/UnrealShopSetup.exe" class="btn btn-red default"
                               style="font-family: GhothamPro;font-size: 14px; width:95px;" download>Linker<span
                                style="position: absolute;right: 4px;top: -1px;font-size: 10px;color: #000;">beta</span></a>
                        </li>


                        <li>
                            <a class="btn btn-red buy_models" href="https://unreal.arhiteach.com/en/about"
                               target="_blank00">About us</a>
                        </li>

                        <!-- v-text="$ml.get('title')"  -->


                    </div>
                    <div class="header_div">
                        <li v-for="(item, index) in JSON.parse(links)" :key="index">
                            <a :href="item.href" :class="[ item.type == 'button' ? 'btn btn-red '+item.buttonType : '']"
                               v-text="item.title" v-if="index === 1" id="getText">
                            </a>
                            <a :href="item.href" :class="[ item.type == 'button' ? 'btn btn-red '+item.buttonType : '']"
                               v-text="item.title" v-else>
                            </a>
                        </li>
                        <b-dropdown id="dropdown-header" :class="user.photo ? '' : 'text-button'" v-if="user != false">
                            <template #button-content>
                                <img :src="`/storage/app/public/avatars/${user.photo}`" alt="UserPhoto"
                                     v-if="user.photo">
                                <span translate="no" v-else>{{ user.name }}</span>
                                <span :class="[user.photo ? '' : 'text-counter' ,'notifications__counter']"
                                      v-if="notificationsCounts && notificationsCounts.length != 0 && notificationsCounts.summary != 0">{{
                                        notificationsCounts.summary.toString().length > 2 ? '...' : notificationsCounts.summary
                                    }}</span>
                            </template>
                            <b-dropdown-item href="/cabinet/notifications">Profile</b-dropdown-item>
                            <b-dropdown-item v-if="user.permisions=='ADMIN' || user.permisions == 'MODERATOR'"
                                             href="/admin">Admin
                            </b-dropdown-item>
                            <b-dropdown-item href="/logout">Logout</b-dropdown-item>
                        </b-dropdown>
                        <li v-else>
                            <a href="/login" translate="no" v-text="$ml.get('Account')">Account</a>
                        </li>
                        <li @click="handleGlobalClick">
                            <a href="#" @click="toggleTranslateBlock" id="asdasda">{{ language }}</a>

                            <Translator translate="no" class="language" v-if="showTranslateBlock"
                                        style="position:absolute; margin-top:10px; background: black; right: 37px"
                                        :show-image="false" :countries="countries"/>
                        </li>
                    </div>
                </ul>
            </nav>
            <button :class="[isMenu ? 'burger active' : 'burger']" @click="isMenu = !isMenu">
                <span class="burger__line"></span>
            </button>
        </div>

        <!-- <div id="getText" style="opacity: 0.99;">You are running Vue in development mode. Make sure to turn on production mode when deploying for production.</div> -->
    </header>
</template>

<script>
import axios from "axios";
import {Translator} from 'vue-google-translate';
import {franc, francAll} from 'franc'

export default {
    name: "Header",
    props: ['links', 'siteName', 'user', 'notificationsCounts'],
    data() {
        return {
            isMenu: false,
            isActive: false,
            countries: [
                {
                    code: "en|ru",
                    title: "RU",
                    altText: "ruZ"
                },

                {
                    code: "en|en",
                    title: "EN",
                    altText: "enZ"
                },
            ],
            showTranslateBlock: false,
            language: 'EN'
        }
    },

    components: {
        Translator
    },

    mounted() {

        const metaTag = document.createElement("meta");
        metaTag.setAttribute("description", "You are running Vue in development mode. Make sure to turn on production mode when deploying for production.");
        document.head.appendChild(metaTag);

        this.$store.dispatch("updateNotifications", this.notificationsCounts)
        this.getStatus()

        // document.addEventListener('click', this.handleGlobalClick);

        const googleTranslateElementInit = () => {
            new google.translate.TranslateElement(
                {pageLanguage: 'en', autoDisplay: false},
                'app'
            );
        };


        const addScript = document.createElement('script');
        addScript.setAttribute(
            'src',
            'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'
        );

        const addRegexScript = document.createElement('script');
        addRegexScript.setAttribute(
            'src',
            'https://cdn.jsdelivr.net/gh/lewis-kori/vue-google-translate@main/src/utils/translatorRegex.js'
        );

        document.body.appendChild(addScript);
        document.body.appendChild(addRegexScript);

        window.googleTranslateElementInit = googleTranslateElementInit;

        document.querySelectorAll('.language-item').forEach(item => {
            item.addEventListener('click', event => {
                this.handleClick(event)
            });
        });

        setTimeout(() => {
            var htmlElement = document.getElementsByTagName('html')[0];
            var langValue = htmlElement.getAttribute('lang');
            this.$ml.change((langValue === 'ru' ? 'russian' : 'english'))
        }, 2000);

        if (localStorage.getItem('lang') === 'en') {
            this.language = 'EN'
        } else {
            this.language = 'RU'
        }
    },

    methods: {
        getMenu() {
            this.isMenu = true
        },
        getStatus() {
            axios.get("/site-status")
                .then(
                    resp => {
                        this.isActive = resp.data
                        if (resp.data == 1) {
                            window.location.href = '/service';
                        }
                    }
                )
        },

        toggleTranslateBlock() {
            this.showTranslateBlock = !this.showTranslateBlock;
        },

        async handleGlobalClick(event) {
            const className = event.target.classList.length > 0 ? event.target.classList[0] : null;

            if (event.target.textContent === 'RU' && className === 'language__text') {
                this.$ml.change('russian');
                localStorage.setItem('lang', 'ru');
                this.$root.$emit('localStorageLangUpdated', 'ru');
                this.language = 'RU';

                // Обновление текста элемента с id "asdasda" в DOM
                const element = document.getElementById('asdasda');
                if (element) {
                    element.textContent = 'RU';
                }

                console.log('ru');
            }

            if (event.target.textContent === 'EN' && className === 'language__text') {
                this.$ml.change('english');
                this.$root.$emit('localStorageLangUpdated', 'en');
                localStorage.setItem('lang', 'en');
                this.language = 'EN';

                // Обновление текста элемента с id "asdasda" в DOM
                const element = document.getElementById('asdasda');
                if (element) {
                    element.textContent = 'EN';
                }

                console.log('en');
            }
        },


        // updateLocalStorageLang(newValue) {
        // localStorage.setItem('lang', newValue);
        // Вызываем глобальное событие
        // this.$root.$emit('localStorageLangUpdated', newValue);
        // },
    },

    beforeUnmount() {
        this.$ml.change('english')
        localStorage.setItem('lang', 'en')
    }

}
</script>

<style scoped>
.form-select {
    background-color: black;
    color: #fff;
    border: none;
    font-family: 'GhothamPro-Bold';
    font-size: 14PX;
}


</style>
