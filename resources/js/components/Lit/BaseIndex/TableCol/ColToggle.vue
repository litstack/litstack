<template>
    <b-checkbox v-model="value" switch @change="update" />
</template>

<script>
export default {
    name: 'ColToggle',
    props: {
        item: {
            required: true,
            type: Object,
        },
        local_key: {
            type: String,
            required: true,
        },
        routePrefix: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            value: false,
        };
    },
    beforeMount() {
        this.value = this.item[this.local_key];
    },
    methods: {
        async update(val) {
            let response = await axios.put(
                this.routePrefix.replace('{id}', this.item.id),
                {
                    payload: {
                        [this.local_key]: val,
                    },
                }
            );
            this.$bvToast.toast(this.__('base.saved'), { variant: 'success' });
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-col-image {
    border-radius: 3px;

    img {
        border-radius: 3px;
    }

    &.empty {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: $font-size-lg;
        color: $gray-500;
        background: $gray-300;
    }
}
</style>
