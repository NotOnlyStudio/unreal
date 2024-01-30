<template>
    <div v-if="rendered" class="container d-flex flex-column">
        <p class="breadcrumbs">
            <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
            <a href="/store" style="color: #b3b3b3; text-decoration: none">Store</a> >
            {{ info ? info.title : undefined }}
        </p>
        <div class="d-flex flex-column product mt-5">

            <b-alert :show="modalShow" @dismissed="modalShow = false" :variant="alertVariant " dismissible>
                {{ modalText }}
            </b-alert>

            <div class="d-flex flex-lg-row flex-md-row flex-column">
                <div class="slider position-relative">
                    <rating
                        :item-id="info.id"
                        type="App\Models\Product"
                        :rating-plus="info.likes_count"
                        :rating-minus="info.dislikes_count"
                        :assessment="info.user_assessment ? info.user_assessment : null    "
                        :author-id="info.user_id"
                        :item-content="info.title"
                        :uncolumn="true"
                    />
                    <author
                        :name="info.users.name"
                        rank="rookie"
                        :id="info.users.id"
                        :photo="info.users.photo"
                    />
                    <Tinybox v-model="index" :images="lightBoxImages"/>
                    <VueSlickCarousel
                        class="gallery-top mt-1"
                        ref="slider-big"
                        v-bind="{
                       responsive: [{
                           breakpoint: 640,
                           settings: {
                               dots: true
                            }
                        }]
                     }"
                        @beforeChange="syncSliders"
                    >
                        <picture v-for="(img,key) in images" :key="key" @click="index = key">
                            <div v-if="info.is_free == 1" title="Up to three free models per day" class="free-banner l-0"><span>Free</span></div>
                            <div v-if="info.is_vr == 1" title="VR" class="free-banner l-0"><span>VR</span></div>
                            <div v-if="info.is_light == 1" title="Lumen" class="free-banner l-0"><span>Lumen</span></div>
                            <source type="image/webp"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | webpAdd"/>
                            <source type="image/png"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | prefix"/>
                            <source type="image/jpg"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | prefix"/>
                            <source type="image/jpeg"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | prefix"/>
                            <b-img-lazy :src="$imgRoute+'models/user-'+info.users.id+'/'+img | prefix"
                                        alt="image item"/>
                        </picture>
                    </VueSlickCarousel>
                    <VueSlickCarousel
                        class="gallery-thumbs"
                        :draggable="images.length <= 5 ? false : true"
                        :swipe="images.length <= 5 ? false : true"
                        :swipeToSlide="images.length <= 5 ? false : true"
                        :touchMove="images.length <= 5 ? false : true"
                        ref="slider-thumb"
                        v-bind="{ slidesToShow: 5 }"
                        :focusOnSelect="true"
                        @beforeChange="syncSliders"
                    >
                        <picture v-for="(img,key) in images" :key="key">
                            <source type="image/webp"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | webpThumb"/>
                            <source type="image/png"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | thumb"/>
                            <source type="image/jpg"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | thumb"/>
                            <source type="image/jpeg"
                                    :srcset="$imgRoute+'models/user-' + info.users.id + '/' + img | thumb"/>
                            <b-img-lazy :src="$imgRoute+'models/user-'+info.users.id+'/'+img | thumb" alt="Image item"/>
                        </picture>
                    </VueSlickCarousel>
                </div>
                <div class="d-flex flex-column product-info">
                    <h1 class="product-title"> {{ info ? info.title : undefined }}
                    </h1>
                    <span class="product-category">
                    <a target="_blank" :href="'/store/?cat='+info.categories.id">{{ info.categories.name }}</a>
                    <span> / </span>
                    <a target="_blank" :href="'/store/?cat='+info.subcategory.id">{{ info.subcategory.name }}</a>
                </span>
                    <div class="my-4">
                        <cart-button
                            :item-id="info.id"
                            :price="info.price"
                            :is-free="info.is_free"
                            :button-download="(info.user_purchases != null || info.is_free) ? true : false"
                            :buy-text="(info.user_purchases != null || info.is_free) ? 'Download' : buy_text"
                            @show-alert="showAlert"
                        />
                    </div>
                    <div class="product__information mt-0">
                        <div class="d-flex flex-lg-row flex-md-row flex-column">
                            <div class="d-flex flex-column">
                                <p class="title">Version:</p>
                                <p class="description">{{ propsData.version }}</p>
                            </div>
                            <div class="d-flex row flex-row px-lg-5 px-md-5 px-0 my-lg-0 my-md-0 my-3">
                                <span class="title col-2 col-md-6"
                                      v-if="propsData.rtx ">{{ propsData.rtx ? "RTX" : '' }}</span>
                                <span class="title col-2 col-md-6"
                                      v-if="info.is_vr ">{{ info.is_vr ? "VR" : '' }}</span>
                                <span class="title col-2 col-md-6"
                                      v-if="info.is_light ">{{ info.is_light ? "Lumen" : '' }}</span>
                                <span class="title col-2 col-md-6"
                                      v-if="propsData.bake ">{{ propsData.bake ? "BAKE" : '' }}</span>
                            </div>
                        </div>

                        <p class="title">General  Description:</p>
                        <p class="description">-3d models for use in computer graphics, have a format compatible with Unreal Engine </p>
                        <p class="description">-Models have good detail and are suitable for use in architectural visualization</p>
                        <p class="description">- Issuance is automatic, on the account from which the selected number of models was paid for</p>

                        <p class="title">Description:</p>
                        <p class="description" v-html="description"></p>
                    </div>
                </div>
            </div>
        </div>
        <commentsWrapper type="App\Models\Product"
                         comments-type="App\Models\Product"
                         :item-id="info.id"
                         :item-user="info.users.id"
        />
    </div>
