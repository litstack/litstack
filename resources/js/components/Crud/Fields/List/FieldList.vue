<template>
    <fj-base-field :field="field" :model="model">
        <b-row class="w-100">
            <b-col cols="6">
                <nested-draggable :list="list" />
            </b-col>
            <b-col cols="6">
                <pre>{{ output }}</pre>
                <pre>{{ list }}</pre>
            </b-col>
        </b-row>
    </fj-base-field>
</template>

<script>
import nestedDraggable from './Nested';
export default {
    name: 'FieldList',
    components: {
        nestedDraggable
    },
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        value: {
            required: true
        }
    },
    data() {
        return {
            list: [
                {
                    id: 1,
                    title: 'task 1',
                    list: [
                        {
                            id: 2,
                            title: 'task 2',
                            list: []
                        }
                    ]
                },
                {
                    id: 3,
                    title: 'task 3',
                    list: [
                        {
                            id: 4,
                            title: 'task 4',
                            list: []
                        }
                    ]
                },
                {
                    id: 5,
                    title: 'task 5',
                    list: []
                }
            ]
        };
    },
    methods: {
        // Credits: https://github.com/MrPeak/flatten-tree
        flattenNodeGenerator(node, parent, index, settings, stack) {
            const { itemsKey, idKey } = settings;

            return list => {
                node = settings.initNode(node);
                node[idKey] = node[idKey] || settings.generateUniqueId();
                list.push(node);

                if (node[itemsKey]) {
                    for (let i = 0, len = node[itemsKey].length; i < len; i++) {
                        stack.push(
                            this.flattenNodeGenerator(
                                node[itemsKey][i],
                                node,
                                i,
                                settings,
                                stack
                            )
                        );
                    }
                }

                if (parent && parent[itemsKey]) {
                    // Records children' id
                    parent[itemsKey][index] = node[idKey];
                    node.parent = parent[idKey];
                }

                return list;
            };
        },
        flatten(tree, options) {
            let list = [];
            const stack = [];
            const _tree = _.cloneDeep(tree);
            const settings = {
                initNode: options.initNode || (node => node),
                itemsKey: options.itemsKey || 'children',
                idKey: options.idKey || 'id',
                uniqueIdStart: options.uniqueIdStart || 1,
                generateUniqueId:
                    options.generateUniqueId || (() => settings.uniqueIdStart++)
            };

            if (Array.isArray(_tree) && _tree.length) {
                // Object Array
                for (let i = 0, len = _tree.length; i < len; i++) {
                    stack.push(
                        this.flattenNodeGenerator(
                            _tree[i],
                            'root', // placeholder
                            i,
                            settings,
                            stack
                        )
                    );
                }
            } else {
                // One object tree
                stack.push(
                    this.flattenNodeGenerator(_tree, 'root', 0, settings, stack)
                );
            }

            while (stack.length) {
                list = stack.shift()(list);
            }

            return list;
        }
    },
    computed: {
        output() {
            return this.flatten(this.list, {
                idKey: 'id',
                itemsKey: 'list'
            });
        }
    }
};
</script>
