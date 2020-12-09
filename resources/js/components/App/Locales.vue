<template>
    <b-col cols="12">
        <h6>{{ __('base.system_language').capitalizeAll() }}</h6>
        <b-select
            :value="$i18n.locale"
            :options="options"
            @change="setAppLocale"
        />
    </b-col>
</template>

<script>
export default {
    name: 'Locales',
    data() {
        return {
            config: Lit.config.translatable,
        };
    },
    methods: {
        async setAppLocale(locale) {
            let response = await this.$store.dispatch('setAppLocale', {
                locale,
            });
        },
        flagIcon(locale) {
            if (locale == 'en') {
                locale = 'gb';
            }
            return `flag-icon-${locale}`;
        },
        isLocaleActive(locale) {
            return this.$i18n.locale == locale;
        },
    },
    computed: {
        options() {
            let options = {};
            for (let i in this.config.locales) {
                let locale = this.config.locales[i];
                options[locale] = this.__language(locale);
            }
            return options;
        },
    },
};
</script>
