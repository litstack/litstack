<template>
    <span>
        {{
            this.$te(`permissions.groups.${group}`)
                ? this.__(`permissions.group.${group}`).capitalize()
                : group.capitalize()
        }}
    </span>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    name: 'IndexGroupName',
    props: {
        item: {
            required: true,
            type: [Object, Array],
        },
        col: {
            type: Object,
        },
    },
    computed: {
        group() {
            return this.item.name
                .split(' ')
                .slice(1)
                .join(' ');
        },
    },
};
</script>
