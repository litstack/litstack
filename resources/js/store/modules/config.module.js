import Bus from '@lit-js/common/event.bus';

const initialState = {
    /**
     * Crud edit locale.
     */
    language: '',

    /**
     * Crud edit locales.
     */
    languages: [],

    /**
     * Crud edit fallback locale.
     */
    fallback_locale: '',

    /**
     * Lit config.
     */
    lit_config: {},

    /**
     * Config.
     */
    config: {},

    /**
     * Base url with a front slash prepended and appended e.g.: "/admin/"
     */
    baseURL: '',

    /**
     * whether debug mode is enabled.
     */
    debug: false,
};

const getters = {
    debug(state) {
        return state.debug;
    },
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
    },
    lit_config(state) {
        return state.lit_config;
    },
};

export const actions = {};

export const state = Object.assign({}, initialState);

export const mutations = {
    SET_DEBUG(state, debug) {
        state.debug = debug;
    },
    SET_CONFIG(state, config) {
        state.config = config;
    },
    SET_LIT_CONFIG(state, config) {
        state.lit_config = config;

        let slash = config.route_prefix.startsWith('/') ? '' : '/';

        state.baseURL = '' + slash + config.route_prefix + '/';
    },
    SET_LANGUAGES(state, languages) {
        state.languages = languages;
    },
    SET_FALLBACK_LOCALE(state, fallback_locale) {
        state.fallback_locale = fallback_locale;
    },
    SET_LANGUAGE(state, lng) {
        state.language = lng;
        Lit.bus.$emit('languageChanged', state.language);
    },
};

export default {
    state,
    actions,
    mutations,
    getters,
};
