import Vue from 'vue';

const state = {
    actions: [],
    globalActions: []
};

const getters = {
    actions(state) {
        return state;
    }
};

const mutations = {
    SET_ACTIONS(state, payload) {
        state.actions = payload;
    }
};

const actions = {};

const module = {
    state,
    getters,
    mutations,
    actions
};

export default module;
