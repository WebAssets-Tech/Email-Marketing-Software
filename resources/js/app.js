import './bootstrap'
import { createApp } from 'vue/dist/vue.esm-bundler';
import Editor from './components/Editor.vue'

const app = createApp({});
app.component('Editor', Editor);

app.mount("#vue-app");
