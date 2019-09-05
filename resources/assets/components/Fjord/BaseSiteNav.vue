<template>
    <div class="col-12 mb-4">
        <b-row>
            <b-col cols="9" class="d-flex justify-content-between">
                <div class="text-secondary fj-site-nav-actions">
                    <slot name="actions"/>
                </div>


                <slot>
                    <a
                        v-if="route"
                        :href="`${baseURL}${route}`"
                        class="btn btn-sm btn-primary">
                        <i class="fas fa-angle-left"></i> back
                    </a>

                </slot>
            </b-col>
        </b-row>

    </div>
</template>

<script>
import { mapGetters } from 'vuex';
let props = {
    route: {
        type: String
    }
};

export default {
    name: 'SiteNav',
    computed: {
        ...mapGetters(['baseURL'])
    },
    props
};
</script>

<style lang="scss">
.fj-site-nav-actions{
    .action{
        cursor: pointer;
        &:hover{
            color: black;
        }
    }
}
</style>
