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
import FlashMessage from '@/Components/FlashMessage.vue';
import StoreItemImagesForm from '@/Pages/Items/Partials/StoreItemImagesForm.vue';
import StoreItemDetailForm from '@/Pages/Items/Partials/StoreItemDetailForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, onMounted } from 'vue';

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

const itemImages = computed(() => {
    return props.items.map(item => {
        return '/storage/images/items/' + item.item_images[0].image_path;
    });
});

const sellerImages = computed(() => {
    return props.items.map(item => {
        return '/storage/images/users/' + item.user.user_image.image_path ?? '/storage/images/users/default.jpg';
    });
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
    <Head title="ホーム" />

    <AuthenticatedLayout>

        <div class="flex justify-center items-center h-20 md:h-36 bg-cover bg-center object-cover object-center rainy-background" style="background-image: url('/images/header-image.jpeg')">
            <h2 class="font-great-vibes text-xl md:text-3xl text-light leading-tight text-center">Here's to your fabulous find!</h2>
        </div>

        <section class="text-light body-font">

            <div class="container px-5 py-10 mx-auto">

                <div class="flex justify-between items-end py-1">
                    <!-- お気に入り表示スイッチ -->
                    <div class="flex items-center">
                        <input type="checkbox" id="hs-xs-switch" class="relative w-[35px] h-[21px] bg-gray-800 border-gray-700 text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-offset-gray-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-primary checked:border-dark focus:checked:border-dark before:inline-block before:w-4 before:h-4 before:bg-white checked:before:bg-light before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 focus:outline-none focus:ring-0">
                        <label for="hs-xs-switch" class="text-sm text-gray-400 ms-2">お気に入りのみを表示</label>
                    </div>

                    <!-- 検索エリア -->
                    <div class="hidden md:block">
                        <div class="relative">
                            <input type="text" id="hs-leading-icon" name="hs-leading-icon" class="py-3 px-4 ps-11 block w-full shadow-sm rounded-lg text-sm focus:z-10 disabled:opacity-50 disabled:pointer-events-none bg-gray-800 border-none text-gray-400 focus:ring-gray-600 placeholder-light" placeholder="何をお探しですか？">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                <svg class="flex-shrink-0 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- 検索エリア レスポンシブ対応（スマホサイズで表示）  -->
                    <div class="relative md:hidden">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-2 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                            <svg class="flex-shrink-0 h-6 w-6 mb-5 -ml-10 text-gray-400 text-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.3-4.3" />
                            </svg>
                        </div>
                    </div>
                </div>

                <hr class="border-light py-2 mt-2">

                <!-- 商品一覧 -->
                <div class="flex flex-wrap -m-4">
                    <div class="p-4 md:w-1/3" v-for="(item, index) in items" :key="item.id">
                        <div class="h-full rounded-lg overflow-hidden">
                            <!-- 商品画像 -->
                            <Link class="group relative block rounded-xl overflow-hidden dark:focus:outline-none" :href="route('items.show', { item: item.id })">
                            <div class="aspect-w-12 aspect-h-7 sm:aspect-none rounded-xl overflow-hidden">
                                <img class="group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-xl h-72 w-96 md:w-full object-cover" :src="itemImages[index]" alt="Image Description">
                            </div>
                            </Link>
                            <!-- 商品情報 -->
                            <div class="p-6">
                                <h2 class="tracking-widest text-xs title-font font-medium text-light mb-1">{{ item.brand ?? '-' }}</h2>
                                <h1 class="title-font text-base font-medium text-light mb-3">{{ item.name }}</h1>
                                <p class="leading-relaxed text-orange-300 text-3xl font-bold mb-3"><span class="text-lg">¥ </span>{{ formattedPrices[index] }}</p>
                                <div class="flex items-center flex-wrap ">
                                    <!-- 出品者画像 -->
                                    <Link class="inline-flex items-center md:mb-2 lg:mb-0 cursor-pointer" as="button">
                                    <img class="inline-block h-[2.75rem] w-[2.75rem] rounded-full object-cover" :src="sellerImages[index]" alt="seller image">
                                    </Link>
                                    <!-- コメント -->
                                    <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm py-1">
                                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                        </svg><span class="text-light">0</span>
                                    </span>
                                    <!-- お気に入り -->
                                    <span class="text-gray-400 inline-flex items-center leading-none text-sm -mr-2">
                                        <button class="rounded-full w-8 h-8 bg-black hover:bg-gray-600 p-0 border-0 inline-flex items-center justify-center text-gray-500">
                                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                                            </svg>
                                        </button>
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
