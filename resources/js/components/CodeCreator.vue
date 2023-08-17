<template>
    <div class="modal-background">
        <div class="modal-container">
            <div class="modal-content">
                <div class="card shadow sm">

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">Create snippet:</div>
                                    </div>
                                    <input type="text" v-model="snippetdata.name" class="form-control form-control-sm" placeholder="Snippet name">
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <button type="button" @click="addParamField" class="btn btn-primary btn-sm mr-2">Add Param Field</button>&nbsp
                                <button type="button" v-if="showParams" @click="hideParamList" class="btn btn-info btn-sm ml-2">Hide params</button>
                                <button type="button" v-if="!showParams" @click="showParamList" class="btn btn-success btn-sm ml-2">Show params</button>
                            </div>
                        </div>


                        <hr/>
                        <form>
                                <div v-for="(field, index) in paramsFields" v-if="showParams" :key="index" class="param-field">
                                    <div class="form-row row align-items-center">
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">name:</div>
                                                </div>
                                                <input type="text" v-model="field.name" class="form-control form-control-sm" placeholder="Param name">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">type</div>
                                                </div>
                                                <select v-model="field.type" class="form-control form-control-sm">
                                                    <option value="string">String</option>
                                                    <option value="select">Select</option>
                                                    <!-- Add other options as needed -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-auto" v-if="field.type === 'select'">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">options</div>
                                                </div>
                                                <input v-model="field.options" type="text" class="form-control  form-control-sm" placeholder="Options (comma separated)">
                                            </div>
                                        </div>

                                        <div class="col-auto">
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">default</div>
                                                </div>
                                                <input type="text" v-model="field.default" class="form-control  form-control-sm" placeholder="default value">
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" @click="removeParamField(index)" class="btn btn-danger btn-sm mb-2">Remove</button>
                                        </div>
                                    </div>


                                </div>
                                <div class="flex-container">
                                    <div class="params-container">
                                        <div class="form-group col-12">
                                            <textarea v-model="paramsConfig" placeholder="Params Config" rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="raw-snippet-container">
                                        <div v-for="(rawSnippet, index) in snippetdata.raw_snippets" :key="index" :class="['mb-3']">
                                            <input v-model="rawSnippet.instruction" type="text" placeholder="Instruction" class="form-control form-control-sm">
                                            <textarea
                                                  v-model="rawSnippet.raw"
                                                  placeholder="Raw Snippet"
                                                  rows="3"
                                                  class="form-control snippet-code"
                                            ></textarea>
                                        </div>
                                        <button type="button" @click="addRawSnippet" class="btn btn-primary btn-sm">Add Raw Snippet</button>
                                    </div>
                                </div>
                                <div class="taglist">
                                    <a
                                            v-for="tag in tagOptions"
                                            :key="tag.slug"
                                            :class="['btn', 'btn-sm', isSelected(tag.slug) ? 'btn-success' : 'btn-primary']"
                                            @click="toggleTagSelection(tag.slug)"
                                    >
                                        {{ tag.name }}
                                    </a>&nbsp;
                                </div>

                            <div class="col-12 mb-2 mt-2">
                                <button type="submit" :disabled="processing" @click="save" class="btn btn-primary btn-block">
                                    {{ processing ? "Please wait" : "Save" }}
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
    import { mapMutations } from 'vuex';

    export default {
        name:"CodeCreator",
        data(){
            return {
                codeTags: [],
                paramsFields: [],
                snippetdata:{
                    name: "New snippet",
                    params:"",
                    raw_snippet:"",
                    raw_snippets: [{ raw: '', instruction: '' }],
                    tags: [],
                },
                showParams: true,
                processing:false
            }
        },
        computed:{
            paramsConfig() {
                const paramsObject = {};
                for (const field of this.paramsFields) {
                    paramsObject[field.name] = {
                        type: field.type,
                        options: field.type === "select" ? field.options.split(",") : null,
                        default: field.default,
                    };
                }
                return JSON.stringify(paramsObject, null, 2);
            },
            tags() {
                return this.$store.state.tags;
            },
            tagOptions() {
                return this.$store.state.tags.map(tag => ({
                    slug: tag.slug,
                    name: tag.name
                }));
            }
        },
        methods:{
            ...mapMutations(['addSnippet']),
            addParamField() {
                this.paramsFields.push({
                    name: "",
                    type: "string",
                    options: "",
                    default: "",
                });
                this.showParams = true;
            },
            addRawSnippet() {
                this.snippetdata.raw_snippets.push({ raw: '', instruction: '' });
            },
            removeParamField(index) {
                this.paramsFields.splice(index, 1);
            },
            showParamList(){
              this.showParams = true;
            },
            hideParamList(){
                this.showParams = false;
            },
            isSelected(tagSlug) {
                return this.codeTags.includes(tagSlug);
            },
            toggleTagSelection(tagSlug){
                if (this.isSelected(tagSlug)) {
                    this.codeTags = this.codeTags.filter(slug => slug !== tagSlug);
                } else {
                    this.codeTags.push(tagSlug);
                }
            },
            async save(){
                this.processing = true
                this.snippetdata.params = this.paramsConfig;
                this.snippetdata.tags = this.codeTags;
                await axios.post('/api/create/snippet', this.snippetdata, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(({data}) => {
                        // Handle success
                        console.log(data);
                        this.addSnippet(data);
                        this.hideModal();

                        this.resetForm();
                    })
                    .catch(error => {
                        if (error.response) {
                            if (error.response.status === 422) {
                                this.validationErrors = error.response.data.errors;
                            } else {
                                this.validationErrors = {};
                                alert(error.response.data.message);
                            }
                        } else {
                            // Handle other errors
                        }
                    })
                    .finally(() => {
                        this.processing = false;
                    });
            },
            resetForm() {
                this.snippetdata.name = "New snippet";
                this.snippetdata.params = "";
                this.snippetdata.raw_snippet = "";
                this.snippetdata.tags = [];
                this.paramsFields = []; // Reset paramsFields to an empty array
            },
            hideModal(){
                this.$store.commit('SET_SHOWCREATOR', false);
            },
        },
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
        min-width: 60%;
        max-height: 80%;
        overflow-y: scroll;
    }

    .param-field {
        margin-top: 10px;
        display: flex;
        gap: 10px;
    }

    .flex-container {
        display: flex;
    }

    .params-container {
        flex: 1;
        padding-right: 1rem;
    }

    .raw-snippet-container {
        flex: 1;
        padding-left: 1rem;
    }
    .snippet-code{
        background: #333942;
        color: #fff;
    }
</style>