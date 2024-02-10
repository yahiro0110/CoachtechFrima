<script setup>
/**
 * @requires ref - リアクティブなデータ参照を作成するために使用
 * @requires watch - Vue 3のリアクティブなデータを監視するための関数
 */
import InputError from '@/Components/InputError.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { computed, ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Object} form - 商品情報の登録に必要なデータを保持するフォームオブジェクト
 */
const props = defineProps({
    item: Object,
});

/**
 * 商品情報入力フォームのデータモデル。
 *
 * @type {Object} form - アカウント情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {Array} itemImages - 商品の画像パス（既存）
 * @property {Array} file - 商品の画像ファイル（新規）
 */
const form = useForm({
    itemImages: props.item.item_images,
    files: [],
});

/**
 * リアクティブプロパティの定義。
 *
 * @property {Array} itemImages - 商品画像のプレビューを表示するための配列
 * @property {Array} selectedFiles - 商品画像のファイルを保持するための配列
 */
let newItemImages = ref([]);
let selectedFiles = ref([]);

/**
 * ファイル選択時に呼び出され、商品画像プレビューを更新する。
 *
 * @param {Event} event - ファイル入力イベント
 */
const handleFileChange = (event) => {
    const newFiles = Array.from(event.target.files);
    newItemImages.value = []; // リセット
    selectedFiles.value = []; // リセット
    newFiles.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            newItemImages.value.push(e.target.result);
            selectedFiles.value.push(file);
        };
        reader.readAsDataURL(file);
    });
};

/**
 * ドラッグ&ドロップ時に呼び出され、商品画像プレビューを更新する。
 *
 * @param {Event} event - ドラッグ&ドロップイベント
 */
const handleDrop = (event) => {
    event.preventDefault();
    const newFiles = Array.from(event.dataTransfer.files);
    newItemImages.value = []; // リセット
    selectedFiles.value = []; // リセット
    newFiles.forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            newItemImages.value.push(e.target.result);
            selectedFiles.value.push(file);
        };
        reader.readAsDataURL(file);
    });
};

/**
 * 選択された商品画像（既存）を削除する。
 *
 * @param {Number} index - 削除する商品画像のインデックス
 */
const removeOldImage = (index) => {
    form.itemImages.splice(index, 1);
};

/**
 * 選択された商品画像（新規）を削除する。
 *
 * @param {Number} index - 削除する商品画像のインデックス
 */
const removeImage = (index) => {
    newItemImages.value.splice(index, 1);
    selectedFiles.value.splice(index, 1);
};

/**
 * 商品画像のエラーメッセージを集約する。
 *
 * @type {String} - 商品画像のエラーメッセージ
 */
const filesErrorMessage = computed(() => {
    // `files` のエラーメッセージを返します。
    if (form.errors.files) {
        return form.errors.files;
    }

    // `files.*` のエラーメッセージを集約します。
    const fileErrors = Object.keys(form.errors)
        .filter(key => key.startsWith('files.'))
        .map(key => form.errors[key])
        .flat() // 複数のエラーメッセージを平坦化
        .reduce((unique, item) => unique.includes(item) ? unique : [...unique, item], []); // 重複を除外

    return fileErrors.length > 0 ? fileErrors.join(', ') : null;
});

/**
 * 商品の画像を更新する関数。
 *
 * Inertia.jsのpostメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 送信前に、transformメソッドを使用してフォームのFilesオブジェクトを更新する。
 * 成功時にはフォームの送信状態を更新し、エラー時にはエラーメッセージを表示するための処理が含まれる。
 * `preserveScroll`はページ遷移後のスクロール位置維持に使用している。
 * `onSuccess`はフォーム送信成功時に実行されるコールバック関数で、`form.recentlySuccessful`をtrueに設定する。
 * また、`form.itemImages`を最新化し、`newItemImages`と`selectedFiles`をリセットする。
 */
const updateImagesInformation = (id) => {
    form
        .transform((data) => ({
            ...data,
            // 画像ファイル（新規）が選択されていない場合は、nullを送信する
            files: selectedFiles.value.length > 0 ? selectedFiles.value : null,
        }))
        .post(route('items.images.update', { item: id }), {
            preserveScroll: true,
            onSuccess: () => {
                form.recentlySuccessful = true;
                form.itemImages = props.item.item_images;
                newItemImages.value = [];
                selectedFiles.value = [];
            },
        });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">商品画像の登録</h2>

            <p class="mt-1 mb-5 text-sm text-gray-600">
                商品画像を登録してください。
            </p>
            <InputError class="mt-2" :message="filesErrorMessage" />
        </header>

        <form @submit.prevent="updateImagesInformation(item.id)" class="mt-6 space-y-6">

            <p class="mt-1 text-sm text-gray-600 flex items-center">
                1. 過去に登録した画像で不要なものがあれば、こちらで削除できます。
            </p>

            <div class="sm:flex sm:justify-between md:grid md:grid-cols-5 md:gap-4 rounded-lg border border-dashed border-gray-900/25 md:px-6 md:py-10 bg-dark">
                <div v-for="(image, index) in item.item_images" :key="index" class="inline-block relative">
                    <img class="h-[10rem] w-[10rem] rounded-s object-cover m-2" :src="'/storage/images/items/' + image.image_path" alt="Image Description">
                    <button class="absolute top-0 right-0 bg-black hover:bg-red-500 text-white p-1 m-1 rounded-full bg-opacity-40 hover:bg-opacity-80" @click.prevent="removeOldImage(index)">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <p class="mt-1 text-sm text-gray-600">
                2. 新たに登録したい画像があれば選択してください。
            </p>

            <div v-show="newItemImages.length > 0" class="sm:flex sm:justify-between md:grid md:grid-cols-5 md:gap-4">
                <div v-for="(image, index) in newItemImages" :key="index" class="inline-block relative">
                    <img class="h-[10rem] w-[10rem] rounded-s object-cover m-2" :src="image" alt="Image Description">
                    <button class="absolute top-0 right-0 bg-black hover:bg-red-500 text-white p-1 m-1 rounded-full bg-opacity-40 hover:bg-opacity-80" @click.prevent="removeImage(index)">
                        <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="col-span-full">
                <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10" @dragover.prevent="e => e.dataTransfer.dropEffect = 'copy'" @drop="handleDrop">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                        </svg>
                        <div class="mt-4 flex flex-col text-sm leading-6 text-gray-600">
                            <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                <span>画像をアップロード</span>
                                <input id="file-upload" name="file-upload" type="file" accept=".png,.jpg,.jpeg" multiple class="sr-only" ref="file" @change="handleFileChange" />
                            </label>
                            <p class="hidden pl-1 md:block">またはこちらにドラッグ&ドロップしてください</p>
                        </div>
                        <p class="text-xs leading-5 text-gray-600">PNG, JPG, JPEG up to 10MB</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">更新</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">商品の画像を更新しました。</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
