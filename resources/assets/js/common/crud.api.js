import Vue from 'vue';

export const CrudApi = {
    init() {},
    getRoute(method, route, payload) {
        switch (method) {
            case 'post':
                return `/admin/${route}`;
                break;
            case 'delete':
            case 'put':
                return `/admin/${route}/${payload.id}`;
                break;
        }
    },
    callEvents(method, response, payload) {
        return;
        // TODO: emit
        switch (method) {
            case 'post':
                this.$emit('created', response.data);
                break;
            case 'delete':
                this.$emit('deleted', payload);
                break;
            case 'put':
                this.$emit('edited', response.data);
                break;
        }
    },
    defaultText(method, data) {
        switch (method) {
            case 'post':
                return 'Erfolgreich erstellt!';
                break;
            case 'delete':
                return 'Erfolgreich gelöscht!';
                break;
            case 'put':
                return 'Erfolgreich bearbeitet!';
                break;
        }
    },
    async call(method, route, payload, options) {
        // call method
        let response = await axios[method](
            //this.getRoute(method, route, payload),
            `/admin/${route}`,
            payload
        );

        // call method events
        this.callEvents(method, response, payload);

        // notify
        let text = this.defaultText(method, response.data);

        Vue.notify({
            group: 'general',
            type: 'aw-success',
            title: 'Speichern erfolgreich',
            text,
            duration: 1500
        });

        return response;
    },
    async create(route, payload, options) {
        return await this.call('post', route, payload, options);
    },
    async update(route, payload, options) {
        return await this.call('put', route, payload, options);
    }
};

export default CrudApi;
