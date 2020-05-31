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

    /**
     * Rendering the fj-field component.
     *
     * @param {Function} createElement
     * @return {Object}
     */
    render(createElement) {
        if (!this.shouldRender) {
            return;
        }

        let props = this.field.props ? this.field.props : {};

        let vm = createElement(this.field.component, {
            props: {
                field: this.field,
                model: this.model,
                modelId: this.modelId === 0 ? this.model.id : this.modelId,
                ...props
            },
            on: this.$listeners
        });

        return vm;
    },
    computed: {
        /**
         * Determines if the component should be rendered.
         *
         * @return {Boolean}
         */
        shouldRender() {
            if (!this.field.dependsOn) {
                return true;
            }
            return this.field.dependsOn.value == this.dependencyValue;
        }
    },
    props: {
        model: {
            type: Object,
            required: true
        },
        modelId: {
            type: [Number, String],
            default() {
                return null;
            }
        },
        field: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            dependencyValue: null
        };
    },
    beforeMount() {
        this.formatRoutePrefix();

        this.detectDepencyChanges();
        Fjord.bus.$on('fieldChanged', this.detectDepencyChanges);
    },
    methods: {
        /**
         * Detect depency changes.
         */
        detectDepencyChanges() {
            if (!this.field.dependsOn) {
                return true;
            }
            if (this.model[this.field.dependsOn.key] == this.dependencyValue) {
                return;
            }
            this.dependencyValue = this.model[this.field.dependsOn.key];
        },

        /**
         * Format field route_prefix.
         */
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
