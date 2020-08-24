//require('regenerator-runtime/runtime');
require('@fj-js/common/bootstrap');
require('@fj-js/common/lit');
require('@fj-js/common/i18n');
require('@fj-js/common/event.bus');

//require('@fj-js/service/component.service');
require('@fj-js/service/library.service');

import Vue from 'vue';
import mixins from '@fj-js/common/mixins';
Vue.mixin({
	methods: {
		...mixins,
		// mocking trans methods
		trans: (key) => key,
		__: (key) => key,
	},
});

//import { createLocalVue } from '@vue/test-utils';
import store from '@fj-js/store';

store.createStore();
