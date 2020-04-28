import VueI18n from 'vue-i18n';
import store from '@fj-js/store';
import CrudModel from '../crud/model';

/**
 * Vue mixed that can be used in components.
 */
export default {
    /**
     * Formats string with values.
     * Example:
     * string: 'Search {name}',
     * values: {name: "Employees"}
     *
     * @param {String} string
     * @param {Object} values
     */
    _format(string, values) {
        let messages = { f: { s: string } };
        const formatter = new VueI18n({ locale: 'f', messages });
        return formatter._t.apply(
            formatter,
            ['s', formatter.locale, formatter._getMessages(), this].concat(
                values
            )
        );
    },

    /**
     * Create new CrudModel instance.
     *
     * @param {Object} model
     */
    crud(model) {
        return new CrudModel(model);
    },

    /**
     *
     *
     * @return {Object}
     */
    user() {
        return store.getters.auth;
    }
};
