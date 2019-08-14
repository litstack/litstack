import Vue from 'vue'
import Vuex from 'vuex'

import config from './config.module'
import savings from './savings.module'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        config,
        savings
    }
})
