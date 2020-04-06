<template>
    <b-list-group flush>
        <template v-if="isString">
            <small class="text-secondary pl-3 pt-2 pb-1">{{ this.item }}</small>
        </template>
        <template v-else-if="isGroup">
            <fj-nav-item v-for="(i, index) in item" :item="i" :key="index" />
            <hr class="fj-navitem-devider" />
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
                    class="fj-navigation-hasChildren"
                    :class="{ active: visible }"
                    v-if="hasChildren"
                >
                    <fa-icon icon="chevron-right" class="float-right text-sm" />
                </div>
            </b-list-group-item>
            <b-collapse v-if="hasChildren" v-model="visible">
                <fj-nav-item
                    v-for="(item, index) in item.children"
                    :item="item"
                    :key="index"
                />
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
        const link = window.location.pathname;

        if (this.hasChildren) {
            for (let i = 0; i < this.item.children.length; i++) {
                const element = this.item.children[i];

                if (link.includes(element.link)) {
                    this.visible = true;
                }
            }
        }

        // set active state
        if (this.hasLink) {
            if (link.includes(this.item.link)) {
                this.active = true;
            }
        }
    },
    computed: {
        ...mapGetters(['baseURL']),
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
        isGroup() {
            return Array.isArray(this.item);
        },
        isString() {
            return typeof this.item === 'string';
        },
    },
};
</script>

<style lang="scss">
.fj-nav-item_icon {
    margin-left: -4px;
    margin-right: 10px;
    opacity: 0.9;
}
.fj-navigation .list-group-item {
    border: none;
}
.fj-navitem-devider {
    margin: 8px 0;
}
.fj-navigation-hasChildren {
    transform: rotate(0);
    transition: 0.2s all ease;
    &.active {
        transform: rotate(90deg);
    }
}
</style>
