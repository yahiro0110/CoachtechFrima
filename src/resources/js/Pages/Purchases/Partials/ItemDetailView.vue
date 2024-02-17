<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import { computed } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 */
const props = defineProps({
    item: Object,
    categoryPath: String,
    conditionName: String,
    formattedPrices: String,
});

/**
 * @requires defineEmits - カスタムイベントを定義するための関数
 */
const emit = defineEmits(['addCartItem']);

/**
 * 商品の説明を整形する算出プロパティ。
 * 商品の説明が改行を含む場合、改行を`<br>`タグに変換して整形する。
 * また商品の説明が存在しない場合は、"特になし"という文字列を返す。
 *
 * @type {string} formattedDescription - 商品の説明を整形した文字列
 */
const formattedDescription = computed(() => {
    return props.item.description ? props.item.description.replace(/\n/g, '<br>') : '特になし';
});

/**
 * 商品の購入手続きボタンがクリックされたときに発火する関数。
 * 商品の購入手続きボタンがクリックされたときに、`addCartItem`イベントを発火する。
 */
const handleAddToCart = () => emit('addCartItem');
</script>

<template>
    <div class="mt-10 flex flex-col sm:w-full md:w-full 2xl:w-1/2 px-7 md:px-20 mx-auto">
        <div class="md:flex md:justify-between md:items-start">
            <div class="md:w-1/2">
                <h2 class="tracking-widest text-sm title-font font-medium text-light mb-1">{{ item.brand ?? '-' }}</h2>
                <h1 class="sm:text-3xl text-2xl font-medium title-font text-light lg:mb-0 mb-4">{{ item.name }}</h1>

                <div class="mt-10">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">商品の説明</h1>
                    <p class="mx-auto leading-relaxed text-base" v-html="formattedDescription" :class="{ 'text-light': item.description }"></p>
                </div>

                <div class="mt-10">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">商品の情報</h1>

                    <div class="md:flex">
                        <p class="leading-relaxed text-sm text-light font-bold mb-1 md:mb-2 md:mr-24">カテゴリ</p>
                        <p class="leading-relaxed text-sm text-light mb-3 md:mb-0">{{ categoryPath }}</p>
                    </div>

                    <div class="md:flex">
                        <p class="leading-relaxed text-sm text-light font-bold mb-1 md:mb-2 md:mr-20">商品の状態</p>
                        <p class="leading-relaxed text-sm text-light">{{ conditionName }}</p>
                    </div>
                </div>
            </div>

            <div class="lg:w-2/6 md:w-1/2 bg-dark rounded-lg p-8 flex flex-col md:ml-auto w-full mt-10 md:mt-0">
                <h2 class="text-light text-lg font-medium title-font mb-0">販売価格（税込）</h2>
                <div class="relative mb-4">
                    <p class="leading-relaxed text-orange-300 text-4xl font-bold"><span class="text-lg">¥ </span>{{ formattedPrices }}</p>
                </div>
                <button class="text-light bg-primary border border-gray-700 py-2 px-8 focus:outline-none hover:bg-selected-button rounded text-lg" @click.prevent="handleAddToCart" v-if="!item.purchased">購入手続きへ</button>
                <p class="text-light bg-dark border border-gray-700 py-2 px-8 focus:outline-none rounded text-lg text-center" v-else>売り切れました</p>
            </div>
        </div>
    </div>
</template>
