<template>
    <div>
        <b-row class="fjord-form">
            <b-col lg="12">
                <b-card
                    v-for="(ids, key) in formConfig.layout"
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
        formConfig: {
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
