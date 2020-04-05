<template>
    <fj-base-container class="flex-center position-ref full-height">
        <div class="code">
            {{ code }}
        </div>
        <div class="message" style="padding: 10px;">
            {{ message }}
        </div>
    </fj-base-container>
</template>
<script>
export default {
    name: 'BaseError',
    props: {
        code: {
            type: String,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
    },
};
</script>
<style scoped>
.fjord-container {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.code {
    font-size: 26px;
    padding: 0 15px 0 15px;
    text-align: center;
    border-right: 2px solid;
}
.message {
    font-size: 18px;
    text-align: center;
}
</style>
