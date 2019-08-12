<template>
    <div>
        <div class="row fjord-form">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <fj-form
                            :model="pageContent"
                        />

                        <fj-form-block
                            v-for="(model, id) in repeatables"
                            :key="id"
                            :field="model.config.field"
                            :repeatables="model"
                            :pageName="pageName"
                            @newRepeatable="
                                repeatable => {
                                    newRepeatable(model, repeatable);
                                }
                            "
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PageShow',
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
        };
    },
    methods: {
        newRepeatable(model, repeatable) {
            model.items.items.push(repeatable);
        }
    },
    beforeMount() {
        this.pageContent = this.models.pageContent;

        for (name in this.models) {
            if (name == 'pageContent') {
                continue;
            }
            this.repeatables[name] = this.models[name];
        }
    }
};
</script>

<style lang="css"></style>
