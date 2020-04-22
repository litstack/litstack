<template>
    <fj-base-container>
        <fj-base-header :title="title" />
    </fj-base-container>
</template>

<script>
export default {
    name: 'Example',
    props: {
        title: {
            required: true,
            type: String
        }
    }
};
</script>
