<script setup>
/**
 * @requires AuthenticatedLayout - 認証済みユーザー向けのレイアウトコンポーネント
 * @requires UpdateItemImagesForm - 商品画像を更新するためのフォームコンポーネント
 * @requires UpdateItemDetailForm - 商品の詳細情報を更新するためのフォームコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires Head - Inertia.jsのヘルパーメソッドを提供し、ページのタイトルやメタ情報を動的に変更する
 * @requires Inertia - Inertia.jsのページ遷移機能を提供し、サーバーサイドとの通信を行う
 */
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import UpdateItemImagesForm from '@/Pages/Items/Partials/UpdateItemImagesForm.vue';
import UpdateItemDetailForm from '@/Pages/Items/Partials/UpdateItemDetailForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { Inertia } from '@inertiajs/inertia';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} item - 商品の情報を含むオブジェクト(商品画像を含む)
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    item: Object,
    categories: Array,
    conditions: Array,
});

/**
 * 商品の詳細ページに戻す関数。
 */
const goBack = (id) => {
    Inertia.get(route('items.show', { item: id }));
}
</script>

<template>
    <Head title="出品" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-light leading-tight text-center">商品の編集</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateItemImagesForm class="mx-auto" :item="item" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateItemDetailForm class="max-w-xl" :item="item" :categories="categories" :conditions="conditions" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h2 class="text-lg font-medium text-gray-900">編集を終了した場合</h2>
                    <p class="mt-1 mb-5 text-sm text-gray-600">
                        「戻る」ボタンで商品の詳細ページに戻ります。
                    </p>
                    <PrimaryButton @click="goBack(item.id)">戻る</PrimaryButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
