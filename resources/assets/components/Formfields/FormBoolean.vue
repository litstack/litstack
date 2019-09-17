<template>
    <fj-form-item :field="field" :model="model">

        <b-form-checkbox v-model="selected" name="check-button" switch>

        </b-form-checkbox>
        <!--
        <button
            @click.prevent="toggleValue"
            class="btn btn-lg">
            <i
                class="fas fa-toggle-on text-success"
                v-if="model[`${field.id}Model`]"/>
            <i
                class="fas fa-toggle-off text-danger"
                v-else/>
        </button>
        -->
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
            required: true,
        }
    },
    data() {
        return {
            selected: false,
        }

    },
    beforeMount() {
        this.selected = this.model[`${this.field.id}Model`]
    },
    watch: {
        selected(val) {
            console.log('SEL', val)
            this.model[`${this.field.id}Model`] = val
            this.$emit('changed')
        }
    },
    methods: {
        toggleValue() {
            console.log('LSLDKSKLS')
            // = !this.model[`${this.field.id}Model`]

        }
    }
};
</script>

<style lang="css"></style>
