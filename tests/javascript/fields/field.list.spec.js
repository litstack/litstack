require('fjord-test');

import { mount, shallowMount } from '@vue/test-utils';
import List from '@fj-js/components/Crud/Fields/List/FieldList';
import CrudModel from '@fj-js/crud/model';
import FjordStore from '@fj-js/store';
import Vue from 'vue';

const field = {
    local_key: 'list',
    slots: {}
};

const model = new CrudModel({
    attributes: {
        id: 1,
        list: 'abc'
    }
});

const FjBaseField = {
    template: '<div><slot/></div>'
};

const store = FjordStore.store;

describe('List field', () => {
    it('renders list field', () => {
        const wrapper = mount(List, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        expect(wrapper.find('.fj-list').exists()).toBe(true);
    });
});

describe('List field methods', () => {
    it('unflattens list', () => {
        const wrapper = mount(List, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        let list = [
            { id: 1, parent_id: 0 },
            { id: 2, parent_id: 1 },
            { id: 3, parent_id: 2 }
        ];

        expect(wrapper.vm.unflatten(list)).toStrictEqual([
            {
                id: 1,
                parent_id: 0,
                children: [
                    {
                        id: 2,
                        parent_id: 1,
                        children: [{ id: 3, parent_id: 2, children: [] }]
                    }
                ]
            }
        ]);
    });
    it('unflattens and orders list', () => {
        const wrapper = mount(List, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        let list = [
            {
                id: 1,
                order_column: 2,
                parent_id: 0
            },
            {
                id: 2,
                order_column: 1,
                parent_id: 0
            },
            {
                id: 3,
                order_column: 2,
                parent_id: 1
            },
            {
                id: 4,
                order_column: 1,
                parent_id: 1
            }
        ];

        expect(wrapper.vm.unflatten(list)).toStrictEqual([
            {
                id: 2,
                parent_id: 0,
                order_column: 1,
                children: []
            },
            {
                id: 1,
                parent_id: 0,
                order_column: 2,
                children: [
                    {
                        id: 4,
                        parent_id: 1,
                        order_column: 1,
                        children: []
                    },
                    {
                        id: 3,
                        parent_id: 1,
                        order_column: 2,
                        children: []
                    }
                ]
            }
        ]);
    });
    it('flattens tree', () => {
        const wrapper = mount(List, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        let list = [
            {
                id: 1,
                children: [
                    {
                        id: 2,
                        children: [
                            {
                                id: 3,
                                children: []
                            }
                        ]
                    }
                ]
            }
        ];

        expect(wrapper.vm.flatten(list)).toStrictEqual([
            { id: 1, parent_id: 0 },
            { id: 2, parent_id: 1 },
            { id: 3, parent_id: 2 }
        ]);
    });
});
