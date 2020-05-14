<template>
    <b-list-group flush>
        <b-list-group-item v-if="isTitle" class="fj-nav-title">
            {{ this.item.title }}
        </b-list-group-item>
        <template v-else-if="isSection">
            <fj-nav-item
                v-for="(i, index) in item"
                :item="i"
                :key="index"
                v-if="!(i instanceof String) && i !== null"
            />
            <hr class="fj-navitem-divider" />
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
                <div>
                    <span v-html="item.icon" class="fj-nav-item_icon"></span>
                    {{ item.title }}
                </div>
                <div
                    class="fj-navigation-hasChildren fj-nav-item_icon fj-nav-toggle"
                    :class="{ active: visible }"
                    v-if="hasChildren"
                >
                    <fa-icon icon="chevron-right" class="float-right text-sm" />
                </div>
            </b-list-group-item>
            <b-collapse v-if="hasChildren" v-model="visible">
                <div class="fj-navigation-spacer">
                    <fj-nav-item
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
            type: [Array, Object, String]
        }
    },
    data() {
        return {
            visible: false,
            active: false
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
            const link = window.location.pathname;
            if (Fjord.baseURL.includes(link)) {
                return link.includes(item.link);
            }
            return link.includes(item.link);
        }
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
            return Array.isArray(this.item);
        },
        isTitle() {
            return this.item.type === 'title';
        }
    }
};
</script>

<style lang="scss">
@import '@fj-sass/_variables';

.fj-navigation {
    .fj-nav-title {
        text-transform: uppercase;
        color: $nav-title-color;
        letter-spacing: $nav-title-letter-spacing;
        font-size: $nav-title-font-size;
        border-bottom: none;
    }

    .list-group-item {
        &.active {
            .fj-nav-item_icon {
                color: $nav-item-active-color !important;
            }
        }
    }

    .fj-nav-item_icon {
        color: $nav-item-icon-color;
        margin-left: -4px;
        margin-right: 10px;
        display: inline-block;
        width: 20px;
        text-align: center;
        &.fj-nav-toggle {
            width: auto;
        }
    }
    .fj-navigation .list-group-item {
        border: none;
    }
    .fj-navitem-divider {
        margin: 0 0 $nav-padding-top 0;
        border-width: 0;
    }
    .fj-navigation-hasChildren {
        transform: rotate(0);
        transition: 0.2s all ease;
        &.active {
            transform: rotate(90deg);
        }
    }
    &-spacer {
        padding: $list-group-item-padding-y 0;
        background: linear-gradient(
            180deg,
            rgba(235, 235, 235, 0.5) 0%,
            rgba(0, 0, 0, 0) 10px,
            rgba(0, 0, 0, 0) calc(100% - 10px),
            rgba(235, 235, 235, 0.5) 100%
        );
    }
}
</style>
