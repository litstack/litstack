<template>
    <div :class="config.layout == 'horizontal' ? 'container' : ''">
        <fj-nav-item-vertical
            v-if="isVertical"
            v-for="(item, index) in items"
            :item="item"
            :key="index"
        />

        <fj-nav-horizontal v-if="isHorizontal" :items="items" />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'MainNavigation',
    props: {
        items: Array
    },
    data() {
        return {};
    },
    computed: {
        ...mapGetters(['baseURL', 'config']),
        isHorizontal() {
            return this.config.layout == 'horizontal';
        },
        isVertical() {
            return this.config.layout == 'vertical';
        }
    },
    methods: {
        isValidItem(item) {
            return typeof item != 'string';
        }
    }
};
</script>
