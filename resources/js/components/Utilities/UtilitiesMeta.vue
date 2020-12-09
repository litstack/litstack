<template>
    <lit-col :width="12">
        <div class="meta-preview">
            <div class="google-url">
                {{ location.host }}
                <span style="color: #5f6368;">› ...</span>
            </div>
            <div class="google-title">{{ model.meta_title }}</div>
            <div class="google-description">
                <span>{{ model.meta_description }}</span>
            </div>
        </div>
    </lit-col>
</template>

<script>
export default {
    name: 'UtilitiesMeta',
    props: {
        model: {
            required: true,
            type: Object,
        },
    },
    computed: {
        location() {
            return window.location;
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';
.meta-preview {
    border: 1px solid $border-color;
    padding: 1rem;
    border-radius: $input-border-radius;
}

.google-title {
    color: #1a0dab;
    font-family: arial, sans-serif;
    font-size: 20px;
    font-weight: 400;
    height: auto;
    padding-top: 4px;
    font-size: 20px;
    line-height: 1.3;
    display: inline-block;
    line-height: 1.3;
    margin-bottom: 3px;

    :hover {
        text-decoration: underline;
    }
}

.google-url {
    color: #202124;
    display: block;
    font-family: arial, sans-serif;
    font-style: normal;
    font-weight: normal;
    height: auto;
    line-height: 1.3;
}

.google-description {
    // .rc .s
    position: relative;
    color: #545454;
    max-width: 48em;
    min-height: 50px;
    line-height: 1.58;

    span {
        // .st
        line-height: 1.58;
        color: #222;
        font-family: arial, sans-serif;
        font-size: 14px;
    }

    &::after {
        position: absolute;
        display: block;
        content: '';
        bottom: 2px;
        right: 0;
        width: 200px;
        height: 20px;
        background-color: rgba(255, 50, 50, 0.5);
    }
}
</style>
