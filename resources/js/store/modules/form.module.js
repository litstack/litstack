import Vue from 'vue';

const state = {
    config: null
};

const getters = {
    form(state) {
        return state;
    }
};

const mutations = {
    SET_FORM_CONFIG(state, config) {
        state.config = config;
    }
};

const actions = {
    setFormConfig({ commit }, config) {
        commit('SET_FORM_CONFIG', config);
    }
};

const module = {
    state,
    getters,
    mutations,
    actions
};

export default module;
