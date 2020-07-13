<script>
export default {
    name: 'BaseComponent',
    render(createElement) {
        return createElement(this.component.name, {
            on: {
                ...this.$listeners,
                ...this.events
            },
            attrs: this.props
        });
    },
    props: {
        component: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            events: {}
        };
    },
    computed: {
        props() {
            return {
                ...this.$attrs,
                ...this.component.props
            };
        }
    },
    beforeMount() {
        this.setEvents();
    },
    methods: {
        setEvents() {
            if (!this.component.events) {
                return;
            }
            for (let event in this.component.events) {
                let handler = this.component.events[event];
                this.events[event] = data => {
                    this.handleEvent(handler, data);
                };
            }
        },
        async handleEvent(handler, data) {
            let response = await this.sendHandleEvent(handler, data);

            if (!response) {
                return;
            }
        },
        async sendHandleEvent(handler, data) {
            try {
                return await axios.post(`handle-event`, { ...data, handler });
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>
