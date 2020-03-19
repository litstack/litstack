<template>
    <fj-form-item :field="field" :model="model">
        <b-checkbox-group
            :checked="value"
            :options="options"
            @input="changed"
        />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormCheckboxes',
    props: {
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
            value: null
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
            this.value = this.model[`${this.field.id}Model`];
        },
        changed(val) {
            if (this.model.isFjordModel()) {
                this.model[`${this.field.id}Model`] = JSON.stringify(val);
            } else {
                this.model[`${this.field.id}Model`] = val;
            }

            this.$emit('changed');
        }
    },
    computed: {
        options() {
            return this.field.options;
        }
    }
};
</script>

<style lang="css"></style>
