<script>
export default {
    name: 'Input',
    props: {
        field: {
            required: true,
            type: Object,
        },
        model: {
            type: Object,
            required: true,
        },
    },
    render() {
        return this.$scopedSlots.default({
            value: this.value,
        });
    },
    data() {
        return {
            value: null,
            original: null,
        };
    },
    beforeMount() {
        this.$on('changed', this.changed);
    },
    methods: {
        changed(val) {
            console.log('Changed', val);
            //
        },
    },
};
</script>
