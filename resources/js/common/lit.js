import Bus from './event.bus';
import store from '@lit-js/store';

const Lit = {
	bus: Bus,
	config: {},
	baseURL: null,

	/**
	 * Get node env.
	 *
	 * @return {String}
	 */
	env() {
		return process.env.NODE_ENV;
	},

	/**
	 * Get authenticated lit-user model.
	 *
	 * @return {Object}
	 */
	user() {
		return store.getters.auth;
	},

	/**
	 * Get Lit application locale.
	 *
	 * @param {Object} obj
	 * @return {String}
	 */
	getLocale() {
		return i18n.locale;
	},

	/**
	 * Check if Lit application locale is locale.
	 *
	 * @param {Object} obj
	 * @return {Boolean}
	 */
	isLocale(locale) {
		return i18n.locale == locale;
	},

	/**
	 * Clone object.
	 *
	 * @param {Object} obj
	 */
	clone(obj) {
		return JSON.parse(JSON.stringify(obj));
	},
};

window.Lit = Lit;

const setConfig = (config) => {
	window.Lit.config = config;
	window.Lit.baseURL = store.state.config.baseURL;
};

Bus.$on('configSet', setConfig);

export default Lit;
