<template>
    <lit-base-field :field="field" :model="model">
        <template v-if="!field.readonly">
            <b-dropdown
                split
                variant="input"
                class="lit-icon-picker"
                v-bind:disabled="field.readonly"
            >
                <div slot="button-content" v-html="value" />
                <div class="lit-icon-search" v-if="field.search">
                    <b-input-group size="sm">
                        <b-input
                            v-model="search"
                            :placeholder="__('base.search').capitalize()"
                        />
                        <template v-slot:prepend>
                            <b-input-group-text>
                                <lit-fa-icon icon="search" />
                            </b-input-group-text>
                        </template>
                    </b-input-group>
                </div>
                <div
                    class="lit-icons-wrapper"
                    :style="field.search ? '' : 'top: 0;'"
                >
                    <div class="icons">
                        <b-button
                            class="icon"
                            v-for="(icon, key) in searched"
                            :key="key"
                            v-html="icon"
                            @click="setIcon(icon)"
                            :variant="value == icon ? 'primary' : 'input'"
                        />
                    </div>
                </div>
            </b-dropdown>
        </template>
        <template v-else>
            <div
                class="form-control"
                style="display: inline-block; width: auto; flex: none;"
                readonly
                v-html="value"
            ></div>
        </template>
    </lit-base-field>
</template>

<script>
export default {
    name: 'FieldIcon',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
    },
    data() {
        return {
            search: '',
            searched: [],
        };
    },
    watch: {
        search(val) {
            console.log(val);
            this.searched = _.filter(this.field.icons, (icon) => {
                return icon.includes(val);
            });
        },
    },
    beforeMount() {
        this.searched = this.field.icons;
    },
    methods: {
        setIcon(icon) {
            this.$emit('input', icon);
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-icon-picker {
    position: relative;
    i {
        color: $secondary;
    }
    .lit-icon-search {
        margin-bottom: $field-icon-spacer;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        padding: $field-icon-spacer;
        background: $white;
        z-index: 1;
        border-top-left-radius: $border-radius;
        border-top-right-radius: $border-radius;
    }
    .dropdown-menu {
        width: calc(100px + 10.625rem);
        height: 300px;
        position: relative;
    }
    .lit-icons-wrapper {
        position: absolute;
        top: 3.25rem;
        left: 0;
        right: 0;
        bottom: 0;
        padding: $field-icon-spacer;
        overflow-y: scroll;
    }
    .icons {
        display: grid;
        grid-template-columns: repeat(
            auto-fill,
            minmax($field-icon-btn-width, 1fr)
        );
        grid-auto-rows: 1fr;
        grid-gap: $field-icon-spacer;

        .icon:first-child {
            grid-row: 1 / 1;
            grid-column: 1 / 1;
        }
        &::before {
            content: '';
            width: 0;
            padding-bottom: 100%;
            grid-row: 1 / 1;
            grid-column: 1 / 1;
        }
        .icon {
            padding: 0 !important;
            width: 100%;
            height: 100%;
            text-align: center;
            font-size: $font-size-base;
            display: flex;
            justify-content: center;
            align-items: center;
            color: $secondary;
            &.btn-primary {
                i {
                    color: white;
                }
            }
        }
    }
}
</style>
