<template>
    <div
        class="tab-pane fade"
        :class="{ 'show active': isActive }"
        :id="id"
        role="tabpanel"
        :aria-labelledby="`${id}-tab`"
    >
        <slot />
    </div>
</template>

<script>
export default {
    name: 'BaseTab',
    props: {
        name: {
            type: String
        }
    },
    data() {
        return {
            isActive: false
        };
    },
    computed: {
        header() {
            return this.name;
        },
        id() {
            return this.name.toLowerCase().replace(/ /g, '-');
        }
    }
};
</script>

<style lang="css" scoped></style>
