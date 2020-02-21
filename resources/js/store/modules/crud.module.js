import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';

const state = {
    models: [],
    model: {}
};

const getters = {
    crud(state) {
        return state;
    }
};

const mutations = {
    SET_MODEL(state, data) {
        state.model = data;
    }
};

const actions = {
    async loadItems({ commit, state }, locale) {
        // const { data } = await axios.post(
        //     `${this.form.config.names.table}/index`,
        //     payload
        // );
    },
    async loadModel({ commit }, { route, id }) {
        try {
            const { data } = await axios.get(`${route}/${id}`);

            commit('SET_MODEL', data);

            return;
        } catch (e) {
            console.log(e);
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
