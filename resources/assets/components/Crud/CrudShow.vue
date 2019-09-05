<template>
    <b-row>
        <fj-site-nav :route="model.route">
            <template slot="actions" class="text-secondary">

                <span class="action" v-b-modal.fj-page-preview v-if="formConfig.preview_route">
                    <fa-icon icon="eye"/> Preview
                </span>

            </template>
        </fj-site-nav>

        <b-col cols="12" md="9" order-md="1">
            <b-row class="fjord-form">
                <b-col cols="12">
                    <b-card
                        v-for="(ids, key) in formConfig.layout"
                        :key="key"
                        class="mb-4">
                        <fj-form
                            :ids="ids"
                            :model="model"/>
                    </b-card>
                </b-col>
            </b-row>
        </b-col>

        <fj-controlls/>

        <fj-page-preview :route="formConfig.preview_route" v-if="formConfig.preview_route"/>
    </b-row>
</template>

<script>
export default {
    name: 'CrudShow',
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
            model: null
        };
    },

    methods: {
        saved() {
            if (window.location.pathname.split('/').pop() == 'create') {
                window.location.replace(`${this.model.id}/edit`);
            }
        }
    },
    beforeMount() {
        this.model = this.models.model;

        this.$bus.$on('modelsSaved', this.saved);
    }
};
</script>
