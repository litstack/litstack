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

    /**
     * Debounce.
     *
     * @param {Function} func
     * @param {Number} wait
     * @param {Boolean} immediate
     */
    debounce(func, wait, immediate) {
        // 'private' variable for instance
        // The returned function will be able to reference this due to closure.
        // Each call to the returned function will share this common timer.
        var timeout;

        // Calling debounce returns a new anonymous function
        return function() {
            // reference the context and args for the setTimeout function
            var context = this,
                args = arguments;

            // Should the function be called now? If immediate is true
            //   and not already in a timeout then the answer is: Yes
            var callNow = immediate && !timeout;

            // This is the basic debounce behaviour where you can call this
            //   function several times, but it will only execute once
            //   [before or after imposing a delay].
            //   Each time the returned function is called, the timer starts over.
            clearTimeout(timeout);

            // Set the new timeout
            timeout = setTimeout(function() {
                // Inside the timeout function, clear the timeout variable
                // which will let the next execution run when in 'immediate' mode
                timeout = null;

                // Check if the function already ran with the immediate flag
                if (!immediate) {
                    // Call the original function with apply
                    // apply lets you define the 'this' object as well as the arguments
                    //    (both captured before setTimeout)
                    func.apply(context, args);
                }
            }, wait);

            // Immediate mode and no wait timer? Execute the function..
            if (callNow) func.apply(context, args);
        };
    },

    /**
     * A simple uuid using [Math.random].
     *
     */
    uuidv4() {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(
            c
        ) {
            var r = (Math.random() * 16) | 0,
                v = c == 'x' ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        });
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
