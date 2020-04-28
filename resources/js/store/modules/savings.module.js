import Vue from 'vue';
import Bus from '@fj-js/common/event.bus';
import { ToastPlugin } from 'bootstrap-vue';
import store from '@fj-js/store';

Vue.use(ToastPlugin);

const initialState = {
    jobs: []
};

const getters = {
    canSave(state) {
        return state.jobs.length > 0;
    }
};

export const actions = {
    async save({ commit, state }) {
        // Run save jobs.
        let promises = [];
        for (let i = 0; i < state.jobs.length; i++) {
            let job = state.jobs[i];
            let promise = axios({
                method: job.method,
                url: job.route,
                data: job.params
            });
            promises.push(promise);
        }

        // Parallel map flow.
        let results = await Promise.all(promises);

        Fjord.event.$emit('saved', results[0]);

        commit('FLUSH_SAVE_JOBS');

        return results;
    },
    saveJob({ commit }, job) {
        commit('ADD_SAVE_JOB', job);
    }
};

export const state = Object.assign({}, initialState);

export const mutations = {
    ADD_SAVE_JOB(state, job) {
        let saveJob = null;
        let index = state.jobs.findIndex(j => {
            return j.method == job.method && j.route == job.route;
        });

        if (index > -1) {
            saveJob = state.jobs[index];
        } else {
            saveJob = job;
        }

        // Merge params.
        saveJob.params = _.merge(saveJob.params, job.params);

        if (index > -1) {
            state.jobs[index] = saveJob;
        } else {
            state.jobs.push(saveJob);
        }
    },
    REMOVE_SAVE_JOB(state, job) {
        let index = state.jobs.findIndex(j => {
            return j.method == job.method && j.route == job.route;
        });

        if (index == -1) {
            return;
        }
        let saveJob = state.jobs[index];
        saveJob.params = _.omit(saveJob.params, job.params);

        state.jobs[index] = saveJob;
    },
    FLUSH_SAVE_JOBS(state) {
        state.jobs = [];
    }
};

export default {
    state,
    actions,
    mutations,
    getters
};
