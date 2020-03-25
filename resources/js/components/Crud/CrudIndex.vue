<template>
    <fj-base-container>
        <template v-if="form.config">
            <fj-base-header :title="form.config.names.title.plural">
                <div slot="actions-right">
                    <component
                        v-for="(component, key) in globalActions"
                        :key="key"
                        :is="component"
                        :formConfig="form.config"
                    />

                    <b-button size="sm" variant="primary" :href="createRoute">
                        <fa-icon icon="plus" />
                        {{ $t('fj.add_model', { model: modelNameSingular }) }}
                    </b-button>
                </div>
            </fj-base-header>

            <fj-crud-index-table
                :cols="tableCols"
                :recordActions="recordActions"
                @selectedItemsChanged="setSelectedItems"
            >
            </fj-crud-index-table>
        </template>
    </fj-base-container>
</template>

<script>
import { mapGetters } from 'vuex';
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
            tableCols: [],
            selectedItems: []
        };
    },
    beforeMount() {
        this.setTableCols();
        this.$store.dispatch('setFormConfig', this.formConfig);

        this.$store.commit('SET_ACTIONS', this.actions);
    },
    computed: {
        ...mapGetters(['form']),
        createRoute() {
            return `${this.form.config.names.table}/create`;
        },
        modelNameSingular() {
            return this.form.config.names.title.singular;
        }
    },
    methods: {
        setSelectedItems(items) {
            this.selectedItems = items;
        },
        setTableCols() {
            for (let i = 0; i < this.formConfig.index.preview.length; i++) {
                let field = this.formConfig.index.preview[i];

                if (typeof field == typeof '') {
                    field = { key: field };
                }
                this.tableCols.push(field);
            }
        }
    }
};
</script>
