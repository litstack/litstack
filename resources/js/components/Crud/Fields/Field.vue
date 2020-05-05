<template>
    <component
        :is="field.component"
        :field="field"
        :model="model"
        :model-id="modelId === 0 ? model.id : modelId"
        v-bind="field.props ? field.props : {}"
        v-on="$listeners"
    />
</template>

<script>
export default {
    name: 'Field',
    props: {
        model: {
            type: Object,
            required: true
        },
        modelId: {
            type: Number,
            default() {
                return 0;
            }
        },
        field: {
            type: Object,
            required: true
        }
    },
    beforeMount() {
        // This allows Fields like Blocks to set individual Model id's that
        // differ from the id of the model that gets passed to the Field.
        // For e.g: In a Block the model of the Block would be passed but the
        // route Model id is not the id for the Block but for the Crud Model.
        let modelId = this.modelId ? this.modelId : this.model.id;

        this.field.route_prefix = this.field.route_prefix.replace(
            '{id}',
            modelId
        );
    }
};
</script>