</template>
<script>
const Tinybox = () => import("vue-tinybox");
const CartButton = () => import("../components/Models/CartButton")
const VueSlickCarousel = () => import('vue-slick-carousel')
const author = () => import("../components/Author")
const rating = () => import("../components/Rating/RatingWrapper")
const commentsWrapper = () => import("../components/Comments/CommentsWrapper");

export default {
    name: "ModelItem",
    props: [
        "info"
    ],
    data() {
        return {
            rendered: true,
            index: null,
            options1: {
                arrows: false,
                asNavFor: this.$refs.c2,
                slidesToScroll: 1,
                focusOnSelect: "true",
            },
            options2: {
                asNavFor: this.$refs.c1,
                slidesToScroll: 3,
                slidesToShow: 5,
                centerPadding: "30px",
                centerMode: true,
                arrows: false,
                infinite: false,
                focusOnSelect: "true"
            },
            modalText: "",
            modalShow: false,
            alertVariant: "",
            buy_text: "Buy Now",
        }
    },
    computed: {
        propsData() {
            let props = JSON.parse(this.info.props)
            props.description = props.description.replace('\n', '<br>')
            return props
        },
        description() {
            let descr = this.propsData.description
            descr = descr.replace('\n', '<br>')
            descr = descr.replace('\t', '<br>')
            return descr.replace(/\r?\n/g, '<br />')
        },
        images() {
            return JSON.parse(this.info.photos);
        },
        lightBoxImages() {
            let arr = [];
            if (this.images != null) {
                this.images.forEach(
                    item => {
                        arr.push(this.$imgRoute + 'models/user-' + this.info.user_id + '/' + item)
                    }
                )
            }
            return arr;
        },
    },
    components: {
        VueSlickCarousel, author, commentsWrapper, Tinybox,
        "cart-button": CartButton,
        rating
    },

    methods: {
        showAlert(data) {
            this.alertVariant = data.variant
            this.modalText = data.text
            this.modalShow = true
        },
        setFirstSwiper(swiper) {
            this.firstSwiper = swiper;
        },
        setSecondSwiper(swiper) {
            this.secondSwiper = swiper;
        },
        syncSliders(currentPosition, nextPosition) {
            this.$refs['slider-big'].goTo(nextPosition)
            this.$refs['slider-thumb'].goTo(nextPosition)
        }
    },
    created() {
        console.clear()
        console.log(this.info)

    },

}
</script>
<style>
@import '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css';
</style>
