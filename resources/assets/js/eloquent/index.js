import store from './../store'
import Bus from './../common/event.bus'
import EloquentCollection from './collection'
import Field from './field'
let WatchJS = require("melanke-watchjs")

export default class Eloquent {
    constructor(config) {
        if(Array.isArray(config.data) || !config.data) {
            return new EloquentCollection(config)
        }
        this.config = config
        this.saveable = false
        this.translatable = config.translatable
        this.fillable = config.fillable
        this.model = config.model
        this.route = config.route
        this.relations = config.relations
        this.deleted = false
        this.setData(config.data)
        this.formFields = []
        if(this.data.fields) {
            this._setFields()
        }
        this._setRelations()
        this.saveable = true

        return new Proxy(this, this)
    }

    _setRelations() {
        for(name in this.relations) {
            this.data[name] = new Eloquent(this.relations[name])
            this.relations[name] = this.data[name]
        }
    }

    _setFields() {
        for(let i=0;i<this.data.fields.length;i++) {
            let field = new Field(this.data.fields[i], this.data, this.route)
            this.formFields.push(field)
        }
    }

    get(target, prop) {
        return this[prop] || this.data[prop] || undefined;
    }

    _getEmptyFields() {
        let obj = {}
        for(let i=0;i<this.data.fields.length;i++) {
            let field = this.data.fields[i]
            if(['block', 'image'].includes(field.id)) {
                continue;
            }
            obj[field.id] = ''
        }
        return obj
    }

    setData(data) {
        this.data = data
        if(!this.translatable) {
            this.startingData = JSON.parse(JSON.stringify(this.data))
            return;
        }

        for(let i=0;i<store.state.main.languages.length;i++) {
            let locale = store.state.main.languages[i]
            // set empty object if locale entry doesnt exist
            if(!(locale in this.data.translation)) {
                this.data[locale] = this._getEmptyFields()
            } else {
                this.data[locale] = this.data.translation[locale]
            }
        }
        this.translation = this.data.translation
        this.translations = this.data.translations
        delete this.data.translation
        delete this.data.translations
        this.startingData = JSON.parse(JSON.stringify(this.data))
    }

    getPayload() {
        let payload = {
            model: this.model,
            data: Object.assign({}, this.data)
        }
        return payload
    }

    async save() {
        let method = this.data.id ? 'put' : 'post'
        let route = `/admin/eloquent/${this.data.id ? this.data.id : '' }`
        let response = await axios[method](route, this.getPayload())
        this.setData(response.data)
        for(name in this.relations) {
            this.data[name] = this.relations[name]
        }
    }

    async delete() {
        let route = `/admin/eloquent/destroy/${this.data.id}`
        let response = await axios.post(route, {model:this.model})
        Bus.$emit('deletedModel', this)
    }

    isFieldTranslatable(id) {
        if(!this.translatable) {
            return false
        }

        return Object.keys(this.translation[Object.keys(this.translation)[0]]).includes(id)
    }

    hasField(id) {
        return Object.keys(this.data).includes(id)
    }
}
