import Vue from 'vue';
import Bus from '@lit-js/common/event.bus';
import { ToastPlugin } from 'bootstrap-vue';
import store from '@lit-js/store';
import ResultsHandler from './savings.results';

Vue.use(ToastPlugin);

const initialState = {
    /**
     * Save jobs.
     */
    jobs: [],
};

const getters = {
    /**
     * Save job getter.
     */
    saveJobs(state) {
        return state.jobs;
    },

    /**
     * canSave getter. Determines if save jobs exists.
     */
    canSave(state) {
        return state.jobs.length > 0;
    },
};

export const methods = {
    /**
     * Merge params for job.
     *
     * @param {Object} job
     * @return {*}
     */
    mergeParamsFromJob(job) {
        let params = {};
        for (let key in job.params) {
            params = _.merge(params, job.params[key]);
        }
        return params;
    },
};

export const actions = {
    async save({ commit, state }) {
        // Save jobs.
        let promises = [];
        for (let i = 0; i < state.jobs.length; i++) {
            let job = state.jobs[i];

            let promise = axios({
                method: job.method,
                url: job.route,
                data: methods.mergeParamsFromJob(job),
            });
            promises.push(promise);
        }

        // Parallel map flow.
        let results = new ResultsHandler(
            await Promise.all(promises.map(p => p.catch(e => e)))
        );

        if (results._hasSucceeded()) {
            commit('FLUSH_SAVE_JOBS');
        }

        for (let i in results.results) {
            let result = results.results[i];
            let job = state.jobs[i];

            // Failed jobs remain in the store.
            if (result instanceof Error) {
                continue;
            }

            state.jobs.splice(i, 1);
        }

        Lit.bus.$emit('saved', results);
        Lit.bus.$emit('reload');

        return results;
    },
    cancelSave({ commit }) {
        commit('FLUSH_SAVE_JOBS');

        Lit.bus.$emit('saveCanceled');
    },
    saveJob({ commit }, job) {
        commit('ADD_SAVE_JOB', job);
    },
};

export const state = Object.assign({}, initialState);

export const mutations = {
    ADD_SAVE_JOB(state, job) {
        let saveJob = null;
        let index = state.jobs.findIndex(j => {
            if (job.id && j.id) {
                return job.id == j.id;
            }

            return j.method == job.method && j.route == job.route;
        });

        if (index > -1) {
            saveJob = state.jobs[index];
        } else {
            saveJob = {
                route: job.route,
                method: job.method,
                params: {},
                id: job.id,
            };
        }

        // Merge params.
        saveJob.params[job.key] = job.params;

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

        delete saveJob.params[job.key];

        if (_.isEmpty(saveJob.params)) {
            state.jobs.splice(index, 1);
        } else {
            state.jobs[index] = saveJob;
        }
    },
    FLUSH_SAVE_JOBS(state) {
        state.jobs = [];
    },
};

export default {
    state,
    actions,
    mutations,
    getters,
    methods,
};
