<template>
    <b-input-group
        v-if="selectedItems.length > 0"
        :size="'sm'"
        class="mt-3"
    >
        <b-input-group-prepend is-text>
            <strong>
                {{ selectedItems.length }}
                {{
                    selectedItems.length == 1
                        ? ' Item'
                        : ' Items'
                }}
                {{
                    selectedItems.length == items.length
                        ? ' (all)'
                        : ''
                }}
                selected
            </strong>
        </b-input-group-prepend>

        <template v-slot:append>
            <b-dropdown
                text="Actions"
                class="btn-brl-none"
                variant="outline-secondary"
            >
                <slot name="actions" />
            </b-dropdown>
        </template>
    </b-input-group>
</template>

<script>
export default {
    name: 'SelectedItemsActions',
    props: {
        items: {
            type: Array,
            required: true
        },
        selectedItems: {
            type: Array,
            required: true
        },
    }
}
</script>
