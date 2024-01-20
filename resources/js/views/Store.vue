<template>
    <div>
        <div class="top-menu filter" v-if="loadCategories">
            <ul
                class="list-unstyled d-flex justify-content-between"
                style="margin: 0 auto"
            >
                <li
                    v-for="cat in categories"
                    v-if="cat.parent == null"
                    @click.prevent="getSubmenu(cat.id)"
                >
                    <a :href="route">{{ cat.name }}</a>
                </li>
            </ul>
            <div class="submenu-wrapper" v-if="loadSubmenu" v-show="submenu">
                <ul class="submenu d-flex" style="margin: 0 auto">
                    <li
                        v-for="cat in categories"
                        v-if="cat.parent == subCatParent"
                    >
                        <a :href="route + '?cat=' + cat.id">{{ cat.name }}</a>
                    </li>
                </ul>
            </div>
            <div class="container">
                <div class="top-menu-cards">
                    <a
                        :href="route + '?cat=' + cat.id"
                        class="card card-mini"
                        @click.prevent="getSubmenu(cat.id)"
                        v-for="cat in categories"
                        v-if="cat.image != null && cat.image != 'null'"
                    >
                        <img :src="cat.image | imgSrc" :alt="cat.name" />
                        <p class="title">{{ cat.name }}</p>
                    </a>
                </div>
            </div>
            <div class="container"></div>
        </div>
        <div
            v-else
            style="max-width: 1400px; margin: 0 auto"
            class="top-menu filter w-100"
        >
            <ul class="list-unstyled d-flex justify-content-between w-100">
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
                <div style="width: 75px">
                    <b-skeleton class="w-100"></b-skeleton>
                </div>
            </ul>
        </div>
        <div class="container" v-if="loaded">
            <p
                class="block-title block-title__big block-title-black text-center mb-0"
            >
                3d library
            </p>
            <div
                class="top-menu-cards ttc"
                style="justify-content: end !important"
            >
                <a
                    :href="route + '?dop=free'"
                    class="h6 p-2"
                    v-if="loca == '?dop=free'"
                    style="
                        color: #ff8767;
                        font-family: GhothamPro;
                        text-decoration: underline;
                    "
                    >Free</a
                >
                <a
                    :href="route + '?dop=free'"
                    class="h6 p-2 text-decoration-none"
                    v-if="loca != '?dop=free'"
                    style="color: #ff8767; font-family: GhothamPro"
                    >Free</a
                >
                <a
                    :href="route + '?dop=vr'"
                    class="h6 p-2"
                    v-if="loca == '?dop=vr'"
                    style="
                        color: #ff8767;
                        font-family: GhothamPro;
                        text-decoration: underline;
                    "
                    >VR</a
                >
                <a
                    :href="route + '?dop=vr'"
                    class="h6 p-2 text-decoration-none"
                    v-if="loca != '?dop=vr'"
                    style="color: #ff8767; font-family: GhothamPro"
                    >VR</a
                >
                <a
                    :href="route + '?dop=pro'"
                    class="h6 p-2"
                    v-if="loca == '?dop=pro'"
                    style="
                        color: #ff8767;
                        font-family: GhothamPro;
                        text-decoration: underline;
                    "
                    >PRO</a
                >
                <a
                    :href="route + '?dop=pro'"
                    class="h6 p-2 text-decoration-none"
                    v-if="loca != '?dop=pro'"
                    style="color: #ff8767; font-family: GhothamPro"
                    >PRO</a
                >

                <a
                    :href="route + '?dop=lumen'"
                    class="h6 p-2"
                    v-if="loca == '?dop=lumen'"
                    style="
                        color: #ff8767;
                        font-family: GhothamPro;
                        text-decoration: underline;
                    "
                >Lumen</a
                >
                <a
                    :href="route + '?dop=lumen'"
                    class="h6 p-2 text-decoration-none"
                    v-if="loca != '?dop=lumen'"
                    style="color: #ff8767; font-family: GhothamPro"
                >Lumen</a
                >

            </div>
            <div class="cards">
                <ProductCard
                    v-for="(card, index) in cards"
                    :key="`product-card-${card.id}-${currentPage}`"
                    :url="'/models/' + card.alias"
                    :cardTitle="card.title"
                    :id="card.id"
                    :is-free="card.is_free"
                    :is-vr="card.is_vr"
                    :checked="card.user_bookmarks_count == 1 ? true : false"
                    :buyed="card.user_purchases ? card.user_purchases.id : null"
                    :user-id="card.user_id"
                    :images="JSON.parse(card.photos)"
                ></ProductCard>
            </div>
            <div class="container">
                <pagination
                    :limit="4"
                    :data="laravelData"
                    @pagination-change-page="getResults"
                >
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div>
        </div>

        <div
            v-else
            class="d-flex cards flex-wrap justify-content-between container"
        >
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
            <div class="card card-white">
                <b-skeleton class="card-img-top card-skeleton-bg"></b-skeleton>
                <div class="card-body w-100">
                    <b-skeleton class="card-title w-75"></b-skeleton>
                    <b-skeleton type="button"></b-skeleton>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import ProductCard from "../components/ProductCard";
