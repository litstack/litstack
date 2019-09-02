<template>
    <div class="row">
        <div class="col-12 col-md-3 order-md-2 pb-4 mb-md-0">
            <div class="fjord-pagecontrols card">
                <div class="card-header">
                    <i class="fas fa-sliders-h text-primary pr-2"></i>
                    <b>Controls</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 pb-3">
                            <b class="text-muted d-block pb-1">
                                Select language
                            </b>
                            <fj-lang-select
                                :languages="translatable.languages"
                                :currentLanguage="translatable.language"
                            />
                        </div>
                        <div class="col-12">
                            <b class="text-muted d-block pb-1">
                                Save changes
                            </b>
                            <button
                                class="btn btn-sm btn-primary"
                                :disabled.prop="!canSave"
                                @click="saveAll"
                            >
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-9 order-md-1">
            <component :is="component" v-bind="preparedProps" />
        </div>
    </div>
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
        saveAll() {
            this.$store.dispatch('saveModels');
        },
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
