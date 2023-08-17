<template>
    <div class="snippet-box">
        <div class="snippet-header">
            <h6>{{snippet.name}}</h6>
        </div>
        <div v-if="hasParams" class="params-section mb-2">
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
        <div v-for="(formattedRaw, index) in formattedRaws" :key="index">
            <div v-if="formattedRaw.instruction" class="instruction">{{ formattedRaw.instruction }}</div>
            <pre @mouseenter="showButton(index)" @mouseleave="hideButton(index)"><code>{{ formattedRaw.rawCode }}</code><button
                    v-if="hoveredIndex === index"
                    :class="['copy-button', { 'copied': formattedRaw.copied }]"
                    @click="copyRawCode(formattedRaw, index)">{{ formattedRaw.copied ? 'Copied!' : 'Copy' }}</button></pre>
        </div>

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
                hoveredIndex: null,
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
            },
            formattedRaws() {
                const formattedRaws = [];
                for (const [rawCode, instruction] of Object.entries(this.snippet.raws)) {
                    let formattedRaw = rawCode;
                    for (let key in this.inputParams) {
                        const value = this.inputParams[key] || this.parsedParams[key].default || '';
                        const regex = new RegExp("#params." + key + "#", "g");
                        formattedRaw = formattedRaw.replace(regex, value);
                    }
                    formattedRaws.push({
                        instruction,
                        rawCode: formattedRaw,
                        copied: false,
                    });
                }
                return formattedRaws;
            }
        },
        methods: {
            showButton(index) {
                this.hoveredIndex = index;
            },
            hideButton(index) {
                this.hoveredIndex = null;
            },
            copyRawCode(formattedRaw, index) {
                const el = document.createElement('textarea');
                el.value = formattedRaw.rawCode;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                // Update the copied property
                this.formattedRaws[index].copied = true;
                this.$forceUpdate()
                // Reset the copied property after a delay (e.g., 2 seconds)
                setTimeout(() => {
                    this.formattedRaws[index].copied = false;
                }, 2000);
            },
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

    .copy-button {
        position: absolute;
        top: 0;
        right: 0;
        background-color: transparent;
        border: none;
        color: #fff;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    }
    .copy-button.copied {
        color: green;
    }

    pre:hover .copy-button {
        opacity: 1;
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
    .instruction{
        font-size: 12px;
        font-style: italic;
    }

</style>
