<template>
    <fj-base-field :field="field" :model="model">
        <template slot="title-right">
            <b-button
                variant="secondary"
                class="mb-2"
                size="sm"
                @click="addListItem"
            >
                {{
                    trans('base.item_add', {
                        item: trans('base.element')
                    })
                }}
            </b-button>
        </template>
        <div class="d-flex justify-content-around w-100" v-if="busy">
            <fj-spinner />
        </div>
        <nested-draggable
            v-else
            :children="list"
            :field="field"
            :model="model"
            @deleteItem="deleteListItem"
        />
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
        /**
         * Field attributes.
         */
        field: {
            required: true,
            type: Object
        },

        /**
         * Model.
         */
        model: {
            required: true,
            type: Object
        }
    },
    data() {
        return {
            busy: false,
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
        //this.list = this.unflatten(copy);
    },
    mounted() {
        this.loadItems();
    },
    methods: {
        async deleteListItem(item) {
            // let response = await this.sendDeleteListItem(item);
            // if (!response) {
            //     return;
            // }

            let flattened = this.flatten(this.list);
            console.log(item);
            flattened = _.filter(flattened, current => current.id != item.id);
            console.log(flattened);

            this.list = this.unflatten(flattened);
        },

        sendDeleteListItem(item) {
            try {
                return axios.delete(
                    `${this.field.route_prefix}/list/${this.field.id}/${item.id}`
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Load items.
         */
        async loadItems() {
            this.busy = true;
            let response = await this.sendLoadItems();
            if (!response) {
                return (this.busy = false);
            }

            let listItems = this.crud(response.data);
            this.list = this.unflatten(listItems);
            this.busy = false;
        },

        /**
         * Send loat items.
         */
        sendLoadItems(parent) {
            try {
                return axios.get(
                    `${this.field.route_prefix}/list/${this.field.id}`
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Add list.
         */
        async addListItem(parent) {
            let response = await this.sendAddListItem(parent);
            if (!response) {
                return;
            }

            let flattened = this.flatten(this.list);
            let listItem = this.crud(response.data);

            flattened.push(listItem);

            this.list = this.unflatten(flattened);
        },

        /**
         * Send add list item request.
         */
        sendAddListItem(parent) {
            try {
                return axios.post(
                    `${this.field.route_prefix}/list/${this.field.id}`,
                    { parent_id: parent ? parent.id : null }
                );
            } catch (e) {
                console.log(e);
            }
        },

        /**
         * Flatten node generator.
         * Credits: https://github.com/MrPeak/flatten-tree
         */
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

        /**
         * Flatten tree.
         */
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

        /**
         * Unflatten.
         * https://stackoverflow.com/questions/18017869/build-tree-array-from-flat-array-in-javascript
         */
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
        /**
         * Output.
         */
        output() {
            return this.flatten(this.list);
        },

        /**
         * Unflattened.
         */
        unflattened() {
            return this.unflatten(this.input);
        }
    }
};
</script>
