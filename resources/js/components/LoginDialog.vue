<template>
    <div class="modal-background">
        <div class="modal-container">
            <div class="modal-content">
                <div class="card shadow sm">
                    <div class="card-body">
                        <h1 class="text-center">Login</h1>
                        <hr/>
                        <form action="javascript:void(0)" class="row" method="post">
                            <div class="col-12" v-if="Object.keys(validationErrors).length > 0">
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <li v-for="(value, key) in validationErrors" :key="key">{{ value[0] }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group col-12">
                                <label for="email" class="font-weight-bold">Email</label>
                                <input type="text" v-model="auth.email" name="email" id="email" class="form-control">
                            </div>
                            <div class="form-group col-12 my-2">
                                <label for="password" class="font-weight-bold">Password</label>
                                <input type="password" v-model="auth.password" name="password" id="password" class="form-control">
                            </div>
                            <div class="col-12 mb-2">
                                <button type="submit" :disabled="processing" @click="login" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Login" }}
                                </button>
                                <a class="btn btn-close-white" @click="hideModal">Close</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { mapActions } from 'vuex'
    export default {
        name:"LoginDialog",
        data(){
            return {
                auth:{
                    email:"",
                    password:""
                },
                validationErrors:{},
                processing:false
            }
        },
        methods:{
            ...mapActions({
                signIn:'login'
            }),
            hideModal(){
                this.$store.commit('SET_SHOWLOGIN', false);
            },
            async login(){
                this.processing = true
                await axios.get('/sanctum/csrf-cookie')
                await axios.post('/login',this.auth).then(({data})=>{
                    const token = data.token;
                    this.$store.commit('SET_USER_TOKEN', token);
                    this.signIn()
                }).catch(({response})=>{
                    if(response.status===422){
                        this.validationErrors = response.data.errors
                    }else{
                        this.validationErrors = {}
                        alert(response.data.message)
                    }
                }).finally(()=>{
                    this.processing = false
                    this.$store.commit('SET_SHOWLOGIN', false);
                })
            },
        }
    }
</script>
<style scoped>
    .modal-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-container {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .modal-content {
        /* Your dialog content styles */
    }
</style>