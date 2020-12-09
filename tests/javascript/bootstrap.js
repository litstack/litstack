require('@lit-js/common/bootstrap');
require('@lit-js/common/lit');
require('@lit-js/common/i18n');
require('@lit-js/common/event.bus');

require('@lit-js/service/library.service');

import Vue from 'vue';
import mixins from '@lit-js/common/mixins';
Vue.mixin({
    methods: {
        ...mixins,
        // mocking trans methods
        trans: (key) => key,
        __: (key) => key,
    },
});

//import { createLocalVue } from '@vue/test-utils';
import store from '@lit-js/store';

store.createStore();
