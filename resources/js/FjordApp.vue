<template>
    <component :is="component" v-bind="preparedProps" :slots="slots" />
</template>

<script>
import Fjord from './fjord';
import { mapGetters } from 'vuex';
import axiosMethods from './common/axios';

export default {
    inheritAttrs: false,
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
        roles: {
            type: Array,
            default() {
                return [];
            }
        },
        auth: {
            type: Object,
            required: true
        },
        permissions: {
            type: Array,
            required: true
        },
        appLocale: {
            type: String,
            required: true
        },
        slots: {
            type: [Object, Array],
            default() {
                return {};
            }
        }
    },
    data() {
        return {
            preparedProps: {}
        };
    },
    beforeMount() {
        axios.interceptors.response.use(
            this.axiosResponseSuccess,
            this.axiosResponseError
        );

        this.$store.commit('SET_ROLES', this.roles);
        this.$store.commit('SET_PERMISSIONS', this.permissions);
        this.$store.commit('SET_LANGUAGES', this.translatable.languages);
        this.$store.commit('SET_LANGUAGE', this.translatable.language);
        this.$store.commit(
            'SET_FALLBACK_LOCALE',
            this.translatable.fallback_locale
        );
        this.$store.commit('SET_FJORD_CONFIG', this.config);

        this.prepareProps();
        this.setAuthData();
        this.setAppLocale();

        this.callPluginExtensions();
        this.callPluginMethods('beforeMount');

        this.$Bus.$on('save', this.save);
        this.$Bus.$on('cancelSave', this.cancelSave);
    },
    mounted() {
        document.querySelector('.fj-nav-loader').remove();
        this.showHiddenElements();

        this.$Bus.$on('error', e => {
            this.$bvToast.toast(e, {
                variant: 'danger'
            });
        });

        this.callPluginMethods('mounted');
    },
    methods: {
        ...axiosMethods,
        cancelSave() {
            this.$store.dispatch('cancelSave');
        },
        async save() {
            let results = await this.$store.dispatch('save');

            if (results.hasSucceeded()) {
                this.$bvToast.toast(this.$t('fj.saved'), {
                    variant: 'success'
                });
            }
        },
        setAppLocale() {
            this.$i18n.locale = this.appLocale;
        },
        showHiddenElements() {
            let elements = document.getElementsByClassName('fj-hide');

            for (let i in elements) {
                let element = elements[i];
                if (!element.classList) {
                    continue;
                }
                element.classList.toggle('show');
            }
        },
        callPluginExtensions() {
            let plugins = Fjord.getPlugins();
            for (let i = 0; i < plugins.length; i++) {
                let plugin = plugins[i];
                if (!('extensions' in plugin)) {
                    continue;
                }
                if (!(this.component in plugin.extensions)) {
                    continue;
                }

                plugin.extensions[this.component](this, this.props);
            }
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
        prepareProps() {
            if (typeof this.props == typeof {}) {
                this.preparedProps = Object.assign({}, this.props);
            }

            if (this.preparedModels) {
                this.preparedProps.models = this.preparedModels;
            }
        }
    }
};
</script>
