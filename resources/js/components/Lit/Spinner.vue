<template>
    <div
        :class="`lds-ripple lds-ripple-${variant} d-flex justify-content-center align-items-center`"
    >
        <div></div>
        <div></div>
    </div>
</template>

<script>
export default {
    name: 'Spinner',
    props: {
        variant: {
            type: String,
            default() {
                return 'primary';
            },
        },
    },
};
</script>
