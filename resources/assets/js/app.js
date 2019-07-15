require('./bootstrap');

import Navigation from './components/Partials/Navigation';
import Notify from './components/Notify';

import Notifications from 'vue-notification';
import VModal from 'vue-js-modal';
import { ServerTable, ClientTable, Event } from 'vue-tables-2';
import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use(Notifications);
Vue.use(VModal);
Vue.use(ClientTable);
Vue.use(CKEditor);

/**
 * Import all Crud views
 */
const req = require.context('./components/Crud/', true, /\.(js|vue)$/i);
for (const key of req.keys()) {
    const name = key.match(/\w+/)[0];
    Vue.component(name, req(key).default);
}

/**
 * Import Formfields
 */
const formfields = require.context(
    './components/Formfields/',
    true,
    /\.(js|vue)$/i
);
for (const key of formfields.keys()) {
    const name = key.match(/\w+/)[0];
    Vue.component(name, formfields(key).default);
}

import PagesShow from './components/Pages/PagesShow';

const app = new Vue({
    el: '#app',
    components: {
        Navigation,
        Notify,
        PagesShow
    }
});
