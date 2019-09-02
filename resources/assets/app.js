require('./bootstrap');

import FjordApp from './FjordApp';
import Navigation from './components/Partials/Navigation';
import Notify from './components/Notify';

import store from './store';

const app = new Vue({
    el: '#app',
    store,
    components: {
        FjordApp,
        Navigation,
        Notify,
    }
});
