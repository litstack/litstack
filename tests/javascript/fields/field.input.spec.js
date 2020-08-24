require('lit-test');

import { mount, shallowMount } from '@vue/test-utils';
import Input from '@fj-js/components/Crud/Fields/Input/FieldInput';
import CrudModel from '@fj-js/crud/model';
import LitStore from '@fj-js/store';
import Vue from 'vue';

const field = {
	local_key: 'text',
	slots: {},
};

const model = new CrudModel({
	attributes: {
		id: 1,
		text: 'abc',
	},
});

const FjBaseField = {
	template: '<div><slot/></div>',
};

const store = LitStore.store;

describe('Input field', () => {
	it('renders Input field', () => {
		const wrapper = mount(Input, {
			store,
			localVue: Vue,
			stubs: { FjBaseField },
			propsData: { field, model, value: '' },
		});

		expect(wrapper.find('.fj-field-input').exists()).toBe(true);
	});

	it('renders default input type text', () => {
		const wrapper = mount(Input, {
			store,
			localVue: Vue,
			stubs: { FjBaseField },
			propsData: { field, model, value: '' },
		});

		expect(wrapper.find('.fj-field-input').attributes('type')).toBe('text');
	});

	it('renders different input type', () => {
		// Testing using input type email
		let fieldWithTypeEmail = {
			...field,
			type: 'email',
		};
		const wrapper = mount(Input, {
			store,
			localVue: Vue,
			stubs: { FjBaseField },
			propsData: { field: fieldWithTypeEmail, model, value: '' },
		});

		let inputType = wrapper.find('.fj-field-input').attributes('type');
		expect(inputType).toBe('email');
	});

	it('prepends button with value', () => {
		let fieldWithPrepend = {
			...field,
			prepend: 'dummy prepend value',
		};
		const wrapper = mount(Input, {
			store,
			localVue: Vue,
			stubs: { FjBaseField },
			propsData: { field: fieldWithPrepend, model, value: '' },
		});

		let prependText = wrapper
			.find('.input-group-prepend .input-group-text')
			.text();
		expect(prependText).toBe('dummy prepend value');
	});

	it('appends button with value', () => {
		let fieldWithPrepend = {
			...field,
			append: 'dummy append value',
		};
		const wrapper = mount(Input, {
			store,
			localVue: Vue,
			stubs: { FjBaseField },
			propsData: { field: fieldWithPrepend, model, value: '' },
		});

		let prependText = wrapper
			.find('.input-group-append .input-group-text')
			.text();
		expect(prependText).toBe('dummy append value');
	});
});
