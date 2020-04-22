import EloquentCollection from '@fj-js/eloquent/collection';
import EloquentModel from '@fj-js/eloquent/model';
import Bus from '@fj-js/common/event.bus';
import store from '@fj-js/store';

let FormMixin = Base =>
    class extends Base {
        constructor(config) {
            let proxy = super(config);

            if (proxy.constructor == EloquentCollection) {
                return;
            }

            this.fields = [];

            this._setFields(this.attributes.fields);

            this.originalModels = {};
            //this._setOriginalModels();
            //Bus.$on('modelsSaved', this._setOriginalModels);
        }

        isFjordModel() {
            return [
                'Fjord\\Crud\\Models\\FormField',
                'Fjord\\Crud\\Models\\FormBlock'
            ].includes(this.model);
        }

        getPayload(ids) {
            let attributes = {};
            for (let key in ids) {
                let fallback_locale = store.state.config.fallback_locale;
                let currentLng = store.state.config.language;
                let field = this.getFieldById(ids[key]);
                if (!this.translatable) {
                    attributes[field.local_key] = this.attributes[
                        field.local_key
                    ];
                }
                if (field.translatable && this.translatable) {
                    for (let i in store.state.config.languages) {
                        let lng = store.state.config.languages[i];
                        attributes[lng] = this.attributes[lng];
                    }
                }

                if (this.translatable && this.isFjordModel()) {
                    attributes[fallback_locale] = this.attributes[
                        fallback_locale
                    ];
                }
                if (
                    this.translatable &&
                    !this.isFjordModel() &&
                    !field.translatable
                ) {
                    attributes[field.local_key] = this.attributes[
                        field.local_key
                    ];
                }
            }

            return attributes;
        }

        getOriginalModel(field) {
            return this._getModel(this.originals, field.id);
        }

        _setOriginalModels() {
            if (!this.fields) {
                return;
            }

            for (let i = 0; i < this.fields.length; i++) {
                let field = this.fields[i];
                this.originalModels[field.id] =
                    this[`${field.id}Model`] || null;
            }
        }

        _setFields(fields) {
            this.fields = [];
            for (let index in fields) {
                this.fields.push(fields[index]);
            }
        }

        set(obj, prop, value) {
            let id = prop.replace('Model', '');

            if (!String(prop).endsWith('Model') || !this.fieldExists(id)) {
                return Reflect.set(...arguments);
            }

            let field = this.getFieldById(id);

            if (field.translatable) {
                // translatable field
                obj = this.attributes[store.state.config.language];
            } else if (this.translatable && this.isFjordModel()) {
                // not translatable field but translatable model
                // using fallback_locale
                obj = this.attributes[store.state.config.fallback_locale];
            } else {
                // not translatable model
                obj = this.attributes;
            }

            return Reflect.set(obj, field.local_key, value);
        }

        get(target, prop) {
            if (String(prop).endsWith('Model')) {
                let id = prop.replace('Model', '');

                if (this.fieldExists(id)) {
                    return this._getModel(this.attributes, id);
                }
            }

            return EloquentModel.prototype.get.call(this, target, prop);
        }

        _getModel(attributes, id) {
            let fallback_locale = store.state.config.fallback_locale;
            let lng = store.state.config.language;
            let field = this.getFieldById(id);

            // Form field is translatable.
            if (field.translatable) {
                // set not existing object keys
                if (!attributes[lng]) {
                    attributes[lng] = {};
                }

                if (!(field.local_key in attributes[lng])) {
                    attributes[lng][field.local_key] = null;
                }
            }
            // Model is translatable.
            if (this.translatable) {
                // set not existing object keys
                if (
                    !(fallback_locale in attributes) ||
                    attributes[fallback_locale] === undefined
                ) {
                    attributes[fallback_locale] = {};
                }

                if (!(field.local_key in attributes[fallback_locale])) {
                    attributes[fallback_locale][field.local_key] = null;
                }
            }

            // translatable field
            if (field.translatable) {
                // ckeditor is not able to work with null values
                if (
                    attributes[lng][field.local_key] === null &&
                    field.type == ('wysiwyg' || 'textarea')
                ) {
                    attributes[lng][field.local_key] = '';
                }
                if (field.id == 'testtext') {
                    //console.log(attributes, lng, attributes[lng]);
                }
                return attributes[lng][field.local_key];
            }

            // not translatable field but translatable model
            // using fallback_locale
            if (this.translatable && this.isFjordModel()) {
                return attributes[fallback_locale][field.local_key];
            }

            // not translatable model
            return attributes[field.local_key];
        }

        hasFieldChanged(id) {
            return this.originals != this.model;
        }

        isFieldTranslatable(id) {
            if (!this.translatable) {
                return false;
            }

            return Object.keys(
                this.translation[Object.keys(this.translation)[0]]
            ).includes(id);
        }

        fieldExists(id) {
            for (let i = 0; i < this.fields.length; i++) {
                if (this.fields[i].id == id) {
                    return true;
                }
            }
            return false;
        }

        getFieldById(id) {
            for (let i = 0; i < this.fields.length; i++) {
                if ('comp' in this.fields[i]) {
                    continue;
                }
                if (this.fields[i].id == id) {
                    return this.fields[i];
                }
            }
        }
    };

export default FormMixin;
