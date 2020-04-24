<template>
    <b-col :cols="field.cols">
        <div class="fjord-draggable">
            <div
                v-if="!readonly"
                class="fjord-draggable__dragbar d-flex justify-content-center"
            >
                <i class="fas fa-grip-horizontal text-muted"></i>
            </div>

            <fj-crud-show-form-item
                v-for="(field, key) in block.fields"
                :key="key"
                :field="field"
                :model-id="model.id"
                :model="block"
            />

            <b-row v-if="!readonly">
                <b-col sm="12" class="text-center fj-trash text-muted">
                    <fa-icon icon="trash" @click="deleteBlock(block)" />
                </b-col>
            </b-row>
        </div>
    </b-col>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FormBlocksItem',
    props: {
        model: {
            type: Object,
            required: true
        },
        block: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    beforeMount() {
        this.setFieldsRoutePrefixBlockId();
    },
    computed: {
        ...mapGetters(['form'])
    },
    methods: {
        async deleteBlock(block) {
            let response = await axios.delete(
                `${this.form.config.route_prefix}/${this.model.id}/blocks/${block.field_id}/${block.id}`
            );
            this.$emit('deleteBlock', block);
        },
        setFieldsRoutePrefixBlockId() {
            for (let i in this.block.fields) {
                let field = this.block.fields[i];
                this.block.fields[i].route_prefix = field.route_prefix
                    .replace('{block_id}', this.block.id)
                    .replace('{id}', this.model.id);
            }
        }
    }
};
</script>
