<template>
    <div>
        <template v-if="config.translatable && payload[field.id]">
            <FormInput
                v-if="field.type == 'input'"
                :item="field"
                :value="payload[field.id][language]['content']"
                v-model="payload[field.id][language]['content']"
            >
                <div class="input-group-append">
                    <span class="input-group-text">{{ language }}</span>
                </div>
            </FormInput>
            <FormWysiwyg
                v-if="field.type == 'wysiwyg'"
                :item="field"
                :value="payload[field.id][language]['content']"
                v-model="payload[field.id][language]['content']"
            >
                <div class="input-group-append">
                    <span class="input-group-text">{{ language }}</span>
                </div>
            </FormWysiwyg>
        </template>
        <template v-else>
            <FormInput
                v-if="field.type == 'input'"
                :item="field"
                :value="payload[field.id]"
                v-model="payload[field.id]"
            />
            <FormWysiwyg
                v-if="field.type == 'wysiwyg'"
                :item="field"
                :value="payload[field.id]"
                v-model="payload[field.id]"
            />
        </template>
    </div>
</template>

<script>
export default {
    name: 'PageInput',
    props: {
        field: {
            type: Object
        },
        val: {
            type: Object
        },
        language: {
            type: String
        },
        config: {
            type: Object
        }
    },
    data() {
        return {
            payload: {}
        };
    },
    beforeMount() {
        this.payload = this.val;
    }
};
</script>
