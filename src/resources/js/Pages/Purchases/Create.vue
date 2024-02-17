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
import ItemDetailView from '@/Pages/Purchases/Partials/ItemDetailView.vue';
import CommentForm from '@/Pages/Purchases/Partials/CommentForm.vue';
import CartView from '@/Pages/Purchases/Partials/CartView.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { computed, ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} item - 商品情報を含むオブジェクト
 * @property {Array} comments - 商品に対するコメントの一覧を含む配列
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 * @property {Array} payments - 支払い方法の一覧を含む配列
 */
const props = defineProps({
    item: Object,
    comments: Array,
    categories: Array,
    conditions: Array,
    payments: Array,
});

/**
 * 出品者の画像アイコンを取得するための算出プロパティ。
 * 出品者の画像アイコンが存在する場合は、画像のパスを返し、存在しない場合はデフォルトのアイコンを返す。
 *
 * @returns {String} - 出品者のアイコンのパス
 */
const sellerIcon = computed(() => {
    return props.item.user.user_image.image_path ? '/storage/images/users/' + props.item.user.user_image.image_path : '/images/default-user-icon.jpg';
});

/**
 * 価格のフォーマットを提供する算出プロパティ。
 */
const formattedPrices = computed(() => {
    if (!props.item.price) return '---';
    return props.item.price.toLocaleString();
});

/**
 * 商品の状態名を取得するための算出プロパティ。
 */
const conditionName = computed(() => {
    // conditions 配列から item.condition_id に一致するオブジェクトを探す
    const condition = props.conditions.find(c => c.id === props.item.condition_id);
    // 条件名を返す。条件オブジェクトが見つからない場合はデフォルトのテキストを返す
    return condition ? condition.name : '不明';
});

/**
 * 商品のカテゴリパスを構築するための関数。
 *
 * @param {Number} categoryId - 商品のカテゴリID
 * @returns {String} - 商品のカテゴリパス
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
 * 商品のカテゴリパスを取得するための算出プロパティ。
 */
const categoryPath = computed(() => {
    return buildCategoryPath(props.item.category_id);
});

/**
 * カートアイテム情報を表示するためのリアクティブなプロパティ。
 */
const cartItemInformation = ref(false);

/**
 * カートアイテム情報を表示するための関数。
 * その際、ページのトップにスクロールする。
 */
const addCartItem = () => {
    cartItemInformation.value = true;
    window.scrollTo(0, 0);
}

/**
 * カートアイテム情報を非表示にするための関数。
 * その際、ページのトップにスクロールする。
 */
const closeCartItem = () => {
    cartItemInformation.value = false;
    window.scrollTo(0, 0);
}

/**
 * ユーザーを前のページに戻す関数。
 */
const goBack = () => {
    Inertia.get(route('home'));
}
</script>

<template>
    <Head title="購入" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-center items-center">
                <Link class="inline-flex items-center md:mb-2 lg:mb-0 cursor-pointer" as="button">
                <img class="inline-block h-[3.5rem] w-[3.5rem] rounded-full object-cover" :src="sellerIcon" alt="seller image">
                </Link>
                <h2 class="font-great-vibes text-xl text-light leading-tight text-center ml-4">Thank You for Your Interest!</h2>
            </div>
        </template>

        <section class="text-gray-600 body-font">
            <!-- 戻るリンクエリア -->
            <div class="my-4 text-center md:text-left md:ml-36 2xl:ml-96">
                <a class="inline-flex items-center gap-x-1 text-sm text-light hover:text-indigo-400 cursor-pointer" @click="goBack">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    戻る
                </a>
            </div>

            <!-- 商品画像エリア -->
            <GalleryView :itemImages="item.item_images" v-if="!cartItemInformation" />

            <!-- 商品詳細エリア -->
            <ItemDetailView :item="item" :categoryPath="categoryPath" :conditionName="conditionName" :formattedPrices="formattedPrices" @addCartItem="addCartItem" v-if="!cartItemInformation" />

            <!-- コメントフォームエリア -->
            <CommentForm :item="item" :comments="comments" v-if="!cartItemInformation" />

            <!-- 商品カートエリア -->
            <CartView :item="item" :formattedPrices="formattedPrices" :payments="payments" @closeCartItem="closeCartItem" v-if="cartItemInformation" />

        </section>

    </AuthenticatedLayout>
</template>
