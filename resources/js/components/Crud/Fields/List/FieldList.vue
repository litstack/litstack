<template>
    <fj-base-field :field="field" :model="model">
        <nested-draggable :children="list" />
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
        }
    },
    data() {
        return {
            list: [],
            input: [
                {
                    id: 1,
                    title: 'Item 1',
                    order_column: 2,
                    parent_id: 0
                },
                {
                    id: 2,
                    title: 'Item 2',
                    order_column: 1,
                    parent_id: 0
                },
                {
                    id: 3,
                    title: 'Item 3',
                    order_column: 3,
                    parent_id: 0
                },
                {
                    id: 4,
                    title: 'Item 4',
                    order_column: 1,
                    parent_id: 3
                }
            ]
        };
    },
    beforeMount() {
        let copy = Fjord.clone(this.input);
        this.list = this.unflatten(copy);
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
                    node.parent_id = parent[idKey];
                }

                return list;
            };
        },
        flatten(tree) {
            let list = [];
            const stack = [];
            const _tree = _.cloneDeep(tree);
            const settings = {
                initNode: node => node,
                itemsKey: 'children',
                idKey: 'id',
                uniqueIdStart: 1,
                generateUniqueId: () => settings.uniqueIdStart++
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

            // cleanup
            list = _.map(list, item => {
                if (!item.parent_id) {
                    item.parent_id = 0;
                }
                return _.omit(item, ['children']);
            });

            return list;
        },
        // https://stackoverflow.com/questions/18017869/build-tree-array-from-flat-array-in-javascript
        unflatten(array, parent, tree) {
            array = _.sortBy(array, item => {
                return item.order_column;
            });

            tree = typeof tree !== 'undefined' ? tree : [];
            parent = typeof parent !== 'undefined' ? parent : { id: 0 };

            if (!parent.hasOwnProperty('children')) {
                parent.children = [];
            }

            var children = _.filter(array, child => {
                return child.parent_id == parent.id;
            });

            if (!_.isEmpty(children)) {
                if (parent.id == 0) {
                    tree = children;
                } else {
                    parent['children'] = children;
                }
                _.each(children, child => {
                    this.unflatten(array, child);
                });
            }

            return tree;
        }
    },
    computed: {
        output() {
            return this.flatten(this.list);
        },
        unflattened() {
            return this.unflatten(this.input);
        }
    }
};
</script>
