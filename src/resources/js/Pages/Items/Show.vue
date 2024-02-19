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
import GalleryView from '@/Pages/Items/Partials/GalleryView.vue';
import ItemDetailView from '@/Pages/Items/Partials/ItemDetailView.vue';
import PurchaseView from '@/Pages/Items/Partials/PurchaseView.vue';
import CommentForm from '@/Pages/Purchases/Partials/CommentForm.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} item - 商品情報を含むオブジェクト
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 * @property {Array} purchases - 商品の購入情報を含む配列
 * @property {Array} comments - 商品のコメント情報を含む配列
 * @property {Array} statuses - 商品のステータス情報を含む配列
 */
const props = defineProps({
    item: Object,
    categories: Array,
    conditions: Array,
    purchases: Array,
    comments: Array,
    statuses: Array,
});

/**
 * 価格のフォーマットを提供する算出プロパティ。
 */
const formattedPrices = computed(() => {
    if (!props.item.price) return '---';
    return props.item.price.toLocaleString();
});

// 条件名を取得するための算出プロパティ
const conditionName = computed(() => {
    // conditions 配列から item.condition_id に一致するオブジェクトを探す
    const condition = props.conditions.find(c => c.id === props.item.condition_id);
    // 条件名を返す。条件オブジェクトが見つからない場合はデフォルトのテキストを返す
    return condition ? condition.name : '不明';
});

/**
 * カテゴリパスを構築する関数。
 *
 * @param {number} categoryId - カテゴリID
 */
const buildCategoryPath = (categoryId) => {
    const path = [];
    let currentCategory = props.categories.find(category => category.id === categoryId);
    while (currentCategory) {
        path.unshift(currentCategory.name); // パスの先頭にカテゴリ名を追加
        currentCategory = props.categories.find(category => category.id === currentCategory.parent_id);
    }
    return path.join(' > '); // カテゴリパスを文字列として結合
};

/**
 * 商品のカテゴリパスを提供する算出プロパティ。
 */
const categoryPath = computed(() => {
    return buildCategoryPath(props.item.category_id);
});

/**
 * ユーザーを前のページに戻す関数。
 */
const goBack = () => {
    Inertia.get(route('items.index'));
}
</script>

<template>
    <Head title="出品履歴" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">商品の詳細</h2>
        </template>

        <section class="text-gray-600 body-font">
            <!-- 戻るリンクエリア -->
            <div class="my-4 text-center md:text-left md:ml-36 2xl:ml-96 2xl:px-80">
                <a class="inline-flex items-center gap-x-1 text-sm text-light hover:text-indigo-400 cursor-pointer" @click="goBack">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    戻る
                </a>
            </div>

            <!-- 商品画像エリア -->
            <GalleryView :itemImages="item.item_images" />

            <!-- 商品詳細エリア -->
            <ItemDetailView :item="item" :categoryPath="categoryPath" :conditionName="conditionName" :formattedPrices="formattedPrices" />

            <!-- 購入情報エリア -->
            <PurchaseView :purchases="purchases" :statuses="statuses" />

            <!-- コメントフォームエリア -->
            <CommentForm :item="item" :comments="comments" />

        </section>

    </AuthenticatedLayout>
</template>
