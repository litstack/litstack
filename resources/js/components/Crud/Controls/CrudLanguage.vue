<template>
    <component
        :is="wrapper"
        variant="outline-primary"
        :text="language"
        split
        split-variant="primary"
    >
        <component
            :is="child"
            v-for="(lang, index) in languages"
            :key="index"
            @click="setLanguage(lang)"
            :class="{ active: active(lang) }"
            :active="active(lang)"
            variant="outline-primary"
            size="md"
        >
            {{ lang }}
        </component>
    </component>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'CrudLanguage',
    methods: {
        /**
         * Set edit language.
         *
         * @param {String} language
         * @return {undefined}
         */
        setLanguage(language) {
            this.$store.commit('SET_LANGUAGE', language);
        },

        /**
         * Determines if edit language is active.
         *
         * @param {String} language
         * @return {Boolean}
         */
        active(language) {
            return language == this.language;
        }
    },
    computed: {
        ...mapGetters(['language', 'languages']),

        /**
         * Wether to show dropdown or not.
         *
         * @return {Boolean}
         */
        dropdown() {
            return this.languages.length > 3;
        },

        /**
         * Wrapper component name.
         *
         * @return {String}
         */
        wrapper() {
            return this.dropdown ? 'b-dropdown' : 'b-button-group';
        },

        /**
         * Child component name.
         *
         * @return {String}
         */
        child() {
            return this.dropdown ? 'b-dropdown-item' : 'b-button';
        }
    }
};
</script>
