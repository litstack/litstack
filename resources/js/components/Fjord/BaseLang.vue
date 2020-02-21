<template>
    <div class="btn-group btn-group-sm" role="group">
        <button
            class="btn"
            :class="[
                {
                    'btn-primary': lang == language,
                    'btn-secondary': lang != language
                }
            ]"
            v-for="lang in languages"
            @click="switchLanguage(lang)"
        >
            {{ lang }}
        </button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'BaseLang',
    computed: {
        ...mapGetters(['language'])
    },
    props: {
        languages: {
            require: true,
            type: Array
        },
        currentLanguage: {
            require: true,
            type: String
        }
    },
    methods: {
        switchLanguage(language) {
            this.$store.commit('SET_LANGUAGE', language);
        }
    }
};
</script>
