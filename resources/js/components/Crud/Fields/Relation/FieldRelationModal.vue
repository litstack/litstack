<template>
    <b-modal
        :id="modalId"
        size="lg"
        hide-footer
        content-class="lit-relation-modal"
    >
        <template slot="modal-title">
            {{ title.capitalize() }}
            <!-- <b-button size="sm" variant="primary" @click="createNew">
				<lit-fa-icon icon="plus" /> Create New
			</b-button> -->
        </template>
        <lit-index-table
            ref="table"
            :cols="cols"
            :items="items"
            :load-items="loadItems"
            :search-keys="field.search"
            :per-page="10"
            :name-singular="field.names.singular"
            :name-plural="field.names.plural"
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
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        modalId: {
            type: String,
            required: true,
        },
        cols: {},
        selectedRelations: {
            type: [Object, Array],
            default: () => {
                return {};
            },
        },
    },
    beforeMount() {
        this.$on('refresh', () => {
            this.$refs.table.$emit('refreshSelected');
        });
    },
    data() {
        return {
            items: [],
        };
    },
    methods: {
        createNew() {
            this.$bvModal.hide(this.modalId);
        },
        remove(item) {
            this.$emit('remove', item);
        },
        select(item) {
            this.$emit('select', item);
        },
        async loadItems(payload) {
            let response = await this.sendLoadItems(payload);

            if (!response) {
                return;
            }

            this.items = this.crud(response.data.items);

            return response;
        },

        /**
         * Send load items request.
         */
        async sendLoadItems(payload) {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/relation/index`,
                    {
                        field_id: this.field.id,
                        ...this.field.params,
                        ...payload,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },
    },
    computed: {
        title() {
            return this.field.many
                ? this.__('base.item_add', { item: this.field.title })
                : this.__('base.item_select', { item: this.field.title });
        },
    },
};
</script>

<style lang="scss">
.lit-relation-modal {
    .modal-body {
        padding: 0;
    }
}
</style>
