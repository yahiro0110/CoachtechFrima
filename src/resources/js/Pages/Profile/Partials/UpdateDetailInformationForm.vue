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
import PostalInput from '@/Components/PostalInput.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { Core as YubinBangoCore } from "yubinbango-core2";
import { watch } from 'vue';

/**
 * コンポーネントのプロパティ定義。
 *
 * @property {Boolean} mustVerifyEmail - ユーザーがメールアドレスを確認する必要があるかどうかを示すブール値
 * @property {String} status - ユーザーのメールアドレス確認ステータスを示す文字列
 * @property {Object} userDetail - 現在ログインしているユーザーの詳細情報を含むオブジェクト
 */
const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    userDetail: Object,
});

/**
 * アカウント情報入力フォームのデータモデル。
 *
 * @type {Object} form - アカウント情報の登録に必要なデータを保持するフォームオブジェクト
 * @property {String} postal - ユーザーの郵便番号
 * @property {String} address - ユーザーの住所
 * @property {String} building - ユーザーの建物名
 * @property {String} introduction - ユーザーの自己紹介文
 */
const form = useForm({
    postal: props.userDetail.postal,
    address: props.userDetail.address,
    building: props.userDetail.building,
    introduction: props.userDetail.introduction,
});

/**
 * 郵便番号に基づいて住所を取得し、更新する関数。
 */
const fetchAddress = () => {
    new YubinBangoCore(String(form.postal), (value) => {
        form.address = value.region + value.locality + value.street;
    });
};

/**
 * 郵便番号の変更を監視し、住所を取得する。
 */
watch(() => form.postal, (newPostal, oldPostal) => {
    // 郵便番号が7桁の場合のみ住所をフェッチする
    if (newPostal.length === 7) {
        fetchAddress();
    }
});

/**
 * 入力フォーム（住所、建物、自己紹介）の最大文字数。
 * 補足）郵便番号は７桁で浸透しているため、敢えて表示しない。
 *
 * @type {number} - 最大文字数
 */
const maxAddressLength = 161;
const maxBuildingLength = 161;
const maxIntroductionLength = 1000;

/**
 * プロフィール情報を更新する関数。
 *
 * Inertia.jsのpatchメソッドを使用して、フォームに入力されたデータをサーバーに送信する。
 * 成功時にはフォームの送信状態を更新し、エラー時にはエラーメッセージを表示するための処理が含まれる。
 * `preserveScroll`はページ遷移後のスクロール位置維持に使用。
 * `onSuccess`はフォーム送信成功時に実行されるコールバック関数で、`form.recentlySuccessful`をtrueに設定する。
 */
const updateDetailInformation = () => {
    form.patch(route('profile.updateUserDetail'), {
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
            <h2 class="text-lg font-medium text-gray-900">プロフィール情報</h2>

            <p class="mt-1 text-sm text-gray-600">
                住所および出品時の自己紹介文を変更します。
            </p>
        </header>

        <form @submit.prevent="updateDetailInformation" class="mt-6 space-y-6">
            <div>
                <InputLabel for="postal" value="郵便番号" />

                <PostalInput id="postal" type="text" class="mt-1 block w-full" placeholder="半角数字７桁で入力してください。" v-model="form.postal" />

                <InputError class="mt-2" :message="form.errors.postal" />
            </div>

            <div>
                <InputLabel for="address" value="住所" />

                <TextInput id="address" type="text" class="mt-1 block w-full" v-model="form.address" />

                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.address.length <= maxAddressLength, 'text-red-500': form.address.length > maxAddressLength }">{{ form.address?.length }} / {{ maxAddressLength }}</p>

                <InputError v-show="form.address.length > maxAddressLength" class="mt-2" :message="'住所は' + maxAddressLength + '文字までです'" />

                <InputError class="mt-2" :message="form.errors.address" />
            </div>

            <div>
                <InputLabel for="building" value="建物" />

                <TextInput id="building" type="text" class="mt-1 block w-full" v-model="form.building" />

                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.building.length <= maxBuildingLength, 'text-red-500': form.building.length > maxBuildingLength }">{{ form.building?.length }} / {{ maxBuildingLength }}</p>

                <InputError v-show="form.building.length > maxBuildingLength" class="mt-2" :message="'建物は' + maxBuildingLength + '文字までです'" />

                <InputError class="mt-2" :message="form.errors.building" />
            </div>

            <div>
                <InputLabel for="introduction" value="自己紹介" />

                <textarea class="py-3 px-4 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" placeholder="出品ページの自己紹介文を入力してください。" v-model="form.introduction"></textarea>

                <p class="mt-1 text-sm text-right" :class="{ 'text-gray-500': form.introduction.length <= maxIntroductionLength, 'text-red-500': form.introduction.length > maxIntroductionLength }">{{ form.introduction?.length }} / {{ maxIntroductionLength }}</p>

                <InputError v-show="form.introduction.length > maxIntroductionLength" class="mt-2" :message="'自己紹介は' + maxIntroductionLength + '文字までです'" />

                <InputError class="mt-2" :message="form.errors.introduction" />
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
