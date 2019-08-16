import Vue from 'vue';
import EloquentCollection from './../eloquent/collection';
import Bus from './../common/event.bus';

const initialState = {
    modelsToSave: [],
    saveJobs: [],
    saveModelIds: {}
};

const getters = {
    canSave(state) {
        return state.modelsToSave.length > 0 || state.saveJobs.length > 0;
    }
};

export const actions = {
    async ['saveModels']({ commit, state }) {
        // save jobs
        let promises = [];
        for (let i = 0; i < state.saveJobs.length; i++) {
            let saveJob = state.saveJobs[i];
            let promise = axios[saveJob.method](saveJob.route, saveJob.data);
            promises.push(promise);
        }

        // save eloquent models
        if (state.modelsToSave.length > 0) {
            let collection = new EloquentCollection({ data: [] });
            collection.items = collect(state.modelsToSave);
            let promise = collection.save();
            promises.push(promise);
        }

        // parallel map flow
        let results = await promises.map(async job => await job);

        Vue.notify({
            group: 'general',
            type: 'aw-success',
            title: 'Saved successfully.',
            text: '',
            duration: 1500
        });

        Bus.$emit('modelsSaved');

        commit('saved');
    }
};

export const state = Object.assign({}, initialState);

export const mutations = {
    ['addSaveJob'](state, job) {
        state.saveJobs.push(job);
    },
    ['removeSaveJob'](state, job) {
        if (!state.saveJobs.includes(job)) {
            return;
        }
        state.saveJobs.splice(state.saveJobs.indexOf(job), 1);
    },
    ['addModelToSave'](state, { model, id }) {
        if (!state.modelsToSave.includes(model)) {
            state.modelsToSave.push(model);
            state.saveModelIds[model.model] = [];
        }
        if (!state.saveModelIds[model.model].includes(id)) {
            state.saveModelIds[model.model].push(id);
        }
    },
    ['removeModelFromSave'](state, { model, id }) {
        if (!state.modelsToSave.includes(model)) {
            return;
        }
        if (!state.saveModelIds[model.model].includes(id)) {
            return;
        }
        state.saveModelIds[model.model].splice(
            state.saveModelIds[model.model].indexOf(id),
            1
        );
        if (state.saveModelIds[model.model].length > 0) {
            return;
        }
        state.modelsToSave.splice(state.modelsToSave.indexOf(model), 1);
    },
    ['saved'](state) {
        state.modelsToSave = [];
        state.saveModelIds = {};
        state.saveJobs = [];
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
