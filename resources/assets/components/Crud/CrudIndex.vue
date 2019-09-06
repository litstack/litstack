<template>
    <fj-container>
        <fj-header :title="formConfig.names.title.plural">

            <div slot="actions-right">

                <b-button
                    size="sm"
                    variant="primary"
                    :href="createRoute">
                    <fa-icon icon="plus"/> add {{ formConfig.names.title.singular }}
                </b-button>

            </div>

        </fj-header>

        <b-row>
            <b-col cols="12">
                <b-card>
                    <fj-crud-index-table
                        :title="formConfig.names.title.plural"
                        :cols="fields"
                        :route="this.formConfig.names.table"
                        :actions="['edit', 'delete']"/>
                </b-card>
            </b-col>
        </b-row>

    </fj-container>
</template>

<script>
export default {
    name: 'CrudIndex',
    props: {
        formConfig: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            fields: [
                {
                    key: 'title',
                    label: 'Title',
                }
            ],
        };
    },
    computed: {
        createRoute() {
            return `${this.formConfig.names.table}/create`;
        }
    },
};
</script>
