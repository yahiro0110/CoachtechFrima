<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires StoreItemImagesForm - 商品画像をアップロードするためのフォームコンポーネント
 * @requires StoreItemDetailForm - 商品の詳細情報を入力するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import Modal from '@/Components/Modal.vue';
import { Inertia } from '@inertiajs/inertia';
import { computed, onMounted, ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} items - 商品の一覧を含む配列
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    items: Array,
    categories: Array,
    conditions: Array,
});

/**
 * リアクティブな商品情報の配列。
 * propsから取得した商品情報に基づいて初期化され、ユーザーの操作に応じて更新される。
 *
 * @type {ref<Array>} - リアクティブな商品情報の配列
 */
const reactiveItems = ref([]);

/**
 * モーダルダイアログを表示するためのリアクティブなプロパティ。
 *
 * @type {Ref<boolean>} - モーダルダイアログを表示するかどうかを示すブール値
 */
const searchItemByNameOrCategory = ref(false);

/**
 * モーダルダイアログを表示する関数。
 */
const openModal = () => searchItemByNameOrCategory.value = true;

/**
 * モーダルダイアログを非表示にする関数。
 */
const closeModal = () => searchItemByNameOrCategory.value = false;

/**
 * 各フィルタ条件を保持するリアクティブな参照。
 * 初期値はfalseもしくは空文字列で、ユーザーがフィルタ条件を選択すると更新される。
 *
 * @type {Ref<boolean>} favoriteOnly - お気に入りのみを表示するかどうかを保持するリアクティブな参照
 * @type {Ref<string>} itemNameFilterKey - 商品名でフィルタするためのキーワードを保持するリアクティブな参照
 */
const favoriteOnly = ref(false);
const itemNameFilterKey = ref('');

/**
 * フィルタ関数。
 * フィルタ条件に基づいて、商品情報をフィルタリングする。
 * filterByFavorite - お気に入りのみを表示するかどうかに基づいて商品情報をフィルタリング
 * filterByName - 商品名でフィルタするためのキーワードに基づいて商品情報をフィルタリング
 *
 * @param {Array} items - フィルタリングする商品情報の配列
 * @returns {Array} - フィルタリングされた商品情報の配列
 */
const filterByFavorite = (items) => favoriteOnly.value ? items.filter(item => item.liked) : items;
const filterByName = (items) => itemNameFilterKey.value ? items.filter(item => item.name.toLowerCase().includes(itemNameFilterKey.value.toLowerCase())) : items;

/**
 * 特定の検索条件でフィルタされた商品情報とその件数を取得する関数。
 *
 * @returns {Object} - フィルタされた商品情報とその件数を含むオブジェクト
 */
const getFilteredItemsAndCount = () => {
    let filtered = reactiveItems.value;
    // お気に入りによるフィルタ
    filtered = filterByFavorite(filtered);
    // 商品名によるフィルタが適用された商品の件数を計算
    const filteredByName = filterByName(filtered);
    return {
        filteredItems: filtered.length ? filteredByName : filtered, // 商品名フィルタが空の場合はお気に入りフィルタのみ適用
        filteredByNameCount: filteredByName.length,
    };
};

/**
 * フィルタされた商品情報を保持する算出プロパティ。
 * フィルタ条件に基づいて、filteredItemsを更新する。
 *
 * @type {Ref<Array>} - 商品名でフィルタされた商品情報の配列
 */
const filteredItems = computed(() => {
    const { filteredItems } = getFilteredItemsAndCount();
    return filteredItems;
});

/**
 * 商品名でフィルタされた商品情報の件数を保持する算出プロパティ。
 *
 * @type {Ref<number>} - 商品名でフィルタされた商品情報の件数
 */
const filteredByNameCount = computed(() => {
    const { filteredByNameCount } = getFilteredItemsAndCount();
    return filteredByNameCount;
});

/**
 * コンポーネントがマウントされた際に、propsから取得した商品情報からreactiveItemsを初期化する。
 */
