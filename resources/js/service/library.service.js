import Vue from 'vue';
import Vuex from 'vuex';

import Bus from '@lit-js/common/event.bus';

import VueDropzone from 'vue2-dropzone';
import VueApexCharts from 'vue-apexcharts';
import BootstrapVue from 'bootstrap-vue';
import VCalendar from 'v-calendar';
import Draggable from 'vuedraggable';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';
import vSelect from 'vue-select';

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
Vue.use(VCalendar);

Vue.component('apexchart', VueApexCharts);
Vue.component('v-select', vSelect);

// prototypes
Vue.prototype.$bus = Bus;
