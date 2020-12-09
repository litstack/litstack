import Vue from 'vue';
import VueI18n from 'vue-i18n';

const messages = window.i18n_m;

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: localStorage.getItem('lit-locale') || 'en',
    messages,
});

window.i18n = i18n;

export default i18n;
