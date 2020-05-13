<template>
    <b-col :cols="fieldCols">
        <div :class="`pb-4 fjord-form fj-form-item-${field.id}`">
            <h6 class="fj-form-item-title mb-0 d-flex justify-content-between">
                <fj-slot
                    v-if="'title' in field.slots"
                    :name="field.slots.title"
                    :props="{ field, model }"
                />
                <label :for="field.id" v-else>{{ field.title }}</label>
                <div>
                    <slot name="title-right" />
                    <b-badge v-if="field.translatable" variant="secondary">
                        <small>{{ language }}</small>
                    </b-badge>
                </div>
            </h6>
            <p class="text-secondary" v-if="field.info">{{ field.info }}</p>
            <div class="input-group">
                <slot :state="state" />
                <b-form-invalid-feedback
                    v-for="(message, key) in messages"
                    :key="key"
                    :style="`display:${state == null ? 'none' : 'block'}`"
                >
                    {{ message }}
                </b-form-invalid-feedback>
            </div>
            <div
                class="d-flex justify-content-between"
                v-if="
                    (field.hint && !noHint) ||
                        field.max ||
                        field.min ||
                        field.maxFiles
                "
            >
                <small
                    class="form-text text-muted"
                    v-html="field.hint && !noHint ? field.hint : ''"
                />

                <small class="form-text text-muted" v-if="!noMinMax">
                    <template v-if="field.max && field.min === undefined">
                        {{ length }}/{{ field.max }}
                    </template>
                    <template v-if="field.max && field.min !== undefined">
                        {{ value }}
                    </template>
                    <template v-if="field.maxFiles">
                        {{ value.length }}/{{ field.maxFiles }}
                    </template>
                </small>
            </div>
        </div>
    </b-col>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormItem',
    props: {
        field: {
            type: [Object, Array],
            required: true
        },
        model: {},
        value: {},
        noHint: {
            type: Boolean,
            default() {
                return false;
            }
        },
        noMinMax: {
            type: Boolean,
            default() {
                return false;
            }
        }
    },
    data() {
        return {
            state: null,
            messages: []
        };
    },
    beforeMount() {
        this.$on('refresh', () => {
            this.$forceUpdate();
        });
        Fjord.bus.$on('saved', this.checkForErrors);
        Fjord.bus.$on('saveCanceled', this.resetErrors);
    },
    methods: {
        resetErrors() {
            this.state = null;
            this.messages = [];
        },
        checkForErrors(results) {
            let result = results.findFailed(
                this.field._method,
                this.field.route_prefix
            );

            if (!result) {
                return;
            }

            if (!result.isAxiosError) {
                return this.resetErrors();
            }

            let errors = this.findErrors(result);
            if (!errors || _.isEmpty(errors)) {
                return this.resetErrors();
            }

            this.state = false;
            this.messages = errors;
        },
        findErrors(result) {
            if (!('errors' in result.response.data)) {
                return;
            }
            if (!this.field.translatable) {
                return result.response.data.errors[this.field.local_key];
            }
            let errors = [];
            for (let key in result.response.data.errors) {
                let error = result.response.data.errors[key];
                if (key.endsWith('.' + this.field.local_key)) {
                    for (let i in error) {
                        let message = error[i];
                        errors.push(message);
                    }
                }
            }
            return errors;
        }
    },
    computed: {
        ...mapGetters(['language']),
        length() {
            return this.value ? this.value.length : 0;
        },
        fieldCols() {
            return this.field.cols !== undefined ? this.field.cols : 12;
        }
    }
};
</script>
