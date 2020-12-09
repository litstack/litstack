import store from '@lit-js/store';

window.onbeforeunload = function () {
    return store.getters.canSave
        ? 'There are unsaved changes, are you sure you want to leave?'
        : undefined;
};
