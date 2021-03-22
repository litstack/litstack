<template>
    <b-list-group flush>
        <b-list-group-item
            v-if="isTitle"
            class="lit-nav-title"
            v-html="item.title"
        />
        <template v-else-if="isSection">
            <lit-nav-item
                v-for="(i, index) in item"
                :item="i"
                :key="index"
                v-if="!(i instanceof String) && i !== null"
            />
            <hr class="lit-navitem-divider" />
        </template>
        <template v-else>
            <component :is="item.component" v-if="hasComponent" />
            <b-list-group-item
                size="sm"
                :active="active"
                :href="link"
                @click="visible = !visible"
                class="d-flex justify-content-between align-items-center"
                v-else
            >
                <div class="d-flex w-100">
                    <span v-html="item.icon" class="lit-nav-item_icon"/>
                    <span v-html="item.title" class="ml-1" style="flex-grow: 1;"/>
                    <span v-if="item.badge">
                        <span v-html="item.badge" :class="`badge badge-${item.badge_variant || 'primary'}`"/>
                    </span>
                </div>
                <div
                    class="lit-navigation-hasChildren lit-nav-item_icon lit-nav-toggle"
                    :class="{ active: visible }"
                    v-if="hasChildren"
                >
                    <lit-fa-icon
                        icon="chevron-right"
                        class="float-right text-sm"
                    />
                </div>
            </b-list-group-item>
            <b-collapse v-if="hasChildren" v-model="visible">
                <div class="lit-navigation-spacer">
                    <lit-nav-item
                        v-for="(item, index) in item.children"
                        :item="item"
                        :key="index"
                    />
                </div>
            </b-collapse>
        </template>
    </b-list-group>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'NavItem',
    props: {
        item: {
            type: [Array, Object, String],
        },
    },
    data() {
        return {
            visible: false,
            active: false,
        };
    },
    mounted() {
        // make children visible
        if (this.hasChildren) {
            for (let i = 0; i < this.item.children.length; i++) {
                if (this.isActive(this.item.children[i])) {
                    this.visible = true;
                }
            }
        }

        // set active state
        if (this.hasLink) {
            this.active = this.isActive(this.item);
        }
    },
    methods: {
        isActive(item) {
            const currentLink = window.location.pathname;
            if (item.link == Lit.baseURL) {
                return currentLink == item.link;
            }
            if (Lit.baseURL.includes(currentLink)) {
                return currentLink.includes(item.link);
            }
            return currentLink.includes(item.link);
        },
    },
    computed: {
        link() {
            return this.hasChildren ? '#' : this.item.link;
        },
        hasLink() {
            return this.item.hasOwnProperty('link');
        },
        hasChildren() {
            return this.item.hasOwnProperty('children');
        },
        hasComponent() {
            return this.item.hasOwnProperty('component');
        },
        isSection() {
            return Array.isArray(this.item) || !('type' in this.item);
        },
        isTitle() {
            return this.item.type === 'title';
        },
    },
};
</script>

<style lang="scss">
@import '@lit-sass/_variables';

.lit-navigation {
    .lit-nav-title {
        text-transform: uppercase;
        color: $nav-title-color;
        letter-spacing: $nav-title-letter-spacing;
        font-size: $nav-title-font-size;
        border-bottom: none;
    }

    .list-group-item {
        padding-right: 1rem;
        &.active {
            .lit-nav-item_icon {
                color: $nav-item-active-color !important;
            }
        }
    }

    .lit-nav-item_icon {
        color: $nav-item-icon-color;
        margin-left: -4px;
        margin-right: 10px;
        display: inline-block;
        width: 20px;
        text-align: center;
        &.lit-nav-toggle {
            width: auto;
        }
    }
    .lit-navigation .list-group-item {
        border: none;
    }
    .lit-navitem-divider {
        margin: 0 0 $nav-padding-top 0;
        border-width: 0;
    }
    .lit-navigation-hasChildren {
        font-size: 0.75rem;
        transform: rotate(0);
        transition: 0.2s all ease;
        &.active {
            transform: rotate(90deg);
        }
    }
    &-spacer {
        padding: $list-group-item-padding-y 0;
        box-shadow: inset 0px 11px 8px -6px $gray-300,
            inset 0px -11px 8px -6px $gray-300;
    }
}
</style>