onMounted(() => {
    reactiveItems.value = props.items.map(item => ({
        id: item.id,
        name: item.name,
        brand: item.brand,
        price: item.price ? item.price.toLocaleString() : '---',
        item_image: '/storage/images/items/' + item.item_images[0].image_path,
        seller_image: item.user.user_image.image_path ? '/storage/images/users/' + item.user.user_image.image_path : '/images/default-user-icon.jpg',
        favorite_count: item.favoriteUser_count, // お気に入り登録者数
        liked: item.user_attached === 1 ? true : false, // お気に入り登録済みかどうか
    }));
});

/**
 * 選択された商品のお気に入り状態を切り替える関数。
 * 選択された商品のlikedプロパティを切り替え、状態に応じてattachItemまたはattachItemを呼び出す。
 * restaurant.likedがtrue - attachRestaurantを呼び出し、お気に入り登録し、restaurant.favorite_countをインクリメント
 * restaurant.likedがfalse - detachRestaurantを呼び出し、お気に入り解除、restaurant.favorite_countをデクリメント
 *
 * @param {Object} item - お気に入り状態を切り替える店舗のオブジェクト
 */
const toggleLike = (item) => {
    item.liked = !item.liked;
    if (item.liked) {
        item.favorite_count++;
        attachItem(item);
    } else {
        item.favorite_count--;
        detachItem(item);
    }
}

/**
 * 特定の商品をユーザーのお気に入りに登録するための関数。
 * Inertia.jsを使用して、サーバーの `items.attach` ルートにPOSTリクエストを送信する。
 *
 * @param {Object} item - お気に入りに追加する商品のオブジェクト
 *                        必要なプロパティ: `id` - 商品の一意の識別子
 */
