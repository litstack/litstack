import Vue from 'vue';
import Bus from './event.bus';
import store from '@lit-js/store';

const Lit = {
	bus: Bus,
	config: {},
	baseURL: null,
	bootingCallbacks: window.Lit?.bootingCallbacks || [],

	/**
	 * Get node env.
	 *
	 * @return {String}
	 */
	env() {
		return store.getters.env;
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

	/**
	 * Add booting callback.
	 *
	 * @param {Function} cb
	 */
	booting(cb) {
		this.bootingCallbacks.push(cb);
	},
};

for (let i = 0; i < Lit.bootingCallbacks.length; i++) {
	Lit.bootingCallbacks[i](Vue);
}

window.Lit = Lit;

const setConfig = () => {
	window.Lit.config = store.state.config.lit_config;
	window.Lit.baseURL = store.state.config.baseURL;
};

Bus.$on('mounted', setConfig);

export default Lit;
