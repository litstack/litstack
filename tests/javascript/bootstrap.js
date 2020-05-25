require('@fj-js/common/fjord');
require('@fj-js/common/event.bus');

//require('@fj-js/service/component.service');
//require('@fj-js/service/library.service');

import { createLocalVue } from '@vue/test-utils';
import Vuex from 'vuex';
import store from '@fj-js/store';

const localVue = createLocalVue();
localVue.use(Vuex);
store.createStore();
