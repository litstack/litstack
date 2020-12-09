<template>
    <component :is="component" v-bind="preparedProps" :slots="slots" />
</template>

<script>
import Litstack from './litstack';
import { mapGetters } from 'vuex';

export default {
    inheritAttrs: false,
    name: 'LitstackApp',
    computed: {
        ...mapGetters(['canSave']),
    },
    props: {
        models: {
            type: [Object, Array],
        },
        component: {
            type: String,
            required: true,
        },
        props: {
            type: [Object, Array],
        },
        translatable: {
            type: Object,
            required: true,
        },
        config: {
            type: Object,
            required: true,
        },
        debug: {
            type: Boolean,
            require: true,
        },
        roles: {
            type: Array,
            default() {
                return [];
            },
        },
        auth: {
            type: Object,
            required: true,
        },
        permissions: {
            type: Array,
            required: true,
        },
        appLocale: {
            type: String,
            required: true,
        },
        slots: {
            type: [Object, Array],
            default() {
                return {};
            },
        },
    },
    data() {
        return {
            preparedProps: {},
        };
    },
    beforeMount() {
        window.toast = this.$bvToast.toast;
        this.fillStore();
        this.prepareProps();
        this.setAppLocale();

        this.$Bus.$on('save', this.save);
        this.$Bus.$on('cancelSave', this.cancelSave);

        this.$Bus.$emit('mounted');
    },
    mounted() {
        this.loaded();

        this.showHiddenElements();

        this.$Bus.$on('error', (e) => {
            this.$bvToast.toast(e, {
                variant: 'danger',
            });
        });
    },
    methods: {
        /**
         * Fill store.
         */
        fillStore() {
            this.$store.commit('SET_DEBUG', this.debug);
            this.$store.commit('SET_ROLES', this.roles);
            this.$store.commit('SET_PERMISSIONS', this.permissions);
            this.$store.commit('SET_LANGUAGES', this.translatable.languages);
            this.$store.commit('SET_LANGUAGE', this.translatable.language);
            this.$store.commit(
                'SET_FALLBACK_LOCALE',
                this.translatable.fallback_locale
            );
            this.$store.commit('SET_LIT_CONFIG', this.config);
            this.$store.commit('SET_AUTH_DATA', this.auth);
        },

        /**
         * Handle document loaded.
         */
        loaded() {
            // Hide nav loader.
            document.querySelector('.lit-nav-loader').remove();

            let spinner = document.getElementById('lit-spinner');
            let main = document.querySelector('div#litstack > main');
            if (spinner && main) {
                spinner.classList.add('loaded');
                main.classList.add('loaded');
            }
            this.toggleSidebar();
        },

        /**
         * Toggle mobile sidebar.
         */
        toggleSidebar() {
            const toggleSidebar = () => {
                document
                    .querySelector('.lit-navigation')
                    .classList.toggle('visible');

                document
                    .querySelector('#litstack')
                    .classList.toggle('navigation-visible');
            };

            document
                .querySelector('.lit-main-navigation-toggle')
                .addEventListener('click', (e) => {
                    toggleSidebar();
                });
        },

        /**
         * Dispatch cancelSave when event is emitted to remove save jobs from
         * store.
         */
        cancelSave() {
            this.$store.dispatch('cancelSave');
        },

        /**
         * Show success toast after saved.
         */
        async save() {
            let results = await this.$store.dispatch('save');

            if (results.hasSucceeded()) {
                this.$bvToast.toast(this.__('base.saved'), {
                    variant: 'success',
                });
            }
        },

        /**
         * Set i18n locale.
         */
        setAppLocale() {
            this.$i18n.locale = this.appLocale;
        },

        /**
         * Show hidden elements that should be shown.
         */
        showHiddenElements() {
            let elements = document.getElementsByClassName('lit-hide');

            for (let i in elements) {
                let element = elements[i];
                if (!element.classList) {
                    continue;
                }
                element.classList.toggle('show');
            }
        },

        /**
         * Prepare props.
         */
        prepareProps() {
            if (typeof this.props == typeof {}) {
                this.preparedProps = Object.assign({}, this.props);
            }

            if (this.preparedModels) {
                this.preparedProps.models = this.preparedModels;
            }
        },
    },
};
</script>