import pagination from "laravel-vue-pagination";
import { paginationMethods } from "../Mixins/pagination";
export default {
    name: "Store",
    data() {
        return {
            loaded: false,
            loadCategories: false,
            cards: [],
            categories: [],
            laravelData: null,
            submenu: false,
            loadSubmenu: false,
            submenuHeight: 0,
            subCatParent: false,
            loca: location.search,
            currentPage: 1,
        };
    },
    mixins: [paginationMethods],
    components: {
        ProductCard,
        pagination,
    },
    filters: {
        imgSrc(data) {
            let imgSrc = data.replace(/['"]+/g, "");
            return "/storage/app/public/categories/" + imgSrc;
        },
    },
    methods: {
        getResults(page = 1) {
            console.log('alsdlfsdlldfl')
            console.log(this.cards)
            this.cards = []
            let params = window.location.href;
            let params_s = params.indexOf("?");
            params = params_s != -1 ? params.substr(params_s + 1) : "";

            if (params) {
                const searchParams = new URLSearchParams(params);
                const paramsArray = [];
                for (const [key, value] of searchParams.entries()) {
                    if (key === "page") continue;
                    paramsArray.push(key.concat("=", value));
                }
                params = paramsArray.join("&");
            }

            function get(name) {
                if (
                    (name = new RegExp(
                        "[?&]" + encodeURIComponent(name) + "=([^&]*)"
                    ).exec(location.search))
                )
                    return decodeURIComponent(name[1]);
            }
            let cat = get("cat");
            let title = get("title");
            params = cat != undefined ? params + `&cat=${cat}` : params;
            params = title != undefined ? params + "&title=" + title : params;
            var newUrl = this.paginationUrl(window.location.href, "page", page);
            this.currentPage = page;
            window.history.pushState("", document.title, newUrl);

            axios
                .get(
                    "/products?count=9&page=" +
                        page +
                        (params == "" ? "" : "&" + params)
                )
                .then((resp) => {
                    if (resp.data.data.length != 0) {
                        this.cards = resp.data.data;
                        this.loaded = true;
                        this.laravelData = resp.data;
                    }
                });
        },
        getSubmenu(id) {
            this.subCatParent = id;
            this.loadSubmenu = true;
            this.submenu = true;
            setTimeout(() => {
                this.submenuHeight =
                    document.querySelector(".submenu").offsetHeight;
                document.querySelector(".submenu-wrapper").style.height =
                    this.submenuHeight + "px";
            }, 150);
        },
    },
    props: ["serverData", "route"],
    mounted() {
        axios.get("/categories-get").then((resp) => {
            this.categories = resp.data;
            this.loadCategories = true;
        });
        if (this.serverData) {
            this.cards = this.serverData.data;
            this.laravelData = this.serverData;
            this.loaded = true;
        } else {
            this.getResults();
        }
    },
};
</script>
