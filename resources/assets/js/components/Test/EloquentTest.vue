<template>
    <div>
        <h1>Eloquent Test</h1>
        <div class="row fjord-form">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">

                    </div>
                    <div class="card-body">
                        <test-form
                            :model="post"
                            />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Eloquent from './../../eloquent'
import TestForm from './TestForm'

export default {
    name: 'EloquentTest',
    components: {
        TestForm
    },
    props: {
        models: {
            type: Object
        }
    },
    data() {
        return {
            model: null,
            post: null
        }
    },
    methods: {
        save() {

        }
    },
    beforeMount() {
        this.model = this.models.post
        this.article = this.models.article
    }
}
</script>

<style lang="css" scoped>
</style>
