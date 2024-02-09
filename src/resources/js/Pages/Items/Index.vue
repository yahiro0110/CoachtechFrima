<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires StoreItemImagesForm - 商品画像をアップロードするためのフォームコンポーネント
 * @requires StoreItemDetailForm - 商品の詳細情報を入力するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 * @requires useForm - Inertia.jsのフォームハンドリング機能を提供し、フォームの状態管理や送信時の処理を容易にする
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StoreItemImagesForm from '@/Pages/Items/Partials/StoreItemImagesForm.vue';
import StoreItemDetailForm from '@/Pages/Items/Partials/StoreItemDetailForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    items: Array,
    categories: Array,
    conditions: Array,
});

/**
 * 価格のフォーマットを提供する算出プロパティ。
 */
const formattedPrices = computed(() => {
    return props.items.map(item => {
        if (!item.price) return '---';
        return item.price.toLocaleString();
    });
});

/**
 * 商品情報を登録する関数。
 *
 * Inertia.jsのpostメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時にはフォームの送信状態を更新し、エラー時にはエラーメッセージを表示するための処理が含まれる。
 * `preserveScroll`はページ遷移後のスクロール位置維持に使用。
 * `onSuccess`はフォーム送信成功時に実行されるコールバック関数で、`form.recentlySuccessful`をtrueに設定する。
 */
const StoreItem = () => {
    // imagesFormRef からファイルリストを取得
    form.files = imagesFormRef.value.getSelectedFiles();

    form.post(route('items.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.recentlySuccessful = true;
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="出品履歴" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">商品した商品</h2>
        </template>

        <section class="text-gray-600 body-font">
            <!-- navigation area -->
            <div class="flex flex-col items-center">
                <div class="border-b-2 border-gray-200 w-3/4">
                    <nav class="mb-1 flex justify-center space-x-6">
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#">
                            出品中
                        </a>
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80  focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#" aria-current="page">
                            取引中
                        </a>
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80  focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#">
                            販売履歴
                        </a>
                    </nav>
                </div>
            </div>

            <!-- items area -->
            <div class="container px-5 py-10 mx-auto">
                <div class="flex flex-wrap -m-4">
                    <div class="p-4 md:w-1/3" v-for="(item, index) in items" :key="item.id">
                        <div class="h-full rounded-lg overflow-hidden hover:shadow-md hover:shadow-gray-200">
                            <img class="h-72 md:h-72 w-96 md:w-full object-cover object-center rounded-lg" :src="'/storage/images/items/' + item.item_images[0].image_path" alt="item images">
                            <div class="p-6">
                                <h2 class="tracking-widest text-xs title-font font-medium text-light mb-1">{{ item.brand ?? '-' }}</h2>
                                <h1 class="title-font text-base font-medium text-light mb-3">{{ item.name }}</h1>
                                <p class="leading-relaxed text-orange-300 text-3xl font-bold mb-3"><span class="text-lg">¥ </span>{{ formattedPrices[index] }}</p>
                                <div class="flex items-center flex-wrap ">
                                    <Link class="text-indigo-400 inline-flex items-center md:mb-2 lg:mb-0 cursor-pointer" as="button" :href="route('items.show', { item: item.id })">詳細
                                    <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5l7 7-7 7"></path>
                                    </svg>
                                    </Link>
                                    <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm py-1">
                                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                        </svg><span class="text-light">0</span>
                                    </span>
                                    <!-- <span class="text-gray-400 inline-flex items-center leading-none text-sm ml-2 -mr-2">
                                        <button class="rounded-full w-8 h-8 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500">
                                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                                            </svg>
                                        </button>
                                    </span> -->
                                    <span class="text-gray-400 inline-flex items-center leading-none text-sm">
                                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5 mr-1" viewBox="0 0 24 24">
                                            <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                                        </svg><span class="text-light">10</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </AuthenticatedLayout>
</template>
