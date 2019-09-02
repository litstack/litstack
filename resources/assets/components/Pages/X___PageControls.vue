<template>
    <div class="d-flex justify-content-end">
        <button class="btn btn-primary" @click="save">
            <i class="far fa-save"></i> Save
        </button>
    </div>
</template>

<script>

export default {
    name: 'PageControls',
    props: {
        config: {
            type: Object,
            required: true
        },
        payload: {
            type: Object
        }
    },
    methods: {
        save() {
            let data = this.payload;
            for (var field in data) {
                if (data.hasOwnProperty(field)) {
                    let type = this.getFieldType(field);

                    switch (type) {
                        case 'input':
                        case 'wysiwyg':
                            let d = JSON.parse(JSON.stringify(data[field]));
                            this.saveContent(field, d);
                            break;
                        case 'block':
                            for (var i = 0; i < data[field].length; i++) {
                                this.saveRepeatable(data[field][i]);
                            }
                            break;
                        default:
                    }
                }
            }
        },
        async saveRepeatable(data) {
            let response = await CrudApi.update(`repeatables/${data.id}`, data);
        },
        async saveContent(id, data) {
            let response = await CrudApi.update(
                `pages/${this.config.page}/page-content/${id}`,
                data
            );
        },
        switchLanguage(language) {
            this.lng = language;
            this.$nextTick(() => {
                console.log(this.payload);
            });
        },
        getFieldType(title) {
            return _.filter(this.config.fields, ['id', title])[0].type;
        },
        activeLanguage(lng) {
            return lng == this.language;
        }
    },
    watch: {
        lng(val) {
            this.$emit('input', val);
        }
    }
};
</script>

<style lang="css"></style>
