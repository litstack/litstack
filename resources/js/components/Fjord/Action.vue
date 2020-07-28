<template>
    <fj-base-component :component="wrapper" @click="$bvModal.show(modalId)">
        <fj-base-component
            :component="modal"
            v-if="modal"
            :id="modalId"
            @ok="$emit('run', { attributes: attributes.attributes })"
        >
            <span>{{ modal.props.message }}</span>
            <div class="row mt-2" v-if="modal.form">
                <fj-field
                    v-for="(field, key) in modal.form.fields"
                    :key="key"
                    :field="field"
                    :model-id="0"
                    :model="attributes"
                />
            </div>
        </fj-base-component>
    </fj-base-component>
</template>

<script>
export default {
    name: 'Action',
    props: {
        wrapper: {
            type: Object,
            required: true
        },
        modal: {
            type: Object
        }
    },
    data() {
        return {
            modalId: this.uuidv4(),
            show: true,
            attributes: this.crud({
                attributes: {},
                translatable: true,
                cast: true
            })
        };
    },
    methods: {
        uuidv4() {
            return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(
                /[xy]/g,
                function(c) {
                    var r = (Math.random() * 16) | 0,
                        v = c == 'x' ? r : (r & 0x3) | 0x8;
                    return v.toString(16);
                }
            );
        }
    }
};
</script>
