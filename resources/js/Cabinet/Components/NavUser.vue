<template>
    <div class="d-flex container flex-column">
        <ul class="list-unstyled profile-nav d-flex" v-if="loaded">
            <li v-for="(link, key) in links"
                :key="key"
                :class="[link.path == current ? 'active' : '','position-relative']"
                :data-type="link.name"
            >
                <span v-if="link.countNotifications != null && link.countNotifications != 0 "
                      class="notifications__counter">
                    {{ link.countNotifications.toString().length > 2 ? '...' : link.countNotifications }}
                </span>
                <a :href="link.path">{{ link.name }}</a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "NavUser",
    props: {
        userNoOwner: {
            type: Number,
            default: null
        },
    },
    data() {
        return {
            loaded: true,
            current: window.location.pathname,
            links: [
                {
                    name: "Forum",
                    path: "/user/forum" + (this.userNoOwner ? '/' + this.userNoOwner : ''),
                    notifications_label: "forum_count",
                },
                {
                    name: "Blog",
                    path: "/user/blog" + (this.userNoOwner ? '/' + this.userNoOwner : ''),
                    notifications_label: "",
                },
                {
                    name: "Models",
                    path: "/user/models" + (this.userNoOwner ? '/' + this.userNoOwner : ''),
                    notifications_label: "",
                },
                {
                    name: "Gallery",
                    path: "/user/gallery" + (this.userNoOwner ? '/' + this.userNoOwner : ''),
                    notifications_label: "",
                },

            ]
        }
    },
    mounted() {
        let notifications = this.$store.getters.getNotifications;
        let links = this.links
        Object.entries(notifications).forEach(
            item => {
                let key = item[0]
                links.map(
                    elem => {
                        if (elem.notifications_label == key) {
                            elem.countNotifications = item[1]
                        }

                    }
                )
            }
        )
        this.loaded = false
        this.links = links
        this.$nextTick(
            () => {
                this.loaded = true
            }
        )
    }
}
</script>

