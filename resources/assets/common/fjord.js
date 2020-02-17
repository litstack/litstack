import Vue from 'vue';

export const Fjord = {
    components(components) {
        for (let key of components.keys()) {
            this.component(components(key));
        }
    },
    component(component) {
        if (!component.default.name) {
            throw 'Component name required in ' + component.default.__file;
        }
        Vue.component(`Fj${component.default.name}`, component.default);
    }
};

export default Fjord;
