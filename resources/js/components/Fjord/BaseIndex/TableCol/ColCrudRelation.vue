<template>
    <div class="fj-col-relation" v-if="related">
        {{ related[value] }}
        <a :href="`${Fjord.baseURL}${relationLink}/${related.id}/edit`">
            <fa-icon icon="external-link-alt" />
        </a>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'ColCrudRelation',
    props: {
        item: {
            required: true,
            type: Object
        },
        related: {
            type: [Object, Boolean]
        },
        value: {
            required: true,
            type: String
        },
        route_prefix: {
            required: false,
            type: String
        }
    },
    computed: {
        relationLink() {
            if (this.route_prefix) {
                return this.route_prefix;
            }
            return this.relation;
        }
    }
};
</script>

<style lang="scss">
.fj-col-relation {
    > a {
        opacity: 0;
        cursor: pointer !important;
    }

    &:hover {
        > a {
            opacity: 1;
        }
    }
}
</style>
