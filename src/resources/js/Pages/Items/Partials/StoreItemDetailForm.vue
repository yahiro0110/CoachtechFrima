<script setup>
/**
 * @requires InputLabel - フォーム入力ラベルを表示するためのコンポーネント
 * @requires TextInput - テキスト入力を表示するためのコンポーネント
 * @requires InputError - フォーム入力エラーを表示するためのコンポーネント
 * @requires PriceInput - 価格入力を表示するためのコンポーネント
 * @requires ShowCategoryForm - カテゴリフォームを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires watch - Vue 3のリアクティブなデータを監視するための関数
 */
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PriceInput from '@/Components/PriceInput.vue';
import ShowCategoryForm from './ShowCategoryForm.vue';
import { ref, watch } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} form - 商品情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 * @property {Array} conditions - 商品の状態の一覧を含む配列
 */
const props = defineProps({
    form: Object,
    categories: Array,
    conditions: Array,
});

/**
 * 入力フォーム（商品名、ブランド、商品の説明）の最大文字数。
 *
 * @type {number} - 最大文字数
 */
const maxNameLength = 40;
const maxBrandLength = 20;
const maxDescriptionLength = 1000;

/**
 * カテゴリフォームを表示するためのリアクティブなプロパティ。
 *
 * @type {boolean} - カテゴリフォームを表示するかどうかを示すブール値
 */
const categoryFormButton = ref(false);

/**
 * カテゴリフォームを表示する関数。
 */
const categorySelection = () => {
    categoryFormButton.value = true;
};

/**
 * カテゴリフォームを非表示にする関数。
 */
const closeModal = () => {
    categoryFormButton.value = false;
};

/**
 * カテゴリパスを保持するリアクティブなプロパティ。
 * 子コンポーネントから受け取るカテゴリパスを格納する。
 *
 * @type {Array} - カテゴリパスを保持する配列
 */
const categoryPath = ref([]);

/**
 * カテゴリパスを更新する関数。
 * 子コンポーネントから受け取ったカテゴリパスを更新する。
 *
 * @param {Array} path - 子コンポーネントから受け取ったカテゴリパスを保持する配列
 */
const handleCategoryPathUpdate = (path) => {
    categoryPath.value = path;
};

/**
 * カテゴリパスを監視し、最下層のカテゴリIDをフォームに設定する。
 */
watch(categoryPath, (newValue, oldValue) => {
    if (newValue.length > 0) {
        const lastCategory = newValue[newValue.length - 1];
        props.form.category_id = lastCategory.id; // 最下層のカテゴリIDをform.category_idに設定
    }
}, { deep: true });
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">商品の情報</h2>

            <p class="mt-1 mb-5 text-sm text-gray-600">
                出品する商品の情報を入力してください。
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <div>
                <div class="flex items-baseline">
                    <InputLabel for="name" value="商品名" />
                    <span class="text-red-500 text-lg">*</span>
                </div>
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" :class="{ 'focus:border-red-500': form.name.length > maxNameLength, 'focus:ring-red-500': form.name.length > maxNameLength }" />
                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.name.length <= maxNameLength, 'text-red-500': form.name.length > maxNameLength }">{{ form.name?.length }} / {{ maxNameLength }}</p>
                <InputError class="-mt-4" :message="form.errors.name" />
                <InputError v-show="form.name.length > maxNameLength" :message="'商品名は' + maxNameLength + '文字までです'" :class="{ '-mt-4': !(form.errors.name), 'mt-2': form.errors.name }" />
            </div>

            <div>
                <div class="flex items-baseline -mt-1">
                    <InputLabel for="category" value="カテゴリ" />
                    <span class="text-red-500 text-lg">*</span>
                </div>
                <div class="flex justify-between items-center" v-show="form.category_id">
                    <ol class="mt-2 ml-1 flex items-center whitespace-nowrap" aria-label="Breadcrumb">
                        <li class="inline-flex items-center" v-for="(category, index) in categoryPath" :key="index">
                            <span class="flex items-center text-sm text-gray-600 focus:outline-none">
                                {{ category.name }}
                            </span>
                            <svg class="flex-shrink-0 mx-2 overflow-visible h-4 w-4 text-gray-400 dark:text-neutral-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" v-if="index < categoryPath.length - 1">
                                <path d="m9 18 6-6-6-6" />
                            </svg>
                        </li>
                    </ol>
                    <p class="mt-2 flex items-center"><a class="cursor-pointer text-sm text-indigo-600 underline underline-offset-2 decoration-indigo-600 hover:opacity-80" @click="categorySelection">変更する</a>
                        <svg class="flex-shrink-0 mx-2 overflow-visible h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                        </svg>
                    </p>
                </div>
                <div v-show="!(form.category_id)">
                    <button type="button" class="mt-2 py-3 px-4 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-gray-200 bg-white text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500" @click="categorySelection">
                        <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        カテゴリを選択する
                    </button>
                </div>
                <InputError class="mt-2" :message="form.errors.category_id" />
            </div>

            <div>
                <InputLabel for="brand" value="ブランド" />
                <TextInput id="brand" type="text" class="mt-1 block w-full" v-model="form.brand" :class="{ 'focus:border-red-500': form.brand.length > maxBrandLength, 'focus:ring-red-500': form.brand.length > maxBrandLength }" />
                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.brand.length <= maxBrandLength, 'text-red-500': form.brand.length > maxBrandLength }">{{ form.brand?.length }} / {{ maxBrandLength }}</p>
                <InputError class="-mt-4" :message="form.errors.brand" />
                <InputError v-show="form.brand.length > maxBrandLength" :message="'ブランド名は' + maxBrandLength + '文字までです'" :class="{ '-mt-4': !(form.errors.brand), 'mt-2': form.errors.brand }" />
            </div>

            <div>
                <div class="flex items-baseline">
                    <InputLabel for="condition" value="商品の状態" />
                    <span class="text-red-500 text-lg">*</span>
                </div>
                <select class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.condition_id">
                    <option v-for="condition in conditions" :key="condition.id" :value="condition.id">{{ condition.name }}</option>
                </select>
                <InputError class="mt-2" :message="form.errors.condition_id" />
            </div>

            <div>
                <InputLabel for="description" value="商品の説明" />
                <textarea class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6" placeholder="色、素材、重さ、定価、注意点など" v-model="form.description" :class="{ 'focus:border-red-500': form.description.length > maxDescriptionLength, 'focus:ring-red-500': form.description.length > maxDescriptionLength }"></textarea>
                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.description.length <= maxDescriptionLength, 'text-red-500': form.description.length > maxDescriptionLength }">{{ form.description?.length }} / {{ maxDescriptionLength }}</p>
                <InputError class="-mt-4" :message="form.errors.description" />
                <InputError v-show="form.description.length > maxDescriptionLength" :message="'説明文は' + maxDescriptionLength + '文字までです'" :class="{ '-mt-4': !(form.errors.description), 'mt-2': form.errors.description }" />
            </div>

            <div>
                <div class="flex items-baseline">
                    <InputLabel for="price" value="販売価格 (¥100〜9,999,999)" />
                    <span class="text-red-500 text-lg">*</span>
                </div>
                <PriceInput id="price" type="text" class="mt-1 w-full" v-model="form.price" />
                <InputError class="mt-2" :message="form.errors.price" />
            </div>
        </div>
        <ShowCategoryForm :show="categoryFormButton" @close="closeModal" :categories="categories" @update:categoryPath="handleCategoryPathUpdate" />
    </section>
</template>
