<template>
    <div>
        <b-button-group size="md">
            <b-button
                v-for="(lang, index) in languages"
                :key="index"
                @click="setLanguage(lang)"
                :class="{ active: active(lang) }"
                variant="outline-primary"
                size="md"
            >
                {{ lang }}
            </b-button>
        </b-button-group>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'CrudLanguage',
    methods: {
        setLanguage(language) {
            this.$store.commit('SET_LANGUAGE', language);
        },
        active(language) {
            return language == this.language;
        }
    },
    computed: {
        ...mapGetters(['language', 'languages'])
    }
};
</script>
