<template>
    <div class="row">
        <div class="col-12">
            <slot>
                <a
                    v-if="route"
                    :href="`/admin/${route}`"
                    class="btn btn-sm btn-primary add-button"
                >
                    <i class="fas fa-angle-left"></i> zurück
                </a>

                <slot name="actions"/>
            </slot>
        </div>

    </div>
</template>

<script>
let props = {
    route: {
        type: String
    }
}

export default {
    props
}
</script>

<style lang="css" scoped>
</style>
