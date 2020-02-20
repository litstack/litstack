import Vue from 'vue';
import VueI18n from 'vue-i18n';

const messages = window.i18n;

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: localStorage.getItem('fj-locale') || 'en',
    messages
});

export default i18n;
