<template>
    <div>
        <div class="search-button mr-2" @click="show = true">
            <i class="fas fa-search"></i>
        </div>
        <b-modal
            v-model="show"
            modal-class="search-modal"
            hide-footer
            hide-header
            size="lg"
        >
            <b-input
                v-model="query"
                ref="input"
                :placeholder="__('base.search').capitalize()"
                size="lg"
            />

            <b-list-group class="mt-4" v-if="!_.isEmpty(results)">
                <lit-search-result
                    v-for="(result, index) in results"
                    :key="index"
                    :result="result"
                />
            </b-list-group>
        </b-modal>
    </div>
</template>

<script>
export default {
    name: 'Search',
    data() {
        return { show: false, query: '', results: [] };
    },
    mounted() {
        this.openSearchShortCut();
    },
    watch: {
        query(val) {
            this.search(val);
        },
        show(val) {
            if (!val) {
                this.query = '';
            } else {
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.$refs.input.$el.focus();
                    }, 1);
                });
            }
        },
    },
    methods: {
        openSearchShortCut() {
            document.body.addEventListener(
                'keydown',
                e => {
                    console.log(e.ctrlKey, e.metaKey, e.keyCode);
                    if (
                        (window.navigator.platform.match('Mac')
                            ? e.metaKey
                            : e.ctrlKey) &&
                        e.keyCode == 55
                    ) {
                        e.preventDefault();
                        this.show = true;
                    }
                },
                false
            );
        },
        async search(query) {
            if (!query) {
                return (this.results = []);
            }

            let response = await this.sendSearchRequest(query);

            if (!response) {
                return;
            }

            if (_.isArray(response.data)) {
                this.results = response.data;
            }
        },
        async sendSearchRequest(query) {
            try {
                return await axios.post('search', { query });
            } catch (e) {
                console.log(e);
            }
        },
    },
};
</script>

<style lang="scss">
.search-button {
    color: #9b9da5;
    padding-top: 2px;
    cursor: pointer;
    &:hover {
        color: white;
    }
}
.search-modal {
    .modal-content {
        background: transparent;
        border: none;
    }
}
</style>