const attachItem = (item) => {
    Inertia.post(route('items.attach', { item: item.id }), {},
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

/**
 * 特定の商品をユーザーのお気に入りから削除するための関数。
 * Inertia.jsを使用して、サーバーの `items.detach` ルートにDELETEリクエストを送信する。
 *
 * @param {Object} item - お気に入りから削除する商品のオブジェクト
 *                        必要なプロパティ: `id` - 商品の一意の識別子
 */
const detachItem = (item) => {
    Inertia.delete(route('items.detach', { item: item.id }),
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}
</script>

<template>
    <Head title="ホーム" />

    <AuthenticatedLayout>
        <!-- トップ上部メッセージ -->
        <div class="flex justify-center items-center h-20 md:h-36 bg-cover bg-center object-cover object-center rainy-background" style="background-image: url('/images/header-image.jpeg')">
            <h2 class="font-great-vibes text-xl md:text-3xl text-light leading-tight text-center">Here's to your fabulous find!</h2>
        </div>

        <section class="text-light body-font">

            <div class="container px-5 py-10 mx-auto">

                <div class="flex justify-between items-end py-1 z-0">
                    <!-- お気に入り表示スイッチ -->
                    <div class="flex items-center">
                        <input type="checkbox" id="hs-xs-switch" class="relative w-[35px] h-[21px] bg-gray-800 border-gray-700 text-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:ring-offset-gray-600 disabled:opacity-50 disabled:pointer-events-none checked:bg-none checked:text-primary checked:border-dark focus:checked:border-dark before:inline-block before:w-4 before:h-4 before:bg-white checked:before:bg-light before:translate-x-0 checked:before:translate-x-full before:rounded-full before:shadow before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 focus:outline-none focus:ring-0" v-model="favoriteOnly">
                        <label for="hs-xs-switch" class="text-sm text-gray-400 ms-2">お気に入りのみを表示</label>
                    </div>

                    <!-- 検索エリア -->
                    <div class="w-8 md:w-64 md:block">
                        <div class="relative">
                            <input type="text" id="hs-leading-icon" name="hs-leading-icon" class="md:py-3 md:px-4 md:ps-11 block w-0 md:w-full shadow-sm rounded-lg text-sm focus:z-10 disabled:opacity-50 disabled:pointer-events-none bg-black md:bg-gray-800 border-none text-gray-400 focus:ring-black md:focus:ring-gray-600 placeholder-gray-400" placeholder="何をお探しですか？" @click="openModal" :value="itemNameFilterKey">
                            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                                <svg class="flex-shrink-0 md:h-4 md:w-4 h-6 w-6 -mb-4 md:mb-0 -ml-3 md:ml-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-700 py-2 mt-2">

                <!-- 商品一覧 -->
                <div class="flex flex-wrap -m-4">
                    <div class="p-4 md:w-1/5" v-for="(item, index) in filteredItems" :key="item.id">
                        <div class="h-full rounded-lg overflow-hidden">
                            <!-- 商品画像 -->
                            <Link class="group relative block rounded-xl overflow-hidden dark:focus:outline-none" :href="route('items.show', { item: item.id })">
                            <div class="aspect-w-12 aspect-h-7 sm:aspect-none rounded-xl overflow-hidden">
                                <img class="group-hover:scale-105 transition-transform duration-500 ease-in-out rounded-xl h-72 w-96 md:w-full object-cover" :src="item.item_image" alt="Image Description">
                            </div>
                            </Link>
                            <!-- 商品情報 -->
                            <div class="p-6">
                                <h2 class="tracking-widest text-xs title-font font-medium text-light mb-1">{{ item.brand ?? '-' }}</h2>
                                <h1 class="title-font text-base font-medium text-light mb-3">{{ item.name }}</h1>
                                <p class="leading-relaxed text-orange-300 text-3xl font-bold mb-3"><span class="text-lg">¥ </span>{{ item.price }}</p>
                                <div class="flex items-center flex-wrap ">
                                    <!-- 出品者画像 -->
                                    <Link class="inline-flex items-center md:mb-2 lg:mb-0 cursor-pointer" as="button">
                                    <img class="inline-block h-[2.75rem] w-[2.75rem] rounded-full object-cover" :src="item.seller_image" alt="seller image">
                                    </Link>
                                    <!-- コメント -->
                                    <span class="text-gray-400 mr-3 inline-flex items-center lg:ml-auto md:ml-0 ml-auto leading-none text-sm py-1">
                                        <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                        </svg><span class="text-light">0</span>
                                    </span>
                                    <!-- お気に入り -->
                                    <span class="text-gray-400 inline-flex items-center leading-none text-sm -mr-2">
                                        <button class="rounded-full w-8 h-8 bg-black hover:bg-gray-600 p-0 border-0 inline-flex items-center justify-center text-gray-500" @click="toggleLike(item)">
                                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24" :class="{ 'text-red-500': item.liked }">
                                                <path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"></path>
                                            </svg>
                                        </button>
                                        <span class="text-light" :class="{ 'text-red-500': item.liked }">{{ item.favorite_count }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Modal :show="searchItemByNameOrCategory" @close="closeModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-700">
                        何をお探しですか？
                    </h2>

                    <div class="relative">
                        <input type="text" class="peer py-3 pe-0 ps-8 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-500 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" placeholder="商品名を入力してください" v-model="itemNameFilterKey">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none ps-2 peer-disabled:opacity-50 peer-disabled:pointer-events-none">
                            <svg class="flex-shrink-0 w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="3" y1="9" x2="21" y2="9"></line>
                                <line x1="3" y1="15" x2="21" y2="15"></line>
                                <line x1="9" y1="3" x2="9" y2="21"></line>
                                <line x1="15" y1="3" x2="15" y2="21"></line>
                            </svg>
                        </div>
                    </div>

                    <!-- ヒット件数 -->
                    <div class="mt-3" v-show="itemNameFilterKey && filteredByNameCount > 0">
                        <p class="text-dark text-sm"><span class="text-base">{{ filteredByNameCount }}&nbsp;</span>件、見つかりました</p>
                    </div>

                    <div class="mt-3">
                        <ul>
                            <li class="text-gray-600 p-2 cursor-pointer hover:bg-gray-100" @click="">
                                カテゴリから探す
                            </li>
                            <li class="text-gray-600 p-2 cursor-pointer hover:bg-gray-100" @click="">
                                ブランドから探す
                            </li>
                        </ul>
                    </div>

                </div>
            </Modal>

        </section>
    </AuthenticatedLayout>
</template>
