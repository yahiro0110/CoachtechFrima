<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires StoreItemImagesForm - 商品画像をアップロードするためのフォームコンポーネント
 * @requires StoreItemDetailForm - 商品の詳細情報を入力するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { computed, onMounted, ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} items - 商品の一覧を含む配列
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    purchases: Array,
    statuses: Array,
});

const filteredStatuses = computed(() => {
    return props.statuses.filter(status => status.name !== '購入が確定しました' && status.name !== '発送しました');
});

/**
 * リアクティブな商品情報の配列。
 * propsから取得した商品情報に基づいて初期化され、ユーザーの操作に応じて更新される。
 *
 * @type {ref<Array>} - リアクティブな商品情報の配列
 */
const reactivePurchases = ref([]);

const initializePurchases = () => {
    reactivePurchases.value = props.purchases.map(purchase => ({
        id: purchase.id,
        name: purchase.item.name,
        price: purchase.item.price ? purchase.item.price.toLocaleString() : '---',
        item_image: '/storage/images/items/' + purchase.item.item_images[0].image_path,
        orderDate: new Date(purchase.created_at).toISOString().split('T')[0],
        ship_address: purchase.ship_address,
        payment: purchase.payment.name,
        status: purchase.status.name,
        form: useForm({
            status_id: purchase.status.id,
        }),
    }));
};

// コンポーネントがマウントされた際に初期化
onMounted(initializePurchases);

/**
 * 発送状況を更新する関数。
 *
 * Inertia.jsのputメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時にはフォームの送信状態を更新し、エラー時にはエラーメッセージを表示するための処理が含まれる。
 * `preserveScroll`はページ遷移後のスクロール位置維持に使用。
 * `onSuccess`はフォーム送信成功時に実行されるコールバック関数で、`form.recentlySuccessful`をtrueに設定する。
 */
const updateStatus = (purchaseForm, purchaseId) => {
    purchaseForm.put(route('purchases.update', { purchase: purchaseId }), {
        preserveScroll: true,
        onSuccess: () => {
            initializePurchases();
        },
    });
};
</script>

<template>
    <Head title="購入履歴" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">購入した商品</h2>
        </template>

        <section class="text-gray-600 body-font">
            <!-- navigation area -->
            <div class="flex flex-col items-center">
                <div class="border-b border-gray-700 w-3/4">
                    <nav class="mb-1 flex justify-center space-x-6">
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#">
                            出品中
                        </a>
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80  focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#" aria-current="page">
                            取引中
                        </a>
                        <a class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80  focus:outline-none focus:text-light focus:bg-primary rounded-full" href="#">
                            売却済
                        </a>
                    </nav>
                </div>
            </div>

            <!-- items area -->
            <div class="container md:px-5 mx-auto w-3/4">
                <div class="divide-y divide-gray-400">
                    <div class="py-8 flex flex-wrap md:flex-nowrap" v-for="(purchase, index) in reactivePurchases" :key="index">

                        <div class="md:w-64 md:mb-0 mb-6 flex-shrink-0 flex flex-col">
                            <span class="font-semibold title-font text-gray-700">注文日</span>
                            <span class="mt-1 mb-2 text-gray-500 text-sm">{{ purchase.orderDate }}</span>
                            <img class="group-hover:scale-110 transition-transform duration-500 ease-in-out rounded-xl h-40 w-40 object-cover" :src="purchase.item_image" alt="item image">
                        </div>

                        <div class="md:flex-grow md:mb-0 mb-6 md:w-1/3 md:mr-16 2xl:mr-0">
                            <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">{{ purchase.name }}</h2>
                            <p class="leading-relaxed">価格</p>
                            <p class="leading-relaxed text-light mb-2">{{ purchase.price }}円</p>
                            <p class="leading-relaxed">配送先</p>
                            <p class="leading-relaxed text-light mb-2">〒{{ purchase.ship_address }}</p>
                            <p class="leading-relaxed">決済方法</p>
                            <p class="leading-relaxed text-light">{{ purchase.payment }}</p>
                        </div>

                        <div class="md:flex-grow md:w-1/12 2xl:px-28">
                            <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">配送状況</h2>
                            <p class="leading-relaxed text-light mb-11">{{ purchase.status }}</p>
                            <form @submit.prevent="updateStatus(purchase.form, purchase.id)">
                                <select class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" v-model="purchase.form.status_id">
                                    <option value="1">選択してください</option>
                                    <option v-for="status in filteredStatuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                                </select>
                                <button class="text-light bg-primary border-none py-2 px-8 focus:outline-none hover:bg-selected-button rounded text-lg mt-3 w-full">更新する</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template >
