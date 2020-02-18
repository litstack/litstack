import Vue from 'vue';
import VueI18n from 'vue-i18n';
import messages from '../../translations.json';
Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: 'de',
    messages
});

export default i18n;
