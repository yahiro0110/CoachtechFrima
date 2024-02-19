<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires StoreItemImagesForm - 商品画像をアップロードするためのフォームコンポーネント
 * @requires StoreItemDetailForm - 商品の詳細情報を入力するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { computed, onMounted, ref, watch } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} purchases - 購入情報の一覧を含む配列
 * @property {Array} statuses - 商品の発送状況の一覧を含む配列
 */
const props = defineProps({
    purchases: Array,
    statuses: Array,
});

/**
 * 発送状況の算出プロパティ。
 * 購入直後のステータスおよび出品者側が操作するステータスを除外する。
 *
 * @type {ref<Array>} - フィルタリングされた発送状況の配列
 */
const filteredStatuses = computed(() => {
    return props.statuses.filter(status => status.name !== '購入が確定しました' && status.name !== '発送しました');
});

/**
 * 各フィルタ条件を保持するリアクティブなプロパティ。
 * 初期値はfalseもしくは空文字列で、ユーザーがフィルタ条件を選択すると更新される。
 *
 * @type {ref<Boolean>} - statusNotShipped 未発送の商品を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusCanceled 取消済の商品を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusShipped 発送済の商品を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusReceived 受取済の商品を表示するためのフィルタ条件
 * @type {ref<Boolean>} - statusReturned 返品済の商品を表示するためのフィルタ条件
 */
const statusNotShipped = ref(false);
const statusCanceled = ref(false);
const statusShipped = ref(false);
const statusReceived = ref(false);
const statusReturned = ref(false);

/**
 * 各フィルタ条件を更新する関数。
 * ユーザーがフィルタ条件を選択すると、他のフィルタ条件はfalseになる。
 * - setStatusNotShipped: 未発送の商品を表示するためのフィルタ条件を更新する関数
 * - setStatusCanceled: 取消済の商品を表示するためのフィルタ条件を更新する関数
 * - setStatusShipped: 発送済の商品を表示するためのフィルタ条件を更新する関数
 * - setStatusReceived: 受取済の商品を表示するためのフィルタ条件を更新する関数
 * - setStatusReturned: 返品済の商品を表示するためのフィルタ条件を更新する関数
 */
const setStatusNotShipped = () => {
    statusNotShipped.value = !statusNotShipped.value;
    statusCanceled.value = false;
    statusShipped.value = false;
    statusReceived.value = false;
    statusReturned.value = false;
};

const setStatusCanceled = () => {
    statusCanceled.value = !statusCanceled.value;
    statusNotShipped.value = false;
    statusShipped.value = false;
    statusReceived.value = false;
    statusReturned.value = false;
};

const setStatusShipped = () => {
    statusShipped.value = !statusShipped.value;
    statusNotShipped.value = false;
    statusCanceled.value = false;
    statusReceived.value = false;
    statusReturned.value = false;
};

const setStatusReceived = () => {
    statusReceived.value = !statusReceived.value;
    statusNotShipped.value = false;
    statusCanceled.value = false;
    statusShipped.value = false;
    statusReturned.value = false;
};

const setStatusReturned = () => {
    statusReturned.value = !statusReturned.value;
    statusNotShipped.value = false;
    statusCanceled.value = false;
    statusShipped.value = false;
    statusReceived.value = false;
};

/**
 * 購入情報に関するリアクティブなプロパティ。
 * propsから取得した購入情報に基づいて初期化され、ユーザーの操作に応じて更新される。
 *
 * @type {ref<Array>} - リアクティブな商品情報の配列
 */
const reactivePurchases = ref([]);

/**
 * 購入情報を初期化する関数。
 * propsから取得した購入情報を元に、リアクティブなプロパティを初期化する。
 */
const initializePurchases = () => {
    reactivePurchases.value = props.purchases.map(purchase => ({
        id: purchase.id,
        item_id: purchase.item.id,
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
 * フィルタリングされた購入情報を更新する関数。
 * ユーザーが発送状況のフィルタ条件を選択すると、リアクティブなプロパティを更新する。
 */
const updateFilteredPurchases = () => {
    initializePurchases();
    if (statusNotShipped.value) {
        reactivePurchases.value = reactivePurchases.value.filter(purchase => purchase.status === '購入が確定しました');
    } else if (statusCanceled.value) {
        reactivePurchases.value = reactivePurchases.value.filter(purchase => purchase.status === '購入をキャンセルしました');
    } else if (statusShipped.value) {
        reactivePurchases.value = reactivePurchases.value.filter(purchase => purchase.status === '発送しました');
    } else if (statusReceived.value) {
        reactivePurchases.value = reactivePurchases.value.filter(purchase => purchase.status === '受取完了しました');
    } else if (statusReturned.value) {
        reactivePurchases.value = reactivePurchases.value.filter(purchase => purchase.status === '返品しました');
    }
};

/**
 * 発送状況の変更を監視するウォッチャー。
 * ユーザーが発送状況のフィルタ条件を選択すると、リアクティブなプロパティを更新する。
 */
watch([statusNotShipped, statusCanceled, statusShipped, statusReceived, statusReturned], () => {
    updateFilteredPurchases(); // フィルタリングされた購入情報を更新
});

/**
 * 発送状況を更新する関数。
 *
 * Inertia.jsのputメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時には購入情報を再取得し、リアクティブなプロパティを更新する。
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

        <section class="text-gray-600 body-font 2xl:w-2/3 2xl:mx-auto">
            <!-- navigation area -->
            <div class="flex flex-col items-center">
                <div class="border-b border-gray-700 w-3/4">
                    <nav class="mb-1 flex justify-center space-x-2 md:space-x-6">
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusNotShipped, 'bg-primary': statusNotShipped, 'outline-none': statusNotShipped }" @click.prevent="setStatusNotShipped">
                            未発送
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusCanceled, 'bg-primary': statusCanceled, 'outline-none': statusCanceled }" @click.prevent="setStatusCanceled">
                            取消済
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusShipped, 'bg-primary': statusShipped, 'outline-none': statusShipped }" @click.prevent="setStatusShipped">
                            発送済
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusReceived, 'bg-primary': statusReceived, 'outline-none': statusReceived }" @click.prevent="setStatusReceived">
                            受取済
                        </button>
                        <button class="py-4 px-1 inline-flex items-center gap-2 border-b-2 border-transparent text-sm focus:font-medium whitespace-nowrap text-gray-500 hover:text-light hover:bg-gray-900 hover:opacity-80 rounded-full" :class="{ 'text-light': statusReturned, 'bg-primary': statusReturned, 'outline-none': statusReturned }" @click.prevent="setStatusReturned">
                            返品済
                        </button>
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
                            <Link :href="route('purchases.create.withParam', { item: purchase.item_id })">
                            <img class="group-hover:scale-110 transition-transform duration-500 ease-in-out rounded-xl h-40 w-40 object-cover" :src="purchase.item_image" alt="item image">
                            </Link>
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

                        <div class="md:flex-grow md:w-1/12">
                            <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">配送状況</h2>
                            <p class="leading-relaxed text-light mb-11">{{ purchase.status }}</p>
                            <form @submit.prevent="updateStatus(purchase.form, purchase.id)">
                                <select class="mt-1 py-3 px-4 block w-full 2xl:w-5/6 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" v-model="purchase.form.status_id">
                                    <option value="1">選択してください</option>
                                    <option v-for="status in filteredStatuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                                </select>
                                <button class="text-light bg-primary border-none py-2 px-8 focus:outline-none hover:bg-selected-button rounded text-lg mt-3 w-full 2xl:w-5/6">更新する</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AuthenticatedLayout>
</template >
