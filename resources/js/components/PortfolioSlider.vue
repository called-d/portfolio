<template>
    <section role="feed" :aria-busy="isBusy" tabindex="0" v-on="keyboardEvents"
             class="portfolio_slider">
        <article v-for="(article, i) in articles" :key="article.id"
                 :id="article.slug" class="portfolio_article card" :style="transformation(i)"
                 tabindex="-1"
                 :aria-posinset="i + 1" :aria-setsize="articles.length">
                 <h4>{{ article.title }}</h4>
                 <div>{{ article.content }}</div>
        </article>
    </section>
</template>

<script lang="ts">
import { defineComponent, PropType, ref, computed } from "vue"
import Article from "../models/Article"

export default defineComponent({
    props: {
        articles: {
            type: Array as PropType<Article[]>,
            required: true,
        }
    },
    mounted () {
        this.$el.focus()
    },
    setup(props) {
        const isBusy = ref(false)
        const selectedIndex = ref(0)
        const selectedArticle = computed(() => props.articles[selectedIndex.value])

        const focus = () => document.getElementById(selectedArticle.value.slug)?.focus()
        const selectFirst = () => {
            selectedIndex.value = 0
            focus()
        }
        const selectLast = () => {
            selectedIndex.value = props.articles.length - 1
            focus()
        }
        const selectNext = () => {
            selectedIndex.value = (selectedIndex.value + 1) % props.articles.length
            focus()
        }
        const selectPrev = () => {
            selectedIndex.value = (selectedIndex.value + props.articles.length - 1) % props.articles.length
            focus()
        }

        // selectedArticleが0になるような -1 からのインデックス
        const ringIndexes = computed(() => props.articles.map((_, i) => (props.articles.length + i + -selectedIndex.value + 1) % props.articles.length - 1))

        return {
            isBusy,
            selectedIndex,
            transformation (i: number) {
                let zIndex: number
                let pos = ringIndexes.value[i]
                let offsetX = (pos <= 0 ? pos : pos + 40) * 10 / props.articles.length // TODO 本当に 10 なのかは議論の余地が

                zIndex = 0
                console.log({ zIndex, offsetX })
                return {
                    'zIndex': 5 + pos,
                    '--offsetX': offsetX + '%',
                    'opacity': (pos === -1 || pos === props.articles.length - 2) ? 0.3 : 1.0,
                }
            },
            keyboardEvents: {
                keydown: (e: KeyboardEvent) => {
                    switch (e.code) {
                        case "ArrowDown": // fallthrough
                        case "ArrowRight":
                            if (e.ctrlKey || e.metaKey) {
                                selectLast()
                                break
                            }
                            // fallthrough
                        case "PageDown":
                            selectNext()
                            break
                        case "ArrowUp": // fallthrough
                        case "ArrowLeft":
                            if (e.ctrlKey || e.metaKey) {
                                selectFirst()
                                break
                            }
                            // fallthrough
                        case "PageUp":
                            selectPrev()
                            break
                        case "End":
                            if (e.ctrlKey || e.metaKey) {
                                selectLast()
                            }
                            break
                        case "Home":
                            if (e.ctrlKey || e.metaKey) {
                                selectFirst()
                            }
                            break
                        default:
                    }
                }
            }
        }
    }
})
</script>

<style lang="scss" scoped>
.portfolio {
    &_slider {
        position: relative;
        overflow-x: hidden;
        width: 100vw;
        height: 80vh;
    }
    &_article {
        &.hidden { display: none; }

        &.card {
            position: absolute;
            left: 50%;

            width: 65%;
            border: 3px solid #999;

            transition-property: z-index, transform;
            transition-duration: 300ms;
            transition-timing-function: step-end, ease;
            transform: translateX(calc(-50% + var(--offsetX, 0%)));

            background: white;
        }
    }
}
</style>
