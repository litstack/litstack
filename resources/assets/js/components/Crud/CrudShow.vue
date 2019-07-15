<template>
    <div>
        <base-site-nav :route="parameters.route" />

        <base-create
            v-if="preparedData"
            :data="preparedData"
            :method="method"
            :parameters="parameters"
        />

        <!-- Content Stuff -->
        <template v-if="preparedData.has_content && fields">
            <base-create
                v-for="(c, key) in preparedData.content"
                :key="key"
                class="mt-4"
                :data="c"
                :method="'post'"
                :controls="['save', 'delete']"
                :parameters="
                    getParameters({
                        route: 'contents',
                        title: 'content',
                        fields: c.fields,
                        translatedAttributes: c.fields.map(x => {
                            return x.id;
                        })
                    })
                "
            />

            <base-create
                class="mt-4"
                :data="content"
                :method="'post'"
                :parameters="
                    getParameters({
                        route: 'contents',
                        title: 'content',
                        fields: contentFields,
                        translatedAttributes: ['data']
                    })
                "
            />
        </template>
    </div>
</template>

<script>
export default {
    name: 'CrudShow',
    props: {
        data: {
            type: [Object, Array],
            required: true
        },
        method: {
            type: String,
            required: true
        },
        parameters: {
            type: Object,
            required: true
        },
        fields: {
            type: Object
        },
        content: {
            type: [Object, Array]
        }
    },
    data() {
        return {
            preparedData: null,
            contentFields: [
                {
                    type: 'select',
                    options: [],
                    id: 'type',
                    title: 'Neues Content Element hinzufügen',
                    hint: 'Lorem ipsum',
                    width: 8
                }
            ]
        };
    },
    mounted() {
        this.contentFields[0].options = [];
        for (let key in this.fields) {
            this.contentFields[0].options.push({
                value: key,
                title: key
            });
        }
    },
    beforeMount() {
        let data = this.emptyArrayToObject();

        // if the model is translatable
        if (this.parameters.translatable) {
            // for each language, prepare an object
            for (var i = 0; i < this.parameters.languages.length; i++) {
                let language = this.parameters.languages[i];

                if (this.hasTranslation(language)) {
                    data[language] = this.data.translation[language];
                } else {
                    data[language] = {};
                }
            }
            delete data.translation;
            delete data.translations;
        }
        console.log(this.parameters);
        this.preparedData = data;
    },
    methods: {
        getParameters(options) {
            return {
                fields: options.fields || this.parameters.fields,
                languages: options.languages || this.parameters.languages,
                route: options.route || this.parameters.route,
                title: options.title || this.parameters.title,
                translatable:
                    options.translatable || this.parameters.translatable,
                translatedAttributes:
                    options.translatedAttributes ||
                    this.parameters.translatedAttributes
            };
        },
        emptyArrayToObject() {
            return Array.isArray(this.data) && this.data.length === 0
                ? {}
                : this.data;
        },
        hasTranslation(language) {
            let translation = JSON.parse(JSON.stringify(this.data.translation));
            return translation.hasOwnProperty(language);
        }
    }
};
</script>
