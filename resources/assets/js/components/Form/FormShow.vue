<template>
    <div>
        <b-row class="fjord-form">
            <b-col lg="12">
                <b-card>
                    <fj-form
                        :model="formFields"
                    />
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
        }
    },
    data() {
        return {
            formFields: []
        };
    },
    beforeMount() {
        this.formFields = this.models.formFields;
    }
};
</script>
