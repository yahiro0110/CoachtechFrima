<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PostalInput from '@/Components/PostalInput.vue';
import { Core as YubinBangoCore } from "yubinbango-core2";
import { computed, ref, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/inertia-vue3';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} items - 商品の一覧を含む配列
 * @property {Array} formattedPrices - 商品の価格をフォーマットした文字列を含む配列
 * @property {Array} payments - お支払い方法の一覧を含む配列
 */
const props = defineProps({
    item: Object,
    formattedPrices: String,
    payments: Array,
});

/**
 * @requires defineEmits - カスタムイベントを定義するための関数
 */
const emit = defineEmits(['closeCartItem']);

/**
 * 購入フォームのデータモデル。
 *
 * @type {Object} form - 購入情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {Number} item_id - 商品の一意の識別子（ID）
 * @property {Number} purchaser_id - 購入者の一意の識別子（ID）
 * @property {String} ship_address - 配送先の住所
 * @property {String} email - 購入者のメールアドレス
 * @property {Number} payment_id - お支払い方法の一意の識別子（ID）、初期値は1（クレジットカード）
 */
const form = useForm({
    item_id: props.item.id,
    purchaser_id: usePage().props.value.auth.user.id,
    ship_address: usePage().props.value.auth.userInfo.address ? usePage().props.value.auth.userInfo.postal + ' ' + usePage().props.value.auth.userInfo.address : '',
    email: usePage().props.value.auth.user.email,
    payment_id: 1,
});

/**
 * 住所入力フォームのデータモデル。
 *
 * @type {Object} form - 住所情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {String} postal - 郵便番号
 * @property {String} address - 住所
 * @property {String} building - 建物名
 */
const userForm = useForm({
    postal: null,
    address: null,
    building: null,
});

/**
 * 郵便番号に基づいて住所を取得し、更新する関数。
 */
const fetchAddress = () => {
    new YubinBangoCore(String(userForm.postal), (value) => {
        userForm.address = value.region + value.locality + value.street;
    });
};

/**
 * 郵便番号の変更を監視し、住所を取得する。
 */
watch(() => userForm.postal, (newPostal, oldPostal) => {
    // 郵便番号が7桁の場合のみ住所をフェッチする
    if (newPostal.length === 7) {
        fetchAddress();
    }
});

/**
 * 入力フォーム（住所、建物）の最大文字数。
 * 補足）郵便番号は７桁で浸透しているため、敢えて表示しない。
 *
 * @type {number} - 最大文字数
 */
const maxAddressLength = 161;
const maxBuildingLength = 161;

/**
 * 住所入力フォームの表示状態を管理するリアクティブなプロパティ。
 *
 * @type {boolean} inputAddressForm - 住所入力フォームの表示状態
 */
const inputAddressForm = ref(false);

/**
 * 住所入力フォームを表示する関数。
 */
const openInputAddressForm = () => inputAddressForm.value = true;
const closeInputAddressForm = () => inputAddressForm.value = false;

/**
 * 配送先の住所を更新し、住所入力フォームを閉じる関数。
 */
const setNewShipAddress = () => {
    form.ship_address = (userForm.postal ?? '') + ' ' + (userForm.address ?? '') + ' ' + (userForm.building ?? '');
    // .trim()を使用して空白を取り除いた後にチェック
    if (form.ship_address.trim() === '') {
        form.ship_address = false;
    }
    inputAddressForm.value = false;
};

/**
 * カート表示エリアを閉じる関数。
 */
const handleCloseCart = () => emit('closeCartItem');

/**
 * 購入を確定する関数。
 * Inertia.jsのpostメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 */
const StorePurchase = () => form.post(route('purchases.store'));
</script>

<template>
    <div class="container md:flex md:justify-between md:items-start md:w-full mx-auto px-5 md:px-36 md:mt-10">

        <!-- 配送先・お支払い方法編集 -->
        <div class="md:w-1/2">
            <h1 class="sm:text-3xl text-2xl font-medium title-font text-light lg:mb-0 mb-4 text-center md:text-left">購入の確認</h1>

            <div class="mt-10">
                <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">商品名</h1>
                <p class="mx-auto leading-relaxed text-xl text-light">{{ item.name }}</p>
            </div>

            <div class="mt-10">
                <div class="flex justify-between items-start">
                    <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">配送先</h1>
                    <p class="mt-2 flex items-center"><a class="cursor-pointer text-sm text-indigo-600 underline underline-offset-2 decoration-indigo-600 hover:opacity-80" @click="openInputAddressForm">変更する</a>
                        <svg class="flex-shrink-0 mx-2 overflow-visible h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                        </svg>
                    </p>
                </div>

                <p class="mx-auto leading-relaxed text-xl " :class="{ 'text-light': form.ship_address, 'text-gray-500': !form.ship_address }">{{ form.ship_address ? '〒' + form.ship_address : '住所が登録されていません' }}</p>
                <InputError :message="form.errors.ship_address" />
            </div>

            <div class="mt-10">
                <h1 class="text-lg md:text-2xl font-medium title-font text-gray-500 mb-4">お支払い方法</h1>
                <div class="flex flex-col gap-y-3">
                    <div class="flex flex-col gap-y-3">
                        <div class="flex" v-for="payment in payments" :key="payment.id">
                            <input type="radio" :id="'payment-' + payment.id" :value="payment.id" v-model="form.payment_id" name="payment-method" class="shrink-0 mt-0.5 rounded-full focus:ring-primary hover:ring-selected-button disabled:opacity-50 disabled:pointer-events-none bg-gray-800 border-gray-700 checked:bg-primary checked:border-primary focus:ring-offset-gray-800">
                            <label :for="'payment-' + payment.id" class="text-sm ml-2" :class="{ 'text-light': form.payment_id === payment.id, 'font-bold': form.payment_id === payment.id, 'text-gray-400': form.payment_id !== payment.id }">{{ payment.name }}</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 商品画像と購入確定ボタン、キャンセルボタン -->
        <div class="lg:w-2/6 md:w-full bg-dark rounded-lg flex flex-col py-4 w-full mt-20 md:mt-0">
            <img class="group-hover:scale-110 transition-transform duration-500 ease-in-out rounded-xl h-72 w-64 md:w-72 object-cover mx-auto" :src="'/storage/images/items/' + item.item_images[0].image_path" alt="">

            <div class="flex justify-between items-center mt-4 mb-2 mx-5">
                <h2 class=" text-light text-lg font-medium">商品代金</h2>
                <div class="relative">
                    <p class="text-light text-lg font-medium"><span class="text-lg">¥ </span>{{ formattedPrices }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center mx-5 mb-5">
                <h2 class=" text-light text-lg font-medium title-font">支払い金額</h2>
                <div class="relative">
                    <p class="leading-relaxed text-orange-300 text-4xl font-bold"><span class="text-lg">¥ </span>{{ formattedPrices }}</p>
                </div>
            </div>

            <p class="text-xs text-gray-500 mx-5 mb-2">利用規約およびプライバシーポリシーに同意の上、ご購入ください。</p>

            <button class="text-light bg-dark border border-gray-700 py-2 px-8 focus:outline-none hover:bg-primary rounded text-lg mx-4 my-4" @click="StorePurchase">購入を確定する</button>

            <button class="text-light bg-dark border border-gray-700 py-2 px-8 focus:outline-none hover:bg-danger rounded text-lg mx-4" @click="handleCloseCart">カートを閉じる</button>
        </div>
    </div>

    <!-- 住所編集モーダル -->
    <Modal :show="inputAddressForm" @close="closeInputAddressForm">
        <div class="p-6">
            <div class="flex flex-col md:ml-auto w-full md:py-8 mt-8 md:mt-0 px-10">

                <header>
                    <h2 class="text-lg font-medium text-gray-900">住所の編集</h2>

                    <p class="mt-1 text-sm text-gray-600">
                        配送先の住所を入力してください。
                    </p>
                </header>

                <form @submit.prevent="updateDetailInformation" class="mt-6 space-y-6">
                    <div>
                        <InputLabel for="postal" value="郵便番号" />

                        <PostalInput id="postal" type="text" class="mt-1 block w-full" placeholder="半角数字７桁で入力してください。" v-model="userForm.postal" />

                        <InputError class="mt-2" :message="userForm.errors.postal" />
                    </div>

                    <div>
                        <InputLabel for="address" value="住所" />

                        <TextInput id="address" type="text" class="mt-1 block w-full" v-model="userForm.address" :class="{ 'focus:border-red-500': userForm.address?.length > maxAddressLength, 'focus:ring-red-500': userForm.address?.length > maxAddressLength }" />

                        <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': userForm.address?.length <= maxAddressLength, 'text-red-500': userForm.address?.length > maxAddressLength }">{{ userForm.address?.length ?? 0 }} / {{ maxAddressLength }}</p>

                        <InputError class="-mt-4" :message="userForm.errors.address" />

                        <InputError v-show="userForm.address?.length > maxAddressLength" :message="'住所は' + maxAddressLength + '文字までです'" :class="{ '-mt-4': !(userForm.errors.address), 'mt-2': userForm.errors.address }" />
                    </div>

                    <div>
                        <InputLabel for="building" value="建物" />

                        <TextInput id="building" type="text" class="mt-1 block w-full" v-model="userForm.building" :class="{ 'focus:border-red-500': userForm.building?.length > maxBuildingLength, 'focus:ring-red-500': userForm.building?.length > maxBuildingLength }" />

                        <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': userForm.building?.length <= maxBuildingLength, 'text-red-500': userForm.building?.length > maxBuildingLength }">{{ userForm.building?.length ?? 0 }} / {{ maxBuildingLength }}</p>

                        <InputError class="-mt-4" :message="userForm.errors.building" />

                        <InputError v-show="userForm.building?.length > maxBuildingLength" :message="'建物は' + maxBuildingLength + '文字までです'" :class="{ '-mt-4': !(userForm.errors.building), 'mt-2': userForm.errors.building }" />
                    </div>
                </form>

                <button class="text-light bg-primary border py-2 px-8 focus:outline-none hover:bg-selected-button rounded text-lg mt-12 w-full" @click.prevent="setNewShipAddress">更新する</button>
            </div>
        </div>
    </Modal>
</template>
