<template>
    <b-col :cols="field.cols">
        <div class="fj-draggable fj-block mb-2">
            <fj-field-block-header
                :expand="expand"
                :block="block"
                :field="field"
                :model="model"
                @deleteBlock="deleteBlock"
                @toggleExpand="expand = !expand"
            />

            <div :class="`fj-block-form ${expand ? 'show' : ''}`">
                <fj-field-block-form
                    :block="block"
                    :field="field"
                    :model="model"
                />
            </div>
        </div>
    </b-col>
</template>

<script>
export default {
    name: 'FieldBlock',
    props: {
        block: {
            required: true,
            type: Object
        },
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            expand: false
        };
    },
    beforeMount() {
        this.setFieldsRoutePrefixBlockId();
    },
    methods: {
        async deleteBlock() {
            try {
                let response = await axios.delete(
                    `${this.field.route_prefix}/blocks/${this.block.field_id}/${this.block.id}`
                );
            } catch (e) {
                console.log(e);
                return;
            }
            this.$emit('deleteBlock', this.block);
        },
        setFieldsRoutePrefixBlockId() {
            for (let i in this.block.fields) {
                let field = this.block.fields[i];
                this.block.fields[i].route_prefix = field.route_prefix
                    .replace('{block_id}', this.block.id)
                    .replace('{id}', this.model.id);
                if (this.field.readonly) {
                    this.block.fields[i].readonly = true;
                }
            }
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';
.fj-block-form {
    display: none;

    &.show {
        display: block;
    }
    .fj-form-item-title {
        font-weight: $font-weight-normal;
    }
}
</style>
