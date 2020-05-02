<template>
    <b-col :cols="field.cols">
        <div class="fj-draggable fj-block mb-2">
            <fj-field-block-header
                :sortable="sortable"
                :expand="expand"
                :block="block"
                :field="field"
                :model="model"
                :fields="_fields"
                :preview="preview"
                :delete-icon="deleteIcon"
                @deleteItem="$emit('deleteItem')"
                @toggleExpand="expand = !expand"
            />

            <div :class="`fj-block-form ${expand ? 'show' : ''}`">
                <fj-field-block-form
                    :block="block"
                    :field="field"
                    :model="model"
                    :fields="_fields"
                />
            </div>
        </div>
    </b-col>
</template>

<script>
export default {
    name: 'FieldBlock',
    props: {
        deleteIcon: {
            type: String,
            default() {
                return 'trash';
            }
        },
        sortable: {
            type: Boolean,
            default() {
                return true;
            }
        },
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
        },
        setRoutePrefix: {
            required: true
        },
        preview: {
            type: Array,
            required: true
        },
        fields: {
            type: Array,
            default() {
                return [];
            }
        }
    },
    data() {
        return {
            expand: false,
            _fields: []
        };
    },
    beforeMount() {
        this._fields = this.setRoutePrefix(this.clone(this.fields), this.block);
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
