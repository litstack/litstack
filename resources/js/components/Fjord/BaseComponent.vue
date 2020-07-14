<script>
export default {
    name: 'BaseComponent',
    render(createElement) {
        return createElement(
            this.component.name,
            {
                on: {
                    ...this.$listeners,
                    ...this.events
                },
                attrs: this.props,
                slot: this.slot
            },
            [this.createChildren(createElement)]
        );
    },
    props: {
        component: {
            type: Object,
            required: true
        },
        eventData: {
            type: Object,
            default() {
                return {};
            }
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
        },
        slot() {
            return this.component.slot;
        },
        children() {
            return this.component.children;
        }
    },
    beforeMount() {
        this.setEvents();
    },
    methods: {
        createChildren(createElement) {
            let children = [];
            for (let i in this.children) {
                let child = this.children[i];
                if (typeof child === 'object') {
                    children.push(
                        createElement('fj-base-component', {
                            attrs: { ...this.props, ...child.props },
                            props: {
                                component: child
                            }
                        })
                    );
                } else {
                    children.push(child);
                }
            }
            return children;
        },
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

            this.$emit('eventHandled', response);
        },
        async sendHandleEvent(handler, data) {
            try {
                return await axios.post(`handle-event`, {
                    ...this.eventData,
                    ...(this.component.props.eventData || {}),
                    ...data,
                    handler
                });
            } catch (e) {
                console.log(e);
            }
        }
    }
};
</script>
