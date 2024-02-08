<script setup>
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    categories: Array,
});

const emit = defineEmits(['update:categoryPath', 'close']); // emitを定義

const selectedCategoryId = ref(null);
const output = ref([]);
const navigationHistory = ref([]); // カテゴリ選択の履歴を保持する配列

const fetchCategories = (parentId = null) => {
    return props.categories.filter(category => category.parent_id === parentId);
};

const rootCategories = computed(() => {
    return fetchCategories();
});

const selectedCategories = ref(rootCategories.value);

const buildCategoryPath = (categoryId) => {
    const path = [];
    let currentCategory = props.categories.find(category => category.id === categoryId);
    while (currentCategory) {
        path.unshift(currentCategory); // パスの先頭に追加
        currentCategory = props.categories.find(category => category.id === currentCategory.parent_id);
    }
    return path;
};

const selectCategory = (categoryId) => {
    selectedCategoryId.value = categoryId;
    const children = fetchCategories(categoryId);
    navigationHistory.value.push(selectedCategories.value); // 現在の状態を履歴に追加

    if (children.length > 0) {
        selectedCategories.value = children;
    } else {
        // 階層パスを構築
        output.value = buildCategoryPath(categoryId);
        // 親コンポーネントにカテゴリパスを通知
        emit('update:categoryPath', output.value);
        selectedCategories.value = rootCategories.value; // ルートカテゴリに戻る
        navigationHistory.value = []; // 履歴をクリア
        emit('close'); // モーダルを閉じる
    }
};

// 「戻る」をクリックしたときの処理
const goBack = () => {
    if (navigationHistory.value.length > 0) {
        selectedCategories.value = navigationHistory.value.pop(); // 最後の履歴を取り出し、表示を更新
    }
};

const closeModal = () => {
    selectedCategories.value = rootCategories.value; // ルートカテゴリに戻る
    navigationHistory.value = []; // 履歴をクリア
    emit('close');
};
</script>

<template>
    <Modal>
        <section class="p-6">
            <div class="flex justify-between items-center py-3 px-4 border-b mb-4">
                <h3 class="font-bold text-gray-800">
                    カテゴリを選択してください。
                </h3>
                <button type="button" class="flex justify-center items-center w-7 h-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none" data-hs-overlay="#hs-basic-modal" @click="closeModal">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>
            </div>
            <ul>
                <li class="text-gray-800 p-2 cursor-pointer hover:bg-gray-100" v-for="category in selectedCategories" :key="category.id" @click="selectCategory(category.id)">
                    {{ category.name }}
                </li>
            </ul>
            <!-- 「戻る」リンクの表示。履歴がある場合のみ表示 -->
            <div class="my-4">
                <a class="inline-flex items-center gap-x-1 text-sm text-gray-800 hover:text-blue-600" v-show="navigationHistory.length" @click="goBack">
                    <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    戻る
                </a>
            </div>
        </section>
    </Modal>
</template>
