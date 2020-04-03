import Bus from '@fj-js/common/event.bus';
import EloquentCollection from '@fj-js/eloquent/collection';
import EloquentModel from '@fj-js/eloquent/model';
import store from '@fj-js/store';

let TranslatableMixin = Base =>
    class extends Base {
        constructor(config) {
            let proxy = super(config);

            if (proxy.constructor == EloquentCollection) {
                return;
            }

            this.translatable = config.translatable;
            this.translation = null;
            this.translations = null;
            this.canSetKeys = [];

            let self = this;
            Bus.$on('languageChanged', () => {
                self.setCurrentLanguageAttributes();
            });

            this.setAttributes(this.attributes);
        }

        setAttributes(attributes) {
            // like parent::setAttributes in PHP
            EloquentModel.prototype.setAttributes.call(this, attributes);

            this.setLanguageAttributes();

            this.setOriginals();
        }

        setLanguageAttributes() {
            if (!this.translatable) {
                return;
            }

            if ('translation' in this.attributes) {
                this.translation = this.attributes.translation;
                delete this.attributes.translation;
            } else {
                return;
            }

            if ('translations' in this.attributes) {
                this.translations = this.attributes.translations;
                delete this.attributes.translations;
            }

            for (let i = 0; i < store.state.config.languages.length; i++) {
                let locale = store.state.config.languages[i];

                this.attributes[locale] = this.translation[locale];
            }

            this.setCurrentLanguageAttributes();
        }

        setCurrentLanguageAttributes() {
            let lng = store.state.config.language;

            if (!(lng in this.attributes)) {
                return;
            }

            for (let key in this.attributes[lng]) {
                if (this.getFormFieldById) {
                    let form_field = this.getFormFieldById(key);

                    if (form_field) {
                        if (
                            ['image', 'block', 'relation'].includes(
                                form_field.type
                            )
                        ) {
                            continue;
                        }

                        if (!form_field.translatable) {
                            continue;
                        }
                    }
                }

                this.attributes[key] = this.attributes[lng][key];
            }
        }
    };

export default TranslatableMixin;
