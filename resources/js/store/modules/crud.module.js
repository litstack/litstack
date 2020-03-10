import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';
import FjordModel from '@fj-js/eloquent/fjord.model';
//import TranslatableModel from '@fj-js/eloquent/translatable.model';
import TableModel from '@fj-js/eloquent/table.model';

const prepareModel = model => {
    switch (model.type) {
        case 'fjord':
            return new FjordModel(model);
        case 'translatable':
        // TODO: this throws:
        // Super expression must either be null or a function
        //return new TranslatableModel(model);
        default:
            return new FjordModel(model);
    }
};

const state = {
    model: {},
    items: [],
    total: null,
    page: null,
    number_of_pages: 1
};

const getters = {
    crud(state) {
        return state;
    }
};

const mutations = {
    SET_MODEL(state, data) {
        state.model = data;
    },
    SET_ITEMS(state, data) {
        let items = [];
        for (let i = 0; i < data.items.length; i++) {
            items.push(new TableModel(data.items[i]));
        }
        state.items = items;
    },
    SET_NUMBER_OF_PAGES(state, { n, per_page, page }) {
        if (n && per_page) {
            state.number_of_pages = Math.ceil(n / per_page);
            state.total = n;
            state.page = page;
        }
    }
};

const actions = {
    async loadItems({ commit, state }, { table, payload }) {
        try {
            const { data } = await axios.post(`${table}/index`, payload);
            commit('SET_ITEMS', data);
            commit('SET_NUMBER_OF_PAGES', {
                n: data.count,
                per_page: payload.perPage,
                page: payload.page
            });

            return;
        } catch (e) {
            this.$bus.$emit('error', e);
        }
    },
    async loadModel({ commit }, { route, id }) {
        try {
            const { data } = await axios.get(`${route}/${id}`);

            commit('SET_MODEL', prepareModel(data));

            return;
        } catch (e) {
            this.$bus.$emit('error', e);
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
