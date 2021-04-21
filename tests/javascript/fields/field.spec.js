require('lit-test');

import { mount, shallowMount } from '@vue/test-utils';
import CrudModel from '@lit-js/crud/model';
import Field from '@lit-js/components/Crud/Fields/Field';
import LitStore from '@lit-js/store';
import Vue from 'vue';

const stubs = { TestField: { template: '<div class="test-field"/>' } };

let store = LitStore.store;

store.commit('SET_LANGUAGE', 'en');
store.commit('SET_LANGUAGES', ['en', 'de']);

let field;
let wrapper;
let model;
let propsData;
beforeEach(() => {
    model = new CrudModel({
        attributes: { id: 1, dummy_attribute: 'default value' },
    });

    field = {
        component: 'test-field',
        route_prefix: '',
        local_key: 'dummy_attribute',
        translatable: false,
    };

    propsData = { field, model };

    wrapper = shallowMount(Field, {
        store,
        stubs,
        localVue: Vue,
        propsData,
    });
});

afterEach(() => {
    field = {
        component: 'test-field',
        route_prefix: '',
        local_key: 'dummy_attribute',
        translatable: false,
    };

    wrapper.destroy();

    store.commit('FLUSH_SAVE_JOBS');
});

describe('field rendering', () => {
    /*
    const wrapper = mount(Field, {
        store,
        stubs,
        propsData,
        localVue: Vue
    });
    */

    it('renders field', () => {
        expect(wrapper.find('div').exists()).toBe(true);
        expect(wrapper.find('.test-field').exists()).toBe(true);
    });
});

describe('field value setting', () => {
    it('sets correct value for non translatable fields', () => {
        model.attributes.dummy_attribute = 'some dummy value';

        wrapper.vm.setCurrentValue();
        expect(wrapper.vm.value).toBe('some dummy value');
    });

    it('sets correct current value for translatable fields', () => {
        model.attributes.de = { dummy_attribute: 'german dummy value' };
        model.attributes.en = { dummy_attribute: 'english dummy value' };
        field.translatable = true;

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.setCurrentValue();
        expect(wrapper.vm.value).toBe('english dummy value');

        store.commit('SET_LANGUAGE', 'de');
        wrapper.vm.setCurrentValue();
        expect(wrapper.vm.value).toBe('german dummy value');
    });

    it('sets current value before mount', () => {
        expect(wrapper.vm.value).toBe('default value');
    });

    it('sets new value when language changed for translatable fields', () => {
        model.attributes.de = { dummy_attribute: 'german dummy value' };
        model.attributes.en = { dummy_attribute: 'english dummy value' };
        field.translatable = true;

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.setCurrentValue();
        expect(wrapper.vm.value).toBe('english dummy value');

        store.commit('SET_LANGUAGE', 'de');
        expect(wrapper.vm.value).toBe('german dummy value');
    });
});

describe('original value handling', () => {
    it('stores original value for non translatable fields', () => {
        model.attributes.dummy_attribute = 'dummy value';
        wrapper.vm.storeOriginalValues();

        expect(wrapper.vm.original).toBe('dummy value');
    });

    it('stores original value for translatable fields', () => {
        model.attributes.de = { dummy_attribute: 'german dummy value' };
        model.attributes.en = { dummy_attribute: 'english dummy value' };
        field.translatable = true;

        wrapper.vm.storeOriginalValues();

        expect(wrapper.vm.original).toStrictEqual({
            de: 'german dummy value',
            en: 'english dummy value',
        });
    });

    it('resets model value to original', () => {
        wrapper.vm.original = 'original value';
        model.attributes.dummy_attribute = 'some other value';

        wrapper.vm.resetModelValuesToOriginal();

        expect(model.attributes.dummy_attribute).toBe('original value');
    });

    it('resets translatable model values to original values', () => {
        wrapper.vm.original = {
            de: 'original german dummy value',
            en: 'original english dummy value',
        };
        model.attributes.en = { dummy_attribute: 'some other english value' };
        model.attributes.de = { dummy_attribute: 'some other german value' };
        field.translatable = true;

        wrapper.vm.resetModelValuesToOriginal();

        expect(model.attributes.en.dummy_attribute).toBe(
            'original english dummy value'
        );
        expect(model.attributes.de.dummy_attribute).toBe(
            'original german dummy value'
        );
    });

    it('resets field value after resetting model value to original value', () => {
        wrapper.vm.original = 'original value';
        model.attributes.dummy_attribute = 'some other value';

        wrapper.vm.resetModelValuesToOriginal();

        expect(wrapper.vm.value).toBe('original value');
    });

    it('resets original value after saving', () => {
        wrapper.vm.original = 'base original value';
        model.attributes.dummy_attribute = 'new value';

        Lit.bus.$emit('saved', {
            hasFailed() {
                return false;
            },
        });

        expect(wrapper.vm.original).toBe('new value');
    });
});

