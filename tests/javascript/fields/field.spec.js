require('fjord-test');

import { mount, shallowMount } from '@vue/test-utils';
import CrudModel from '@fj-js/crud/model';
import Field from '@fj-js/components/Crud/Fields/Field';

const field = {
    component: 'test-field',
    route_prefix: ''
};

const model = new CrudModel({
    attributes: {
        id: 1,
        text: 'abc'
    }
});

const stubs = { TestField: { template: '<div class="test-field"/>' } };
const propsData = { field, model };

describe('Field', () => {
    const wrapper = mount(Field, { stubs, propsData });

    it('renders field', () => {
        expect(wrapper.find('div').exists()).toBe(true);
        expect(wrapper.find('.test-field').exists()).toBe(true);
    });
});

describe('Field route_prefix', () => {
    it('replaces "{id}" with model id', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}'
        };

        const wrapper = shallowMount(Field, {
            stubs,
            propsData: {
                field: fieldWithRoutePrefix,
                model: { id: 'test-id' }
            }
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });

    it('replaces "{id}" with modelId prop', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}'
        };

        const wrapper = shallowMount(Field, {
            stubs,
            propsData: {
                field: fieldWithRoutePrefix,
                model: {},
                modelId: 'test-id'
            }
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });

    it('replaces "{id}" with modelId prop even when model.id exists', () => {
        let fieldWithRoutePrefix = {
            ...field,
            route_prefix: '/{id}'
        };

        const wrapper = shallowMount(Field, {
            stubs,
            propsData: {
                field: fieldWithRoutePrefix,
                model: { id: 'model-id' },
                modelId: 'test-id'
            }
        });

        expect(wrapper.vm.field.route_prefix).toBe('/test-id');
    });
});
