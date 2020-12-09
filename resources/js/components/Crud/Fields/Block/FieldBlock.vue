<template>
    <lit-base-field
        :field="field"
        :model="model"
        :class="`lit-block-${field.id}`"
    >
        <template slot="title-right">
            <a
                href="#"
                @click="toggleExpand"
                v-if="!this.create"
                class="text-capitalize"
            >
                <lit-fa-icon :icon="expandedAll ? 'angle-up' : 'angle-down'" />
                {{
                    __(
                        `crud.fields.block.${
                            expandedAll ? 'collapse_all' : 'expand_all'
                        }`
                    )
                }}
            </a>
        </template>
        <div class="form-control-expand" v-if="model.id">
            <div v-if="busy" class="d-flex justify-content-around">
                <lit-spinner />
            </div>
            <draggable
                v-model="sortableBlocks"
                @end="newOrder"
                handle=".lit-draggable__dragbar"
                tag="b-row"
                :class="{ 'mb-0': field.readonly }"
                v-else-if="sortableBlocks.length > 0"
            >
                <template v-for="block in sortableBlocks">
                    <lit-field-repeatable
                        v-if="block.type in field.repeatables"
                        ref="block"
                        :key="block.id"
                        :block="block"
                        :field="field"
                        :model="model"
                        :width="field.blockWidth"
                        :reload="reloadBlock"
                        :model-id="modelId || model.id"
                        :preview="field.repeatables[block.type].preview"
                        :fields="field.repeatables[block.type].form.fields"
                        :set-route-prefix="setFieldsRoutePrefixBlockId"
                        @deleteItem="deleteBlock(block)"
                    />
                </template>
            </draggable>

            <div v-else>
                <lit-field-alert-empty
                    :field="field"
                    :class="{ 'mb-0': field.readonly }"
                />
            </div>

            <lit-field-block-add-repeatable-buttons
                v-if="!field.readonly"
                :field="field"
                :model="model"
                :sortableBlocks="sortableBlocks"
                @newBlock="newBlock"
            />
        </div>
        <template v-else>
            <lit-field-alert-not-created :field="field" class="mb-0" />
        </template>
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldBlock',
    props: {
        /**
         * Field attributes.
         */
        field: {
            type: Object,
        },

        /**
         * Model.
         */
        model: {
            type: Object,
        },

        modelId: {},
    },
    data() {
        return {
            sortableBlocks: [],
            busy: true,
            expandedAll: false,
        };
    },
    beforeMount() {
        this.loadBlocks();

        Lit.bus.$on('saved', () => {
            this.reloadBlocks();
        });
    },
    computed: {
        create() {
            return this.model.id === undefined;
        },
    },
    methods: {
        /**
         * Reload block.
         */
        async reloadBlock(block) {
            let response = await this.sendReloadBlock();

            if (!response) {
                return;
            }

            let newBlock = this.crud(response.data);
            for (let i in this.sortableBlocks) {
                let block = this.sortableBlocks[i];
                if (block.id == newBlock.id) {
                    this.sortableBlocks[i] = newBlock;
                }
            }
        },

        /**
         * Send reload block request.
         */
        async sendReloadBlock() {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/block/load`,
                    {
                        field_id: this.field.id,
                        repeatable_id: this.block.id,
                    }
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Toggle expand all.
         */
        toggleExpand() {
            for (let i in this.$refs.block) {
                let block = this.$refs.block[i];
                block.$emit('expand', !this.expandedAll);
            }

            this.expandedAll = !this.expandedAll;
        },

        /**
         * Set fields route prefix block id.
         */
        setFieldsRoutePrefixBlockId(fields, block) {
            for (let i in fields) {
                let field = fields[i];
                fields[i].params.repeatable_id = block.id;
                if (this.field.readonly) {
                    fields[i].readonly = true;
                }

                if (!('field_id' in (this.field.params || {}))) {
                    fields[i].params.child_repeatable_id = block.id;
                } else {
                    if ('child_repeatable_id' in this.field.params) {
                        fields[
                            i
                        ].params.child_repeatable_id = this.field.params.child_repeatable_id;
                    }
                    fields[i].params.child_field_id = this.field.id;
                    fields[i].params.field_id = this.field.params.field_id;
                }
            }
            return fields;
        },

        /**
         * Reload blocks.
         */
        reloadBlocks() {
            this._loadBlocks();
        },

        /**
         * Load blocks and set busy state.
         */
        async loadBlocks() {
            this.busy = true;
            await this._loadBlocks();
            this.busy = false;
        },

        /**
         * Load blocks.
         */
        async _loadBlocks() {
            if (this.create) {
                return;
            }
            let response = await this.sendLoadBlocks();

            if (!response) {
                return;
            }

            this.sortableBlocks = [];
            for (let i in response.data) {
                let block = response.data[i];
                this.newBlock(block, false);
            }
        },

        /**
         * Send load blocks.
         */
        async sendLoadBlocks() {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/block/index`,
                    this.qualifyPayload({})
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Add new block to list.
         */
        newBlock(block, open = true) {
            this.sortableBlocks.push(this.crud(block));
            if (open) {
                this.$nextTick(() => {
                    this.$refs.block[this.$refs.block.length - 1].$emit(
                        'expand',
                        true
                    );
                });
            }
        },

        /**
         * Delete block.
         */
        async deleteBlock(block) {
            let response = await this.sendDeleteBlock(block);

            if (!response) {
                return;
            }

            this.sortableBlocks.splice(this.sortableBlocks.indexOf(block), 1);

            Lit.bus.$emit('field:updated', 'block:deleted');

            this.$bvToast.toast(
                this.__('base.item_delete', { item: 'Repeatable' }),
                {
                    variant: 'success',
                }
            );
        },

        /**
         * Send load block request.
         */
        async sendDeleteBlock(block) {
            try {
                return await axios.post(
                    `${this.field.route_prefix}/block/destroy`,
                    this.qualifyPayload({
                        //field_id: this.field.id,
                        repeatable_id: block.id,
                    })
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Set new order.
         */
        async newOrder() {
            let payload = {
                ids: this.sortableBlocks.map((item) => item.id),
            };

            let response = await this.sendNewOrder(payload);

            if (!response) {
                return;
            }

            Lit.bus.$emit('field:updated', 'block:ordered');

            this.$bvToast.toast(this.__('base.messages.order_changed'), {
                variant: 'success',
            });
        },

        /**
         * Send new order request.
         */
        async sendNewOrder(payload) {
            try {
                return await axios.put(
                    `${this.field.route_prefix}/block/order`,
                    this.qualifyPayload({
                        payload,
                    })
                );
            } catch (e) {
                console.log(e);
            }
        },

        qualifyPayload(payload = {}) {
            payload = {
                field_id: this.field.id,
                ...(this.field.params || {}),
                ...payload,
            };

            if ('field_id' in (this.field.params || {})) {
                payload.child_field_id = this.field.id;
            }

            return payload;
        },
    },
};
</script>
