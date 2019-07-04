<template>
    <div>
        <base-create
            v-if="preparedData"
            :data="preparedData"
            :method="method"
            :parameters="parameters"
        />
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
        }
    },
    data() {
        return {
            preparedData: null
        };
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
        this.preparedData = data;
    },
    methods: {
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
