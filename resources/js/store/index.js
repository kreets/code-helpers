import { createStore } from 'vuex';

export default createStore({
    state: {
        selectedTag: null,
        searchQuery: null,
        authenticated:false,
        showLogin: false,
        showCreator: false,
        user:{},
        token: null,
        snippets: [],
    },
    actions:{
        initializeStore({ commit, state, dispatch }) {
            dispatch('fetchTags');
            dispatch('fetchSnippets');
            const token = localStorage.getItem('accessToken');
            if (token) {
                commit('SET_USER_TOKEN', token)
                dispatch('login')
            }
        },

        login({commit, state}){
            return axios.get('/api/user', {
                headers: {
                    Authorization: `Bearer ${state.token}`
                }
            }).then(({data})=>{
                commit('SET_USER',data)
                commit('SET_AUTHENTICATED',true)
            }).catch(({response:{data}})=>{
                commit('SET_USER',{})
                commit('SET_AUTHENTICATED',false)
            })
        },
        logout({commit}){
            commit('SET_USER',{})
            commit('SET_USER_TOKEN', null)
            commit('SET_AUTHENTICATED',false)
        },
        fetchTags({ commit }) {
            axios.get('/api/tags')
                .then(response => {
                    const tags = Object.entries(response.data).map(([key, value]) => ({
                        slug: key,
                        name: value
                    }));
                    commit('SET_TAGS', tags);
                })
                .catch(error => {
                    console.error('Error fetching tags:', error);
                });
        },
        fetchSnippets({ commit }){
            axios.get('/api/snippets').then(response => {
                commit('SET_SNIPPETS', response.data);
                this.snippets = response.data;
            });
        }

    },
    mutations: {
        SET_AUTHENTICATED (state, value) {
            state.authenticated = value
        },
        SET_USER (state, value) {
            state.user = value
        },
        SET_USER_TOKEN(state,value){
          if(value){
              state.token = value;
              localStorage.setItem('accessToken', value);
          }else{
              state.token = value;
              localStorage.removeItem('accessToken');
          }

        },
        SET_SHOWLOGIN(state, value){
            state.showLogin = value;
        },
        SET_SHOWCREATOR(state, value){
            state.showCreator = value;
        },
        SET_TAGS(state, tags) {
            state.tags = tags;
        },
        SET_SNIPPETS(state, snippets){
            state.snippets = snippets
        },
        setSelectedTag(state, tag) {
            state.selectedTag = tag;
            state.searchQuery = null;
        },
        setSearchQuery(state, query) {
            state.searchQuery = query;
            state.selectedTag = null;
        },
        addSnippet(state, snippet) {
            state.snippets.push(snippet);
        }
    }
});