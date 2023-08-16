import './bootstrap';
import { createApp } from 'vue';
import store from './store/index.js';
import MasonryWall from '@yeger/vue-masonry-wall'
import MainLayout from './components/MainLayout.vue';
import NavigatorComponent from './components/NavigatorComponent.vue';
const app = createApp({});
app.use(store);
app.use(MasonryWall)
app.component('navigator-component', NavigatorComponent);
app.component('main-layout', MainLayout);
app.mount('#app');
