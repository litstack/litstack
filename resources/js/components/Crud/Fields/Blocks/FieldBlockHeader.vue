<template>
    <div class="d-flex justify-content-between">
        <div>
            HEader
        </div>

        <div>
            <b-button
                variant="outline-secondary"
                size="sm"
                @click="$emit('toggleExpand')"
            >
                <fa-icon :icon="expand ? 'angle-up' : 'angle-down'" />
            </b-button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FieldBlockHeader',
    props: {
        expand: {
            required: true,
            type: Boolean
        }
    }
};
</script>
