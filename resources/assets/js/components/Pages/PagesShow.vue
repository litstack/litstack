<template>
    <div class="card">
        <div class="card-header">
            <page-language
                :config="config"
                :language="language"
                :payload="payload"
                v-model="language"
            />
        </div>
        <div class="card-body">
            <div class="row">
                <div
                    :class="`col col-${field.width}`"
                    v-for="(field, index) in config.fields"
                >
                    <PageInput
                        :field="field"
                        :val="payload"
                        :language="language"
                        :config="config"
                    />

                    <FormBlock
                        v-if="field.type == 'block'"
                        :field="field"
                        :config="config"
                        :language="language"
                        :data="repeatables"
                        v-model="payload[field.id]"
                    />
                </div>
            </div>
            <page-controls :config="config" :payload="payload" />
        </div>
    </div>
</template>

<script>
import PageControls from './PageControls';
import PageLanguage from './PageLanguage';
import PageInput from './PageInput';

export default {
    name: 'PagesShow',
    props: {
        /**
         * The config holds information of the page's fields
         * and the structure of each included repeatable
         */
        config: {
            type: Object,
            required: true
        },
        /**
         * The repeatables array holds the data of each
         * repeatable field that has been set
         */
        repeatables: {
            type: Array
        },
        pagecontent: {
            type: Array
        }
    },
    components: {
        PageControls,
        PageLanguage,
        PageInput
    },
    data() {
        return {
            payload: {},
            language: null
        };
    },
    beforeMount() {
        this.init();

        // set a default language if the page is translatable
        if (this.config.translatable) {
            this.language = this.config.languages[0];
        }
    },
    methods: {
        init() {
            /**
             * If the page is translatable, init payload with translation objects
             */
            if (this.config.translatable == true) {
                let content = this.pagecontent;
                for (var i = 0; i < content.length; i++) {
                    let translation = this.translationObject(content[i]);
                    this.$set(this.payload, content[i].field_name, translation);
                }
            }

            // init empty fields
            for (var i = 0; i < this.config.fields.length; i++) {
                let field = this.config.fields[i];

                if (
                    !this.payload.hasOwnProperty(field.id) &&
                    field.type != 'block'
                ) {
                    this.$set(
                        this.payload,
                        field.id,
                        this.emptyTranslationObject()
                    );
                }
            }
        },
        emptyTranslationObject() {
            let object = {};

            for (var i = 0; i < this.config.languages.length; i++) {
                let lng = this.config.languages[i];

                object[lng] = {
                    content: null
                };
            }
            return object;
        },
        translationObject(field) {
            let object = {};
            for (var i = 0; i < field.translations.length; i++) {
                object[field.translations[i].locale] = {};
                object[field.translations[i].locale]['content'] =
                    field.translations[i].content;
            }

            return object;
        }
    }
};
</script>
