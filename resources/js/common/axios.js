import Bus from './event.bus';
import store from '@lit-js/store';

const methods = {
	/**
	 * Handle successfull axios response.
	 *
	 * @param {Object} response
	 */
	axiosResponseSuccess(response) {
		if (typeof response.data !== 'object') {
			return response;
		}

		if (!('message' in response.data)) {
			return response;
		}

		// Create toast if the response has a message.
		let message = response.data.message;
		let variant = 'success';
		if ('variant' in response.data) {
			variant = response.data.variant;
		}

		message = this.$te(`messages.${message}`)
			? this.$t(`messages.${message}`)
			: message;

		this.$bvToast.toast(message, { variant });

		return response;
	},

	/**
	 * Handle axios error.
	 *
	 * @param {Object} error
	 */
	axiosResponseError(error) {
		// Any status codes that falls outside the range of 2xx cause this function to trigger
		// Do something with response error

		console.log(store.getters.debug);
		if (!error.response) {
			return Promise.reject(error);
		}

		// Show livewire error in development.
		if (store.getters.debug) {
			livewire.connection.driver.showHtmlModal(error.response.data);
			return Promise.reject(error);
		}

		if (typeof error.response.data !== 'object') {
			return Promise.reject(error);
		}

		if (!('message' in error.response.data)) {
			return Promise.reject(error);
		}

		let message = error.response.data.message;

		if ([405, 404].includes(error.response.status) && !message) {
			message = this.$t('lit.errors.not_found');
		}

		message = this.$te(`messages.${message}`)
			? this.$t(`messages.${message}`)
			: message;

		this.$bvToast.toast(message, {
			variant: 'danger',
		});

		return Promise.reject(error);
	},
};

const setBaseUrl = () => {
	window.axios.defaults.baseURL = store.state.config.baseURL;
};

Bus.$on('mounted', setBaseUrl);

export default methods;
