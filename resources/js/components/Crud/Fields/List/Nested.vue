<template>
    <draggable
        class="fj-list"
        handle=".fj-list__dragbar"
        tag="ul"
        :list="children"
        :group="{ name: 'g1' }"
    >
        <li v-for="el in children" :key="el.id" class="">
            <div class="fj-list-entry fj-block mb-2">
                <i
                    class="fas fa-grip-horizontal text-secondary fj-list__dragbar"
                ></i>
                <div class="pl-2 d-inline-block">
                    {{ el.title }}
                </div>
            </div>
            <nested-draggable :children="el.children" />
        </li>
    </draggable>
</template>
<script>
import draggable from 'vuedraggable';
export default {
    name: 'nested-draggable',
    props: {
        children: {
            required: true,
            type: Array
        }
    },
    components: {
        draggable
    }
};
</script>

<style lang="scss" scoped>
ul.fj-list {
    width: 100%;
    padding: 0;
    list-style-type: none;
    ul.fj-list {
        padding-left: 2rem;
    }
}
.fj-list-entry {
    padding: 1rem;
}
.fj-list__dragbar {
    cursor: grab;
}
</style>
