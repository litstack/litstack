<template>
    <div>
        <h1>Page Test</h1>
        <div class="row fjord-form">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">

                    </div>
                    <div class="card-body">
                        <test-form
                            :model="pageContent"
                            :repeatables="repeatables"
                            />

                        <FormBlock
                            v-for="(model, id) in repeatables"
                            :key="id"
                            :field="model.config.field"
                            :repeatables="model"
                            :pageName="pageName"
                            @newRepeatable="(repeatable) => {newRepeatable(model, repeatable)}"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TestForm from './TestForm'

export default {
    name: 'PageTest',
    components: {
        TestForm
    },
    props: {
        models: {
            type: Object
        },
        pageName: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            repeatables: {},
            pageContent: []
        }
    },
    methods: {
        newRepeatable(model, repeatable) {
            model.items.items.push(repeatable)
        },
    },
    beforeMount() {
        //this.repeatables = this.models.repeatables
        this.pageContent = this.models.pageContent

        for(name in this.models) {
            if(name == 'pageContent') {
                continue
            }
            console.log(name, this.models)
            this.repeatables[name] = this.models[name]
        }
    }
}
</script>

<style lang="css">
</style>
