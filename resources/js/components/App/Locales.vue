<template>
    <b-dropdown-group
        :header="$t('fj.choose_language')"
        v-if="config.translatable ? config.translatable.translatable : false"
    >
        <b-dropdown-item-button
            v-for="(locale, index) in config.translatable.locales"
            :key="index"
            @click="setAppLocale(locale)"
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
import { mapGetters } from 'vuex'

export default {
    name: 'Locales',
    methods: {
        async setAppLocale(locale) {
            let response = await this.$store.dispatch('setAppLocale', { locale });
            this.$bvToast.toast(this.$t('fj.locale_set'), {
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
        ...mapGetters([
            'config'
        ]),
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
