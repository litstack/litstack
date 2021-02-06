<!--
<template>
    <lit-col :width="width">
        <b-row>
            <slot />
        </b-row>
    </lit-col>
</template>
-->

<script>
import dependencyMethods from './dependecy_methods';

export default {
    name: 'FieldWrapperGroup',

    /**
     * Rendering the component.
     *
     * @param {Function} createElement
     * @return {Object}
     */
    render(createElement) {
        if (!this.shouldRender) {
            return;
        }

        return createElement(
            'lit-col',
            {
                props: { width: this.width },
            },
            [createElement('b-row', this.$slots.default)]
        );
    },

    props: {
        title: {
            type: String,
        },
        width: {
            type: Number,
            default() {
                return 12;
            },
        },
        model: {
            required: true,
        },
        dependencies: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    computed: {
        /**
         * Determines if the component should be rendered.
         *
         * @return {Boolean}
         */
        shouldRender() {
            if (!this.dependencies) {
                return true;
            }

            return this.fulfillsConditions;
        },
    },
    data() {
        return {
            /**
             * Determines if the field fulfills conditions.
             */
            fulfillsConditions: true,
        };
    },
    beforeMount() {
        // Render dependency stuff.
        this.resolveDependecies(this.dependencies);
        Lit.bus.$on('fieldChanged', () =>
            this.resolveDependecies(this.dependencies)
        );
        Lit.bus.$on('reloaded', () => {
            this.resolveDependecies(this.dependencies);
        });
    },
    methods: {
        ...dependencyMethods,
    },
};
</script>
