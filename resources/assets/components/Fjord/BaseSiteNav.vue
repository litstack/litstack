<template>
    <div class="row">
        <div class="col-12 d-flex justify-content-end">
            <slot>
                <a
                    v-if="route"
                    :href="`${baseURL}${route}`"
                    class="btn btn-sm btn-primary add-button"
                >
                    <i class="fas fa-angle-left"></i> back
                </a>

                <slot name="actions" />
            </slot>
        </div>
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

<style lang="css"></style>
