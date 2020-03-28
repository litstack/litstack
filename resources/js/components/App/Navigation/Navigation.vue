<template>
    <div :class="config.layout == 'horizontal' ? 'container' : ''">
        <fj-nav-item v-for="(item, index) in items" :item="item" :key="index" />
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Navigation',
    props: {
        items: Array
    },
    data() {
        return {};
    },
    computed: {
        ...mapGetters(['baseURL', 'config'])
    }
};
</script>
