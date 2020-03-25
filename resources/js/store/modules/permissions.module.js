import Vue from 'vue';

const state = {
    permissions: null
};

const getters = {
    permissions(state) {
        return state;
    }
};

const mutations = {
    SET_PERMISSIONS(state, permissions) {
        state.permissions = permissions;
    }
};

const actions = {

};

const module = {
    state,
    getters,
    mutations,
    actions
};

export default module;
