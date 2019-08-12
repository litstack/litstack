<template>
    <div>
        <slot :model="model"/>
    </div>
</template>

<script>
export default {
    name: 'TestFormField',
    props: {
        model: {
            required: true
        }
    },
    watch: {
        model(val) {
            this.$emit('input', val)
        }
    }
}
</script>
