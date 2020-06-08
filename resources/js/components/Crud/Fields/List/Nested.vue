<template>
    <draggable class="dragArea" tag="ul" :list="list" :group="{ name: 'g1' }">
        <li v-for="el in list" :key="el.name" class="">
            <div class="fj-list-entry fj-block mb-2">
                <i class="fas fa-grip-horizontal text-secondary"></i>
                <div class="pl-2 d-inline-block">
                    {{ el.name }}
                </div>
            </div>
            <nested-draggable :list="el.list" />
        </li>
    </draggable>
</template>
<script>
import draggable from 'vuedraggable';
export default {
    props: {
        list: {
            required: true,
            type: Array
        }
    },
    components: {
        draggable
    },
    name: 'nested-draggable'
};
</script>

<style lang="scss" scoped>
ul.dragArea {
    padding: 0;
    list-style-type: none;

    ul.dragArea {
        padding-left: 2rem;
    }
}
.fj-list-entry {
    padding: 1rem;
}
</style>
