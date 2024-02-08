<script setup>
/**
 * @requires InputError - フォーム入力エラーを表示するためのコンポーネント
 * @requires InputLabel - フォーム入力ラベルを表示するためのコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires TextInput - テキスト入力を表示するためのコンポーネント
 * @requires useForm - Inertia.jsのフォームハンドリング機能を提供し、フォームの状態管理や送信時の処理を容易にする
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires YubinBangoCore - yubinbango-core2ライブラリからインポート、日本の郵便番号から住所情報を取得する機能を提供
 */
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import PriceInput from '@/Components/PriceInput.vue';
import ShowCategoryForm from './ShowCategoryForm.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    form: Object,
    categories: Array,
    conditions: Array,
});

const confirmingUserDeletion = ref(false);

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

const categoryPath = ref([]); // 子コンポーネントから受け取るカテゴリパスを格納

// categoryPathの変化を監視する
watch(categoryPath, (newValue, oldValue) => {
    if (newValue.length > 0) {
        const lastCategory = newValue[newValue.length - 1];
        props.form.category_id = lastCategory.id; // 最下層のカテゴリIDをform.category_idに設定
    }
}, { deep: true });

const handleCategoryPathUpdate = (path) => {
    categoryPath.value = path; // 子コンポーネントから受け取ったカテゴリパスを更新
};
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
                <InputLabel for="name" value="商品名" />
                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" />
            </div>

            <div>
                <InputLabel for="category" value="カテゴリ" />
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
                    <p class="mt-2 flex items-center"><a class="cursor-pointer text-sm text-indigo-600 underline underline-offset-2 decoration-indigo-600 hover:opacity-80" @click="confirmUserDeletion">変更する</a>
                        <svg class="flex-shrink-0 mx-2 overflow-visible h-4 w-4 text-indigo-600" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="16 3 21 8 8 21 3 21 3 16 16 3"></polygon>
                        </svg>
                    </p>
                </div>
                <div v-show="!(form.category_id)">
                    <button type="button" class="mt-2 py-3 px-4 inline-flex items-center gap-x-1 text-xs font-medium rounded-full border border-dashed border-gray-200 bg-white text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500" @click="confirmUserDeletion">
                        <svg class="flex-shrink-0 w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                        カテゴリを選択する
                    </button>
                </div>
            </div>



            <div>
                <InputLabel for="brand" value="ブランド" />
                <TextInput id="brand" type="text" class="mt-1 block w-full" v-model="form.brand" />
            </div>

            <div>
                <InputLabel for="condition" value="商品の状態" />
                <select class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.condition_id">
                    <option v-for="condition in conditions" :key="condition.id" :value="condition.id">{{ condition.name }}</option>
                </select>
            </div>

            <div>
                <InputLabel for="description" value="商品の説明" />
                <textarea class="mt-1 py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="6" placeholder="色、素材、重さ、定価、注意点など" v-model="form.description"></textarea>
            </div>

            <div>
                <InputLabel for="price" value="販売価格" />
                <PriceInput id="price" type="text" class="mt-1 w-full" v-model="form.price" />
            </div>
        </div>
        <ShowCategoryForm :show="confirmingUserDeletion" @close="closeModal" :categories="categories" @update:categoryPath="handleCategoryPathUpdate" />
    </section>
</template>
