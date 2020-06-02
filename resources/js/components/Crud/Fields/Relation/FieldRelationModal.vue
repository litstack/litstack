<template>
    <b-modal
        :id="modalId"
        size="lg"
        hide-footer
        :title="title"
        content-class="fj-relation-modal"
    >
        <fj-index-table
            ref="table"
            :cols="field.preview"
            :items="items"
            :load-items="loadItems"
            :search-keys="field.config.search"
            :per-page="10"
            :name-singular="field.config.names.singular"
            :name-plural="field.config.names.plural"
            :selected="selectedRelations"
            @select="select"
            @unselect="remove"
            v-bind:radio="!field.many"
            no-card
            no-head
            small
        />
    </b-modal>
</template>

<script>
export default {
    name: 'FieldRelationModal',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        modalId: {
            type: String,
            required: true
        },
        selectedRelations: {
            type: [Object, Array],
            default: () => {
                return {};
            }
        }
    },
    beforeMount() {
        this.$on('refresh', () => {
            this.$refs.table.$emit('refreshSelected');
        });
    },
    data() {
        return {
            items: []
        };
    },
    methods: {
        remove(item) {
            this.$emit('remove', item);
        },
        select(item) {
            this.$emit('select', item);
        },
        async loadItems(payload) {
            let response = await axios.post(
                `${this.field.route_prefix}/${this.field.id}/index`,
                payload
            );

            this.items = this.crud(response.data.items);

            return response;
        }
    },
    computed: {
        title() {
            return this.field.many
                ? this.__('fj.add_model', { model: this.field.title })
                : this.__('fj.select_item', { item: this.field.title });
        }
    }
};
</script>

<style lang="scss">
.fj-relation-modal {
    .modal-body {
        padding: 0;
    }
}
</style>
