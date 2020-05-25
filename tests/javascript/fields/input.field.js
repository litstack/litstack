require('fjord-test');

import { mount } from '@vue/test-utils';
import { render } from '@vue/server-test-utils';
import CrudModel from '@fj-js/crud/model';
import Field from '@fj-js/components/Crud/Fields/Field';

const field = {
    local_key: 'text',
    component: 'fj-field-input',
    route_prefix: '/{id}'
};

const model = new CrudModel({
    attributes: {
        id: 1,
        text: 'abc'
    }
});

describe('Input field', () => {
    it('shows value after rendering', async () => {
        const wrapper = await render(Field, { propsData: { field, model } });
        expect(wrapper.text()).toContain('<div></div>');
    });
});
