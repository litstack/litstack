require('fjord-test');

import CrudModel from '@fj-js/crud/model';
import store from '@fj-js/store';

describe('CrudModel getter', () => {
    it('returns normal attribute value', () => {
        let dummyModel = { attributes: { text: 'dummy text' } };
        let model = new CrudModel(dummyModel);

        expect(model.text).toBe('dummy text');
    });

    it('returns related value', () => {
        let dummyModel = { attributes: { post: { text: 'post text' } } };
        let model = new CrudModel(dummyModel);

        expect(model.post.text).toBe('post text');
        expect(model['post.text']).toBe('post text');
    });

    it('returns translated value', () => {
        let dummyModel = {
            attributes: {
                en: { text: 'english text' },
                de: { text: 'german text' }
            }
        };
        let model = new CrudModel(dummyModel);

        store.commit('SET_LANGUAGE', 'en');
        expect(model.text).toBe('english text');
        store.commit('SET_LANGUAGE', 'de');
        expect(model.text).toBe('german text');
    });

    it('returns related translated value', () => {
        let dummyModel = {
            attributes: {
                post: {
                    en: { text: 'english post text' },
                    de: { text: 'german post text' }
                }
            }
        };
        let model = new CrudModel(dummyModel);

        store.commit('SET_LANGUAGE', 'en');
        expect(model['post.text']).toBe('english post text');

        store.commit('SET_LANGUAGE', 'de');
        expect(model['post.text']).toBe('german post text');
    });
});

describe('CrudModel setter', () => {
    beforeEach(() => {
        store.commit('SET_LANGUAGE', 'de');
    });

    it('sets normal attribute value', () => {
        let dummyModel = { attributes: { text: 'dummy text' } };
        let model = new CrudModel(dummyModel);

        model.text = 'modified text';

        expect(model.text).toBe('modified text');
    });
});
