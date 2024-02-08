<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    formFile: Object,
});

const emit = defineEmits(['update:formFile']);

/**
 * プロフィール画像のプレビュー用のURLを提供するリアクティブなプロパティ。
 *
 *  @type {string} - プロフィール画像のファイルパス
 */
let itemImages = ref(null);

/**
 * ファイル選択時に呼び出され、画像プレビューを更新する。
 *
 * @param {Event} event - ファイル入力イベント
 */
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            itemImages.value = e.target.result;
            emit('update:formFile', file); // ここでカスタムイベントを発火
        };
        reader.readAsDataURL(file);
    }
};

watch(() => props.formFile, (newVal) => {
    if (Array.isArray(newVal) && newVal.length === 0) {
        itemImages.value = null; // ファイルがクリアされたら画像プレビューもクリア
    }
}, { immediate: true });
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">商品画像の登録</h2>

            <p class="mt-1 mb-5 text-sm text-gray-600">
                商品画像を登録してください。
            </p>
        </header>

        <img class="inline-block h-[8rem] w-[8rem] rounded-full object-cover" v-show="itemImages" :src="itemImages" alt="Image Description">

        <div class="col-span-full">
            <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                    </svg>
                    <div class="mt-4 flex flex-col text-sm leading-6 text-gray-600">
                        <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                            <span>画像をアップロード</span>
                            <input id="file-upload" name="file-upload" type="file" class="sr-only" ref="file" @change="handleFileChange" @input="formFile = $event.target.files[0]" />
                        </label>
                        <p class="hidden pl-1 md:block">またはこちらにドラッグ&ドロップしてください</p>
                    </div>
                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, JPEG up to 10MB</p>
                </div>
            </div>
        </div>
    </section>
</template>