describe('fillValueToModel', () => {
    it('fills value to model', () => {
        field.local_key = 'dummy_attribute_name';

        wrapper.vm.fillValueToModel('some value');
        expect(model.attributes.dummy_attribute_name).toBe('some value');
    });

    it('fills translatable value to model', () => {
        field.local_key = 'dummy_attribute_name';
        field.translatable = true;
        model.attributes.de = {};
        model.attributes.en = {};

        wrapper.vm.fillValueToModel('some german value', 'de');
        expect(model.attributes.de.dummy_attribute_name).toBe(
            'some german value'
        );

        wrapper.vm.fillValueToModel('some english value', 'en');
        expect(model.attributes.en.dummy_attribute_name).toBe(
            'some english value'
        );
    });

    it('fills translatable value to current locale by default', () => {
        field.local_key = 'dummy_attribute_name';
        field.translatable = true;
        model.attributes.de = {};
        model.attributes.en = {};

        store.commit('SET_LANGUAGE', 'de');
        wrapper.vm.fillValueToModel('some value');
        wrapper.vm.fillValueToModel('some german value');
        expect(model.attributes.de.dummy_attribute_name).toBe(
            'some german value'
        );

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.fillValueToModel('some value');
        wrapper.vm.fillValueToModel('some english value');
        expect(model.attributes.en.dummy_attribute_name).toBe(
            'some english value'
        );
    });
});

