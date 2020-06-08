<template>
    <fj-base-field :field="field" :model="model">
        <b-row class="w-100">
            <b-col cols="6">
                <nested-draggable :list="list" />
            </b-col>
            <b-col cols="6">
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
                    name: 'task 1',
                    list: [
                        {
                            name: 'task 2',
                            list: []
                        }
                    ]
                },
                {
                    name: 'task 3',
                    list: [
                        {
                            name: 'task 4',
                            list: []
                        }
                    ]
                },
                {
                    name: 'task 5',
                    list: []
                }
            ]
        };
    },
    methods: {}
};
</script>
