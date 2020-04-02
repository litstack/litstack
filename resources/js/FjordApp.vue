<template>
    <component :is="component" v-bind="preparedProps" />
</template>

<script>
import Fjord from './fjord';
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
        },
        auth: {
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
    beforeMount() {
        console.log(
            this._format('Role: {{ role.name }}', {
                role: { name: 'admin' },
                test: 'TEST'
            })
        );

        this.$store.commit('SET_LANGUAGES', this.translatable.languages);
        this.$store.commit('SET_LANGUAGE', this.translatable.language);
        this.$store.commit(
            'SET_FALLBACK_LOCALE',
            this.translatable.fallback_locale
        );
        this.$store.commit('SET_CONFIG', this.config);

        this.prepareModels();
        this.prepareProps();
        this.setAuthData();

        this.callPluginMethods('beforeMount');
    },
    mounted() {
        this.showHiddenElements();

        this.$Bus.$on('error', e => {
            this.$bvToast.toast(e, {
                variant: 'danger'
            });
        });

        this.callPluginMethods('mounted');
    },
    methods: {
        showHiddenElements() {
            let element = document.getElementById('fjord-topbar-right');
            element.style.opacity = 1;
        },
        callPluginMethods(method) {
            let plugins = Fjord.getPlugins();
            for (let i = 0; i < plugins.length; i++) {
                let plugin = plugins[i];
                if (!(method in plugin)) {
                    continue;
                }

                plugin[method](this);
            }
        },
        setAuthData() {
            this.$store.commit('SET_AUTH_DATA', this.auth);
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
    }
};
</script>

<style lang="css" scoped></style>
