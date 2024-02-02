<template>
    <div class="d-flex flex-column" v-if="loaded">
        <div class="d-flex flex-column container" v-if="to_moder.length != 0">
            <p class="block-title tr-none">Moderation</p>
            <div class="cards">
                <ModerationCard
                    v-for="(mod, key) in to_moder"
                    :card-title="mod.title"
                    :keygen="key"
                    :user-id="mod.user_id"
                    type="models"
                    :key="key"
                    @onDelete="onDelete"
                    :image="JSON.parse(mod.photos)[0]"
                ></ModerationCard>
            </div>
            <pagination
                :limit="4"
                :data="to_moder"
                @pagination-change-page="getNoModerationResults"
            >
                <span slot="prev-nav">&lt; Previous</span>
                <span slot="next-nav">Next &gt;</span>
            </pagination>
        </div>
        <div class="mt-5 d-flex flex-column">
            <div class="d-flex w-100 justify-content-between">
                <form class="search__form user-search search-sm" method="GET">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="23.206"
                        height="23.206"
                        viewBox="0 0 23.206 23.206"
                    >
                        <g
                            id="Group_16"
                            data-name="Group 16"
                            transform="translate(-41589.794 -6523)"
                        >
                            <g
                                id="Ellipse_2"
                                data-name="Ellipse 2"
                                transform="translate(41595 6523)"
                                fill="none"
                                stroke="#707070"
                                stroke-width="1"
                            >
                                <circle cx="9" cy="9" r="9" stroke="none"></circle>
                                <circle cx="9" cy="9" r="8.5" fill="none"></circle>
                            </g>
                            <line
                                id="Line_2"
                                data-name="Line 2"
                                y1="7"
                                x2="8"
                                transform="translate(41590.5 6538.5)"
                                fill="none"
                                stroke="#707070"
                                stroke-linecap="round"
                                stroke-width="1"
                            ></line>
                        </g>
                    </svg>
                    <input
                        type="text"
                        placeholder="Search"
                        name="searchBy"
                        :value="searched"
                    />
                </form>
                <a class="btn btn-bordered sm" href="/upload-model">+ Add Model</a>
            </div>
            <div v-if="cards && cards.length != 0">
                <div class="cards">
                    <ProductCard
                        v-for="(card, key) in cards"
                        :url="'/models/' + card.alias"
                        :cardTitle="card.title"
                        :key="key"
                        :user-id="card.user_id"
                        :is-free="card.is_free"
                        :is-vr="card.is_vr"
                        :id="card.id"
                        :checked="card.user_bookmarks_count == 1 ? true : false"
                        :images="JSON.parse(card.photos)"
                    ></ProductCard>
                </div>
                <pagination
                    :limit="4"
                    :data="cardData"
                    @pagination-change-page="getModerationResults"
                >
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
            </div>
        </div>
    </div>
</template>

<script>
const pagination = () => import("laravel-vue-pagination");
const ProductCard = () => import("../../components/ProductCard");

import ModerationCard from "../Components/ModerationCard";

export default {
    name: "Models",
    data() {
        return {
            to_moder: [],
            cards: [],
            cardData: [],
            loaded: false,
            to_moderData: null,
        };
    },
    computed: {
        path() {
            return window.location.pathname;
        },
        searched() {
            let params = new URLSearchParams(window.location.search)
            return params.has("searchBy") ? params.get("searchBy") : "";
        }
    },
    components: {
        ModerationCard,
        pagination,
        ProductCard,
    },
    methods: {
        getNoModerationResults(page = 1) {
            let href = document.location.href
            let index = href.indexOf("?");
            href = index == "" ? "" : href.substring(index + 1);
            axios
                .get("/user/moderation/products?page=" + page + "&" + href)
                .then((response) => {
                    console.log(response)
                    this.to_moderData = response.data;
                    this.to_moder = response.data.data;
                });
        },
        onDelete(data) {
            let key = data.key;
            axios.delete("/products/" + this.to_moder[key].id).then((resp) => {
                if (resp.status == 200) {
                    this.to_moder.splice(key, 1);
                }
            });
        },
        getModerationResults(page = 1) {
            let href = document.location.href
            let index = href.indexOf("?");
            href = index == "" ? "" : href.substring(index + 1);

            this.cardData = [];
            this.cards = [];

            axios
                .get("/user/moderated/products?page=" + page + "&" + href)

                .then((response) => {
                    this.cardData = response.data;
                    this.cards = response.data.data;
                });
        },
    },
    props: ["serverData"],
    mounted() {
        if (this.serverData) {
            this.cardData = this.serverData[0];
            this.cards = this.serverData[0].data;
            this.to_moderData = this.serverData[1];
            this.to_moder = this.serverData[1].data;
        } else {
            this.getNoModerationResults();
            this.getModerationResults();
        }
        this.loaded = true;
    },
};
</script>

