const state = {
    /**
     * Authenticated user state.
     */
    auth: {},
};

const getters = {
    /**
     * Authenticated user getter.
     */
    auth(state) {
        return state.auth;
    },
};

const mutations = {
    /**
     * Set authenticated user.
     */
    SET_AUTH_DATA(state, payload) {
        state.auth = payload;
    },
};

const actions = {
    /**
     * Set app locale.
     */
    async setAppLocale({ commit, state }, locale) {
        if (locale != state.locale) {
            localStorage.setItem('lit-locale', locale.locale);
            const { data } = await axios.post('/set-locale', locale);
            window.location.reload();
        }
    },
};

const module = {
    state,
    getters,
    mutations,
    actions,
};

export default module;
