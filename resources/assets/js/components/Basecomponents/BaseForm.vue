<template>
    <div class="row fjord-form">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-end">

                </div>
                <form class="card-body">
                    <div class="row">
                        <div
                            :class="fieldWidth(field)"
                            v-for="(field, index) in parameters.fields">

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BaseForm',
    props: {
        model: {
            type: Object,
            required: true,
        }
    },
    methods: {
        fieldWidth(field) {
            return field.width !== undefined ? `col-${field.width}` : 'col-12';
        },
    }
}
</script>

<style lang="css" scoped>
</style>
