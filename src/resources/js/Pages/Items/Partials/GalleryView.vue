<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 */
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} itemImages - 商品画像の一覧を含む配列
 */
const props = defineProps({
    itemImages: Array,
});

/**
 * カテゴリフォームを表示するためのリアクティブなプロパティ。
 *
 * @type {boolean} - カテゴリフォームを表示するかどうかを示すブール値
 */
const galleryViewButton = ref(false);

const currentIndex = ref(0); // 現在の画像のインデックスを追跡

/**
 * カテゴリフォームを表示する関数。
 */
const gallerySelection = () => {
    galleryViewButton.value = true;
    currentIndex.value = 0;
};

/**
 * カテゴリフォームを非表示にする関数。
 */
const closeModal = () => {
    galleryViewButton.value = false;
};

/**
 * 次の画像を表示する関数。
 */
const showNextImage = () => {
    if (currentIndex.value < props.itemImages.length - 1) {
        currentIndex.value++;
    } else {
        currentIndex.value = 0; // 最後の画像の後に最初の画像に戻る
    }
};

/**
 * 前の画像を表示する関数。
 */
const showPreviousImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    } else {
        currentIndex.value = props.itemImages.length - 1; // 最初の画像の前に最後の画像に移動
    }
};
</script>

<template>
    <div class="container px-5 py-5 mx-auto flex flex-wrap justify-center">
        <div class="flex flex-wrap md:-m-2 -m-1 md:w-2/3">
            <div class="flex flex-wrap w-1/2">
                <div class="md:p-2 p-1 w-1/2">
                    <img alt="gallery" class="w-full object-cover h-28 md:h-52 object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[1]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
                <div class="md:p-2 p-1 w-1/2">
                    <img alt="gallery" class="w-full object-cover h-28 md:h-52 object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[2]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
                <div class="md:p-2 p-1 w-full">
                    <img alt="gallery" class="w-full h-52 md:h-96 object-cover object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[0]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
            </div>
            <div class="flex flex-wrap w-1/2">
                <div class="md:p-2 p-1 w-full">
                    <img alt="gallery" class="w-full h-52 md:h-96 object-cover object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[3]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
                <div class="md:p-2 p-1 w-1/2">
                    <img alt="gallery" class="w-full h-28 object-cover md:h-52 object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[4]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
                <div class="md:p-2 p-1 w-1/2">
                    <img alt="gallery" class="w-full h-28 object-cover md:h-52 object-center block cursor-pointer" :src="'/storage/images/items/' + (itemImages[5]?.image_path ?? 'notfound.png')" @click="gallerySelection">
                </div>
            </div>
        </div>
    </div>

    <Modal :show="galleryViewButton" @close="closeModal">
        <div class="p-4 bg-dark">
            <div class="flex justify-between mb-2">
                <a class="text-light flex items-center cursor-pointer" @click="showPreviousImage">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    <span class="text-sm">前の画像</span>
                </a>
                <div class="text-light">
                    {{ currentIndex + 1 }} / {{ itemImages.length }}
                </div>
                <a class="text-light flex items-center cursor-pointer" @click="showNextImage">
                    <span class="text-sm">次の画像</span>
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 6l6 6-6 6" />
                    </svg>
                </a>
            </div>
            <img :src="'/storage/images/items/' + (itemImages[currentIndex]?.image_path ?? 'notfound.jpeg')" alt="...">
        </div>
    </Modal>
</template>
