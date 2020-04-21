<template>
    <div class="fj-col-relation" v-if="relation">
        {{ relation[value] }}
        <a :href="`${baseURL}${relationLink}/${relation.id}/edit`">
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
        relation: {
            type: [Object, Boolean]
        },
        value: {
            required: true,
            type: String
        },
        link: {
            required: false,
            type: String
        }
    },
    computed: {
        ...mapGetters(['baseURL']),
        relationLink() {
            if (this.link) {
                return this.link;
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
