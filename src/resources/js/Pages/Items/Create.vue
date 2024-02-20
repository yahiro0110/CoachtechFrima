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
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    categories: Array,
    conditions: Array,
});

/**
 * 商品情報入力フォームのデータモデル。
 *
 * @type {Object} form - アカウント情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {String} name - 商品名
 * @property {Number} category_id - 商品カテゴリーのID
 * @property {String} brand - ブランド名
 * @property {Number} condition_id - 商品の状態のID
 * @property {String} description - 商品の説明
 * @property {Number} price - 商品の価格
 * @property {Array} file - 商品の画像ファイル
 */
const form = useForm({
    name: '',
    category_id: null,
    brand: '',
    condition_id: null,
    description: '',
    price: '',
    files: [],
});

/**
 * 商品画像をアップロードするためのフォームコンポーネントの参照。
 *
 * @type {Object} imagesFormRef - 商品画像をアップロードするためのフォームコンポーネントの参照
 */
const imagesFormRef = ref(null);

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
    <Head title="出品" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">商品の出品</h2>
        </template>

        <form @submit.prevent="StoreItem">
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <StoreItemImagesForm class="mx-auto" ref="imagesFormRef" :form="form" />
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <StoreItemDetailForm class="max-w-xl" :form="form" :categories="categories" :conditions="conditions" />
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <h2 class="text-lg font-medium text-gray-900">出品前の重要なご確認事項</h2>
                        <p class="mt-1 mb-5 text-sm text-gray-600">
                            禁止されている行為及び出品物を必ずご確認ください。また、加盟店規約及びプライバシーポリシーに同意の上、「出品する」ボタンを押してください。
                        </p>
                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">出品する</PrimaryButton>

                            <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                                <p v-if="form.recentlySuccessful" class="text-sm text-danger">商品の出品登録を完了しました。</p>
                            </Transition>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
