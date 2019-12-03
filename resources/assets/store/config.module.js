import Vue from 'vue';
import Bus from './../common/event.bus';

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
    ['setConfig'](state, config) {
        state.config = config;

        let slash = config.route_prefix.startsWith('/') ? '' : '/';

        state.baseURL = '' + slash + config.route_prefix + '/';
        //console.log(state)
        Bus.$emit('configSet', state.config);
    },
    ['setLanguages'](state, languages) {
        state.languages = languages;
    },
    ['setFallbackLocale'](state, fallback_locale) {
        state.fallback_locale = fallback_locale;
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
