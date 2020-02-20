import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';

const initialState = {
    language: '',
    languages: [],
    fallback_locale: '',
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
    fallback_locale(state) {
        return state.fallback_locale;
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
    SET_CONFIG(state, config) {
        state.config = config;

        let slash = config.route_prefix.startsWith('/') ? '' : '/';

        state.baseURL = '' + slash + config.route_prefix + '/';
        //console.log(state)
        Bus.$emit('configSet', state.config);
    },
    SET_LANGUAGES(state, languages) {
        state.languages = languages;
    },
    SET_FALLBACK_LOCALE(state, fallback_locale) {
        state.fallback_locale = fallback_locale;
    },
    SET_LANGUAGE(state, lng) {
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
