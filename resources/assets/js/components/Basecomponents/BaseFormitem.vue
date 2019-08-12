<template>
    <div class="pb-4 fjord-form">
        <h5 class="mb-0">
            <label :for="field.id">{{ field.title }}</label>
        </h5>
        <div class="input-group">
            <slot />
        </div>
        <div class="d-flex justify-content-between">
            <small class="form-text text-muted">{{ field.hint }}</small>
            <small class="form-text text-muted">
                <template v-if="field.max && value">{{ max }}</template>
            </small>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FormItem',
    props: {
        field: {
            type: [Object, Array],
            required: true
        },
        value: {
            type: String
        }
    },
    computed: {
        max() {
            return `${this.value.length} / ${this.field.max}`;
        }
    }
};
</script>

<style lang="css"></style>
