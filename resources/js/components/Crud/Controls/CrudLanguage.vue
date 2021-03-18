<script>
import { mapGetters } from 'vuex';

export default {
    name: 'CrudLanguage',
    props: {
        variant: {
            type: String,
            default() {
                return 'primary'
            }
        }
    },
    render(createElement) {
        if (this.languages.length < 2) {
            return;
        }

        let children = [];

        for (let i = 0; i < this.languages.length; i++) {
            let lang = this.languages[i];
            children.push(
                createElement(
                    this.child,

                    {
                        class: { active: this.active(lang) },
                        attrs: {
                            variant: `outline-${this.variant}`,
                            size: 'md',
                        },
                        on: {
                            click: () => this.setLanguage(lang),
                        },
                        key: i,
                    },

                    lang
                )
            );
        }

        return createElement(
            this.wrapper,
            {
                attrs: {
                    variant: 'outline-primary',
                    split: true,
                    'split-variant': 'primary',
                    text: this.language,
                },
            },
            children
        );
    },
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
        },
    },
    computed: {
        ...mapGetters(['language', 'languages']),

        /**
         * whether to show dropdown or not.
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
        },
    },
};
</script>
