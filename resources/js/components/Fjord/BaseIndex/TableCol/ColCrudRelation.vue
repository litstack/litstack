<template>
    <div class="fj-col-relation">
        {{ item[relation][value] }}
        <a :href="`${baseURL}${relationLink}/${item[relation].id}/edit`">
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
            required: true,
            type: String
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
