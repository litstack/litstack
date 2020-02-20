import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from '@fj-js/../translations.json';
Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: localStorage.getItem('fj-locale') || 'en',
    messages
});

export default i18n;
