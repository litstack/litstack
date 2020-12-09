import Vue from 'vue';
import Vuex from 'vuex';

import Bus from '@lit-js/common/event.bus';

import VueDropzone from 'vue2-dropzone';
//import 'vue2-dropzone/dist/vue2Dropzone.min.css';

import VueApexCharts from 'vue-apexcharts';

import BootstrapVue from 'bootstrap-vue';
// import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';

if ('vue-ctk-date-time-picker' in window) {
    Vue.component(
        'vue-ctk-date-time-picker',
        window['vue-ctk-date-time-picker']
    );
}

import Draggable from 'vuedraggable';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';

import vSelect from 'vue-select';
Vue.component('v-select', vSelect);

window.numeral = require('numeral');

// Modules
Vue.use(Vuex);
Vue.use(BootstrapVue, {
    BTooltip: {
        delay: {
            show: 800,
            hide: 100,
        },
    },
    BToast: {
        toaster: 'b-toaster-bottom-right',
    },
});

Vue.use(Draggable);
Vue.use(VueDropzone);
Vue.use(VueLodash, { lodash });

Vue.component('apexchart', VueApexCharts);

// prototypes
Vue.prototype.$bus = Bus;
