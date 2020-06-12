require('fjord-test');

import { mount, shallowMount } from '@vue/test-utils';
import Wysiwyg from '@fj-js/components/Crud/Fields/Wysiwyg/FieldWysiwyg';
import CrudModel from '@fj-js/crud/model';
import FjordStore from '@fj-js/store';
import Vue from 'vue';

const field = {
    local_key: 'text',
    slots: {},
    route_prefix: '/test/route',
    local_key: 'foo'
};

const model = new CrudModel({
    attributes: {
        id: 1,
        text: 'abc'
    }
});

const FjBaseField = {
    template: '<div><slot/></div>'
};

const store = FjordStore.store;

describe('WYSIWYG-field', () => {
    it('renders the editor content wrapper', () => {
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        expect(wrapper.find('.fj-field-wysiwyg__content').exists()).toBe(true);
    });

    it('renders custom css wrapper', () => {
        let css = {
            css: '.class {color: red;} #id {color: red;}'
        };
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field: { ...field, ...css }, model, value: '' }
        });

        expect(wrapper.find('.fj-field-wysiwyg__css').exists()).toBe(true);
    });
});

describe('WYSIWYG-field methods', () => {
    it('prepends custom css selectors', () => {
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { FjBaseField },
            propsData: { field, model, value: '' }
        });

        let css = '.class {color: red;} #id {color: red;}';

        expect(wrapper.vm.prependCssSelectors(css)).toStrictEqual(
            ' #foo--test-route .class {color: red;}  #foo--test-route #id {color: red;}'
        );
    });
});
