<template>
    <div>
        <b-row class="fjord-form">
            <b-col lg="12">
                <b-card
                    v-for="(ids, key) in formLayout"
                    :key="key"
                    class="mb-4">
                    <fj-form
                        :ids="ids"
                        :model="formFields"/>
                </b-card>
            </b-col>
        </b-row>
    </div>
</template>

<script>
export default {
    name: 'FormShow',
    props: {
        models: {
            type: Object
        },
        pageName: {
            type: String,
            required: true
        },
        formLayout: {
            type: [Array, Object],
            required: true
        }
    },
    data() {
        return {
            formFields: [],
        };
    },
    beforeMount() {
        this.formFields = this.models.formFields;
    }
};
</script>
