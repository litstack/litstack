import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';

const state = {
    auth: {}
};

const getters = {
    auth(state) {
        return state.auth;
    }
};

const mutations = {
    SET_AUTH_DATA(state, payload) {
        state.auth = payload;
    }
};

const actions = {
    async setAppLocale({ commit, state }, locale) {
        if (locale != state.locale) {
            localStorage.setItem('fj-locale', locale.locale);
            const { data } = await axios.post('/set-locale', locale);
            window.location.reload()
        }
    }
};

const module = {
    state,
    getters,
    mutations,
    actions
};

export default module;
