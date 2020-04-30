<template>
    <b-col :cols="field.cols">
        <div class="fj-draggable fj-block mb-4">
            <fj-field-block-header
                @toggleExpand="expand = !expand"
                :expand="expand"
            />

            <div :class="`fj-block-form ${expand ? 'show' : ''}`">
                <fj-field-block-form />
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
        async deleteBlock(block) {
            try {
                let response = await axios.delete(
                    `${this.field.route_prefix}/blocks/${block.field_id}/${block.id}`
                );
            } catch (e) {
                console.log(e);
                return;
            }
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
.fj-block-form {
    display: none;

    &.show {
        display: block;
    }
}
</style>
