<template>
    <tr class="lit-table-row-no-hover">
        <td :colspan="colspan" class="text-center text-secondary">
            <div class="my-2">
                <lit-fa-icon icon="robot" size="5x" />
            </div>
            <div>
                {{
                    trans_choice('base.no_item_found', 2, { item: namePlural })
                }}
            </div>
        </td>
    </tr>
</template>

<script>
export default {
    name: 'BaseIndexTableEmpty',
    props: {
        colspan: {
            required: true,
        },
        namePlural: {
            type: String,
            required: true,
        },
    },
};
</script>
