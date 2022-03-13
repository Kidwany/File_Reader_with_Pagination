require('./bootstrap');

window.Vue = require('vue').default;

import {createApp, onMounted} from 'vue'
const app = createApp({})
/* ---------- Dashboard Components ------------*/
/* ----------Appointment Components ------------- */
import FileReader from './components/FileReaderComponent'
app.component("file-reader-component", FileReader)
app.mount("#app")

