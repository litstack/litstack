
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

        let attribute = this[prop] || this.attributes[prop]

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

            attribute = attribute[key]

        }
        return attribute
    }
}
