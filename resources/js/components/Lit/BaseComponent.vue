<script>
import dependencyMethods from './../Crud/Fields/dependecy_methods';

export default {
    name: 'BaseComponent',
    render(createElement) {
        if (!this.shouldRender) {
            return;
        }

        return createElement(
            this.component.name,
            {
                class: this.component.classes,
                on: {
                    ...this.$listeners,
                    ...this.events,
                },
                domProps: this.component.domProps,
                attrs: this.props,
                slot: this.slot,
                ref: 'component',
            },
            [this.createChildren(createElement), this.$slots.default]
        );
    },
    props: {
        component: {
            type: Object,
            required: true,
        },
        eventData: {
            type: Object,
            default: () => {},
        },
    },
    data() {
        return {
            /**
             * Determines if the field fulfills conditions.
             */
            fulfillsConditions: true,

            events: {},
            sendingEventRequest: false,
        };
    },
    computed: {
        /**
         * Determines if the component should be rendered.
         *
         * @return {Boolean}
         */
        shouldRender() {
            if (!this.component.dependencies) {
                return true;
            }

            return this.fulfillsConditions;
        },

        props() {
            return {
                ...this.$attrs,
                ...this.component.props,
                eventData: {
                    ...this.eventData,
                    ...(this.$attrs['event-data'] || {}),
                },
                sendingEventRequest: this.sendingEventRequest,
            };
        },
        slot() {
            return this.component.slot;
        },
        children() {
            return this.component.children;
        },
    },
    beforeMount() {
        this.setEvents();

        this.resolveDependecies(this.component.dependencies);
        Lit.bus.$on('resolveDependencies', () => {
            this.resolveDependecies(this.component.dependencies);
        });
    },
    methods: {
        ...dependencyMethods,

        createChildren(createElement) {
            let children = [];
            for (let i in this.children) {
                let child = this.children[i];
                if (typeof child === 'object') {
                    children.push(
                        createElement('lit-base-component', {
                            attrs: { ...this.props, ...child.props },
                            props: {
                                component: child,
                            },
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
            
            for (let name in this.component.events) {
                let event = this.component.events[name];
                this.events[name] = (data) => {
                    return this.handleEvent(event, data);
                };
            }
        },
        async handleEvent(event, data) {
            this.sendingEventRequest = true;
            let response = await this.sendHandleEvent(event, data);
            this.sendingEventRequest = false;

            if (!response) {
                return;
            }

            if (
                typeof response.data == 'object' &&
                'redirect' in response.data
            ) {
                let a = document.createElement('a');
                a.target = '_blank';
                a.href = response.data.redirect;
                a.click();
            }

            let responseURL = response.request.responseURL;

            if (!responseURL.endsWith('handle-event')) {
                window.location.href = responseURL;
            }

            if (this.isFileDownload(response)) {
                this.handleFileDownload(response);
            }

            Lit.bus.$emit('reload');

            this.$emit('eventHandled', {event, response});
            Lit.bus.$emit('eventHandled', {event, response});
        },
        async sendHandleEvent(event, data) {
            let response;
            
            try {
                response = await axios.post(`handle-event`, {
                    ...this.eventData,
                    ...(this.component.props.eventData || {}),
                    ...(this.$attrs['event-data'] || {}),
                    ...(this.$attrs['eventData'] || {}),
                    ...data,
                    handler: event.handler,
                }, this.getEventRequestOptions(event));

                Lit.bus.$emit('response', response);
            } catch (e) {
                Lit.bus.$emit('response', e);
            }

            return response;
        },

        getEventRequestOptions(event) {
            let options = {};

            if(event.isFileDownload) {
                options.responseType = 'blob';
            }

            return options;
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
            const url = window.URL.createObjectURL(new Blob([response.data], {type: response.data.type}));
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
            return split[split.length - 1].replace(/["']/g, "");
        },
    },
};
</script>
