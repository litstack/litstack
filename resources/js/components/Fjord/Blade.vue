<script>
export default {
    name: 'Blade',
    render(createElement) {
        const render = {
            template: '<div>' + this.view + '</div>',
            methods: {
                markComplete() {
                    console.log('the method called');
                }
            }
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
