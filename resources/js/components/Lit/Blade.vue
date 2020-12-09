<script>
export default {
    name: 'Blade',
    render(createElement) {
        const render = {
            template: this.getTemplate(createElement),
            props: Object.keys(this.$attrs),
        };
        return createElement(render, {
            props: { ...this.$attrs },
        });
    },
    props: {
        view: {
            required: true,
            type: String,
        },
        wrapper: {},
    },
    methods: {
        hasScriptTags() {
            return this.view.match(
                /<\s*script[^>]*>([^<]*)<\s*\/\s*script\s*>/
            );
        },
        getTemplate(createElement) {
            if (this.wrapper) {
                return (
                    '<lit-base-component :component="' +
                    this.escapeHtmlSpecialChars(JSON.stringify(this.wrapper)) +
                    '">' +
                    this.getView() +
                    '</lit-base-component>'
                );
            }
            return '<div class="w-100">' + this.getView() + '</div>';
        },
        getView() {
            return this.view
                .replace('<script', '<component is="script"')
                .replace('</scri' + 'pt', '</component');
        },
        escapeHtmlSpecialChars(str) {
            return str
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        },
    },
};
</script>
