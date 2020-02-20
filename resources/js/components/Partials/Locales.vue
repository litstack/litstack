<template>
    <b-dropdown-group :header="$t('choose_language')">
        <b-dropdown-item-button
            v-for="(locale, index) in locales"
            :key="index"
            @click="setLocale(locale)"
        >
            <span
                class="flag-icon flag-icon-squared"
                :class="flagIcon(locale)"
            ></span>
            {{ locale }}
        </b-dropdown-item-button>
    </b-dropdown-group>
</template>

<script>
export default {
    name: 'Locales',
    methods: {
        async setLocale(locale) {
            let response = await this.$store.dispatch('setLocale', { locale });
            this.$bvToast.toast(this.$t('locale_set'), {
                variant: 'success'
            });
        },
        flagIcon(locale) {
            if (locale == 'en') {
                locale = 'gb';
            }
            return `flag-icon-${locale}`;
        }
    },
    computed: {
        locales() {
            return Object.keys(window.i18n);
        }
    }
};
</script>

<style lang="scss" scoped>
.flag-icon {
    width: 20px;
    height: 20px;
    line-height: 20px;
    border-radius: 100px;
}
</style>
