<script>
export default {
    name: 'Blade',
    render(createElement) {
        const render = {
            template: '<div class="w-100">' + this.view + '</div>'
        };
        return createElement(render, {
            props: {
                ...this.$attrs
            }
        });
    },
    props: {
        view: {}
    }
};
</script>
