<!--
<template>
    <component
        ref="component"
        :is="field.component"
        :field="field"
        :model="model"
        :model-id="modelId === 0 ? model.id : modelId"
        v-bind="field.props ? field.props : {}"
        v-on="$listeners"
    />
</template>
-->

<script>
export default {
    name: 'Field',
    render(createElement) {
        if (!this.shouldRender) {
            return;
        }

        let props = this.field.props ? this.field.props : {};

        return createElement(this.field.component, {
            props: {
                field: this.field,
                model: this.model,
                modelId: this.modelId === 0 ? this.model.id : this.modelId,
                ...props
            },
            on: this.$listeners
        });
    },
    computed: {
        shouldRender() {
            if (!this.field.dependsOn) {
                return true;
            }
            return (
                this.field.dependsOn.value ==
                this.model[this.field.dependsOn.key]
            );
        }
    },
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
        this.formatRoutePrefix();
    },
    methods: {
        formatRoutePrefix() {
            // This allows Fields like Blocks to set individual Model id's that
            // differ from the id of the model that gets passed to the Field.
            // For e.g: In a Block the model of the Block would be passed but the
            // route Model id is not the id for the Block but for the Crud Model.
            let modelId = this.modelId ? this.modelId : this.model.id;
            let replace = '{id}';
            this.field._method = 'PUT';

            if (modelId === undefined) {
                this.field._method = 'POST';
                modelId = '';
                replace = '/{id}';
            }

            this.field.route_prefix = this.field.route_prefix.replace(
                replace,
                modelId
            );
        }
    }
};
</script>
