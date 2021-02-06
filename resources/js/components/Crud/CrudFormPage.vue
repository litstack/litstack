<template>
    <lit-page :page="page" :model="model" :event-data="{ ids: [model.id] }" />
</template>

<script>
export default {
    name: 'CrudFormPage',
    props: {
        page: {
            type: Object,
            required: true,
        },
        crudModel: {
            type: [Array, Object],
            required: true,
        },
        config: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            model: {},
        };
    },
    beforeMount() {
        this.model = this.crud(this.crudModel);

        this.$store.commit('SET_CONFIG', this.config);

        Lit.bus.$on('saved', this.saved);
        Lit.bus.$on('field:updated', this.reloadModel);
        Lit.bus.$on('reload', this.reloadModel);
    },
    methods: {
        async reloadModel() {
            if (!this.model.id) {
                return;
            }

            let response;
            try {
                response = await axios.get(
                    `${this.config.route_prefix}/${this.model.id}/api/show/default/load`
                );
            } catch (e) {
                console.log(e);
                return;
            }
            this.model = this.crud(response.data);

            this.$nextTick(() => {
                Lit.bus.$emit('reloaded');
            });
        },
        saved(results) {
            this.setModelFromResults(results);

            this.reloadModel();

            if (
                window.location.pathname.split('/').pop() == 'create' &&
                this.model.id
            ) {
                setTimeout(() => {
                    window.location.replace(`${this.model.id}`);
                }, 1);
            }
        },
        setModelFromResults(results) {
            if (this.model.id) {
                return;
            }

            let result;
            result = results.findSucceeded(
                'put',
                `${this.config.route_prefix}/${this.model.id}/api/show`
            );
            if (result) {
                this.model = this.crud(result.data);
            }
            result = results.findSucceeded(
                'post',
                `${this.config.route_prefix}/api/show`
            );
            if (result) {
                this.model = this.crud(result.data);
            }
        },
        scrollToFormFieldFromHash() {
            if (!window.location.hash) {
                return;
            }
            let hash = window.location.hash.replace('#', '');
            let elements = document.getElementsByClassName(
                `lit-form-item-${hash}`
            );
            if (elements.length < 1) {
                return;
            }
            // Scroll to first one.
            let element = elements[0];
            let pos = element.style.position;
            let top = element.style.top;
            element.style.position = 'relative';
            element.style.top = '-30px';
            element.scrollIntoView({ behavior: 'smooth', block: 'start' });
            element.style.top = top;
            element.style.position = pos;
        },
    },
    computed: {
        isCreate() {
            return window.location.pathname.split('/').pop() == 'create';
        },
    },
};
</script>
