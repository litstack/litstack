<template>
    <div>
        <b-row class="fjord-form">
            <b-col lg="12">
                <b-card>
                    <fj-form
                        :model="pageContent"
                    />
                </b-card>
            </b-col>
        </b-row>
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
