import VueI18n from 'vue-i18n';
import store from '@lit-js/store';
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
     *      string: "Search {name}",
     *      values: {name: "Employees"}
     *      returns: "Search Employees"
     *
     * @param {String} string
     * @param {Object} values
     * @return {String}
     */
    _format(string, values) {
        let messages = { f: { s: string } };
        const formatter = new VueI18n({
            locale: 'f',
            messages,
            silentTranslationWarn: true,
        });
        return formatter._t.apply(
            formatter,
            ['s', formatter.locale, formatter._getMessages(), this].concat(
                values
            )
        );
    },

    /**
     * Check if the authenticated user has a permission.
     *
     * @param {String} permission
     * @return {Boolean}
     */
    can(permission) {
        return store.getters.permissions.includes(permission);
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
        if (!Lit.getLocale() in countries) {
            return;
        }

        locale = locale.toLowerCase();

        for (let i in countries[Lit.getLocale()]) {
            let t = countries[Lit.getLocale()][i];
            if (t.alpha2 == locale) {
                return t.name;
            }
        }
    },

    /**
     * Get bootstrap cols from width.
     *
     * @param {Number} width
     */
    bCols(width) {
        if (width >= 1) {
            return width;
        }

        return Math.round(12 * width);
    },
};
