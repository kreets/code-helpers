<template>
    <div class="row">
        <MasonryWall :items="filteredSnippets" :ssr-columns="1" :column-width="400" :gap="16" :key="snippetCount">
            <template #default="{ item, index }">
                <div
                        :style="{ height: `${item}px` }"
                        class="card flex items-center justify-center"
                >
                    <CodeContainer :key="item.id" :snippet="item" />
                </div>
            </template>
        </MasonryWall>
    </div>
</template>
<script>
    import CodeContainer from './CodeContainer.vue';
    import MasonryWall from '@yeger/vue-masonry-wall'

    export default {
        components: {
            CodeContainer,
            MasonryWall
        },
        computed: {
            snippets() {
                return this.$store.state.snippets;
            },
            filteredSnippets() {
                if (this.$store.state.searchQuery) {
                    const query = this.$store.state.searchQuery.toLowerCase();
                    return this.snippets.filter(snippet =>
                        snippet.name.toLowerCase().includes(query) ||
                        snippet.snippet.toLowerCase().includes(query)
                    );
                }
                if (this.$store.state.selectedTag) {
                    return this.snippets.filter(snippet => snippet.tags && snippet.tags.hasOwnProperty(this.$store.state.selectedTag));
                }
                return this.snippets;
            },
            snippetCount() {
                return this.snippets.length;
            }
        },
    }
</script>
