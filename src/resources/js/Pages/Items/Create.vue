<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StoreItemImagesForm from '@/Pages/Items/Partials/StoreItemImagesForm.vue';
import StoreItemDetailForm from '@/Pages/Items/Partials/StoreItemDetailForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    categories: Array,
    conditions: Array,
});

const form = useForm({
    name: null,
    category_id: null,
    brand: null,
    condition_id: null,
    description: null,
    price: null,
    file: [],
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
    form.post(route('items.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.recentlySuccessful = true;
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
                        <StoreItemImagesForm class="mx-auto" />
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
                            <PrimaryButton class="bg-danger" :disabled="form.processing">出品する</PrimaryButton>

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
