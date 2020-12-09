<template>
    <b-form-input
        :placeholder="
            __('base.item_search', {
                item: strip(namePlural),
            }).capitalize()
        "
        v-model="query"
    />
</template>

<script>
export default {
    name: 'BaseIndexTableSearch',
    props: {
        nameSingular: {
            type: String,
        },
        namePlural: {
            type: String,
        },
        filterScopes: {
            type: Array,
        },
    },
    data() {
        return {
            query: '',
            typingDelay: 500,
        };
    },
    watch: {
        query(val) {
            setTimeout(() => {
                if (this.query == val) {
                    this.$emit('search', val);
                }
            }, this.typingDelay);
        },
    },
    methods: {
        /**
         * Strip tags and content between tags.
         * Remove last character if it is a space.
         *
         * @param  {String}  str
         * @return {String}
         */
        strip(str) {
            return str.replace(/<.*>/, '').replace(/\ $/, '');
        },
    },
};
</script>
