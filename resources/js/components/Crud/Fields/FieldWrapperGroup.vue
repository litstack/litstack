<!--
<template>
    <fj-col :width="width">
        <b-row>
            <slot />
        </b-row>
    </fj-col>
</template>
-->

<script>
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

        let vm = createElement(
            'fj-col',
            {
                props: { width: this.width }
            },
            [createElement('b-row', this.$slots.default)]
        );

        //vm.$forceUpdate();

        return vm;
    },

    props: {
        title: {
            type: String
        },
        width: {
            type: Number,
            default() {
                return 12;
            }
        },
        model: {
            required: true
        },
        dependsOn: {
            type: Object,
            default() {
                return {};
            }
        }
    },
    computed: {
        /**
         * Determines if the component should be rendered.
         *
         * @return {Boolean}
         */
        shouldRender() {
            if (!this.dependsOn) {
                return true;
            }
            return this.dependsOn.value == this.dependencyValue;
        }
    },
    data() {
        return {
            dependencyValue: null
        };
    },
    beforeMount() {
        this.detectDepencyChanges();
        Fjord.bus.$on('fieldChanged', this.detectDepencyChanges);
    },
    methods: {
        /**
         * Detect depency changes.
         */
        detectDepencyChanges() {
            if (!this.dependsOn) {
                return true;
            }
            if (this.model[this.dependsOn.key] == this.dependencyValue) {
                return;
            }
            this.dependencyValue = this.model[this.dependsOn.key];
        }
    }
};
</script>
