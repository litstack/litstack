<template>
    <fj-form-item :field="field" :model="model">
        <template v-if="!readonly">
            <b-dropdown
                split
                variant="input"
                class="fj-icon-picker"
                v-bind:disabled="readonly"
            >
                <div slot="button-content" v-html="value" />
                <div class="icons">
                    <b-button
                        class="icon"
                        v-for="(icon, key) in field.icons"
                        :key="key"
                        v-html="icon"
                        @click="setIcon(icon)"
                        :variant="value == icon ? 'primary' : 'input'"
                    />
                </div>
            </b-dropdown>
        </template>
        <template v-else>
            <div
                class="form-control"
                style="display: inline-block;width:auto;flex:none;"
                readonly
                v-html="model[`${field.id}Model`]"
            ></div>
        </template>
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormIcon',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
            value: null
        };
    },
    methods: {
        setIcon(icon) {
            this.value = icon;
            this.model[`${this.field.id}Model`] = icon;
            this.$emit('changed');
        }
    },
    beforeMount() {
        this.value = this.model[`${this.field.id}Model`];
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-icon-picker {
    .dropdown-menu {
        width: calc(100px + 10.625rem);
        max-height: 300px;
        overflow-y: scroll;
        padding: $dropdown-item-padding-y;
    }
    .icons {
        .icon {
            margin: $dropdown-item-padding-y;
            width: calc(20px + #{2 * $btn-padding-x});
            height: calc(20px + #{2 * $btn-padding-x});
            text-align: center;
            font-size: 20px;
        }
    }
}
</style>
