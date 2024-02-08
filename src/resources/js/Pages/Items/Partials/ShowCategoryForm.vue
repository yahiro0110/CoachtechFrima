<script setup>
/**
 * @requires Modal - モーダルダイアログを表示するためのコンポーネント
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires computed - Vue 3の算出プロパティを作成するために使用
 */
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Array} categories - 商品カテゴリーの一覧を含む配列
 */
const props = defineProps({
    categories: Array,
});

/**
 * @requires defineEmits - カスタムイベントを定義するための関数
 */
const emit = defineEmits(['update:categoryPath', 'close']);

/**
 * リアクティブプロパティの定義。
 *
 * @property {Number} selectedCategoryId - 選択されたカテゴリーのID
 * @property {Array} output - カテゴリパス、親コンポーネントにカテゴリパスを通知するために使用
 * @property {Array} navigationHistory - カテゴリ選択の履歴、戻るリンクを表示するために使用
 * @property {Array} selectedCategories - 選択されたカテゴリー配下の一覧、カテゴリ選択フォームの表示を更新するために使用
 */
const selectedCategoryId = ref(null);
const output = ref([]);
const navigationHistory = ref([]);
const selectedCategories = ref(null);

/**
 * 商品カテゴリーの一覧を取得する関数。
 * 親カテゴリーのIDを指定することで、そのカテゴリーに属する商品カテゴリーの一覧を取得する。
 *
 * @param {Number|null} parentId - 親カテゴリーのID
 * @returns {Array} - 商品カテゴリーの一覧
 */
const fetchCategories = (parentId = null) => {
    return props.categories.filter(category => category.parent_id === parentId);
};

/**
 * 商品カテゴリーの一覧を取得する算出プロパティ。
 * カテゴリフォームを開いたときの初期状態として、ルートカテゴリーの一覧を取得する。
 */
const rootCategories = computed(() => {
    return fetchCategories();
});

// ルートカテゴリーの一覧を初期状態として設定
selectedCategories.value = rootCategories.value;

/**
 * カテゴリパスを構築する関数。
 * 選択されたカテゴリーのIDを指定することで、そのカテゴリーの階層パスを構築する。
 *
 * @param {Number} categoryId - 選択されたカテゴリーのID
 * @returns {Array} - カテゴリパス
 */
const buildCategoryPath = (categoryId) => {
    const path = [];
    let currentCategory = props.categories.find(category => category.id === categoryId);
    while (currentCategory) {
        path.unshift(currentCategory); // パスの先頭に追加
        currentCategory = props.categories.find(category => category.id === currentCategory.parent_id);
    }
    return path;
};

/**
 * カテゴリを選択したときの処理関数。
 * 選択されたカテゴリーのIDを指定することで、そのカテゴリーに属する商品カテゴリーの一覧を取得し、表示を更新する。
 * また、カテゴリが最下層のカテゴリである場合は、カテゴリパスを構築し、親コンポーネントにカテゴリパスを通知する。
 *
 * @param {Number} categoryId - 選択されたカテゴリーのID
 */
const selectCategory = (categoryId) => {
    // 選択されたカテゴリーのIDを保持
    selectedCategoryId.value = categoryId;
    // 選択されたカテゴリーに属する商品カテゴリーの一覧を取得
    const children = fetchCategories(categoryId);
    // カテゴリ選択の履歴に現在の状態を追加
    navigationHistory.value.push(selectedCategories.value);

    // 選択されたカテゴリーが最下層のカテゴリであるかどうかを判定
    if (children.length > 0) {
        selectedCategories.value = children;
    } else {
        // 階層パスを構築
        output.value = buildCategoryPath(categoryId);
        // 親コンポーネントにカテゴリパスを通知
        emit('update:categoryPath', output.value);
        // モーダルウィンドウ表示時に初期状態で表示させるための処置
        selectedCategories.value = rootCategories.value; // ルートカテゴリに戻る
        navigationHistory.value = []; // 履歴をクリア
        // モーダルを閉じる
        emit('close');
    }
};

/**
 * カテゴリ選択の履歴をもとに、前のカテゴリに戻る関数。
 * カテゴリ選択の履歴をもとに、前のカテゴリに戻り、表示を更新する。
 */
const goBack = () => {
    if (navigationHistory.value.length > 0) {
        selectedCategories.value = navigationHistory.value.pop();
    }
};

/**
 * モーダルを閉じる関数。
 * 閉じるボタンをクリックしたときに、履歴をくクリアし、選択フォームを閉じる。
 */
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
