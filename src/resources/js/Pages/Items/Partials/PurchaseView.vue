<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import { computed, onMounted, ref, watch } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} item - 商品の情報を含むオブジェクト(商品画像を含む)
 * @property {Array} comments - 商品に対するコメントのリスト
 */
const props = defineProps({
    purchases: Array,
    statuses: Array,
});

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
        purchaser: purchase.user.name,
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
 * ユーザ操作で選択された配送状況のIDを保持するリアクティブなプロパティ。
 *
 * @type {ref<number>} - 選択された配送状況のID
 */
const selectedStatusId = ref(null);

/**
 * 最新の購入情報の配送状況のIDを保持する算出プロパティ。
 * reactivePurchasesの最後の要素のstatus_idが変更された場合に反応する
 *
 * @type {ref<number>} - 最新の購入情報の配送状況のID
 */
const lastPurchaseStatusId = computed({
    get: () => reactivePurchases.value.length ? reactivePurchases.value[reactivePurchases.value.length - 1].form.status_id : null,
    set: (newValue) => {
        if (reactivePurchases.value.length) {
            reactivePurchases.value[reactivePurchases.value.length - 1].form.status_id = newValue;
        }
    }
});

/**
 * 選択された配送状況のIDが変更された場合に反応するウォッチャー。
 * selectedStatusIdとlastPurchaseStatusIdを同期させるために使用。
 */
watch(selectedStatusId, (newValue) => {
    lastPurchaseStatusId.value = newValue;
});

/**
 * 最新の購入情報の配送状況のIDが変更された場合に反応するウォッチャー。
 * selectedStatusIdとlastPurchaseStatusIdを同期させるために使用。
 */
watch(lastPurchaseStatusId, (newValue) => {
    selectedStatusId.value = newValue;
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
    <section class="mt-10 flex flex-col sm:w-full md:w-full 2xl:w-1/2 px-7 md:px-20 mx-auto">
        <div class="container py-24 -mb-20 mx-auto">
            <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">購入者情報</h1>

            <div v-if="!purchases.length">
                <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">まだ購入されていません</h2>
            </div>

            <div class="py-1 flex flex-wrap md:flex-nowrap" v-if="purchases.length">

                <div class="md:flex-grow md:mb-0 mb-6 md:w-1/3 md:mr-16 2xl:mr-0">
                    <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">{{ reactivePurchases[reactivePurchases.length - 1]?.purchaser }}さんが購入しました</h2>
                    <p class="leading-relaxed">注文日</p>
                    <p class="leading-relaxed text-light mb-2">{{ reactivePurchases[reactivePurchases.length - 1]?.orderDate }}</p>
                    <p class="leading-relaxed">配送先</p>
                    <p class="leading-relaxed text-light mb-2">〒{{ reactivePurchases[reactivePurchases.length - 1]?.ship_address }}</p>
                    <p class="leading-relaxed">決済方法</p>
                    <p class="leading-relaxed text-light">{{ reactivePurchases[reactivePurchases.length - 1]?.payment }}</p>
                </div>

                <div class="md:flex-grow w-full md:w-1/12 md:px-20 2xl:px-28">
                    <h2 class="text-2xl font-medium text-gray-400 title-font mb-2">配送状況</h2>
                    <p class="leading-relaxed text-light mb-11">{{ reactivePurchases[reactivePurchases.length - 1]?.status }}</p>
                    <form @submit.prevent="updateStatus(reactivePurchases[reactivePurchases.length - 1]?.form, reactivePurchases[reactivePurchases.length - 1]?.id)">
                        <select class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" v-model="selectedStatusId">
                            <option value="1">選択してください</option>
                            <option v-for=" status  in  statuses " :key="status.id" :value="status.id">{{ status.name }}</option>
                        </select>
                        <button class="text-light bg-primary border-none py-2 px-8 focus:outline-none hover:bg-selected-button rounded text-lg mt-3 w-full">更新する</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
