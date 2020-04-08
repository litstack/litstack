import Vue from 'vue';
import EloquentCollection from '@fj-js/eloquent/collection';
import FjordModel from '@fj-js/eloquent/fjord.model';
import Bus from '@fj-js/common/event.bus';
import { ToastPlugin } from 'bootstrap-vue';
import store from '@fj-js/store';

Vue.use(ToastPlugin);

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
    async saveModels({ commit, state }) {
        // save jobs
        let promises = [];
        for (let i = 0; i < state.saveJobs.length; i++) {
            let saveJob = state.saveJobs[i];
            let promise = axios[saveJob.method](saveJob.route, saveJob.data);
            promises.push(promise);
        }

        let collection = new EloquentCollection({ data: [] }, FjordModel);
        let items = [];
        for (let key in state.modelsToSave) {
            let model = state.modelsToSave[key];
            if (model.route == 'form_blocks') {
                let promise = axios.put(
                    `${store.state.form.config.route}/${model.model_id}/blocks/${model.id}`,
                    model.getPayload()
                );
            } else {
                items.push(model);
            }
        }
        collection.items = collect(items);

        // save eloquent models
        if (items.length > 0) {
            let promise = collection.save();

            promises.push(promise);
        }

        // parallel map flow
        try {
            let results = await Promise.all(promises);

            Bus.$emit('modelsSaved', results[0]);

            commit('SAVED');
        } catch (e) {
            Bus.$emit('error', e);
        }
    },
    saveJob({ commit }, job) {
        commit('ADD_SAVE_JOB', job);
    }
};

export const state = Object.assign({}, initialState);

export const mutations = {
    ADD_SAVE_JOB(state, job) {
        let saveJob = state.saveJobs.find(saveJob => {
            return (
                saveJob.route == job.route &&
                saveJob.method == job.method &&
                saveJob.data.id == job.data.id
            );
        });
        if (!saveJob) {
            state.saveJobs.push(job);
        } else {
            state.saveJobs[state.saveJobs.indexOf(saveJob)] = job;
        }
    },
    ADD_MODELS_TO_SAVE(state, { model, id }) {
        // TODO: hasModel Changes???
        if (!state.modelsToSave.includes(model)) {
            state.modelsToSave.push(model);
            state.saveModelIds[model.model] = [];
        }
        if (!state.saveModelIds[model.model].includes(id)) {
            state.saveModelIds[model.model].push(id);
        }
    },
    REMOVE_MODELS_FROM_SAVE(state, { model, id }) {
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
    SAVED(state) {
        state.modelsToSave = [];
        state.saveModelIds = {};
        state.saveJobs = [];
    },
    FLUSH_SAVINGS(state) {
        state.modelsToSave = [];
        state.saveJobs = [];
        state.saveModelIds = {};
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
