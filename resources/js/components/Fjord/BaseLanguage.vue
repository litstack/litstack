<template>
    <b-button-group size="sm">
        <b-button
            v-for="(lang, index) in languages"
            :key="index"
            @click="setLanguage(lang)"
            :variant="variant(lang)"
            >{{ lang }}</b-button
        >
    </b-button-group>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'BaseLanguage',
    computed: {
        ...mapGetters(['language', 'languages'])
    },
    methods: {
        setLanguage(language) {
            this.$store.commit('SET_LANGUAGE', language);
        },
        variant(language) {
            return language == this.language ? 'primary' : 'secondary';
        }
    }
};
</script>
