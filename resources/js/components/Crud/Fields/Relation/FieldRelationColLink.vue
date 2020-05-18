<template>
    <b-button
        variant="transparent"
        size="sm"
        class="btn-square"
        v-b-tooltip.hover
        :title="$t('crud.fields.relation.goto')"
        :href="link"
    >
        <fa-icon icon="eye" />
    </b-button>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'FieldRelationColLink',
    props: {
        item: {
            type: Object,
            required: true
        },
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        ...mapGetters(['baseURL']),
        link() {
            return `${this.baseURL}${this.field.config.route_prefix}/${this.item.id}/edit`;
        }
    }
};
</script>
