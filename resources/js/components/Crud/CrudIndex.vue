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
                        {{ $t('add_model', { model: modelNameSingular }) }}
                    </b-button>
                </div>
            </fj-base-header>

            {{ form.config.index.actions }}
            <fj-crud-index-table
                :cols="fields"
                :recordActions="recordActions"
                @selectedItemsChanged="setSelectedItems"
            >
                <component
                    slot="actions"
                    v-for="(component, key) in actions"
                    :key="key"
                    :is="component"
                    :formConfig="form.config"
                    :selectedItems="selectedItems"
                    :sendAction="sendAction"
                />
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
            fields: [],
            selectedItems: []
        };
    },
    beforeMount() {
        this.setFields();
        this.$store.dispatch('setFormConfig', this.formConfig);
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
