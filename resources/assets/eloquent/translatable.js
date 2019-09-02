import store from './../store'

import Bus from './../common/event.bus'

export default class PreviewEloquent {
    constructor(data) {
        this.data = data || {}
        this.setData()

        let self = this

        Bus.$on('languageChanged', () => {
            self.setData()
        })

        return new Proxy(this, this)
    }

    get(target, prop) {
        return this[prop] || this.data[prop] || undefined;
    }

    setData() {
        if(!this.data) {
            return
        }

        if(!('translation' in this.data)) {
            return
        }

        let lng = store.state.config.language
        if(!(lng in this.data.translation)) {
            return
        }

        for(let key in this.data.translation[lng]) {
            this.data[key] = this.data.translation[lng][key]
        }
    }
}
