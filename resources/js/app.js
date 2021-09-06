require('./bootstrap');

import { createApp, defineAsyncComponent } from "vue";
import PortfolioSlider from "./components/PortfolioSlider.vue";
const app = createApp({
    components: {
        PortfolioSlider,
        GraphiumFlap: defineAsyncComponent(() => import('./components/GraphiumFlap.vue')),
    },
}).mount("#app");

const markdownArticles = document.querySelectorAll('[data-is-md]')
if (markdownArticles.length > 0) {
    // 本当はサニタイズしないといけないんだけど、今回は記事の中身がすべて自分の管理化にあるので省略
    import('marked').then(module => module.default).then(marked => markdownArticles.forEach(el => el.innerHTML = marked(el.innerText)))
}
