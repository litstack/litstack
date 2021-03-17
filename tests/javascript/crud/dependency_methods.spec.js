require('lit-test');

import methods from '@lit-js/components/Crud/Fields/dependecy_methods';

describe('fulfillsWhenIn', () => {
    let dependency = {
        attribute: 'foo',
        condition: 'whenIn',
        value: [],
    };
    let field = {
        ...methods,
        model: {
            foo: null,
        },
    };
    beforeEach(() => {
        dependency.value = [];
        field.model.foo = null;
    });
    it('returns true when string is in the expected array', () => {
        dependency.value = ['bar'];
        field.model.foo = 'bar';
        expect(field.fulfillsWhenIn(dependency)).toBe(true);
    });
    it('returns false when string is not in the expected array', () => {
        dependency.value = ['bar'];
        field.model.foo = 'bars';
        expect(field.fulfillsWhenIn(dependency)).toBe(false);
    });
    it('returns true when string is in the expected object', () => {
        dependency.value = { 0: 'bar' };
        field.model.foo = 'bar';
        expect(field.fulfillsWhenIn(dependency)).toBe(true);
    });
    it('returns false when string is not in the expected object', () => {
        dependency.value = { 0: 'bar' };
        field.model.foo = 'bars';
        expect(field.fulfillsWhenIn(dependency)).toBe(false);
    });
});

describe('fulfillsWhenNotIn', () => {
    let dependency = {
        attribute: 'foo',
        condition: 'whenNotIn',
        value: [],
    };
    let field = {
        ...methods,
        model: {
            foo: null,
        },
    };
    beforeEach(() => {
        dependency.value = [];
        field.model.foo = null;
    });
    it('returns false when string is in the expected array', () => {
        dependency.value = ['bar'];
        field.model.foo = 'bar';
        expect(field.fulfillsWhenNotIn(dependency)).toBe(false);
    });
    it('returns true when string is not in the expected array', () => {
        dependency.value = ['bar'];
        field.model.foo = 'ba';
        expect(field.fulfillsWhenNotIn(dependency)).toBe(true);
    });
    it('returns false when string is in the expected object', () => {
        dependency.value = { 0: 'bar' };
        field.model.foo = 'bar';
        expect(field.fulfillsWhenNotIn(dependency)).toBe(false);
    });
    it('returns true when string is not in the expected object', () => {
        dependency.value = { 0: 'bar' };
        field.model.foo = 'ba';
        expect(field.fulfillsWhenNotIn(dependency)).toBe(true);
    });
});
