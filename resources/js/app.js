require('./bootstrap');

import { createApp } from "vue";
import PortfolioSlider from "./components/PortfolioSlider.vue";
const app = createApp({
    components: {
        PortfolioSlider,
    },
}).mount("#app");
