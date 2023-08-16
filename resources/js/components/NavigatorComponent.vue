<template>
    <ul class="navbar-nav me-auto mb-2 mb-md-0">
        <li class="nav-item" @click="selectTag(null)">
            <a class="nav-link" :class="{active: !selectedTag}">All</a>
        </li>
        <!-- This assumes you will get tags from an API or props -->
        <li v-for="tag in tags" :key="tag.id" class="nav-item" @click="selectTag(tag.slug)">
            <a class="nav-link" :class="{active: selectedTag === tag.slug}">{{ tag.name }}</a>
        </li>
    </ul>
    <form class="d-flex">
        <input v-model="searchQuery" @keyup="searchText" class="form-control me-2" type="search" placeholder="Filter" aria-label="Search">
    </form>
    <svg v-if="!isLoggedIn" @click="loginModal" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#d7e0ef}</style><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>
    <svg v-if="isLoggedIn" @click="creatorModal" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#d5eccb}</style><path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V160H256c-17.7 0-32-14.3-32-32V0H64zM256 0V128H384L256 0zM153 289l-31 31 31 31c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0L71 337c-9.4-9.4-9.4-24.6 0-33.9l48-48c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9zM265 255l48 48c9.4 9.4 9.4 24.6 0 33.9l-48 48c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l31-31-31-31c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0z"/></svg>
    <svg v-if="isLoggedIn" @click="signOut" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>
    <LoginDialog v-if="!isLoggedIn" v-show="showLogin"></LoginDialog>
    <CodeCreator v-if="isLoggedIn" v-show="showCreator"></CodeCreator>
</template>

<script>
    import LoginDialog from './LoginDialog.vue';
    import CodeCreator from './CodeCreator.vue';
    import { mapActions } from 'vuex'
    export default {
        components: {
            LoginDialog,
            CodeCreator
        },
        computed: {
            isLoggedIn() {
                return !!this.$store.state.authenticated;
            },
            selectedTag() {
                return this.$store.state.selectedTag;
            },
            showLogin(){
                return this.$store.state.showLogin;
            },
            showCreator(){
                return this.$store.state.showCreator;
            },
            tags() {
                return this.$store.state.tags;
            },
            searchQuery: {
                get() {
                    return this.$store.state.searchQuery;
                },
                set(value) {
                    this.$store.commit('setSearchQuery', value);
                }
            }
        },
        mounted() {
            this.$store.dispatch('initializeStore');
            this.$store.dispatch('fetchTags');
        },
        methods: {
            ...mapActions({
                signOut:'logout'
            }),
            selectTag(tagSlug) {
                this.$store.commit('setSelectedTag', tagSlug);
            },
            loginModal(){
                this.$store.commit('SET_SHOWLOGIN', true);
            },
            creatorModal(){
                this.$store.commit('SET_SHOWCREATOR', true);
            },
        }
    }
</script>
<style scoped>
    .nav-link{
        cursor: pointer;
    }
    svg{
        fill: #fff;
        margin-right: 20px;
        margin-left: 10px;
        cursor: pointer;
    }
</style>