<template>
    <fj-form-item :field="field" :model="model">
        <template v-if="!readonly">
            <vue-ctk-date-time-picker
                :id="`${field.id}-${makeid(10)}`"
                v-model="value"
                :label="field.label"
                :format="'YYYY-MM-DD HH:mm:ss'"
                :no-label="true"
                :inline="field.inline"
                :formatted="field.formatted"
                :onlyDate="field.only_date"
                color="var(--primary)"
            />
        </template>
        <template v-else>
            <b-input class="form-control" :value="value" type="text" readonly />
        </template>
    </fj-form-item>
</template>

<script>
export default {
    name: 'FormDateTime',
    props: {
        field: {
            required: true,
            type: Object
        },
        model: {
            required: true,
            type: Object
        },
        readonly: {
            required: true,
            type: Boolean
        }
    },
    data() {
        return {
            value: null,
            datetimeString: ''
        };
    },
    beforeMount() {
        this.init();
        this.$bus.$on('modelLoaded', () => {
            this.init();
        });
    },
    watch: {
        value(val) {
            this.value = val;
            this.model[`${this.field.id}Model`] = val;
            this.$emit('changed');
        }
    },
    methods: {
        init() {
            this.value = this.model[`${this.field.id}Model`];
        },
        makeid(length) {
            var result = '';
            var characters =
                'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(
                    Math.floor(Math.random() * charactersLength)
                );
            }
            return result;
        }
    }
};
</script>

<style lang="scss">
@import '~vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.css';
.date-time-picker {
    input {
        display: inline-block !important;
        width: 100% !important;
        height: calc(1.6em + 0.75rem + 2px) !important;
        min-height: calc(1.6em + 0.75rem + 2px) !important;
        padding: 0.375rem 1.75rem 0.375rem 0.75rem !important;
        font-size: 0.9rem !important;
        font-weight: 400 !important;
        line-height: 1.6 !important;
        color: #495057 !important;
        vertical-align: middle !important;
        background-color: #fff !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.25rem !important;
        -webkit-appearance: none !important;
    }
}
</style>
