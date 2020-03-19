<template>
    <b-nav tabs class="fj-nav-horizontal">
        <template v-for="(item, index) in items">
            <template v-if="isGroup(item)">
                <template v-for="(children, i) in item">
                    <b-nav-item
                        v-if="hasLink(children)"
                        :href="link(children)"
                        :active="isActive(children)"
                    >
                        <span
                            v-html="children.icon"
                            class="fj-nav-item_icon"
                        ></span>
                        {{ children.title }}
                    </b-nav-item>
                    <b-nav-item-dropdown v-if="hasChildren(children)">
                        <template v-slot:button-content>
                            <span
                                v-html="children.icon"
                                class="fj-nav-item_icon"
                            ></span>
                            {{ children.title }}
                        </template>
                        <b-dropdown-item
                            v-for="(child, n) in children.children"
                            :href="link(child)"
                            :key="n"
                        >
                            <span
                                v-html="child.icon"
                                class="fj-nav-item_icon"
                            ></span>
                            {{ child.title }}
                        </b-dropdown-item>
                    </b-nav-item-dropdown>
                </template>
            </template>
            <b-nav-item v-if="hasLink(item)" :href="link(item)">
                <span v-html="item.icon" class="fj-nav-item_icon"></span>
                {{ item.title }}
            </b-nav-item>
            <b-nav-item-dropdown v-if="hasChildren(item)" :text="item.title">
                <b-dropdown-item
                    v-for="(child, n) in item.children"
                    :key="n"
                    :href="link(child)"
                >
                    <span v-html="child.icon" class="fj-nav-item_icon"></span>
                    {{ child.title }}
                </b-dropdown-item>
            </b-nav-item-dropdown>
        </template>
    </b-nav>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'NavHorizontal',
    props: {
        items: {
            type: Array
        }
    },
    data() {
        return {
            visible: false,
            active: false
        };
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        link(item) {
            return this.hasChildren(item) ? '#' : `${this.baseURL}${item.link}`;
        },
        hasLink(item) {
            return item.hasOwnProperty('link');
        },
        hasChildren(item) {
            return item.hasOwnProperty('children');
        },
        hasComponent(item) {
            return item.hasOwnProperty('component');
        },
        isGroup(item) {
            return Array.isArray(item);
        },
        isString(item) {
            return typeof item === 'string';
        },
        isActive(item) {
            // make children visible
            const link = window.location.pathname.replace(this.baseURL, '');

            // set active state
            if (this.hasLink(item)) {
                if (link.includes(item.link)) {
                    return true;
                }
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.fj-nav-horizontal {
    margin-bottom: -1px;
}
</style>
