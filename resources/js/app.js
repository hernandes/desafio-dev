window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import {createApp, h} from "vue";
import App from "./Pages/App";

const app = createApp({ render: () => h(App) });
app.mount("#app");
