require('./bootstrap');

import { createApp, defineAsyncComponent } from "vue";
import PortfolioSlider from "./components/PortfolioSlider.vue";
const app = createApp({
    components: {
        PortfolioSlider,
        GraphiumFlap: defineAsyncComponent(() => import('./components/GraphiumFlap.vue')),
    },
}).mount("#app");
