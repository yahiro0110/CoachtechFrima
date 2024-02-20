<script setup>
/**
 * @requires InputError - フォーム入力エラーを表示するためのコンポーネント
 * @requires InputLabel - フォーム入力ラベルを表示するためのコンポーネント
 * @requires PrimaryButton - プライマリボタンを表示するためのコンポーネント
 * @requires TextInput - テキスト入力を表示するためのコンポーネント
 * @requires Link - Inertia.jsを用いたアプリケーション内のページ間ナビゲーションを提供するVueコンポーネント
 * @requires useForm - Inertia.jsのフォームハンドリング機能を提供し、フォームの状態管理や送信時の処理を容易にする
 * @requires ref - リアクティブなデータ参照を作成するために使用
 */
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Boolean} mustVerifyEmail - ユーザーがメールアドレスを確認する必要があるかどうかを示すブール値
 * @property {String} status - ユーザーのメールアドレス確認ステータスを示す文字列
 * @property {Object} user - 現在ログインしているユーザー情報を含むオブジェクト
 */
const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: Object,
});

/**
 * アカウント情報入力フォームのデータモデル。
 *
 * @type {Object} form - アカウント情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {String} image_path - ユーザーのプロフィール画像のファイルパス
 * @property {String} name - ユーザーのアカウント名
 * @property {String} email - ユーザーのメールアドレス
 * @property {File|null} file - ユーザーのプロフィール画像ファイル、初期値はnull
 */
const form = useForm({
    image_path: props.user.user_image.image_path,
    name: props.user.name,
    email: props.user.email,
    file: null,
});

/**
 * 入力フォーム（アカウント名）の最大文字数。
 *
 * @type {number} - 最大文字数
 */
const maxNameLength = 20;

/**
 * プロフィール画像のプレビュー用のURLを提供するリアクティブなプロパティ。
 *
 *  @type {string} - プロフィール画像のファイルパス
 */
let userIcon = form.image_path ? ref('/storage/images/users/' + form.image_path) : ref('/images/default-user-icon.jpg');

/**
 * ファイル選択時に呼び出され、画像プレビューを更新する。
 *
 * @param {Event} event - ファイル入力イベント
 */
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => userIcon.value = e.target.result;
        reader.readAsDataURL(file);
    }
};

/**
 * ファイル入力ダイアログをトリガーする関数。
 *
 * `id`が`file`のinput要素を探し、存在する場合クリックイベントを発火させてファイル選択画面を表示させる。
 * これにより、ユーザーは直接ファイル入力要素をクリックすることなく、プロフィール画像を選択できるため、
 * 当該の要素を非表示にしておくことができる。
 */
const triggerFileInput = () => {
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.click();
    }
};

/**
 * アカウント情報を更新する関数。
 *
 * Inertia.jsのpostメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時にはフォームの送信状態を更新し、エラー時にはエラーメッセージを表示するための処理が含まれる。
 * `preserveScroll`はページ遷移後のスクロール位置維持に使用。
 * `onSuccess`はフォーム送信成功時に実行されるコールバック関数で、`form.recentlySuccessful`をtrueに設定する。
 */
const updateProfileInformation = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.recentlySuccessful = true;
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">アカウント情報</h2>

            <p class="mt-1 text-sm text-gray-600">
                アカウント名およびメールアドレスを変更します。
            </p>
        </header>

        <form @submit.prevent="updateProfileInformation" class="mt-6 space-y-6">
            <div>
                <div class="flex flex-row items-center">
                    <img class="inline-block h-[8rem] w-[8rem] rounded-full object-cover" :src="userIcon" alt="Image Description">
                    <div class="flex flex-col items-center mt-7">
                        <button type="button" class="ml-5 py-3 px-4  gap-x-2 text-sm font-semibold rounded-lg border border-gray-500 text-gray-500 hover:border-gray-400 hover:text-gray-400 disabled:opacity-50 disabled:pointer-events-none " @click="triggerFileInput">
                            プロフィール画像の変更
                        </button>
                        <p class="ml-6 mt-2 text-sm text-red-500 inline-flex" id="file_input_help">PNG, JPG or JPEG (MAX. 5MB)</p>
                    </div>
                </div>
                <input type="file" id="file" name="file" ref="file" accept=".png,.jpg,.jpeg" @change="handleFileChange" @input="form.file = $event.target.files[0]" class="hidden">
                <InputError class="p-1" :message="form.errors.file" />
            </div>

            <div>
                <div class="flex items-baseline">
                    <InputLabel for="name" value="アカウント名" />
                    <span class="text-red-500 text-lg">*</span>
                </div>

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" :class="{ 'focus:border-red-500': form.name.length > maxNameLength, 'focus:ring-red-500': form.name.length > maxNameLength }" />

                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.name.length <= maxNameLength, 'text-red-500': form.name.length > maxNameLength }">{{ form.name?.length }} / {{ maxNameLength }}</p>

                <InputError class="-mt-4" :message="form.errors.name" />

                <InputError v-show="form.name.length > maxNameLength" :message="'名前は' + maxNameLength + '文字までです'" :class="{ '-mt-4': !(form.errors.name), 'mt-2': form.errors.name }" />
            </div>

            <div>
                <div class="flex items-baseline">
                    <InputLabel for="email" value="メールアドレス" />
                    <span class="text-red-500 text-lg">*</span>
                </div>

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="props.mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link :href="route('verification.send')" method="post" as="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Click here to re-send the verification email.
                    </Link>
                </p>

                <div v-show="props.status === 'verification-link-sent'" class="mt-2 font-medium text-sm text-green-600">
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">更新</PrimaryButton>

                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">更新しました。</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
