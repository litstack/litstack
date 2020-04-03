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

            this.form_fields = [];
            this._setFormFields(this.attributes.form_fields);

            this.originalModels = {};
            //this._setOriginalModels();
            //Bus.$on('modelsSaved', this._setOriginalModels);
        }

        isFjordModel() {
            return [
                'AwStudio\\Fjord\\Form\\Database\\FormField',
                'AwStudio\\Fjord\\Form\\Database\\FormBlock'
            ].includes(this.model);
        }

        getOriginalModel(form_field) {
            return this._getModel(this.originals, form_field.id);
        }

        _setOriginalModels() {
            if (!this.form_fields) {
                return;
            }

            for (let i = 0; i < this.form_fields.length; i++) {
                let form_field = this.form_fields[i];
                this.originalModels[form_field.id] =
                    this[`${form_field.id}Model`] || null;
            }
        }

        _setFormFields(form_fields) {
            this.form_fields = [];
            for (let index in form_fields) {
                this.form_fields.push(form_fields[index]);
            }
        }

        set(obj, prop, value) {
            let id = prop.replace('Model', '');

            if (!String(prop).endsWith('Model') || !this.formFieldExists(id)) {
                return Reflect.set(...arguments);
            }

            let form_field = this.getFormFieldById(id);

            if (form_field.translatable) {
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

            return Reflect.set(obj, form_field.local_key, value);
        }

        get(target, prop) {
            if (String(prop).endsWith('Model')) {
                let id = prop.replace('Model', '');

                if (this.formFieldExists(id)) {
                    return this._getModel(this.attributes, id);
                }
            }

            return EloquentModel.prototype.get.call(this, target, prop);
        }

        _getModel(attributes, id) {
            let fallback_locale = store.state.config.fallback_locale;
            let lng = store.state.config.language;
            let form_field = this.getFormFieldById(id);

            if (form_field.type == 'relation' && form_field.many == true) {
                // TODO: return relations
                return;
            }

            if (form_field.type == 'block') {
                return;
            }

            if (form_field.translatable) {
                // set not existing object keys

                if (!attributes[lng]) {
                    attributes[lng] = {};
                }

                if (!(form_field.local_key in attributes[lng])) {
                    attributes[lng][form_field.local_key] = null;
                }
            }

            if (this.translatable) {
                // set not existing object keys
                if (
                    !(fallback_locale in attributes) ||
                    attributes[fallback_locale] === undefined
                ) {
                    attributes[fallback_locale] = {};
                }

                if (!(form_field.local_key in attributes[fallback_locale])) {
                    attributes[fallback_locale][form_field.local_key] = null;
                }
            }

            // translatable field
            if (form_field.translatable) {
                // ckeditor is not able to work with null values
                if (
                    attributes[lng][form_field.local_key] === null &&
                    form_field.type == ('wysiwyg' || 'textarea')
                ) {
                    attributes[lng][form_field.local_key] = '';
                }
                if (form_field.id == 'testtext') {
                    //console.log(attributes, lng, attributes[lng]);
                }
                return attributes[lng][form_field.local_key];
            }

            // not translatable field but translatable model
            // using fallback_locale
            if (this.translatable && this.isFjordModel()) {
                return attributes[fallback_locale][form_field.local_key];
            }

            // not translatable model
            return attributes[form_field.local_key];
        }

        hasFormFieldChanged(id) {
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

        formFieldExists(id) {
            for (let i = 0; i < this.form_fields.length; i++) {
                if (this.form_fields[i].id == id) {
                    return true;
                }
            }
            return false;
        }

        getFormFieldById(id) {
            for (let i = 0; i < this.form_fields.length; i++) {
                if (this.form_fields[i].id == id) {
                    return this.form_fields[i];
                }
            }
        }
    };

export default FormMixin;
