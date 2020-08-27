<script>
export default {
    name: 'BaseComponent',
    render(createElement) {
        return createElement(
            this.component.name,
            {
                class: this.component.classes,
                on: {
                    ...this.$listeners,
                    ...this.events
                },
                attrs: this.props,
                slot: this.slot,
                ref: 'component'
            },
            [this.createChildren(createElement), this.$slots.default]
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
            events: {},
            sendingEventRequest: false
        };
    },
    computed: {
        props() {
            return {
                ...this.$attrs,
                ...this.component.props,
                eventData: this.eventData,
                sendingEventRequest: this.sendingEventRequest
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
            this.sendingEventRequest = true;
            let response = await this.sendHandleEvent(handler, data);
            this.sendingEventRequest = false;

            if (!response) {
                return;
            }

            let responseURL = response.request.responseURL;

            if (!responseURL.endsWith('handle-event')) {
                window.location.href = responseURL;
            }

            if (this.isFileDownload(response)) {
                this.handleFileDownload(response);
            }

            Fjord.bus.$emit('reload');

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
        },

        isFileDownload(response) {
            if (!response.headers['content-disposition']) {
                return false;
            }

            if (
                !response.headers['content-disposition'].startsWith(
                    'attachment'
                )
            ) {
                return false;
            }

            if (
                !response.headers['content-disposition'].includes('filename=')
            ) {
                return false;
            }

            return true;
        },

        handleFileDownload(response) {
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute(
                'download',
                this.getDownloadResponseFileName(response)
            );
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        },

        getDownloadResponseFileName(response) {
            let split = response.headers['content-disposition'].split(
                'filename='
            );
            return split[split.length - 1];
        }
    }
};
</script>