describe('save job', () => {
    it('gets correct save job key', () => {
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';

        field.translatable = false;
        expect(wrapper.vm.getSaveJobKey()).toBe('dummy_field_id');

        field.translatable = true;

        store.commit('SET_LANGUAGE', 'en');
        expect(wrapper.vm.getSaveJobKey()).toBe('en.dummy_field_id');

        store.commit('SET_LANGUAGE', 'de');
        expect(wrapper.vm.getSaveJobKey()).toBe('de.dummy_field_id');
    });

    it('gets correct save job payload', () => {
        let params;
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';

        field.translatable = false;
        params = wrapper.vm.getSaveJobPayload('dummy value');
        expect(params).toStrictEqual({ dummy_field_id: 'dummy value' });

        field.translatable = true;

        store.commit('SET_LANGUAGE', 'en');
        params = wrapper.vm.getSaveJobPayload('dummy value');
        expect(params).toStrictEqual({
            en: { dummy_field_id: 'dummy value' },
        });

        store.commit('SET_LANGUAGE', 'de');
        params = wrapper.vm.getSaveJobPayload('dummy value');
        expect(params).toStrictEqual({
            de: { dummy_field_id: 'dummy value' },
        });
    });

    it('recognizes changes correctly', () => {
        field.translatable = false;

        wrapper.vm.original = 'original value';
        wrapper.vm.value = 'other value';
        expect(wrapper.vm.hasValueChanged()).toBe(true);

        wrapper.vm.original = 'original value';
        wrapper.vm.value = 'original value';
        expect(wrapper.vm.hasValueChanged()).toBe(false);
    });

    it('recognizes changes for translatable fields correctly', () => {
        field.translatable = true;

        wrapper.vm.original = {
            en: 'english original value',
            de: 'german original value',
        };

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.value = 'other english value';
        expect(wrapper.vm.hasValueChanged()).toBe(true);
        wrapper.vm.value = 'german original value';
        expect(wrapper.vm.hasValueChanged()).toBe(true);
        wrapper.vm.value = 'english original value';
        expect(wrapper.vm.hasValueChanged()).toBe(false);

        store.commit('SET_LANGUAGE', 'de');
        wrapper.vm.value = 'other german value';
        expect(wrapper.vm.hasValueChanged()).toBe(true);
        wrapper.vm.value = 'english original value';
        expect(wrapper.vm.hasValueChanged()).toBe(true);
        wrapper.vm.value = 'german original value';
        expect(wrapper.vm.hasValueChanged()).toBe(false);
    });

    it('adds save job to store', () => {
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';
        field.translatable = false;
        wrapper.vm.original = 'other value';
        wrapper.vm.value = 'new value';

        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(1);
    });

    it('removes save job from store when value is original again', () => {
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';
        field.translatable = false;
        wrapper.vm.original = 'original value';

        wrapper.vm.value = 'new value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(1);

        wrapper.vm.value = 'original value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(0);
    });

    it('updates save job when value has changes', () => {
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';
        field.translatable = false;
        wrapper.vm.original = 'original value';
        let jobKey = wrapper.vm.getSaveJobKey();

        wrapper.vm.value = 'new value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(1);
        expect(
            store.getters.saveJobs[0].params[jobKey].payload
                .dummy_field_id
        ).toBe('new value');

        wrapper.vm.value = 'other new value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(1);
        expect(
            store.getters.saveJobs[0].params[jobKey].payload
                .dummy_field_id
        ).toBe('other new value');
    });

    it('adds save job to store for translatable field', () => {
        field.translatable = true;
        field.id = 'dummy_field_id';
        field.local_key = 'dummy_attribute_name';

        wrapper.vm.original = {
            de: 'original german value',
            en: 'original english value',
        };

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.value = 'new english value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(1);

        store.commit('SET_LANGUAGE', 'de');
        wrapper.vm.value = 'new german value';
        wrapper.vm.addSaveJob();
        expect(Object.keys(store.getters.saveJobs[0].params).length).toBe(2);

        wrapper.vm.value = 'original german value';
        wrapper.vm.addSaveJob();
        expect(Object.keys(store.getters.saveJobs[0].params).length).toBe(1);

        store.commit('SET_LANGUAGE', 'en');
        wrapper.vm.value = 'original english value';
        wrapper.vm.addSaveJob();
        expect(store.getters.saveJobs.length).toBe(0);
    });
});

describe('field route_prefix', () => {
    it('replaces "{id}" with model id', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}',
        };

        let model = new CrudModel({
            attributes: {
                id: 'test-id',
            },
        });

        const wrapper = shallowMount(Field, {
            store,
            stubs,
            localVue: Vue,
            propsData: {
                field: fieldWithRoutePrefix,
                model,
            },
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });

    it('replaces "{id}" with modelId prop', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}',
        };

        let model = new CrudModel({
            attributes: {
                id: 'model-id',
            },
        });

        const wrapper = shallowMount(Field, {
            store,
            stubs,
            localVue: Vue,
            propsData: {
                field: fieldWithRoutePrefix,
                modelId: 'test-id',
                model,
            },
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });

    it('replaces "{id}" with modelId prop even when model.id exists', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}',
        };

        let model = new CrudModel({
            attributes: {
                id: 'model-id',
            },
        });

        const wrapper = shallowMount(Field, {
            store,
            stubs,
            localVue: Vue,
            propsData: {
                field: fieldWithRoutePrefix,
                modelId: 'test-id',
                model,
            },
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });
});
