<template>
    <fj-form-item :field="field" :model="model">
        <b-form-checkbox v-model="selected" name="check-button" switch>
        </b-form-checkbox>

        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormBoolean',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true
        }
    },
    data() {
        return {
            selected: false
        };
    },
    beforeMount() {
        this.init();
        this.$bus.$on('modelLoaded', () => {
            this.init();
        });
    },
    methods: {
        init() {
            this.selected = this.model[`${this.field.id}Model`];
        }
    },
    watch: {
        selected(val) {
            this.model[`${this.field.id}Model`] = val;
            this.$emit('changed');
        }
    }
};
</script>

<style lang="css"></style>
