<template>
    <div>
        <fj-site-nav :route="model.route" />

        <div class="row fjord-form">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-end">

                    </div>
                    <div class="card-body">
                        <fj-form
                            :model="model"
                            />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CrudShow',
    props: {
        models: {
            type: Object
        }
    },
    data() {
        return {
            model: null,
        }
    },

    methods: {
        saved() {
            console.log(this.model.id)
            // TODO: FIX THIS...
            if(window.location.pathname.split('/').pop() == 'create') {
                //window.location.replace(`${this.model.id}/edit`)
            }
       }
   },
   beforeMount() {
       this.model = this.models.model

       this.$bus.$on('modelsSaved', this.saved)
   }
};
</script>
