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
                        :names="formConfig.names"
                        :config="formConfig.index"
                        :cols="fields"
                        :route="this.formConfig.names.table"
                        :actions="actions"/>
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
            actions: {
                'Delete': this.deleteItems
            }
        };
    },
    computed: {
        createRoute() {
            return `${this.formConfig.names.table}/create`;
        }
    },
    methods: {
        async deleteItems(ids) {

            await axios.post(`${this.formConfig.names.table}/delete-all`, {ids})

            let deletedTitle = ids.length == 1
                ? this.formConfig.names.title.singular
                : this.formConfig.names.title.plural

            this.$notify({
                group: 'general',
                type: 'success',
                title: this.formConfig.names.title.plural,
                text: `Successfully deleted ${ids.length} ${deletedTitle}.`,
                duration: 1500
            });

            this.$bus.$emit('unselectCrudIndex')
            this.$bus.$emit('reloadCrudIndex')
        }
    }
};
</script>
