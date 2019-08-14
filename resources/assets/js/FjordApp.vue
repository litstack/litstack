<template>
    <div class="row">
        <div class="col-12 col-md-3 order-md-2">
            <div class="fjord-pagecontrols card">
                <div class="card-header">
                    <i class="fas fa-sliders-h text-primary pr-2"></i>
                    <b>Controls</b>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <button
                                class="btn btn-sm btn-primary"
                                :disabled.prop="!canSave"
                                @click="saveAll"
                            >
                                <i class="fas fa-save"></i> Speichern
                            </button>
                        </div>
                        <div class="col-12 pt-3">
                            <fj-lang-select
                                :languages="languages"
                                :currentLanguage="language"/>
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
import Eloquent from './eloquent';
import EloquentTest from './components/Test/EloquentTest';
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
        language: {
            type: String,
            required: true
        },
        languages: {
            type: Array,
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
            if(typeof this.models != typeof {}) {
                return
            }

            for (name in this.models) {
                this.preparedModels[name] = new Eloquent(this.models[name]);
            }
        },
        prepareProps() {
            if(typeof this.props == typeof {}) {
                this.preparedProps = Object.assign({}, this.props)
            }

            if(this.preparedModels) {
                this.preparedProps.models = this.preparedModels;
            }
        }
    },
    beforeMount() {
        this.$store.commit('setLanguages', this.languages);
        this.$store.commit('setLanguage', this.language);
        this.$store.commit('setConfig', this.config);

        this.prepareModels()
        this.prepareProps()
    }
};
</script>

<style lang="css" scoped></style>
