require('lit-test');

import store from '@lit-js/store';
import { methods } from '@lit-js/store/modules/savings.module';
import { extendWith } from 'lodash';

beforeEach(() => {});

afterEach(() => {
	store.commit('FLUSH_SAVE_JOBS');
});

describe('commit ADD_SAVE_JOB', () => {
	it('adds savejob once', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { a: 'value' },
		};

		store.commit('ADD_SAVE_JOB', job);
		expect(store.getters.saveJobs.length).toBe(1);

		store.commit('ADD_SAVE_JOB', job);
		expect(store.getters.saveJobs.length).toBe(1);
	});

	it('stores params by key name', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { attribute: 'value' },
		};

		store.commit('ADD_SAVE_JOB', job);
		expect(store.getters.saveJobs.length).toBe(1);

		let savedJob = store.getters.saveJobs[0];
		expect(savedJob.params).toHaveProperty('dummy_key');
		expect(savedJob.params.dummy_key).toStrictEqual({ attribute: 'value' });
	});

	it('adds appends params to same job', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { a: 'value' },
		};
		let otherJob = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'other_key',
			params: { b: 'value' },
		};

		store.commit('ADD_SAVE_JOB', job);
		store.commit('ADD_SAVE_JOB', otherJob);
		expect(store.getters.saveJobs.length).toBe(1);
		expect(Object.keys(store.getters.saveJobs[0].params).length).toBe(2);
		expect(store.getters.saveJobs[0].params).toHaveProperty('dummy_key');
		expect(store.getters.saveJobs[0].params).toHaveProperty('other_key');
	});

	it('adds savejob twice when id is different', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			id: 'foo',
			params: { a: 'value' },
		};

		store.commit('ADD_SAVE_JOB', job);
		expect(store.getters.saveJobs.length).toBe(1);

		// Changing id and expecting to have another save job even if the method
		// and route is the same.
		job.id = 'bar';

		store.commit('ADD_SAVE_JOB', job);
		expect(store.getters.saveJobs.length).toBe(2);
	});
});

describe('method mergeParamsFromJob', () => {
	it('works when single job key is added', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { attribute: 'value' },
		};
		store.commit('ADD_SAVE_JOB', job);

		let result = methods.mergeParamsFromJob(store.getters.saveJobs[0]);
		expect(result).toStrictEqual({ attribute: 'value' });
	});

	it('works when multiple job keys with different attribute names are added', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { attribute: 'value' },
		};
		let otherJob = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'other_key',
			params: { other_attribute: 'other value' },
		};
		store.commit('ADD_SAVE_JOB', job);
		store.commit('ADD_SAVE_JOB', otherJob);

		let result = methods.mergeParamsFromJob(store.getters.saveJobs[0]);
		expect(result).toStrictEqual({
			attribute: 'value',
			other_attribute: 'other value',
		});
	});

	it('merges attributes', () => {
		let job = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'dummy_key',
			params: { attribute: { one: 'one value' } },
		};
		let otherJob = {
			route_prefix: 'dummy_prefix',
			method: 'PUT',
			key: 'other_key',
			params: { attribute: { two: 'two value' } },
		};
		store.commit('ADD_SAVE_JOB', job);
		store.commit('ADD_SAVE_JOB', otherJob);

		let result = methods.mergeParamsFromJob(store.getters.saveJobs[0]);
		expect(result).toStrictEqual({
			attribute: { one: 'one value', two: 'two value' },
		});
	});
});
