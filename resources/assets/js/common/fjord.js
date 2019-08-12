
import Vue from 'vue'

export const Fjord = {
    components(components) {
        for(let key of components.keys()) {
            this.component(components(key))
        }
    },
    component(component) {
        Vue.component(`Fj${component.default.name}`, component.default);
    }
}

export default Fjord
