<template>
    <section role="feed" :aria-busy="isBusy" tabindex="0" v-on="keyboardEvents">
        <article v-for="(article, i) in articles" :key="article.id"
                 :id="article.slug" class="portfolio_article"
                 tabindex="-1"
                 :aria-posinset="i + 1" :aria-setsize="articles.length">
                 {{ i == selectedIndex ? '★' : '' }} {{ article.title }}
                 {{ article.content }}
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

        return {
            isBusy,
            selectedIndex,
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
.portfolio_article {
    &.hidden { display: none; }
}
</style>
