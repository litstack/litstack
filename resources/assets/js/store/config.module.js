import Vue from 'vue';
import Bus from './../common/event.bus';

const initialState = {
    language: '',
    languages: [],
    config: {},
    baseURL: ''
};

const getters = {
    lng(state) {
        return state.language;
    },
    language(state) {
        return state.language;
    },
    lngs(state) {
        return state.languages;
    },
    languages(state) {
        return state.languages;
    },
    config(state) {
        return state.config;
    },
    baseURL(state) {
        return state.baseURL;
    }
};

export const actions = {};

export const state = Object.assign({}, initialState);

export const mutations = {
    ['setConfig'](state, config) {
        state.config = config;

        let slash = config.route_prefix.startsWith('/') ? '' : '/';

        state.baseURL = '' + slash + config.route_prefix + '/';

        Bus.$emit('configSet', state.config);
    },
    ['setLanguages'](state, languages) {
        state.languages = languages;
    },
    ['setLanguage'](state, lng) {
        state.language = lng;
        Bus.$emit('languageChanged', state.language);
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
