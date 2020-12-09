require('lit-test');

import { mount, shallowMount } from '@vue/test-utils';
import Wysiwyg from '@lit-js/components/Crud/Fields/Wysiwyg/FieldWysiwyg';
import CrudModel from '@lit-js/crud/model';
import LitStore from '@lit-js/store';
import Vue from 'vue';

const field = {
    local_key: 'text',
    slots: {},
    route_prefix: '/test/route',
    local_key: 'foo',
};

const model = new CrudModel({
    attributes: {
        id: 1,
        text: 'abc',
    },
});

const LitBaseField = {
    template: '<div><slot/></div>',
};

const store = LitStore.store;

describe('WYSIWYG-field', () => {
    it('renders the editor content wrapper', () => {
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { LitBaseField },
            propsData: { field, model, value: '' },
        });

        expect(wrapper.find('.lit-field-wysiwyg__content').exists()).toBe(true);
    });

    it('renders custom css wrapper', () => {
        let css = {
            css: '.class {color: red;} #id {color: red;}',
        };
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { LitBaseField },
            propsData: { field: { ...field, ...css }, model, value: '' },
        });

        expect(wrapper.find('.lit-field-wysiwyg__css').exists()).toBe(true);
    });
});

describe('WYSIWYG-field methods', () => {
    it('prepends custom css selectors', () => {
        const wrapper = shallowMount(Wysiwyg, {
            store,
            localVue: Vue,
            stubs: { LitBaseField },
            propsData: { field, model, value: '' },
        });

        let css = '.class {color: red;} #id {color: red;}';

        expect(wrapper.vm.prependCssSelectors(css)).toStrictEqual(
            ' #foo--test-route .class {color: red;}  #foo--test-route #id {color: red;}'
        );
    });
});
