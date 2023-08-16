<template>
    <div class="snippet-box">
        <div class="snippet-header">
            <h6>{{snippet.name}}</h6>
            <div class="copy-section" @click="copyToClipboard">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"> <path d="M8 2C7.44772 2 7 2.44772 7 3C7 3.55228 7.44772 4 8 4H10C10.5523 4 11 3.55228 11 3C11 2.44772 10.5523 2 10 2H8Z" fill="#4A5568"/> <path d="M3 5C3 3.89543 3.89543 3 5 3C5 4.65685 6.34315 6 8 6H10C11.6569 6 13 4.65685 13 3C14.1046 3 15 3.89543 15 5V11H10.4142L11.7071 9.70711C12.0976 9.31658 12.0976 8.68342 11.7071 8.29289C11.3166 7.90237 10.6834 7.90237 10.2929 8.29289L7.29289 11.2929C6.90237 11.6834 6.90237 12.3166 7.29289 12.7071L10.2929 15.7071C10.6834 16.0976 11.3166 16.0976 11.7071 15.7071C12.0976 15.3166 12.0976 14.6834 11.7071 14.2929L10.4142 13H15V16C15 17.1046 14.1046 18 13 18H5C3.89543 18 3 17.1046 3 16V5Z" fill="#4A5568"/> <path d="M15 11H17C17.5523 11 18 11.4477 18 12C18 12.5523 17.5523 13 17 13H15V11Z" fill="#4A5568"/> </svg>
                <span v-if="copied">Copied</span>
                <span v-if="!copied">Copy</span>
            </div>
        </div>
        <div v-if="hasParams" class="params-section">
            <div v-for="(param, key) in parsedParams" :key="key" class="form-group row">
                <label :for="key" class="col-sm-3 col-form-label col-form-label-sm">{{key}}</label>
                <div class="col-sm-9">
                    <input  class="form-control form-control-sm" v-if="param.type === 'string'" v-model="inputParams[key]" :placeholder="param.default || ''">
                    <select class="form-control form-control-sm" v-if="param.type === 'select'" v-model="inputParams[key]">
                        <option v-for="option in param.options" :key="option" :value="option">{{ option }}</option>
                    </select>
                </div>
            </div>

        </div>
        <pre><code>{{ formattedSnippet }}</code></pre>
        <div class="tag-list">
            <span v-for="tag in snippet.tags" class="tag-element" :key="tag.name">#{{ tag }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['snippet'],
        data() {
            let initialInputParams = {};
            if (this.snippet && this.snippet.params) {
                const parsedParams = JSON.parse(this.snippet.params);
                for (let key in parsedParams) {
                    if (parsedParams[key].default !== undefined) {
                        initialInputParams[key] = parsedParams[key].default;
                    } else if (parsedParams[key].type === "select" && Array.isArray(parsedParams[key].options)) {
                        initialInputParams[key] = parsedParams[key].options[0] || '';
                    } else {
                        initialInputParams[key] = '';
                    }
                }
            }
            return {
                inputParams: initialInputParams,
                copied: false
            };
        },
        computed: {
            hasParams() {
                return !!this.snippet.params;
            },
            parsedParams() {
                return this.hasParams ? JSON.parse(this.snippet.params) : {};
            },
            formattedSnippet() {
                let formatted = this.snippet.snippet;
                for (let key in this.inputParams) {
                    const value = this.inputParams[key] || this.parsedParams[key].default || '';
                    let regex = new RegExp("#params." + key + "#", "g");
                    formatted = formatted.replace(regex, value);
                }
                return formatted;
            }
        },
        methods: {
            copyToClipboard() {
                const el = document.createElement('textarea');
                el.value = this.formattedSnippet;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                this.copied = true;
            }
        },
        watch: {
            inputParams(newParam){
                console.log(newParam);
            }
        }
    }
</script>

<style scoped>
    .snippet-box {
        padding: 15px;
        position: relative;
        background: #b4b8ca;
    }

    pre {
        position: relative;
        background: #333942;
        padding: 10px;
        font-size: 12px;
        word-wrap: break-word;
        white-space: pre-wrap;
        border: 1px solid #545963;
        margin-top: 10px;
        color: #fff;
        border-radius: 10px 10px 10px 10px;
    }

    svg{
        cursor: pointer;
    }

    .snippet-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        border-bottom: 1px #8387ac solid;
        margin-bottom: 5px;
    }

    .snippet-header .copy-section {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .snippet-header .copy-section span {
        cursor: pointer;
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 2px;
    }
    .tag-element{
        font-size: 12px;
        margin-right: 5px
    }

</style>
