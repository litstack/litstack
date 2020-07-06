<script>
export default {
    name: 'Blade',
    render(createElement) {
        const render = {
            template: '<div class="w-100">' + this.view + '</div>',
            props: Object.keys(this.$attrs)
        };
        return createElement(render, {
            props: { ...this.$attrs }
        });
    },
    props: {
        view: {
            required: true,
            type: String
        }
    }
};
</script>
