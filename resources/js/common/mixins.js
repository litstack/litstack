import VueI18n from 'vue-i18n';
import store from '@fj-js/store';
import CrudModel from '../crud/model';
import { translate } from 'i18n-js';
import languages from '../lang/languages';
import countries from '../lang/countries';

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
        const formatter = new VueI18n({
            locale: 'f',
            messages,
            silentTranslationWarn: true
        });
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
        if (!Array.isArray(model)) {
            return new CrudModel(model);
        }

        let models = [];
        for (let i = 0; i < model.length; i++) {
            models.push(new CrudModel(model[i]));
        }
        return models;
    },

    /**
     * Translate by key.
     *
     * @param  {...any} params
     * @return {string}
     */
    trans(...params) {
        return i18n.t(...params);
    },

    /**
     * Translate choice by key.
     *
     * @param  {...any} params
     * @return {string}
     */
    trans_choice(...params) {
        return i18n.tc(...params);
    },

    /**
     * Translate by key.
     *
     * @param  {...any} params
     * @return {string}
     */
    __(...params) {
        return i18n.t(...params);
    },

    /**
     * Get language name translation for locale.
     *
     * @param {string} locale
     */
    __language(locale) {
        return languages[locale.toLowerCase()].nativeName;
    },

    /**
     * Get country name translation for locale.
     *
     * @param {string} locale
     */
    __country(locale) {
        if (!Fjord.getLocale() in countries) {
            return;
        }

        locale = locale.toLowerCase();

        for (let i in countries[Fjord.getLocale()]) {
            let t = countries[Fjord.getLocale()][i];
            if (t.alpha2 == locale) {
                return t.name;
            }
        }
    },

    /**
     * Get responsive cols.
     *
     * @param {Integer} col
     */
    bCols(col) {
        // if (col > 5) {
        //     return `lg-${col} col-sm-12`;
        // }
        // if (col > 2) {
        //     return `lg-${col} col-md-${col} col-sm-12`;
        // }
        return `xl-${col} col-12`;
    }
};
