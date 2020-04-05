<template>
    <fj-form-item :field="field" :model="model">
        <template v-if="!field.readonly">
            <b-select
                :value="model[`${field.id}Model`]"
                :options="options"
                @input="changed"
            />
        </template>
        <template v-else>
            <b-input class="form-control" :value="value" type="text" readonly />
        </template>
        <slot />
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormSelect',
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
    methods: {
        changed(val) {
            this.model[`${this.field.id}Model`] = val;
            this.$emit('changed');
        },
        handle(val) {
            //t
        }
    },
    computed: {
        options() {
            return this.field.options;
        },
        value() {
            return this.options[this.model[`${this.field.id}Model`]];
        }
    }
};
</script>

<style lang="css"></style>
