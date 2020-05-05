import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';

const initialState = {
    language: '',
    languages: [],
    fallback_locale: '',
    fjord_config: {},
    config: {},
    baseURL: ''
};

const getters = {
    languages(state) {
        return state.languages;
    },
    language(state) {
        return state.language;
    },
    locale(state) {
        return state.language;
    },
    fallback_locale(state) {
        return state.fallback_locale;
    },
    config(state) {
        return state.config;
    },
    fallback_locale(state) {
        return state.fallback_locale;
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
    },
    SET_FJORD_CONFIG(state, config) {
        state.fjord_config = config;

        let slash = config.route_prefix.startsWith('/') ? '' : '/';

        state.baseURL = '' + slash + config.route_prefix + '/';
        Bus.$emit('configSet', state.fjord_config);
    },
    SET_LANGUAGES(state, languages) {
        state.languages = languages;
    },
    SET_FALLBACK_LOCALE(state, fallback_locale) {
        state.fallback_locale = fallback_locale;
    },
    SET_LANGUAGE(state, lng) {
        state.language = lng;
        Fjord.bus.$emit('languageChanged', state.language);
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
