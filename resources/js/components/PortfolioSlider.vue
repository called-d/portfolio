<template>
    <section role="feed" :aria-busy="isBusy" tabindex="0" v-on="keyboardEvents"
             class="portfolio_slider" :style="{ '--articlesCount': articles.length }">
        <article v-for="(article, i) in articles" :key="article.id"
                 :id="article.slug" class="portfolio_article card" :class="classes(i)" :style="transformation(i)"
                 tabindex="-1"
                 :aria-posinset="i + 1" :aria-setsize="articles.length">
                 <h4>{{ article.title }}</h4>
                 <div>{{ article.content }}</div>
        </article>
        <svg aria-hidden="true" class="ui-img img-binder"
             viewBox="-50 -34 100 60">
            <path d="M-48,-24 l12,48 h70 l-12,-48 h-35 l-7,-7 h-25 z" fill="none" stroke="black" stroke-width="3"></path>
        </svg>
        <svg aria-hidden="true" class="ui-img img-binder" style="z-index: 2"
             viewBox="-50 -34 100 60">
            <path d="M34,24 m3,-4 l10,-40 h-16" fill="none" stroke="black" stroke-width="3"></path>
        </svg>
        <button type="button" class="ui"></button>
        <button type="button" class="ui"></button>
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
            classes (i: number) {
                let pos = ringIndexes.value[i]
                return [
                    pos <= 0 ? 'out-of-binder' : 'in-binder',
                    pos === -1 || pos === props.articles.length - 2 ? 'will-animate' : '',
                ];
            },
            transformation (i: number) {
                let pos = ringIndexes.value[i]
                return {
                    '--pos': pos,
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
@import '../../css/named-media-query';

.portfolio {
    &_slider {
        --sliderHeight: 80vh;
        --sliderWidth: 100vw;
        --binderHeight: 30vh;
        --binderWidth: 50vh;
        --outY: calc(var(--binderHeight) * 0.8);

        position: relative;
        overflow-x: hidden;
        @include m('pc') {
            --sliderWidth: 900px;
            margin: 3em auto; // マージン
        }
        margin: 1em auto; // WIP マージンはコンポーネントの外に持たせたいけどここでしか使わないからそのまま書く
        height: var(--sliderHeight);
        width: var(--sliderWidth);

        .ui, .ui-img {
            position: absolute;
            z-index: calc(var(--articlesCount, 0) + 10);
        }
        .ui {
            user-select: none;
        }

        .img-binder {
            height: calc(var(--binderHeight));

            bottom: 0;
            right: 0;
            max-width: 100vw;
        }
    }
    &_article {
        &.hidden { display: none; }

        &.card {
            position: absolute;
            left: 50%;

            border: 3px solid #999;

            --cardWidth: 65%;
            --cardHeight: 250px;
            @include m('pc') {
                --cardWidth: 40%;
                --cardHeight: 35vh;
                min-height: 250px;
            }
            width: var(--cardWidth);
            height: var(--cardHeight);

            transition-property: z-index, transform;
            transition-duration: 300ms;
            transition-timing-function: step-end, ease;

            z-index: calc(5 + var(--pos));
            --baseOffsetY: calc((var(--pos) + 1) * 10vh / var(--articlesCount));
            --baseOffsetY: 0px;
            --Y: var(--baseOffsetY);
            &.out-of-binder {
                --offsetX: calc(var(--pos) * 10% / var(--articlesCount));
            }
            &.in-binder {
                --offsetX: calc(var(--sliderWidth) / 2 - 50% + (-1 * var(--articlesCount) + var(--pos)) * 10% / var(--articlesCount));

                // + 2 は-1始まりだから, 0オリジンだから
                // -2vh はbinder の下の線からの浮き
                --baseY: calc(var(--sliderHeight) - var(--cardHeight));
                --offsetY: calc(-2vh + ((var(--pos) + 2) - var(--articlesCount)) * 10vh / var(--articlesCount));
                --Y: calc(var(--baseY) + var(--offsetY));
            }
            &.will-animate {
                opacity: 0.3;
            }
            transform: translate(calc(-50% + var(--offsetX, 0%)), var(--Y));

            background: white;
        }
    }
}
</style>
