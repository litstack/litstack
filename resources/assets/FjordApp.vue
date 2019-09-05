<template>
    <component :is="component" v-bind="preparedProps" />
</template>

<script>
import FjordModel from './eloquent/fjord.model';
import TranslatableModel from './eloquent/translatable.model';
import { mapGetters } from 'vuex';

export default {
    name: 'FjordApp',
    computed: {
        ...mapGetters(['canSave'])
    },
    props: {
        models: {
            type: [Object, Array]
        },
        component: {
            type: String,
            required: true
        },
        props: {
            type: [Object, Array]
        },
        translatable: {
            type: Object,
            required: true
        },
        config: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            preparedModels: {},
            preparedProps: {}
        };
    },
    methods: {
        prepareModels() {
            if (typeof this.models != typeof {}) {
                return;
            }

            for (name in this.models) {
                this.preparedModels[name] = this.prepareModel(
                    this.models[name]
                );
            }
        },
        prepareProps() {
            if (typeof this.props == typeof {}) {
                this.preparedProps = Object.assign({}, this.props);
            }

            if (this.preparedModels) {
                this.preparedProps.models = this.preparedModels;
            }
        },
        prepareModel(model) {
            switch (model.type) {
                case 'fjord':
                    return new FjordModel(model);
                case 'translatable':
                    return new TranslatableModel(model);
                default:
                    return new FjordModel(model);
            }
        }
    },
    beforeMount() {
        this.$store.commit('setLanguages', this.translatable.languages);
        this.$store.commit('setLanguage', this.translatable.language);
        this.$store.commit(
            'setFallbackLocale',
            this.translatable.fallback_locale
        );
        this.$store.commit('setConfig', this.config);

        this.prepareModels();
        this.prepareProps();
    }
};
</script>

<style lang="css" scoped></style>
