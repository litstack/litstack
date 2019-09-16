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
                        :actions="formConfig.index.actions"
                        @selectedItemsChanged="setSelectedItems">

                        <component
                            slot="actions"
                            v-for="(component, key) in actions"
                            :key="key"
                            :is="component"
                            :formConfig="formConfig"
                            :selectedItems="selectedItems"
                            :sendAction="sendAction"
                        />

                    </fj-crud-index-table>
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
        },
        actions: {
            type: Array,
            required: true,
        }
    },
    data() {
        return {
            fields: [],
            selectedItems: []
        };
    },
    beforeMount() {
        this.setFields()
    },
    computed: {
        createRoute() {
            return `${this.formConfig.names.table}/create`;
        }
    },
    methods: {
        async sendAction(route, ids) {
            let response = null
            let message = ''
            let type = 'success'
            try {
                response = await _axios({
                    method: 'post',
                    url: route,
                    data: {ids},
                })

                message = response.data
            } catch(e) {
                response = e.response
                message = response.data.message
                type = 'danger'
            }

            this.$notify({
                group: 'general',
                type: type,
                title: `${this.names.title.plural}`,
                text: message,
            });
        },
        setSelectedItems(items) {
            this.selectedItems = items
        },
        setFields() {
            for(let i=0;i<this.formConfig.index.preview.length;i++) {
                let field = this.formConfig.index.preview[i]

                if(typeof field == typeof '') {
                    field = {key: field}
                }
                this.fields.push(field)
            }
        },
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
            });

            this.$bus.$emit('unselectCrudIndex')
            this.$bus.$emit('reloadCrudIndex')
        }
    }
};
</script>
