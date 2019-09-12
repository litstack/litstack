
import Bus from './../common/event.bus'
import store from './../store'

export default class TableModel
{
    constructor(attributes)
    {
        this.attributes = attributes

        return new Proxy(this, this)
    }

    get(target, prop) {

        let attribute = this[prop] || this._getTranslatedAttribute(prop, this.attributes)

        if(attribute) {
            return attribute
        }

        prop = String(prop)
        if(!prop.includes('.')) {
            return
        }

        attribute = JSON.parse(JSON.stringify(this.attributes))

        let keys = String(prop).split('.')
        for(let i=0;i<keys.length;i++) {
            let key = keys[i]

            if(!attribute) {
                return
            }
            attribute = this._getTranslatedAttribute(key, attribute)
        }
        return attribute
    }

    _getTranslatedAttribute(key, attributes) {
        let lng = store.state.config.language

        if(!('translation' in attributes)) {
            return attributes[key] || null
        }

        if(!(lng in attributes.translation)) {
            return attributes[key] || null
        }

        if(key in attributes.translation[lng]) {
            return attributes.translation[lng][key]
        }

        return attributes[key]
    }
}
