<template>
    <b-dropdown
        right
        :variant="hasActiveSort ? 'primary': 'outline-secondary'"
        v-if="sortBy"
        class="dropdown-md-square-mobile"
        no-caret
    >
        <template v-slot:button-content>
            <lit-fa-icon icon="sort-amount-down" />
            <span class="d-none d-lg-inline-block">{{ __('base.sort') }}</span>
        </template>

        <b-dropdown-item
            v-for="(text, key) in sortBy"
            :key="key"
            @click="sort(key)"
        >
            <b-form-radio :checked="sortByKey" :value="key">
                {{ text }}
            </b-form-radio>
        </b-dropdown-item>
    </b-dropdown>
</template>

<script>
export default {
    name: 'BaseIndexTableSort',
    props: {
        sortBy: {
            type: Object,
            required: true,
        },
        sortByDefault: {
            type: String,
            required: true,
        },
        sortByKey: {
            type: String
        }
    },
    methods: {
        sort(key) {
            let column = _(key.split('.')).tap(a => a.pop()).value().join('.')
            let direction = _.last(key.split('.'))
            this.$emit('sort', {column, direction});
        },
    },
    computed: {
        hasActiveSort() {
            for(let key in this.sortBy) {
                if(this.sortByKey == key) {
                    return true;
                }
            }

            return false;
        }
    }
};
</script>
