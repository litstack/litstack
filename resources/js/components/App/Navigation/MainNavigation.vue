<template>
    <div>
        <lit-nav-item
            v-for="(item, index) in items"
            :item="item"
            :key="index"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'MainNavigation',
    props: {
        items: Array,
    },
    data() {
        return {};
    },
    computed: {
        ...mapGetters(['baseURL', 'config']),
    },
    methods: {
        toggleNavigation() {
            document
                .querySelector('.lit-navigation')
                .classList.toggle('visible');
        },
    },
};
</script>
<style lang="scss">
.lit-main-navigation-toggle {
    position: fixed;
    bottom: 0.5rem;
    left: 1rem;
    z-index: 1040;
}
</style>
