import VueI18n from 'vue-i18n';
import store from '@fj-js/store';

export default {
    // Vue mixins that can be used in templates

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

    // check if the authenticated user has a permission
    can(permission) {
        return store.getters.permissions.permissions.includes(permission)
    }
};
