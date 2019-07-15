<template>
    <BaseFormitem :field="item">
        <input
            class="form-control"
            :value="value"
            @input="$emit('input', $event.target.value)"
            :placeholder="item.placeholder"
        />
        <slot />
    </BaseFormitem>
</template>

<script>
export default {
    name: 'FormInput',
    props: ['item', 'value']
};
</script>

<style lang="css"></style>
