<template>
    <fj-base-container>
        <fj-base-header :title="formConfig.names.title.plural">
            <div slot="actions-right">
                <component
                    v-for="(component, key) in globalActions"
                    :key="key"
                    :is="component"
                    :formConfig="formConfig"
                />

                <b-button size="sm" variant="primary" :href="createRoute">
                    <fa-icon icon="plus" />
                    {{ $t('add_model', { model: modelNameSingular }) }}
                </b-button>
            </div>
        </fj-base-header>

        <fj-crud-index-table
            :names="formConfig.names"
            :config="formConfig.index"
            :cols="fields"
            :route="this.formConfig.names.table"
            :actions="formConfig.index.actions"
            :recordActions="recordActions"
            @selectedItemsChanged="setSelectedItems"
            :perPage="formConfig.index.per_page"
        >
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
    </fj-base-container>
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
            required: true
        },
        globalActions: {
            type: Array,
            default: () => {
                return [];
            }
        },
        recordActions: {
            type: Array,
            default: () => {
                return [];
            }
        }
    },
    data() {
        return {
            fields: [],
            selectedItems: []
        };
    },
    beforeMount() {
        this.setFields();
    },
    computed: {
        createRoute() {
            return `${this.formConfig.names.table}/create`;
        },
        modelNameSingular() {
            return this.formConfig.names.title.singular;
        }
    },
    methods: {
        async sendAction(route, ids) {
            let response = null;
            let message = '';
            let type = 'success';
            try {
                response = await _axios({
                    method: 'post',
                    url: route,
                    data: { ids }
                });

                message = response.data.message;
            } catch (e) {
                response = e.response;
                message = response.data.message;
                type = 'danger';
            }

            this.$bvToast.toast(message, {
                variant: 'info'
            });
        },
        setSelectedItems(items) {
            this.selectedItems = items;
        },
        setFields() {
            for (let i = 0; i < this.formConfig.index.preview.length; i++) {
                let field = this.formConfig.index.preview[i];

                if (typeof field == typeof '') {
                    field = { key: field };
                }
                this.fields.push(field);
            }
        }
    }
};
</script>
