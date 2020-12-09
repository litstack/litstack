<template>
    <b-button
        variant="transparent"
        size="sm"
        class="btn-square"
        v-b-tooltip.hover
        :title="__('crud.fields.relation.goto')"
        :href="link"
    >
        <lit-fa-icon icon="eye" />
    </b-button>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldRelationColLink',
    props: {
        item: {
            type: Object,
            required: true,
        },
        field: {
            type: Object,
            required: true,
        },
    },
    computed: {
        ...mapGetters(['baseURL']),
        link() {
            return `${this.baseURL}${this.field.related_route_prefix}/${this.item.id}`;
        },
    },
};
</script>
