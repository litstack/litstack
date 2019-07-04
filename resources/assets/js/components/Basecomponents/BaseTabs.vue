<template>
    <div class="aw-tabs">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item" v-for="(tab, i) in tabs">
                <a
                    class="nav-link"
                    :class="{ active: i == 0 }"
                    :id="`${tab.id}-tab`"
                    data-toggle="tab"
                    :href="`#${tab.id}`"
                    role="tab"
                    :aria-controls="tab.id"
                    aria-selected="true"
                    >{{ tab.header }}</a
                >
            </li>
        </ul>
        <div class="tab-content">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    name: 'BaseTabs',
    data() {
        return {
            tabs: null
        };
    },
    created() {
        this.tabs = this.$children;
    },
    mounted() {
        if (this.tabs.length) {
            // Set first tab active
            this.tabs[0].isActive = true;
        }
    }
};
</script>

<style lang="scss" scoped></style>
