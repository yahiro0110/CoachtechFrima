<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 * @requires useForm - Inertia.jsのフォームハンドリング機能を提供し、フォームの状態管理や送信時の処理を容易にする
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { onMounted, ref, watch } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} items - 商品情報の一覧を含む配列
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    items: Array,
    categories: Array,
    conditions: Array,
});

/**
 * 各フィルタ条件を保持するリアクティブなプロパティ。
 * 初期値はfalseもしくは空文字列で、ユーザーがフィルタ条件を選択すると更新される。
 *
 * @type {ref<Boolean>} - statusSale 出品中を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusTransaction 取引中を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusSold 売却済を表示するためのフィルタ条件
 */
const statusSale = ref(false);
const statusTransaction = ref(false);
const statusSold = ref(false);

/**
 * 各フィルタ条件を更新する関数。
 * ユーザーがフィルタ条件を選択すると、他のフィルタ条件はfalseになる。
 * - setStatusSale: 出品中を表示するためのフィルタ条件を更新する関数
 * - setStatusTransaction: 取引中を表示するためのフィルタ条件を更新する関数
 * - setStatusSold: 売却済を表示するためのフィルタ条件を更新する関数
 */
const setStatusSale = () => {
    statusSale.value = !statusSale.value;
    statusTransaction.value = false;
    statusSold.value = false;
};

const setStatusTransaction = () => {
    statusSale.value = false;
    statusTransaction.value = !statusTransaction.value;
    statusSold.value = false;
};

const setStatusSold = () => {
    statusSale.value = false;
    statusTransaction.value = false;
    statusSold.value = !statusSold.value;
};

/**
 * 出品した商品の一覧に関するリアクティブなプロパティ。
 * propsから取得した購入情報に基づいて初期化され、ユーザーの操作に応じて更新される。
 *
 * @type {ref<Array>} - リアクティブな商品情報の配列
 */
const reactiveItems = ref([]);

/**
 * 商品一覧を初期化する関数。
 * propsから取得した商品情報を元に、リアクティブなプロパティを初期化する。
 */
const initializeItems = () => {
    reactiveItems.value = props.items.map(item => ({
        id: item.id,
        name: item.name,
        brand: item.brand,
        price: item.price ? item.price.toLocaleString() : '---',
        item_image: '/storage/images/items/' + item.item_images[0].image_path,
        purchased: item.purchased,
        soldout: item.soldout,
    }));
};

// コンポーネントがマウントされた際に初期化
onMounted(initializeItems);

/**
 * フィルタリングされた出品した商品の一覧を更新する関数。
 * ユーザーが出品状況のフィルタ条件を選択すると、リアクティブなプロパティを更新する。
 */
const updateFilteredItems = () => {
    initializeItems();
    if (statusSale.value) {
        reactiveItems.value = reactiveItems.value.filter(item => item.purchased === false && item.soldout === false);
    } else if (statusTransaction.value) {
        reactiveItems.value = reactiveItems.value.filter(item => item.purchased === true && item.soldout === false);
    } else if (statusSold.value) {
        reactiveItems.value = reactiveItems.value.filter(item => item.soldout === true);
    }
};

/**
 * 出品状況の変更を監視するウォッチャー。
 * ユーザーが出品状況のフィルタ条件を選択すると、リアクティブなプロパティを更新する。
 */
watch([statusSale, statusTransaction, statusSold], () => {
    updateFilteredItems();
});
</script>

<template>
    <Head title="出品履歴" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">出品した商品</h2>
        </template>

        <FlashMessage />

        <section class="text-gray-600 body-font 2xl:w-2/3 2xl:mx-auto">
            <!-- navigation area -->
            <div class="flex flex-col items-center">
                <div class="border-b border-gray-700 w-3/4">
                    <nav class="mb-1 flex justify-center space-x-6">
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusSale, 'bg-primary': statusSale, 'outline-none': statusSale }" @click.prevent="setStatusSale">
                            出品中
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusTransaction, 'bg-primary': statusTransaction, 'outline-none': statusTransaction }" @click.prevent="setStatusTransaction">
                            取引中
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusSold, 'bg-primary': statusSold, 'outline-none': statusSold }" @click.prevent="setStatusSold">
                            売却済
                        </button>
                    </nav>
                </div>
            </div>

            <!-- items area -->
            <div class="container px-5 py-10 mx-auto">
                <div class="flex flex-wrap -m-4">
                    <div class="p-4 md:w-1/4" v-for="(item, index) in reactiveItems" :key="item.id">
                        <div class="h-full rounded-lg overflow-hidden">
                            <img class="h-72 md:h-72 w-96 md:w-full object-cover object-center rounded-lg" :src="item.item_image" alt="item images">
                            <div class="p-6">
                                <h2 class="tracking-widest text-xs title-font font-medium text-light mb-1">{{ item.brand ?? '-' }}</h2>
                                <h1 class="title-font text-base font-medium text-light mb-3">{{ item.name }}</h1>
                                <p class="leading-relaxed text-orange-300 text-3xl font-bold mb-3"><span class="text-lg">¥ </span>{{ item.price }}</p>
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
